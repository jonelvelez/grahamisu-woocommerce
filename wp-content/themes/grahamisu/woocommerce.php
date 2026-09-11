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
        $size_map = [
            'solo-tub'       => '250 ML',
            'cravings'       => '500 ML',
            'satiesfied'     => '1350 ML',
            'super-cravings' => '2300 ML',
        ];
        $serves_map = [
            'solo-tub'       => 'Serves 1',
            'cravings'       => 'Serves 2–3',
            'satiesfied'     => 'Serves 5–6',
            'super-cravings' => 'Serves 10–12',
        ];
        $badge_map = [
            'cravings'       => 'Best Seller',
            'super-cravings' => 'Best Value',
        ];

        $products_query = new WP_Query( [
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_price',
            'order'          => 'ASC',
        ] );
        $product_count = $products_query->found_posts;
        ?>

        <!-- ── Title bar ─────────────────────────────────────────── -->
        <div class="bg-white border-b border-[#e4deda]">
            <div class="max-w-[1240px] mx-auto px-6 pt-8 pb-8 lg:px-8 lg:pt-[64px] lg:pb-[56px] grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,340px)] gap-8 lg:gap-16 items-end">
                <div>
                    <p class="font-['Lato',sans-serif] font-bold text-[12px] tracking-[.20em] uppercase text-rust m-0">Find your size</p>
                    <h1 class="font-primary font-normal text-[48px] lg:text-[60px] leading-[1.02] tracking-[-0.015em] text-[#2c1a0e] m-0 mt-[14px]">Our products</h1>
                </div>
                <p class="font-['Lato',sans-serif] text-[16px] leading-[1.7] text-[#4e4e4e] m-0 mb-[10px]">
                    From solo cravings to family feasts — there's a Grahamisu for every moment. Every tub is layered fresh the day before it reaches you.
                </p>
            </div>
        </div>

        <!-- ── Filter bar ────────────────────────────────────────── -->
        <div class="max-w-[1240px] mx-auto px-6 lg:px-8 pt-[26px] flex items-center justify-between gap-6 flex-wrap">
            <p class="font-['Lato',sans-serif] text-[13px] tracking-[.06em] text-muted m-0">
                Showing all <strong class="text-[#2c1a0e]"><?php echo esc_html( $product_count ); ?> tub<?php echo $product_count !== 1 ? 's' : ''; ?></strong>
            </p>
            <div class="flex items-center gap-[10px]">
                <span class="font-['Lato',sans-serif] font-bold text-[11px] tracking-[.16em] uppercase text-[#838383]">Sort</span>
                <div class="flex gap-[6px]">
                    <span class="px-4 py-[9px] rounded-[100px] bg-rust text-white font-['Lato',sans-serif] font-bold text-[12px] tracking-[.04em] leading-none">Size</span>
                    <span class="px-4 py-[9px] rounded-[100px] border border-[#ded8d4] bg-white text-[#4e4e4e] font-['Lato',sans-serif] text-[12px] tracking-[.04em] leading-none">Price</span>
                    <span class="px-4 py-[9px] rounded-[100px] border border-[#ded8d4] bg-white text-[#4e4e4e] font-['Lato',sans-serif] text-[12px] tracking-[.04em] leading-none">Popular</span>
                </div>
            </div>
        </div>

        <!-- ── Product grid ──────────────────────────────────────── -->
        <div class="max-w-[1240px] mx-auto px-6 lg:px-8 pt-[28px] pb-12 lg:pb-[88px]">

            <?php if ( $products_query->have_posts() ) : ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while ( $products_query->have_posts() ) : $products_query->the_post();
                    $product   = wc_get_product( get_the_ID() );
                    $slug      = $product->get_slug();
                    $name      = $product->get_name();
                    $url       = get_permalink();
                    $img_id    = $product->get_image_id();
                    $img_src   = $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_single' ) : wc_placeholder_img_src();
                    $volume    = $product->get_attribute( 'size' )
                        ?: get_post_meta( get_the_ID(), '_size_label', true )
                        ?: ( $size_map[ $slug ] ?? '' );
                    $serves    = get_post_meta( get_the_ID(), '_serves_label', true )
                        ?: ( $serves_map[ $slug ] ?? '' );
                    $badge     = get_post_meta( get_the_ID(), '_shop_badge', true )
                        ?: ( $badge_map[ $slug ] ?? '' );
                    $atc_url   = esc_url( $product->add_to_cart_url() );
                ?>
                <div class="flex flex-col bg-white border border-[#e7e1dd] rounded-[18px] overflow-hidden group transition-[transform,box-shadow,border-color] duration-[220ms] ease-out hover:-translate-y-[6px] hover:shadow-[0_24px_48px_rgba(55,22,19,.16)] hover:border-[#b8542c]">

                    <!-- Image -->
                    <a href="<?php echo esc_url( $url ); ?>" class="block relative overflow-hidden" style="height:240px">
                        <img src="<?php echo esc_url( $img_src ); ?>"
                             alt="<?php echo esc_attr( $name ); ?>"
                             class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-[1.03]">
                        <!-- Badges overlay -->
                        <div class="absolute left-[14px] right-[14px] top-[14px] flex flex-wrap items-start justify-between gap-2">
                            <?php if ( $badge ) : ?>
                            <span class="px-[14px] py-[6px] rounded-[100px] bg-white font-['Lato',sans-serif] font-bold text-[10px] tracking-[.14em] uppercase text-rust leading-none">
                                <?php echo esc_html( $badge ); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ( $volume ) : ?>
                            <span class="ml-auto px-[12px] py-[6px] rounded-[100px] font-['Lato',sans-serif] font-bold text-[10px] tracking-[.14em] uppercase text-white leading-none" style="background:rgba(44,26,14,.78)">
                                <?php echo esc_html( $volume ); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <!-- Card body -->
                    <div class="flex-1 flex flex-col gap-2 px-[22px] pt-[22px] pb-[22px]">
                        <a href="<?php echo esc_url( $url ); ?>"
                           class="font-primary font-semibold text-[23px] leading-[1.2] text-[#2c1a0e] no-underline hover:text-rust transition-colors m-0">
                            <?php echo esc_html( $name ); ?>
                        </a>
                        <?php if ( $serves ) : ?>
                        <p class="font-['Lato',sans-serif] text-[12px] tracking-[.1em] uppercase text-[#838383] m-0">
                            <?php echo esc_html( $serves ); ?>
                        </p>
                        <?php endif; ?>
                        <div class="flex items-center gap-2 mt-[2px]">
                            <span class="text-[#f2b705] text-[11px] tracking-[.1em]">★★★★★</span>
                            <span class="font-['Lato',sans-serif] text-[12px] text-[#838383]">4.9</span>
                        </div>
                        <div class="flex-1"></div>
                        <!-- Footer: price + add -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-4 border-t border-[#efeae7]">
                            <span class="font-primary text-[24px] text-rust leading-none">
                                <?php echo wp_kses_post( wc_price( $product->get_price() ) ); ?>
                            </span>
                            <a href="<?php echo $atc_url; ?>"
                               class="px-[18px] py-[11px] rounded-[100px] border-[1.5px] border-rust text-rust font-['Lato',sans-serif] font-bold text-[11px] tracking-[.12em] uppercase no-underline shrink-0 leading-none transition-colors hover:bg-rust hover:text-white">
                                <?php echo esc_html( $product->add_to_cart_text() ); ?>
                            </a>
                        </div>
                    </div>

                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- Info cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-[56px]">
                <div class="bg-white border border-[#e7e1dd] rounded-[18px] px-6 py-[26px] flex flex-col gap-2">
                    <span class="font-primary text-[17px] text-[#b8542c]">01</span>
                    <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e]">Not sure which size?</span>
                    <span class="font-['Lato',sans-serif] text-[14px] leading-[1.6] text-[#4e4e4e]">Solo Tub is one generous serving. Craving's shares between two. Anything bigger is for the barkada.</span>
                </div>
                <div class="bg-white border border-[#e7e1dd] rounded-[18px] px-6 py-[26px] flex flex-col gap-2">
                    <span class="font-primary text-[17px] text-[#b8542c]">02</span>
                    <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e]">Order a day ahead</span>
                    <span class="font-['Lato',sans-serif] text-[14px] leading-[1.6] text-[#4e4e4e]">Every tub is layered the day before your slot, so the ladyfingers are still soft when it arrives.</span>
                </div>
                <div class="bg-white border border-[#e7e1dd] rounded-[18px] px-6 py-[26px] flex flex-col gap-2">
                    <span class="font-primary text-[17px] text-[#b8542c]">03</span>
                    <span class="font-['Lato',sans-serif] font-bold text-[15px] text-[#2c1a0e]">Free delivery over ₱1,000</span>
                    <span class="font-['Lato',sans-serif] text-[14px] leading-[1.6] text-[#4e4e4e]">Metro Manila, 10am–2pm. Pickup in Malate is always free.</span>
                </div>
            </div>

            <?php else : ?>
            <p class="font-['Lato',sans-serif] text-muted text-center py-20">No products found.</p>
            <?php endif; ?>

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

    <?php elseif ( is_singular( 'product' ) ) : ?>

        <?php woocommerce_content(); ?>

    <?php else : ?>

        <div class="container">
            <?php woocommerce_content(); ?>
        </div>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
