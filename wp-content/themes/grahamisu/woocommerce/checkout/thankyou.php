<?php
/**
 * Thank You / Order Received — Grahamisu custom layout.
 *
 * @package Grahamisu
 * @var WC_Order $order
 */
defined( 'ABSPATH' ) || exit;
?>

<?php if ( $order ) : ?>
    <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>

        <!-- ── Failed order ──────────────────────────────────────── -->
        <div class="bg-[--color-bg] min-h-[60vh] flex items-center justify-center px-6 py-16">
            <div class="text-center max-w-md">
                <div class="w-16 h-16 rounded-full bg-red-50 border border-red-200 flex items-center justify-center mx-auto mb-6">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                              stroke="#dc2626" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h2 class="font-primary font-semibold text-3xl text-[--color-brown] mb-3">Payment unsuccessful</h2>
                <p class="font-['Lato',sans-serif] text-sm text-[--color-muted] mb-8">
                    Your transaction was declined. Please try again.
                </p>
                <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
                   class="inline-block bg-[--color-rust] text-white font-['Lato',sans-serif] font-semibold text-sm px-8 py-3">
                    Retry Payment
                </a>
            </div>
        </div>

    <?php else : ?>

        <!-- ── Title bar ─────────────────────────────────────────── -->
        <div class="bg-white border-b border-[#e8ddd0] w-full">
            <div class="w-full max-w-[1440px] mx-auto h-auto lg:h-[200px] flex flex-col lg:flex-row items-start lg:items-center justify-between px-4 lg:px-[141px] py-8 lg:py-0 gap-3 lg:gap-0">
                <div>
                    <p class="font-['Inter',sans-serif] font-normal text-xs tracking-[0.18em] uppercase text-[--color-muted] m-0 mb-1">
                        Order #<?php echo esc_html( $order->get_order_number() ); ?>
                    </p>
                    <h1 class="font-script font-normal text-[72px] leading-[1] text-[--color-dark] m-0">
                        Thank You!
                    </h1>
                </div>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                   class="font-['Lato',sans-serif] font-normal text-sm text-[--color-muted] underline underline-offset-2 hover:text-[--color-brown] transition-colors">
                    Continue shopping →
                </a>
            </div>
        </div>

        <!-- ── Body ──────────────────────────────────────────────── -->
        <div class="bg-[--color-bg] w-full py-14">
            <div class="max-w-[860px] mx-auto px-6 flex flex-col gap-8">

                <!-- Success notice -->
                <div class="flex items-start gap-4 bg-white border-l-4 border-[--color-gold] px-7 py-5 shadow-[0_1px_4px_rgba(0,0,0,0.06)]">
                    <div class="shrink-0 mt-0.5 w-9 h-9 rounded-full bg-[#fdf5ea] flex items-center justify-center">
                        <svg width="17" height="13" viewBox="0 0 17 13" fill="none" aria-hidden="true">
                            <path d="M1 6.5L6 11.5L16 1" stroke="#c8a56a" stroke-width="2"
                                  stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-primary font-semibold text-lg text-[--color-brown] m-0 leading-snug">
                            Your order is confirmed!
                        </p>
                        <p class="font-['Lato',sans-serif] text-sm text-[--color-muted] m-0 mt-1">
                            We've sent a confirmation to
                            <span class="font-semibold text-[--color-brown]">
                                <?php echo esc_html( $order->get_billing_email() ); ?>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Two-column layout: order details + items -->
                <div class="flex flex-col lg:flex-row gap-6 items-start">

                    <!-- Left: order meta -->
                    <div class="flex-1 bg-white border border-[#e8ddd0] shadow-[0_1px_4px_rgba(0,0,0,0.05)]">

                        <!-- Section header -->
                        <div class="px-7 pt-6 pb-4 border-b border-[#f0e8df]">
                            <p class="font-['Inter',sans-serif] font-semibold text-[11px] tracking-[0.15em] uppercase text-[--color-muted] m-0">
                                Order Details
                            </p>
                        </div>

                        <!-- Meta rows -->
                        <div class="px-7 py-5 flex flex-col gap-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-['Inter',sans-serif] text-[11px] uppercase tracking-widest text-[--color-muted]">
                                    Date
                                </span>
                                <span class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-dark]">
                                    <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                                </span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-['Inter',sans-serif] text-[11px] uppercase tracking-widest text-[--color-muted]">
                                    Payment method
                                </span>
                                <span class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-dark]">
                                    <?php echo esc_html( $order->get_payment_method_title() ); ?>
                                </span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-['Inter',sans-serif] text-[11px] uppercase tracking-widest text-[--color-muted]">
                                    Fulfillment
                                </span>
                                <span class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-dark]">
                                    <?php echo esc_html( $order->get_shipping_method() ?: 'Standard delivery' ); ?>
                                </span>
                            </div>
                            <?php if ( $order->get_billing_address_1() ) : ?>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-['Inter',sans-serif] text-[11px] uppercase tracking-widest text-[--color-muted]">
                                    Deliver to
                                </span>
                                <span class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-dark] leading-snug">
                                    <?php echo esc_html( trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) ); ?><br>
                                    <?php echo esc_html( $order->get_billing_address_1() ); ?>
                                    <?php if ( $order->get_billing_postcode() ) echo ' ' . esc_html( $order->get_billing_postcode() ); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right: items + totals -->
                    <div class="flex-[1.4] bg-white border border-[#e8ddd0] shadow-[0_1px_4px_rgba(0,0,0,0.05)]">

                        <!-- Section header -->
                        <div class="px-7 pt-6 pb-4 border-b border-[#f0e8df]">
                            <p class="font-['Inter',sans-serif] font-semibold text-[11px] tracking-[0.15em] uppercase text-[--color-muted] m-0">
                                Items Ordered
                            </p>
                        </div>

                        <!-- Item list -->
                        <div class="px-7 py-5 flex flex-col gap-5">
                            <?php foreach ( $order->get_items() as $item ) :
                                $product  = $item->get_product();
                                $thumb_id = $product ? $product->get_image_id() : 0;
                                $thumb    = $thumb_id
                                    ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' )
                                    : wc_placeholder_img_src( 'woocommerce_thumbnail' );
                                $qty      = $item->get_quantity();
                                $subtotal = $order->get_formatted_line_subtotal( $item );
                            ?>
                            <div class="flex items-center gap-4">
                                <div class="shrink-0 w-[56px] h-[56px] bg-[--color-surface] overflow-hidden border border-[#f0e8df]">
                                    <img src="<?php echo esc_url( $thumb ); ?>"
                                         alt="<?php echo esc_attr( $item->get_name() ); ?>"
                                         width="56" height="56"
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-brown] m-0 leading-tight">
                                        <?php echo esc_html( $item->get_name() ); ?>
                                    </p>
                                    <p class="font-['Lato',sans-serif] text-xs text-[--color-muted] m-0 mt-0.5">
                                        Qty <?php echo esc_html( $qty ); ?>
                                    </p>
                                </div>
                                <p class="font-['Lato',sans-serif] font-semibold text-sm text-[--color-brown] m-0 shrink-0">
                                    <?php echo wp_kses_post( $subtotal ); ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Totals -->
                        <div class="px-7 pb-6 pt-1 border-t border-[#f0e8df] flex flex-col gap-2 mt-2">
                            <?php $shipping_total = (float) $order->get_shipping_total(); ?>
                            <div class="flex justify-between items-center">
                                <span class="font-['Lato',sans-serif] text-sm text-[--color-muted]">Shipping</span>
                                <span class="font-['Lato',sans-serif] text-sm text-[--color-dark]">
                                    <?php echo $shipping_total > 0 ? wp_kses_post( wc_price( $shipping_total ) ) : 'FREE'; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-[#f0e8df]">
                                <span class="font-['Lato',sans-serif] font-bold text-base text-[--color-dark]">Total</span>
                                <span class="font-['Lato',sans-serif] font-bold text-base text-[--color-brown]">
                                    <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div><!-- /two-column -->

                <!-- CTA -->
                <div class="flex items-center gap-6 pt-2">
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                       class="inline-block bg-[--color-rust] text-white font-['Lato',sans-serif] font-semibold text-sm px-10 py-4 hover:opacity-90 transition-opacity">
                        Continue Shopping
                    </a>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                       class="font-['Lato',sans-serif] text-sm text-[--color-muted] underline underline-offset-2 hover:text-[--color-brown] transition-colors">
                        Back to Home
                    </a>
                </div>

            </div>
        </div>

        <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
        <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

    <?php endif; ?>

<?php else : ?>

    <!-- ── No order found ────────────────────────────────────────── -->
    <div class="bg-[--color-bg] min-h-[60vh] flex items-center justify-center px-6 py-16">
        <div class="text-center max-w-md">
            <h2 class="font-primary font-semibold text-3xl text-[--color-brown] mb-4">Order not found</h2>
            <p class="font-['Lato',sans-serif] text-sm text-[--color-muted] mb-8">
                We couldn't locate your order. Please check your email for confirmation.
            </p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="inline-block bg-[--color-rust] text-white font-['Lato',sans-serif] font-semibold text-sm px-8 py-3">
                Back to Home
            </a>
        </div>
    </div>

<?php endif; ?>
