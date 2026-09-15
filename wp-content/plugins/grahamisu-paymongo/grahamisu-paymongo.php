<?php
/**
 * Plugin Name: Grahamisu PayMongo Gateway
 * Description: PayMongo payment gateway for Grahamisu — supports GCash, Maya, and cards.
 * Version:     1.0.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

add_action( 'plugins_loaded', function () {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	class WC_Gateway_Grahamisu_PayMongo extends WC_Payment_Gateway {

		const API_BASE = 'https://api.paymongo.com/v1';

		public function __construct() {
			$this->id                 = 'grahamisu_paymongo';
			$this->has_fields         = false;
			$this->method_title       = 'PayMongo';
			$this->method_description = 'Accept GCash, Maya, GrabPay, and card payments via PayMongo hosted checkout.';
			$this->supports           = [ 'products' ];

			$this->init_form_fields();
			$this->init_settings();

			$this->title       = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );

			add_action(
				'woocommerce_update_options_payment_gateways_' . $this->id,
				[ $this, 'process_admin_options' ]
			);

			// Handles the redirect back from PayMongo hosted checkout.
			add_action( 'woocommerce_api_grahamisu_paymongo', [ $this, 'handle_return' ] );
		}

		public function init_form_fields(): void {
			$this->form_fields = [
				'enabled'         => [
					'title'   => 'Enable / Disable',
					'type'    => 'checkbox',
					'label'   => 'Enable PayMongo',
					'default' => 'no',
				],
				'title'           => [
					'title'       => 'Title',
					'type'        => 'text',
					'description' => 'Payment method label shown to customers on the checkout page.',
					'default'     => 'GCash / Maya / Card (PayMongo)',
					'desc_tip'    => true,
				],
				'description'     => [
					'title'   => 'Description',
					'type'    => 'textarea',
					'default' => 'Pay securely via GCash, Maya, or Credit / Debit Card.',
				],
				'secret_key'      => [
					'title'       => 'Secret Key',
					'type'        => 'password',
					'description' => 'Your PayMongo secret key — starts with <code>sk_test_</code> for sandbox or <code>sk_live_</code> for production.',
					'default'     => '',
					'desc_tip'    => false,
				],
				'payment_methods' => [
					'title'       => 'Accepted Payment Methods',
					'type'        => 'multiselect',
					'description' => 'Hold Ctrl / Cmd to select multiple.',
					'options'     => [
						'gcash'    => 'GCash',
						'paymaya'  => 'Maya',
						'card'     => 'Credit / Debit Card',
						'grab_pay' => 'GrabPay',
						'billease' => 'BillEase',
					],
					'default'     => [ 'gcash', 'paymaya', 'card' ],
					'desc_tip'    => true,
				],
			];
		}

		// ── Helpers ──────────────────────────────────────────────────

		private function secret_key(): string {
			return trim( $this->get_option( 'secret_key', '' ) );
		}

		private function accepted_methods(): array {
			$methods = $this->get_option( 'payment_methods', [ 'gcash', 'paymaya', 'card' ] );
			return is_array( $methods ) ? $methods : [ 'gcash', 'paymaya', 'card' ];
		}

		private function format_phone( string $phone ): string {
			$phone = preg_replace( '/[^0-9+]/', '', $phone );
			if ( str_starts_with( $phone, '09' ) ) {
				return '+63' . substr( $phone, 1 );
			}
			if ( str_starts_with( $phone, '9' ) && strlen( $phone ) === 10 ) {
				return '+63' . $phone;
			}
			return $phone;
		}

		private function api( string $method, string $endpoint, array $body = [] ): ?array {
			$sk = $this->secret_key();
			if ( ! $sk ) {
				return null;
			}

			$args = [
				'method'  => $method,
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( $sk . ':' ),
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
				],
				'timeout' => 30,
			];

			if ( $body ) {
				$args['body'] = wp_json_encode( $body );
			}

			$response = wp_remote_request( self::API_BASE . $endpoint, $args );

			if ( is_wp_error( $response ) ) {
				wc_get_logger()->error(
					'PayMongo request failed: ' . $response->get_error_message(),
					[ 'source' => 'grahamisu-paymongo' ]
				);
				return null;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! empty( $data['errors'] ) ) {
				wc_get_logger()->error(
					'PayMongo error: ' . wp_json_encode( $data['errors'] ),
					[ 'source' => 'grahamisu-paymongo' ]
				);
			}

			return $data;
		}

		private function session_is_paid( array $response ): bool {
			$attrs = $response['data']['attributes'] ?? [];

			// Check the embedded payment_intent status.
			$pi_status = $attrs['payment_intent']['attributes']['status'] ?? '';
			if ( $pi_status === 'succeeded' ) {
				return true;
			}

			// Check individual payments array (fallback).
			foreach ( $attrs['payments'] ?? [] as $payment ) {
				if ( ( $payment['attributes']['status'] ?? '' ) === 'paid' ) {
					return true;
				}
			}

			return false;
		}

		// ── WooCommerce hooks ─────────────────────────────────────────

		public function process_payment( $order_id ): array {
			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				wc_add_notice( 'Order not found.', 'error' );
				return [ 'result' => 'fail' ];
			}

			$sk = $this->secret_key();
			if ( ! $sk ) {
				wc_add_notice( 'PayMongo is not configured. Please contact the store owner.', 'error' );
				return [ 'result' => 'fail' ];
			}

			// Return URL after PayMongo checkout completes.
			$return_url = add_query_arg(
				[
					'wc-api'   => 'grahamisu_paymongo',
					'order_id' => $order_id,
					'key'      => $order->get_order_key(),
				],
				home_url( '/' )
			);

			// Build line items (PayMongo amounts are in centavos).
			$line_items = [];
			foreach ( $order->get_items() as $item ) {
				/** @var WC_Order_Item_Product $item */
				$qty = max( 1, (int) $item->get_quantity() );
				$line_items[] = [
					'currency'    => get_woocommerce_currency(),
					'amount'      => (int) round( $item->get_total() / $qty * 100 ),
					'name'        => $item->get_name(),
					'quantity'    => $qty,
					'description' => $item->get_name(),
				];
			}

			// Shipping as a separate line item.
			$shipping = (float) $order->get_shipping_total();
			if ( $shipping > 0 ) {
				$line_items[] = [
					'currency'    => get_woocommerce_currency(),
					'amount'      => (int) round( $shipping * 100 ),
					'name'        => 'Delivery Fee',
					'quantity'    => 1,
					'description' => 'Shipping',
				];
			}

			$name  = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );
			$phone = $order->get_billing_phone();

			$payload = [
				'data' => [
					'attributes' => [
						'billing'              => [
							'name'  => $name ?: $order->get_billing_email(),
							'email' => $order->get_billing_email(),
							'phone' => $phone ? $this->format_phone( $phone ) : null,
						],
						'line_items'           => $line_items,
						'payment_method_types' => $this->accepted_methods(),
						'reference_number'     => (string) $order_id,
						'success_url'          => $return_url,
						'cancel_url'           => $order->get_cancel_order_url_raw(),
						'metadata'             => [ 'order_id' => $order_id ],
					],
				],
			];

			$response = $this->api( 'POST', '/checkout_sessions', $payload );

			$checkout_url = $response['data']['attributes']['checkout_url'] ?? null;

			if ( ! $checkout_url ) {
				$error = $response['errors'][0]['detail'] ?? 'Unable to connect to PayMongo. Please try again.';
				wc_add_notice( esc_html( $error ), 'error' );
				return [ 'result' => 'fail' ];
			}

			$order->update_meta_data( '_paymongo_session_id', $response['data']['id'] );
			$order->update_status( 'pending', 'Awaiting PayMongo payment.' );
			$order->save();

			// NOTE: the cart is intentionally NOT emptied here. If the customer
			// backs out of PayMongo or the payment fails, their cart is preserved
			// so they can retry checkout without re-adding items. The cart is
			// emptied only after the payment is confirmed in handle_return().

			return [
				'result'   => 'success',
				'redirect' => $checkout_url,
			];
		}

		/**
		 * Called when PayMongo redirects the customer back after checkout.
		 * URL: /?wc-api=grahamisu_paymongo&order_id=X&key=Y
		 */
		public function handle_return(): void {
			$order_id = absint( wp_unslash( $_GET['order_id'] ?? 0 ) );
			$key      = sanitize_text_field( wp_unslash( $_GET['key'] ?? '' ) );
			$order    = $order_id ? wc_get_order( $order_id ) : null;

			// Validate order + key before doing anything.
			if ( ! $order || ! hash_equals( $order->get_order_key(), $key ) ) {
				wc_add_notice( 'Invalid order reference.', 'error' );
				wp_safe_redirect( wc_get_page_permalink( 'cart' ) );
				exit;
			}

			// If order is already paid (e.g. double redirect), go straight to thank-you.
			if ( $order->is_paid() ) {
				if ( WC()->cart ) {
					WC()->cart->empty_cart();
				}
				wp_safe_redirect( $order->get_checkout_order_received_url() );
				exit;
			}

			$session_id = $order->get_meta( '_paymongo_session_id' );

			if ( $session_id ) {
				$response = $this->api( 'GET', '/checkout_sessions/' . $session_id );

				if ( $response && $this->session_is_paid( $response ) ) {
					$payment_id = $response['data']['attributes']['payment_intent']['id'] ?? '';
					$order->payment_complete( $payment_id );
					$order->add_order_note( 'PayMongo payment confirmed.' );
					if ( WC()->cart ) {
						WC()->cart->empty_cart();
					}
					wp_safe_redirect( $order->get_checkout_order_received_url() );
					exit;
				}
			}

			// Payment not confirmed — send back to checkout.
			wc_add_notice( 'Your payment was not completed. Please try again.', 'error' );
			wp_safe_redirect( wc_get_checkout_url() );
			exit;
		}
	}

	add_filter( 'woocommerce_payment_gateways', function ( array $gateways ): array {
		$gateways[] = 'WC_Gateway_Grahamisu_PayMongo';
		return $gateways;
	} );
} );
