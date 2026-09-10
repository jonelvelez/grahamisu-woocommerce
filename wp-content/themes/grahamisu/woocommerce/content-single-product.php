<?php
/**
 * Single product content — Grahamisu custom layout.
 *
 * Size family grouping: tag all related size products with the same tag
 * prefixed "grp-" (e.g. "grp-tiramisu"). The template auto-builds size cards.
 * Set a custom attribute "size" (e.g. "250 ML") on each product for the size label.
 *
 * @package Grahamisu
 */

defined( 'ABSPATH' ) || exit;
global $product;

/* ── Images ─────────────────────────────────────────────────── */
$main_id   = $product->get_image_id();
$gallery   = $product->get_gallery_image_ids();
$all_ids   = array_values( array_filter( array_merge( $main_id ? [ $main_id ] : [], $gallery ) ) );
$thumb_ids = array_slice( $all_ids, 0, 3 );
$all_srcs  = array_map( function ( $id ) {
    $src = wp_get_attachment_image_src( $id, 'woocommerce_single' );
    return $src ? $src[0] : '';
}, $all_ids );

/* ── Size family cards (products sharing a grp-* tag) ───────── */
$size_cards = [];
$tags       = wp_get_post_terms( $product->get_id(), 'product_tag' );
foreach ( $tags as $tag ) {
    if ( strpos( $tag->slug, 'grp-' ) === 0 ) {
        $siblings = wc_get_products( [
            'tag'     => [ $tag->slug ],
            'limit'   => 10,
            'orderby' => 'menu_order',
            'order'   => 'ASC',
            'status'  => 'publish',
        ] );
        foreach ( $siblings as $sibling ) {
            $size_label = $sibling->get_attribute( 'size' )
                ?: get_post_meta( $sibling->get_id(), '_size_label', true );
            $size_cards[] = [
                'name'       => $sibling->get_name(),
                'url'        => get_permalink( $sibling->get_id() ),
                'active'     => $sibling->get_id() === $product->get_id(),
                'size_label' => $size_label,
                'price'      => strip_tags( wc_price( $sibling->get_price() ) ),
            ];
        }
        break;
    }
}

/* ── Current product details ─────────────────────────────────── */
$current_size  = $product->get_attribute( 'size' )
    ?: get_post_meta( $product->get_id(), '_size_label', true );
$current_price = $product->get_price();
$description   = $product->get_short_description() ?: $product->get_description();

/* ── Info rows ──────────────────────────────────────────────── */
$info_rows = array_filter( [
    get_post_meta( $product->get_id(), '_info_01', true ),
    get_post_meta( $product->get_id(), '_info_02', true ),
    get_post_meta( $product->get_id(), '_info_03', true ),
] );
if ( empty( $info_rows ) ) {
    $info_rows = [
        'Made fresh the day before your pickup or delivery slot.',
        'Keep chilled. Best within 3 days of pickup.',
        'Contains dairy, eggs, wheat and coffee.',
    ];
}
$info_rows = array_values( $info_rows );
?>

<?php do_action( 'woocommerce_before_single_product' ); ?>
<?php if ( post_password_required() ) { echo get_the_password_form(); return; } ?>

