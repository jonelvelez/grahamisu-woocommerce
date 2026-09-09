<?php
/**
 * WooCommerce wrapper template.
 * Provides the header/footer around WooCommerce pages.
 *
 * @package Grahamisu
 */
get_header();
?>

<main class="site-main woocommerce-page">

    <?php if ( is_checkout() ) : ?>

        <?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

    <?php elseif ( is_shop() ) : ?>

        <?php
        $size_map = array(
            'solo-tub'       => '250 ML',
            'cravings'       => '500 ML',
            'satiesfied'     => '1350 ML',
            'super-cravings' => '2300 ML',
        );

        $products_query = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_price',
            'order'          => 'ASC',
        ) );
        ?>

        <!-- ── Title bar ─────────────────────────────────────────── -->
        <div class="bg-white border-b border-[#e8ddd0] w-full">
            <div class="w-full max-w-[1440px] mx-auto h-auto lg:h-[200px] flex flex-col justify-center px-4 lg:px-[141px] py-8 lg:py-0">
                <p class="font-['Inter',sans-serif] font-normal text-xs tracking-[0.18em] uppercase text-[--color-muted] m-0 mb-2">
                    Find your size
                </p>
                <div class="flex items-baseline justify-between gap-8">
                    <h1 class="font-script font-normal text-5xl lg:text-[72px] leading-[1] text-[--color-dark] m-0 shrink-0">
                        Our Products
                    </h1>
                    <p class="font-['Lato',sans-serif] text-sm text-[--color-muted] m-0">
                        From solo cravings to family feasts — there's a Grahamisu for every moment.
                    </p>
                </div>
            </div>
        </div>

        <!-- ── Product grid ──────────────────────────────────────── -->
        <div class="bg-[--color-bg] w-full py-16">
            <div class="max-w-[1440px] mx-auto px-4 lg:px-[120px]">

                <?php if ( $products_query->have_posts() ) : ?>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 items-start">
                    <?php while ( $products_query->have_posts() ) : $products_query->the_post();
                        $product = wc_get_product( get_the_ID() );
                        $slug    = $product->get_slug();
                        $name    = $product->get_name();
                        $price   = wc_price( $product->get_price() );
                        $url     = get_permalink();
                        $img_id  = $product->get_image_id();
                        $img_src = $img_id
                            ? wp_get_attachment_image_url( $img_id, 'woocommerce_single' )
                            : wc_placeholder_img_src();
                        $volume  = isset( $size_map[ $slug ] ) ? $size_map[ $slug ] : '';
                    ?>
                    <div class="flex flex-col bg-white border border-[#e8ddd0] shadow-[0_2px_8px_rgba(0,0,0,0.05)] overflow-hidden group">

                        <!-- Image: fixed 280px, always fills with object-cover -->
                        <a href="<?php echo esc_url( $url ); ?>" class="block">
                            <div style="height:280px;overflow:hidden;background:var(--color-surface);">
                                <img src="<?php echo esc_url( $img_src ); ?>"
                                     alt="<?php echo esc_attr( $name ); ?>"
                                     style="width:100%;height:100%;object-fit:cover;object-position:center;display:block;transition:transform .5s ease;"
                                     class="group-hover:scale-[1.03]">
                            </div>
                        </a>

                        <!-- Card body -->
                        <div class="px-6 pt-5 pb-6 flex flex-col gap-3">

                            <?php if ( $volume ) : ?>
                            <span class="font-['Inter',sans-serif] text-[11px] tracking-[0.15em] uppercase text-[--color-gold]">
                                <?php echo esc_html( $volume ); ?>
                            </span>
                            <?php endif; ?>

                            <a href="<?php echo esc_url( $url ); ?>"
                               class="font-primary font-semibold text-[22px] leading-tight text-[--color-brown] no-underline hover:text-[--color-rust] transition-colors">
                                <?php echo esc_html( $name ); ?>
                            </a>

                            <p class="font-['Lato',sans-serif] font-bold text-lg text-[--color-dark] m-0">
                                <?php echo wp_kses_post( $price ); ?>
                            </p>

                            <a href="<?php echo esc_url( $url ); ?>"
                               class="mt-2 block text-center bg-[--color-rust] text-white font-['Lato',sans-serif] font-semibold text-sm py-3 no-underline hover:opacity-90 transition-opacity">
                                View Product
                            </a>

                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <?php else : ?>
                <p class="font-['Lato',sans-serif] text-[--color-muted] text-center py-20">
                    No products found.
                </p>
                <?php endif; ?>

            </div>
        </div>

    <?php elseif ( is_account_page() ) : ?>

        <!-- ── Title bar ─────────────────────────────────────────── -->
        <div class="bg-white border-b border-[#e8ddd0] w-full">
            <div class="w-full max-w-[1440px] mx-auto h-auto lg:h-[200px] flex items-center px-4 lg:px-[141px] py-8 lg:py-0">
                <h1 class="font-script font-normal text-[48px] lg:text-[80px] leading-[1.1] text-[--color-dark] m-0">
                    My Account
                </h1>
            </div>
        </div>

        <!-- ── Account content ───────────────────────────────────── -->
        <div class="bg-[--color-bg] w-full min-h-[50vh]">
            <?php woocommerce_content(); ?>
        </div>

    <?php else : ?>

        <div class="container">
            <?php woocommerce_content(); ?>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
