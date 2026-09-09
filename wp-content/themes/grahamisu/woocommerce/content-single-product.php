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

<!-- Breadcrumb -->
<div class="bg-surface">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] py-[26px] flex items-center gap-2">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[1.44px] uppercase no-underline hover:text-rust transition-colors">
            Home
        </a>
        <span class="font-['Lato',sans-serif] text-[12px] text-muted">/</span>
        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="font-['Lato',sans-serif] font-normal text-[12px] text-muted tracking-[1.44px] uppercase no-underline hover:text-brown transition-colors">
            Shop
        </a>
        <span class="font-['Lato',sans-serif] text-[12px] text-muted">/</span>
        <span class="font-['Lato',sans-serif] font-normal text-[12px] text-[#2c1a0e] tracking-[1.44px] uppercase">
            <?php the_title(); ?>
        </span>
    </div>
</div>

<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'sp', $product ); ?>>
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] pt-[32px] lg:pt-[40px] pb-16 lg:pb-[83px] flex flex-col lg:flex-row items-start gap-8 lg:gap-12">

        <!-- ── Gallery (left) ── -->
        <div class="sp__gallery w-full lg:shrink-0 lg:w-[556px]">

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
            <div class="sp__carousel flex items-center gap-3 mt-4"
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

        <!-- ── Details (right) ── -->
        <div class="sp__details flex-1 min-w-0">

            <!-- Title -->
            <?php the_title( '<h1 class="sp__title font-primary text-[40px] lg:text-[52px] font-normal text-[#2c1a0e] leading-[1.08] tracking-[-0.52px] mb-4">', '</h1>' ); ?>

            <!-- Stars + reviews -->
            <div class="flex items-center gap-3 mb-5">
                <span class="font-['Lato',sans-serif] font-normal text-[15px] text-[#f2b705] tracking-[2.1px] leading-none">★★★★★</span>
                <span class="font-['Lato',sans-serif] font-normal text-[14px] text-muted leading-none">4.9 · 116 reviews</span>
            </div>

            <!-- Price + size label -->
            <div class="flex items-baseline gap-4 mb-5">
                <span class="font-primary font-normal text-[38px] text-rust leading-[38px]">
                    <?php echo wc_price( $current_price ); ?>
                </span>
                <?php if ( $current_size ) : ?>
                <span class="font-['Lato',sans-serif] font-bold text-[13px] text-muted tracking-[1.82px] uppercase leading-none">
                    <?php echo esc_html( $current_size ); ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <?php if ( $description ) : ?>
            <div class="font-['Lato',sans-serif] font-normal text-[16px] text-[#4e4e4e] leading-[27.2px] mb-6">
                <?php echo wp_kses_post( $description ); ?>
            </div>
            <?php endif; ?>

            <!-- Divider -->
            <div class="bg-[#e4deda] h-px mb-6"></div>

            <!-- Size cards -->
            <?php if ( ! empty( $size_cards ) ) : ?>
            <p class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[1.92px] uppercase leading-none mb-4">
                <?php esc_html_e( 'Size', 'grahamisu' ); ?>
            </p>
            <div class="grid grid-cols-2 gap-3 mb-6">
                <?php foreach ( $size_cards as $card ) : ?>
                <a href="<?php echo esc_url( $card['url'] ); ?>"
                   class="sp-size-pill<?php echo $card['active'] ? ' is-active' : ''; ?> flex flex-col justify-center rounded-[14px] border h-[67px] px-[18px] no-underline transition-colors">
                    <span class="sp-size-pill__name font-primary font-semibold text-[17px] leading-[20px]">
                        <?php echo esc_html( $card['name'] ); ?>
                    </span>
                    <?php if ( $card['size_label'] || $card['price'] ) : ?>
                    <span class="sp-size-pill__meta font-['Lato',sans-serif] font-bold text-[11px] tracking-[1.54px] uppercase opacity-80 leading-none mt-1">
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

            <!-- Quantity label + line total -->
            <div class="flex items-center justify-between mb-3">
                <span class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[1.92px] uppercase leading-none">
                    <?php esc_html_e( 'Quantity', 'grahamisu' ); ?>
                </span>
                <span id="sp-line-total"
                      class="font-['Lato',sans-serif] font-normal text-[12px] text-muted tracking-[1.2px] uppercase leading-none"
                      data-price="<?php echo esc_attr( $current_price ); ?>">
                    <?php echo esc_html__( 'Line total', 'grahamisu' ) . ' ' . strip_tags( wc_price( $current_price ) ); ?>
                </span>
            </div>

            <!-- WooCommerce add-to-cart form (qty + button) -->
            <?php woocommerce_template_single_add_to_cart(); ?>

            <!-- Info rows -->
            <?php if ( ! empty( $info_rows ) ) : ?>
            <div class="sp-info mt-6 border-t border-[#e4deda]">
                <?php foreach ( $info_rows as $idx => $row ) : ?>
                <div class="flex items-center gap-6 py-[18px] border-b border-[#e4deda]">
                    <span class="font-primary font-normal text-[15px] text-[#b8542c] leading-none shrink-0">
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