<div class="max-w-[1240px] mx-auto px-8 pt-[34px] pb-24">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-[10px] mb-[34px]">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[.12em] uppercase no-underline hover:text-rust transition-colors">
            Home
        </a>
        <span class="font-['Lato',sans-serif] text-[12px] text-muted">/</span>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-muted tracking-[.12em] uppercase no-underline hover:text-brown transition-colors">
            Shop
        </a>
        <span class="font-['Lato',sans-serif] text-[12px] text-muted">/</span>
        <span class="font-['Lato',sans-serif] font-normal text-[12px] text-[#2c1a0e] tracking-[.12em] uppercase">
            <?php the_title(); ?>
        </span>
    </div>

    <article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sp', $product ); ?>>

        <!-- ── Two-column grid ── -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">

            <!-- ── Gallery (left column) ── -->
            <div class="sp__gallery flex flex-col gap-4">

                <div class="sp__main-wrap aspect-square bg-white border border-[#e7e1dd] rounded-[20px] overflow-hidden">
                    <?php if ( $main_id ) : ?>
                        <?php echo wp_get_attachment_image( $main_id, 'woocommerce_single', false, [
                            'class' => 'sp__main-img w-full h-full object-cover block transition-opacity duration-200',
                            'id'    => 'sp-main-img',
                        ] ); ?>
                    <?php else : ?>
                        <?php echo wc_placeholder_img( 'woocommerce_single', [
                            'class' => 'sp__main-img w-full h-full object-cover block',
                        ] ); ?>
                    <?php endif; ?>
                </div>

                <?php if ( count( $thumb_ids ) > 1 ) : ?>
                <div class="sp__carousel flex items-center justify-center gap-[14px]"
                     data-images="<?php echo esc_attr( wp_json_encode( array_values( $all_srcs ) ) ); ?>">
                    <?php foreach ( $thumb_ids as $i => $img_id ) : ?>
                    <button class="sp__thumb<?php echo $i === 0 ? ' is-active' : ''; ?>"
                            type="button"
                            data-index="<?php echo esc_attr( $i ); ?>"
                            aria-label="<?php echo esc_attr( sprintf( __( 'View image %d', 'grahamisu' ), $i + 1 ) ); ?>">
                        <?php echo wp_get_attachment_image( $img_id, [ 96, 96 ], false, [
                            'draggable' => 'false',
                        ] ); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

            <!-- ── Details (right column) ── -->
            <div class="sp__details flex flex-col items-start">

                <!-- Title -->
                <?php the_title( '<h1 class="sp__title font-primary text-[52px] font-normal text-[#2c1a0e] leading-[1.08] tracking-[-0.01em] m-0">', '</h1>' ); ?>

                <!-- Stars + reviews -->
                <div class="flex items-center gap-3 mt-4">
                    <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#f2b705] tracking-[.14em] leading-none">★★★★★</span>
                    <span class="font-['Lato',sans-serif] font-normal text-[14px] text-muted leading-none">4.9 · 116 reviews</span>
                </div>

                <!-- Price + size label -->
                <div class="flex items-baseline gap-[10px] mt-[22px]">
                    <span class="font-primary font-normal text-[38px] text-rust leading-[1]">
                        <?php echo wc_price( $current_price ); ?>
                    </span>
                    <?php if ( $current_size ) : ?>
                    <span class="font-['Lato',sans-serif] font-bold text-[13px] text-muted tracking-[.14em] uppercase leading-none">
                        <?php echo esc_html( $current_size ); ?>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <?php if ( $description ) : ?>
                <div class="font-['Lato',sans-serif] font-normal text-[16px] text-[#4e4e4e] leading-[1.7] mt-[22px] max-w-[52ch]">
                    <?php echo wp_kses_post( $description ); ?>
                </div>
                <?php endif; ?>

                <!-- Divider -->
                <div class="w-full h-px bg-[#e4deda] my-[30px]"></div>

                <!-- Size cards -->
                <?php if ( ! empty( $size_cards ) ) : ?>
                <p class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[.16em] uppercase leading-none m-0">
                    <?php esc_html_e( 'Size', 'grahamisu' ); ?>
                </p>
                <div class="grid grid-cols-2 gap-3 w-full mt-[14px]">
                    <?php foreach ( $size_cards as $card ) : ?>
                    <a href="<?php echo esc_url( $card['url'] ); ?>"
                       class="sp-size-pill<?php echo $card['active'] ? ' is-active' : ''; ?> flex flex-col justify-center rounded-[14px] border h-[67px] px-[18px] no-underline transition-colors">
                        <span class="sp-size-pill__name font-primary font-semibold text-[17px] leading-[1.2]">
                            <?php echo esc_html( $card['name'] ); ?>
                        </span>
                        <?php if ( $card['size_label'] || $card['price'] ) : ?>
                        <span class="sp-size-pill__meta font-['Lato',sans-serif] font-bold text-[11px] tracking-[.14em] uppercase opacity-80 leading-none mt-1">
                            <?php
                            $meta_parts = array_filter( [ $card['size_label'], $card['price'] ] );
                            echo esc_html( implode( ' · ', $meta_parts ) );
                            ?>
                        </span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Quantity + ATC form: side-by-side layout matching design -->
                <?php
                $min_qty     = apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product );
                $max_qty     = apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product );
                $atc_input   = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity();
                ?>
                <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
                <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
                    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                    <div class="flex items-end gap-5 w-full mt-[30px]">

                        <!-- Left: Quantity label + stepper -->
                        <div class="flex flex-col gap-[14px]">
                            <span class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[.16em] uppercase leading-none">
                                <?php esc_html_e( 'Quantity', 'grahamisu' ); ?>
                            </span>
                            <?php woocommerce_quantity_input( [
                                'min_value'   => $min_qty,
                                'max_value'   => $max_qty,
                                'input_value' => $atc_input,
                            ] ); ?>
                        </div>

                        <!-- Right: Line total + ATC button -->
                        <div class="flex-1 min-w-0 flex flex-col gap-[14px]">
                            <span id="sp-line-total"
                                  class="font-['Lato',sans-serif] font-normal text-[12px] text-muted tracking-[.1em] uppercase leading-none text-right"
                                  data-price="<?php echo esc_attr( $current_price ); ?>">
                                <?php echo esc_html__( 'Line total', 'grahamisu' ) . ' ' . strip_tags( wc_price( $current_price ) ); ?>
                            </span>
                            <button type="submit"
                                    name="add-to-cart"
                                    value="<?php echo esc_attr( $product->get_id() ); ?>"
                                    class="single_add_to_cart_button button alt"
                                    style="width:100%;display:flex;align-items:center;justify-content:center;">
                                <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                            </button>
                        </div>

                    </div>

                    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
                </form>
                <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

                <!-- Info rows -->
                <?php if ( ! empty( $info_rows ) ) : ?>
                <div class="sp-info w-full mt-[34px] border-t border-[#e4deda]">
                    <?php foreach ( $info_rows as $idx => $row ) : ?>
                    <div class="flex items-center gap-[14px] py-[18px] border-b border-[#e4deda]">
                        <span class="font-primary font-normal text-[15px] text-[#b8542c] leading-none shrink-0 min-w-[24px]">
                            <?php echo esc_html( sprintf( '%02d', $idx + 1 ) ); ?>
                        </span>
                        <span class="font-['Lato',sans-serif] font-normal text-[14px] text-[#4e4e4e] leading-none">
                            <?php echo esc_html( $row ); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

            </div>

        </div>

    </article>

</div>

<script>
(function () {
    var totalEl = document.getElementById('sp-line-total');
    if (!totalEl) return;
    var basePrice = parseFloat(totalEl.dataset.price) || 0;
    var qtyInput  = document.querySelector('.sp form.cart input.qty');
    if (!qtyInput) return;

    function updateTotal() {
        var qty   = Math.max(1, parseInt(qtyInput.value, 10) || 1);
        var total = (basePrice * qty).toFixed(2);
        totalEl.textContent = 'Line total ₱' + parseFloat(total).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }

    qtyInput.addEventListener('change', updateTotal);
    qtyInput.addEventListener('input', updateTotal);

    document.addEventListener('click', function (e) {
        if (e.target.closest('.sp-qty-btn')) {
            setTimeout(updateTotal, 10);
        }
    });
}());
</script>

<?php do_action( 'woocommerce_after_single_product' ); ?>
