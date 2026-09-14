<?php
/**
 * Cart Page — Grahamisu custom layout.
 *
 * @package Grahamisu
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] pt-12 pb-16 lg:pt-[60px] lg:pb-[80px]">

    <!-- ── Header row ── -->
    <div class="flex items-baseline justify-between mb-8 lg:mb-10">
        <h1 class="font-primary font-normal text-[40px] lg:text-[52px] text-[#2c1a0e] leading-none m-0">
            <?php esc_html_e( 'Your cart', 'grahamisu' ); ?>
        </h1>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[1.92px] uppercase border-b border-brown pb-0.5 no-underline hover:text-rust hover:border-rust transition-colors whitespace-nowrap">
            ← <?php esc_html_e( 'Continue shopping', 'grahamisu' ); ?>
        </a>
    </div>

    <!-- ── Two-column layout ── -->
    <div class="flex flex-col xl:flex-row items-start gap-8 xl:gap-10">

        <!-- ── Left: cart items + notes ── -->
        <div class="flex-1 min-w-0 w-full">

            <!-- Column labels -->
            <div class="hidden xl:flex items-center pb-3 border-b border-[#d8d2ce] mb-4 px-6">
                <div class="flex-1 font-['Lato',sans-serif] font-bold text-[11px] text-[#838383] tracking-[1.98px] uppercase">
                    <?php esc_html_e( 'Product', 'grahamisu' ); ?>
                </div>
                <div class="w-[150px] text-center font-['Lato',sans-serif] font-bold text-[11px] text-[#838383] tracking-[1.98px] uppercase">
                    <?php esc_html_e( 'Quantity', 'grahamisu' ); ?>
                </div>
                <div class="hidden xl:block w-[80px] text-right font-['Lato',sans-serif] font-bold text-[11px] text-[#838383] tracking-[1.98px] uppercase">
                    <?php esc_html_e( 'Total', 'grahamisu' ); ?>
                </div>
                <div class="w-6 ml-4"></div>
            </div>

            <!-- Cart items form -->
            <form class="gc-cart-form woocommerce-cart-form"
                  action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                <?php do_action( 'woocommerce_before_cart_table' ); ?>
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <div class="flex flex-col gap-3">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $product    = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( ! $product instanceof WC_Product || ! $product->exists() || $cart_item['quantity'] <= 0 ) continue;
                    if ( ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) continue;

                    $product_name      = apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $cart_item, $cart_item_key );
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $product->is_visible() ? $product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $product->get_image( [ 82, 82 ], [ 'class' => 'w-full h-full object-cover block' ] ), $cart_item, $cart_item_key );
                    $price_raw         = $product->get_price();
                    $subtotal_html     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                    $max_qty           = $product->is_sold_individually() ? 1 : $product->get_max_purchase_quantity();
                    $item_class        = apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key );

                    $size_attr = $product->get_attribute( 'size' )
                        ?: get_post_meta( $product->get_id(), '_size_label', true );
                ?>
                <div class="flex items-center gap-3 lg:gap-4 bg-white border border-[#e7e1dd] rounded-[16px] py-[18px] pl-4 lg:pl-6 pr-4 <?php echo esc_attr( $item_class ); ?>">

                    <!-- Thumbnail -->
                    <div class="shrink-0 w-[72px] h-[72px] lg:w-[82px] lg:h-[82px] rounded-[12px] overflow-hidden bg-surface">
                        <?php if ( $product_permalink ) : ?>
                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="block w-full h-full"><?php echo $thumbnail; // phpcs:ignore ?></a>
                        <?php else : ?>
                            <?php echo $thumbnail; // phpcs:ignore ?>
                        <?php endif; ?>
                    </div>

                    <!-- Right-side wrapper: column on mobile, row on desktop -->
                    <div class="flex-1 min-w-0 flex flex-col lg:flex-row lg:items-center gap-2 lg:gap-4">

                        <!-- Product info -->
                        <div class="flex-1 min-w-0 flex flex-col gap-1">
                            <?php if ( $product_permalink ) : ?>
                                <a href="<?php echo esc_url( $product_permalink ); ?>"
                                   class="font-primary font-semibold text-[18px] lg:text-[20px] text-[#2c1a0e] no-underline leading-snug hover:text-rust transition-colors">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>
                            <?php else : ?>
                                <span class="font-primary font-semibold text-[18px] lg:text-[20px] text-[#2c1a0e] leading-snug">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ( $size_attr ) : ?>
                            <span class="font-['Lato',sans-serif] font-normal text-[12px] text-[#838383] tracking-[1.44px] uppercase leading-none">
                                Size: <?php echo esc_html( $size_attr ); ?>
                            </span>
                            <?php endif; ?>

                            <span class="font-['Lato',sans-serif] font-normal text-[14px] text-rust leading-none">
                                <?php echo wc_price( $price_raw ); // phpcs:ignore ?> each
                            </span>
                        </div>

                        <!-- Controls row: qty + total (desktop) + remove -->
                        <div class="flex items-center gap-3 lg:gap-4 lg:shrink-0">

                            <!-- Qty stepper -->
                            <div class="gc-cart-item__qty-col shrink-0">
                                <?php
                                echo apply_filters( // phpcs:ignore
                                    'woocommerce_cart_item_quantity',
                                    woocommerce_quantity_input(
                                        [
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_qty,
                                            'min_value'    => 0,
                                            'product_name' => $product_name,
                                            'cart_context' => true,
                                        ],
                                        $product,
                                        false
                                    ),
                                    $cart_item_key,
                                    $cart_item
                                );
                                ?>
                            </div>

                            <!-- Line total (wide desktop only) -->
                            <div class="hidden xl:block w-[80px] text-right font-['Lato',sans-serif] font-bold text-[19px] text-[#2c1a0e] leading-none shrink-0 [&_.woocommerce-Price-amount]:text-inherit [&_.woocommerce-Price-currencySymbol]:text-inherit">
                                <?php echo $subtotal_html; // phpcs:ignore ?>
                            </div>

                            <!-- Remove -->
                            <div class="shrink-0 w-5 ml-auto lg:ml-0 flex items-center justify-center">
                                <?php
                                echo apply_filters( // phpcs:ignore
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="gc-cart-item__remove font-[\'Lato\',sans-serif] text-[17px] text-[#a9a29c] leading-none no-underline transition-colors hover:text-rust" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                        esc_attr( $product_id ),
                                        esc_attr( $product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>

                        </div><!-- /controls row -->

                    </div><!-- /right-side wrapper -->

                </div>
                <?php endforeach; ?>
                </div>

                <?php do_action( 'woocommerce_cart_contents' ); ?>

                <button type="submit" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"
                        class="gc-update-cart screen-reader-text" aria-hidden="true">
                    <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                </button>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                <?php do_action( 'woocommerce_after_cart_table' ); ?>

            </form>

            <!-- Order notes -->
            <div class="mt-8">
                <label class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[1.92px] uppercase block mb-3"
                       for="gc-order-notes">
                    <?php esc_html_e( 'Order notes', 'grahamisu' ); ?>
                </label>
                <textarea id="gc-order-notes" name="gc_order_notes" rows="4"
                          placeholder="<?php esc_attr_e( 'Greeting on the box, gate instructions, anything at all…', 'grahamisu' ); ?>"
                          class="w-full xl:max-w-[520px] h-[112px] bg-white border border-rust rounded-[12px] resize-none font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e] placeholder-[#a9a29c] p-[18px] box-border outline-none overflow-auto"></textarea>
            </div>

        </div>

        <!-- ── Right: Order summary card ── -->
        <div class="w-full xl:w-[380px] xl:shrink-0 bg-white border border-[#e7e1dd] rounded-[20px] p-7">

            <!-- Title -->
            <h2 class="font-primary font-normal text-[24px] text-[#2c1a0e] leading-none m-0 mb-[34px]">
                <?php esc_html_e( 'Order summary', 'grahamisu' ); ?>
            </h2>

            <!-- Subtotal + delivery rows -->
            <?php
            $subtotal    = WC()->cart->get_subtotal();
            $cart_count  = WC()->cart->get_cart_contents_count();
            $shipping    = WC()->cart->get_shipping_total();
            $order_total = WC()->cart->get_total( 'edit' );
            ?>

            <div class="flex items-center justify-between mb-3">
                <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e]">
                    <?php
                    /* translators: %d: number of items */
                    printf( esc_html__( 'Subtotal · %d item', 'grahamisu' ) . ( $cart_count !== 1 ? 's' : '' ), (int) $cart_count );
                    ?>
                </span>
                <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e]">
                    <?php echo wc_price( $subtotal ); // phpcs:ignore ?>
                </span>
            </div>

            <?php if ( $shipping > 0 ) : ?>
            <div class="flex items-center justify-between mb-3">
                <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#4e4e4e]">
                    <?php esc_html_e( 'Delivery fee', 'grahamisu' ); ?>
                </span>
                <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e]">
                    <?php echo wc_price( $shipping ); // phpcs:ignore ?>
                </span>
            </div>
            <?php endif; ?>

            <!-- Divider -->
            <div class="bg-[#e4deda] h-px my-5"></div>

            <!-- Estimated total -->
            <div class="flex items-center justify-between mb-6">
                <span class="font-['Lato',sans-serif] font-bold text-[13px] text-brown tracking-[1.82px] uppercase">
                    <?php esc_html_e( 'Estimated total', 'grahamisu' ); ?>
                </span>
                <span class="font-primary font-normal text-[28px] text-rust leading-none [&_.woocommerce-Price-amount]:text-inherit [&_.woocommerce-Price-currencySymbol]:text-inherit">
                    <?php echo wc_price( $order_total ); // phpcs:ignore ?>
                </span>
            </div>

            <!-- How to get it -->
            <p class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[1.92px] uppercase mb-3">
                <?php esc_html_e( 'How to get it', 'grahamisu' ); ?>
            </p>

            <!-- Pickup / Delivery tabs -->
            <div class="gc-fulfillment flex gap-3 mb-3"
                 role="group" aria-label="<?php esc_attr_e( 'Fulfillment method', 'grahamisu' ); ?>">

                <button type="button"
                        class="gc-fulfillment__tab flex-1 flex flex-col items-center justify-center gap-[6px] rounded-[12px] h-[74px] border border-rust cursor-pointer font-['Lato',sans-serif] font-normal text-[12px] leading-none text-[#2c1a0e] bg-white transition-colors"
                        data-tab="pickup">
                    <svg width="21" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M3 9H19V17C19 17.5304 18.7893 18.0391 18.4142 18.4142C18.0391 18.7893 17.5304 19 17 19H5C4.46957 19 3.96086 18.7893 3.58579 18.4142C3.21071 18.0391 3 17.5304 3 17V9Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 9L17 3H5L3 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 13H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span><?php esc_html_e( 'Pickup in store', 'grahamisu' ); ?></span>
                </button>

                <button type="button"
                        class="gc-fulfillment__tab is-active flex-1 flex flex-col items-center justify-center gap-[6px] rounded-[12px] h-[74px] border border-rust cursor-pointer font-['Lato',sans-serif] font-normal text-[12px] leading-none text-white bg-transparent transition-colors"
                        data-tab="delivery">
                    <svg width="26" height="18" viewBox="0 0 30 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M19 3H1V14H19V3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19 7H23L27 11V14H19V7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="7" cy="17" r="2" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="23" cy="17" r="2" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <span><?php esc_html_e( 'Delivery 10am–2pm', 'grahamisu' ); ?></span>
                </button>

            </div>

            <!-- Pickup fields (hidden by default) -->
            <div class="gc-pickup-fields flex flex-col gap-2" hidden>
                <div class="border border-rust bg-white p-[14px] rounded-[12px] box-border">
                    <label class="flex items-start gap-[10px] cursor-pointer">
                        <span class="gc-radio is-selected shrink-0 w-[18px] h-[18px] rounded-full border-2 border-rust bg-white flex items-center justify-center mt-[2px] box-border" aria-hidden="true">
                            <span class="gc-radio__dot w-[6px] h-[6px] rounded-full bg-white"></span>
                        </span>
                        <div class="flex flex-col gap-[2px]">
                            <div class="font-['Lato',sans-serif] font-bold text-[13px] leading-5 text-black">Tiramisu Tea-ramisu</div>
                            <div class="font-['Lato',sans-serif] font-normal text-[12px] leading-[18px] text-[#4e4e4e]">260B Ang Mo Kio Street 21</div>
                            <div class="font-['Lato',sans-serif] font-normal text-[12px] leading-[18px] text-[#4e4e4e]">Singapore, 562260</div>
                        </div>
                    </label>
                </div>
                <button type="button"
                        class="gc-date-picker gc-date-picker--pickup w-full flex items-center gap-3 py-[15px] px-4 bg-white border border-rust rounded-[12px] cursor-pointer font-['Lato',sans-serif] font-normal text-[13px] text-[#a9a29c] text-left box-border">
                    <svg width="17" height="19" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="1" y="3" width="16" height="16" rx="2" stroke="#6c290f" stroke-width="1.5"/>
                        <path d="M1 8H17" stroke="#6c290f" stroke-width="1.5"/>
                        <path d="M5 1V5M13 1V5" stroke="#6c290f" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="flex-1"><?php esc_html_e( 'Choose a date and time', 'grahamisu' ); ?></span>
                </button>
            </div>

            <!-- Delivery date picker (shown by default) -->
            <div class="gc-delivery-fields mb-4">
                <button type="button"
                        class="gc-date-picker gc-date-picker--delivery w-full flex items-center gap-3 h-[52px] px-4 bg-white border border-rust rounded-[12px] cursor-pointer font-['Lato',sans-serif] font-normal text-[13px] text-[#a9a29c] text-left box-border">
                    <svg width="17" height="19" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="1" y="3" width="16" height="16" rx="2" stroke="#6c290f" stroke-width="1.5"/>
                        <path d="M1 8H17" stroke="#6c290f" stroke-width="1.5"/>
                        <path d="M5 1V5M13 1V5" stroke="#6c290f" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="flex-1"><?php esc_html_e( 'Choose a date and time', 'grahamisu' ); ?></span>
                </button>
            </div>

            <!-- Checkout button -->
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
               class="gc-checkout-btn flex items-center justify-center w-full h-[56px] bg-rust text-white rounded-[100px] font-['Lato',sans-serif] font-bold text-[15px] tracking-[0.9px] no-underline transition-colors hover:bg-[#8b3515] hover:text-white mb-4"
               style="box-shadow: inset 0 0 0 2px #ffffff;">
                <?php esc_html_e( 'Check out', 'grahamisu' ); ?>
            </a>

            <!-- Disclaimer -->
            <p class="font-['Lato',sans-serif] font-normal text-[12px] text-[#838383] text-center leading-none m-0">
                <?php esc_html_e( 'Taxes and discounts calculated at checkout.', 'grahamisu' ); ?>
            </p>

        </div>

    </div>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
