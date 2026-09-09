<?php
/**
 * Checkout Page — Grahamisu custom layout.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;

// Payment gateway
$gateways     = WC()->payment_gateways()->get_available_payment_gateways();
$payment_slug = ! empty( $gateways ) ? array_key_first( $gateways ) : 'bacs';
$first_gw     = ! empty( $gateways ) ? reset( $gateways ) : null;

// Shipping method IDs from zone config
$pickup_rate_id   = '';
$delivery_rate_id = '';
foreach ( WC_Shipping_Zones::get_zones() as $zone_data ) {
    $zone = new WC_Shipping_Zone( $zone_data['zone_id'] );
    foreach ( $zone->get_shipping_methods( true ) as $method ) {
        if ( in_array( $method->id, [ 'free_shipping', 'local_pickup', 'pickup_location' ], true ) && ! $pickup_rate_id ) {
            $pickup_rate_id = $method->id . ':' . $method->instance_id;
        } elseif ( $method->id === 'flat_rate' && ! $delivery_rate_id ) {
            $delivery_rate_id = $method->id . ':' . $method->instance_id;
        }
    }
}
$chosen_methods   = WC()->session->get( 'chosen_shipping_methods', [] );
$default_shipping = ! empty( $chosen_methods[0] ) ? $chosen_methods[0] : $delivery_rate_id;

// Order totals
$cart_count  = WC()->cart->get_cart_contents_count();
$subtotal    = WC()->cart->get_subtotal();
$shipping    = WC()->cart->get_shipping_total();
$order_total = WC()->cart->get_total( 'edit' );
?>

<div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] pt-12 pb-16 lg:pt-[60px] lg:pb-[80px]">

    <!-- ── Header ── -->
    <div class="flex items-baseline justify-between mb-8 lg:mb-10">
        <h1 class="font-primary font-normal text-[40px] lg:text-[52px] text-[#2c1a0e] leading-none m-0">
            <?php esc_html_e( 'Checkout', 'grahamisu' ); ?>
        </h1>
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[1.92px] uppercase border-b border-brown pb-0.5 no-underline hover:text-rust hover:border-rust transition-colors whitespace-nowrap">
            ← <?php esc_html_e( 'Back to cart', 'grahamisu' ); ?>
        </a>
    </div>

    <!-- ── Two-column layout ── -->
    <div class="flex flex-col lg:flex-row items-start gap-8 lg:gap-10">

        <!-- ── LEFT: Checkout form ── -->
        <div class="flex-1 min-w-0 w-full">
        <form name="checkout" method="post" class="checkout woocommerce-checkout flex flex-col gap-8"
              action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
              enctype="multipart/form-data">

            <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
            <input type="hidden" name="billing_country"  value="PH">
            <input type="hidden" name="billing_state"    value="00">
            <input type="hidden" name="billing_city"     value="Manila">
            <input type="hidden" name="payment_method"   value="<?php echo esc_attr( $payment_slug ); ?>">
            <input type="hidden" name="shipping_method[0]" id="gc-shipping-method" value="<?php echo esc_attr( $default_shipping ); ?>">

            <!-- ── CONTACT ── -->
            <div>
                <h2 class="font-primary font-normal text-[24px] text-[#2c1a0e] leading-none m-0 mb-1">
                    <?php esc_html_e( 'Contact', 'grahamisu' ); ?>
                </h2>
                <p class="font-['Lato',sans-serif] font-normal text-[14px] text-[#838383] leading-none m-0 mb-5">
                    <?php esc_html_e( 'We only use this to send your order confirmation.', 'grahamisu' ); ?>
                </p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 mb-3">
                    <input type="text" name="billing_first_name" required
                           placeholder="<?php esc_attr_e( 'First name', 'grahamisu' ); ?>"
                           class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                    <input type="text" name="billing_last_name" required
                           placeholder="<?php esc_attr_e( 'Last name', 'grahamisu' ); ?>"
                           class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                    <input type="email" name="billing_email" required
                           placeholder="<?php esc_attr_e( 'Email address', 'grahamisu' ); ?>"
                           class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                    <input type="tel" name="billing_phone" required
                           placeholder="<?php esc_attr_e( 'Phone (required for pickup)', 'grahamisu' ); ?>"
                           class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                </div>
            </div>

            <!-- ── PICKUP / DELIVERY DETAILS ── -->
            <div>
                <h2 class="font-primary font-normal text-[24px] text-[#2c1a0e] leading-none m-0 mb-5">
                    <?php esc_html_e( 'Pickup / delivery details', 'grahamisu' ); ?>
                </h2>

                <!-- Fulfillment tabs -->
                <div class="gc-fulfillment flex gap-3 mb-5"
                     role="group" aria-label="<?php esc_attr_e( 'Fulfillment method', 'grahamisu' ); ?>">
                    <button type="button"
                            class="gc-fulfillment__tab flex flex-row items-center justify-center gap-3 rounded-[12px] h-[58px] px-5 border border-rust cursor-pointer font-['Lato',sans-serif] font-normal text-[13px] text-[#2c1a0e] bg-white transition-colors"
                            data-tab="pickup"
                            data-shipping-method="<?php echo esc_attr( $pickup_rate_id ); ?>"
                            aria-pressed="false">
                        <svg width="21" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3 9H19V17C19 17.5304 18.7893 18.0391 18.4142 18.4142C18.0391 18.7893 17.5304 19 17 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 9L17 3H5L3 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8 13H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span><?php esc_html_e( 'Pickup in store', 'grahamisu' ); ?></span>
                    </button>
                    <button type="button"
                            class="gc-fulfillment__tab is-active flex flex-row items-center justify-center gap-3 rounded-[12px] h-[58px] px-5 border border-rust cursor-pointer font-['Lato',sans-serif] font-normal text-[13px] text-white bg-transparent transition-colors"
                            data-tab="delivery"
                            data-shipping-method="<?php echo esc_attr( $delivery_rate_id ); ?>"
                            aria-pressed="true">
                        <svg width="26" height="18" viewBox="0 0 30 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M19 3H1V14H19V3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 7H23L27 11V14H19V7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="7" cy="17" r="2" stroke="currentColor" stroke-width="1.5"/>
                            <circle cx="23" cy="17" r="2" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span><?php esc_html_e( 'Delivery 10am–2pm', 'grahamisu' ); ?></span>
                    </button>
                </div>

                <!-- Pickup fields -->
                <div class="gc-pickup-fields flex flex-col gap-3" hidden>
                    <div class="bg-white border border-[#e7e1dd] rounded-[12px] p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <span class="gc-radio is-selected shrink-0 w-[18px] h-[18px] rounded-full border-2 border-rust bg-white flex items-center justify-center mt-0.5" aria-hidden="true">
                                <span class="gc-radio__dot w-[6px] h-[6px] rounded-full bg-white"></span>
                            </span>
                            <div>
                                <p class="font-['Lato',sans-serif] font-bold text-[13px] text-[#2c1a0e] leading-5 m-0">Grahamisu Pickup Point</p>
                                <p class="font-['Lato',sans-serif] font-normal text-[12px] text-[#4e4e4e] leading-[18px] m-0">12 Mabini St, Malate, Manila</p>
                                <p class="font-['Lato',sans-serif] font-normal text-[12px] text-[#4e4e4e] leading-[18px] m-0">Open 9am–6pm daily</p>
                            </div>
                        </label>
                    </div>
                    <button type="button"
                            class="gc-date-picker gc-date-picker--pickup w-full flex items-center gap-3 h-[52px] px-4 bg-white border border-rust rounded-[12px] cursor-pointer font-['Lato',sans-serif] font-normal text-[14px] text-[#a9a29c] text-left box-border">
                        <svg width="17" height="19" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="1" y="3" width="16" height="16" rx="2" stroke="#6c290f" stroke-width="1.5"/>
                            <path d="M1 8H17" stroke="#6c290f" stroke-width="1.5"/>
                            <path d="M5 1V5M13 1V5" stroke="#6c290f" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span class="flex-1"><?php esc_html_e( 'Choose a date and time', 'grahamisu' ); ?></span>
                    </button>
                </div>

                <!-- Delivery fields -->
                <div class="gc-delivery-fields flex flex-col gap-3">
                    <input type="text" name="billing_address_1" required
                           placeholder="<?php esc_attr_e( 'Street address', 'grahamisu' ); ?>"
                           class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">

                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="billing_postcode" required
                               placeholder="<?php esc_attr_e( 'Postal code', 'grahamisu' ); ?>"
                               class="gc-field h-[54px] bg-white border border-[#d1d5db] rounded-[12px] px-[18px] font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                        <div class="gc-field relative h-[54px] bg-white border border-[#d1d5db] rounded-[12px] flex items-center px-[18px]">
                            <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] flex-1">Philippines</span>
                            <span class="text-[#838383] text-[11px] leading-none">▾</span>
                        </div>
                    </div>

                    <button type="button"
                            class="gc-date-picker gc-date-picker--delivery w-full flex items-center gap-3 h-[52px] px-4 bg-white border border-rust rounded-[12px] cursor-pointer font-['Lato',sans-serif] font-normal text-[14px] text-[#a9a29c] text-left box-border">
                        <svg width="17" height="19" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="1" y="3" width="16" height="16" rx="2" stroke="#6c290f" stroke-width="1.5"/>
                            <path d="M1 8H17" stroke="#6c290f" stroke-width="1.5"/>
                            <path d="M5 1V5M13 1V5" stroke="#6c290f" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span class="flex-1"><?php esc_html_e( 'Choose a date and time', 'grahamisu' ); ?></span>
                    </button>
                </div>

                <!-- Info box -->
                <div class="flex items-start gap-3 bg-white border border-[#e7e1dd] rounded-[12px] p-4 mt-3">
                    <span class="shrink-0 w-[7px] h-[7px] rounded-[3.5px] bg-[#f2b705] mt-1"></span>
                    <p class="font-['Lato',sans-serif] font-normal text-[14px] text-[#4e4e4e] leading-[22.4px] m-0">
                        <?php esc_html_e( 'Your order is made fresh the day before pickup or delivery. Pickup: 12 Mabini St, Malate, Manila · 9am–6pm daily.', 'grahamisu' ); ?>
                    </p>
                </div>
            </div>

            <!-- ── PAYMENT ── -->
            <div>
                <h2 class="font-primary font-normal text-[24px] text-[#2c1a0e] leading-none m-0 mb-1">
                    <?php esc_html_e( 'Payment', 'grahamisu' ); ?>
                </h2>
                <p class="font-['Lato',sans-serif] font-normal text-[14px] text-[#838383] leading-none m-0 mb-5">
                    <?php esc_html_e( 'All transactions are secure and encrypted.', 'grahamisu' ); ?>
                </p>

                <div class="bg-white border border-rust rounded-[12px] overflow-hidden">
                    <!-- Header row -->
                    <div class="flex items-center gap-3 px-5 h-[55px] bg-[#fdf8f6] border-b border-[#e7e1dd]">
                        <span class="shrink-0 w-4 h-4 rounded-[4px] border-[4px] border-rust bg-white flex items-center justify-center"></span>
                        <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e] flex-1">
                            <?php
                            echo $first_gw
                                ? esc_html( $first_gw->get_title() )
                                : esc_html__( 'PayNow / Bank transfer', 'grahamisu' );
                            ?>
                        </span>
                        <span class="font-['Lato',sans-serif] font-bold text-[11px] text-brown tracking-[1.54px] uppercase shrink-0">
                            <?php esc_html_e( 'Recommended', 'grahamisu' ); ?>
                        </span>
                    </div>
                    <!-- Body -->
                    <div class="px-5 py-4 font-['Lato',sans-serif] font-normal text-[14px] text-[#4e4e4e] leading-[23.1px]">
                        <?php if ( $first_gw && $first_gw->get_description() ) : ?>
                            <?php echo wp_kses_post( wpautop( wptexturize( $first_gw->get_description() ) ) ); ?>
                        <?php else : ?>
                            Transfer the total to <strong class="font-bold text-[#2c1a0e]">BPI 1234-5678-90 · Grahamisu PH</strong> and send the receipt to our page. We confirm within an hour during store hours.
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ── PLACE ORDER ── -->
            <button type="submit"
                    class="w-full h-[60px] bg-rust text-white rounded-[100px] font-['Lato',sans-serif] font-bold text-[16px] tracking-[0.96px] border-0 cursor-pointer transition-colors hover:bg-[#8b3515] flex items-center justify-center"
                    style="box-shadow: inset 0 0 0 2px #ffffff;">
                <?php
                $total_display = strip_tags( wc_price( $order_total ) );
                printf(
                    esc_html__( 'Place order · %s', 'grahamisu' ),
                    esc_html( $total_display )
                );
                ?>
            </button>

        </form>
        </div>

        <!-- ── RIGHT: Order summary card ── -->
        <div class="w-full lg:w-[380px] lg:shrink-0 bg-white border border-[#e7e1dd] rounded-[20px] p-7">

            <h2 class="font-primary font-normal text-[22px] text-[#2c1a0e] leading-none m-0 mb-6">
                <?php esc_html_e( 'Your order', 'grahamisu' ); ?>
            </h2>

            <!-- Cart items -->
            <div class="flex flex-col divide-y divide-[#efeae7] mb-5">
            <?php foreach ( WC()->cart->get_cart() as $cart_item ) :
                $product    = $cart_item['data'];
                $qty        = $cart_item['quantity'];
                $thumb_id   = $product->get_image_id();
                $thumb      = $thumb_id
                    ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' )
                    : wc_placeholder_img_src( 'woocommerce_thumbnail' );
                $line_total = $product->get_price() * $qty;
                $size_attr  = $product->get_attribute( 'size' )
                    ?: get_post_meta( $product->get_id(), '_size_label', true );
            ?>
            <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                <div class="shrink-0 w-14 h-14 rounded-[10px] overflow-hidden bg-surface">
                    <img src="<?php echo esc_url( $thumb ); ?>"
                         alt="<?php echo esc_attr( $product->get_name() ); ?>"
                         class="w-full h-full object-cover block">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-primary font-semibold text-[17px] text-[#2c1a0e] leading-none m-0 mb-1">
                        <?php echo esc_html( $product->get_name() ); ?>
                    </p>
                    <p class="font-['Lato',sans-serif] font-normal text-[11px] text-[#838383] tracking-[1.32px] uppercase leading-none m-0">
                        <?php
                        $meta = array_filter( [ $size_attr, 'qty ' . $qty ] );
                        echo esc_html( implode( ' · ', $meta ) );
                        ?>
                    </p>
                </div>
                <p class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e] leading-none m-0 shrink-0 [&_.woocommerce-Price-amount]:text-inherit">
                    <?php echo wc_price( $line_total ); // phpcs:ignore ?>
                </p>
            </div>
            <?php endforeach; ?>
            </div>

            <!-- Discount code -->
            <div class="flex gap-2 mb-2">
                <input type="text" id="gc-coupon-code"
                       placeholder="<?php esc_attr_e( 'Discount code', 'grahamisu' ); ?>"
                       class="flex-1 h-[48px] bg-white border border-[#d1d5db] rounded-[12px] px-4 font-['Lato',sans-serif] font-normal text-[14px] text-[#4e4e4e] placeholder-[#a9a29c] outline-none focus:border-rust transition-colors">
                <button type="button" id="gc-apply-coupon"
                        class="h-[48px] px-5 bg-white border border-rust rounded-[12px] font-['Lato',sans-serif] font-bold text-[13px] text-rust tracking-[1.04px] cursor-pointer hover:bg-[#fdf5f1] transition-colors shrink-0">
                    <?php esc_html_e( 'Apply', 'grahamisu' ); ?>
                </button>
            </div>
            <p class="font-['Lato',sans-serif] font-normal text-[12px] text-brown leading-none m-0 mb-5">
                <?php esc_html_e( 'Try GRAHAM10 for 10% off.', 'grahamisu' ); ?>
            </p>

            <!-- Divider -->
            <div class="bg-[#e4deda] h-px mb-5"></div>

            <!-- Subtotal + delivery -->
            <div class="flex justify-between mb-3">
                <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e]">
                    <?php printf( esc_html__( 'Subtotal · %d item', 'grahamisu' ) . ( $cart_count !== 1 ? 's' : '' ), (int) $cart_count ); ?>
                </span>
                <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e] [&_.woocommerce-Price-amount]:text-inherit">
                    <?php echo wc_price( $subtotal ); // phpcs:ignore ?>
                </span>
            </div>
            <?php if ( $shipping > 0 ) : ?>
            <div class="flex justify-between mb-3">
                <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e]">
                    <?php esc_html_e( 'Delivery fee', 'grahamisu' ); ?>
                </span>
                <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e] [&_.woocommerce-Price-amount]:text-inherit">
                    <?php echo wc_price( $shipping ); // phpcs:ignore ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Divider -->
            <div class="bg-[#e4deda] h-px mb-5"></div>

            <!-- Total -->
            <div class="flex items-center justify-between">
                <span class="font-['Lato',sans-serif] font-bold text-[13px] text-brown tracking-[1.82px] uppercase">
                    <?php esc_html_e( 'Total', 'grahamisu' ); ?>
                </span>
                <span class="font-primary font-normal text-[30px] text-rust leading-none [&_.woocommerce-Price-amount]:text-inherit">
                    <?php echo wc_price( $order_total ); // phpcs:ignore ?>
                </span>
            </div>

        </div>

    </div><!-- /two-column -->

</div>

<script>
(function () {
    // ── Delivery required/disabled toggle ──
    // When the user switches to Pickup, disable the delivery address fields so they
    // are excluded from HTML5 validation and not submitted to WooCommerce.
    var deliveryAddressFields = document.querySelectorAll(
        '.gc-delivery-fields input[name="billing_address_1"], .gc-delivery-fields input[name="billing_postcode"]'
    );

    function syncDeliveryFields(isPickup) {
        deliveryAddressFields.forEach(function (field) {
            field.required = !isPickup;
            field.disabled = isPickup;
        });
    }

    document.querySelectorAll('.gc-fulfillment__tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            syncDeliveryFields(tab.dataset.tab === 'pickup');
        });
    });

    syncDeliveryFields(false); // delivery active by default

    // ── Coupon AJAX ──
    var btn = document.getElementById('gc-apply-coupon');
    var inp = document.getElementById('gc-coupon-code');
    if (btn && inp) {
        btn.addEventListener('click', function () {
            var code = inp.value.trim();
            if (!code) return;
            var params = new URLSearchParams({
                coupon_code: code,
                security:    '<?php echo esc_js( wp_create_nonce( 'apply-coupon' ) ); ?>',
            });
            fetch('/?wc-ajax=apply_coupon', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    params.toString(),
            })
            .then(function (r) { return r.text(); })
            .then(function (msg) {
                if (msg) {
                    var div = document.createElement('div');
                    div.innerHTML = msg;
                    var text = div.textContent || div.innerText || '';
                    if (text.trim()) alert(text.trim());
                }
                if (typeof jQuery !== 'undefined') {
                    jQuery(document.body).trigger('update_checkout');
                }
            });
        });
    }
}());
</script>
