<?php
/**
 * Template Name: Gallery
 *
 * @package Grahamisu
 */

get_header();

$theme_uri = get_template_directory_uri();

$gallery_items = [
    [ 'span' => 2, 'cat' => 'the-tubs',          'label' => 'The tubs',          'title' => "Craving's, halfway gone",            'img' => 'gallery-main.png' ],
    [ 'span' => 1, 'cat' => 'behind-the-layers',  'label' => 'Behind the layers', 'title' => 'Cocoa dusting, last step',            'img' => 'gallery-sm-1.png' ],
    [ 'span' => 1, 'cat' => 'behind-the-layers',  'label' => 'Behind the layers', 'title' => 'Mascarpone, folded by hand',          'img' => 'gallery-sm-2.png', 'tall' => true ],
    [ 'span' => 1, 'cat' => 'the-tubs',           'label' => 'The tubs',          'title' => 'Solo Tub, straight from the chiller', 'img' => 'product-solo-tub.png', 'tall' => true ],
    [ 'span' => 1, 'cat' => 'behind-the-layers',  'label' => 'Behind the layers', 'title' => 'Espresso soak',                       'img' => 'gallery-sm-3.png' ],
    [ 'span' => 1, 'cat' => 'yours',              'label' => 'Yours',              'title' => 'First spoon',                         'img' => 'gallery/gallery-first-spoon.png' ],
    [ 'span' => 1, 'cat' => 'yours',              'label' => 'Yours',              'title' => 'Packed for Malate pickup',            'img' => 'gallery-sm-4.png' ],
    [ 'span' => 2, 'cat' => 'behind-the-layers',  'label' => 'Behind the layers', 'title' => 'Ladyfingers, lined up',               'img' => 'banner-tiramisu.png' ],
    [ 'span' => 1, 'cat' => 'the-tubs',           'label' => 'The tubs',          'title' => 'Every layer, counted',                'img' => 'gallery/gallery-every-layer.png' ],
    [ 'span' => 1, 'cat' => 'the-tubs',           'label' => 'The tubs',          'title' => 'Super Cravings for the barkada',      'img' => 'gallery/gallery-super-cravings.png', 'tall' => true ],
    [ 'span' => 3, 'cat' => 'yours',              'label' => 'Yours',              'title' => 'Shared, always',                      'img' => 'gallery/gallery-shared-always.png' ],
];

$total = count( $gallery_items );
?>

<!-- ── Hero ─────────────────────────────────────────────────────── -->
<section class="bg-dark w-full">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] py-[60px] lg:py-[82px]">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 lg:gap-16">

            <div class="min-w-0">
                <div class="flex items-center gap-3 mb-5">
                    <span class="block w-10 h-px bg-brown flex-shrink-0"></span>
                    <span class="font-['Lato',sans-serif] font-bold text-[11px] text-[#ffe9e9] tracking-[2.42px] uppercase">Gallery</span>
                </div>
                <h1 class="font-primary font-normal text-[44px] lg:text-[62px] text-white leading-[1.02] tracking-[-0.93px] m-0">
                    Every layer, up close
                </h1>
            </div>

            <p class="font-['Lato',sans-serif] font-normal text-[16px] text-[#ffe9e9] leading-[28px] opacity-90 m-0 lg:max-w-[340px] lg:pb-3">
                The kitchen in Malate, the tubs on their way out, and the moments they land in. Shot on the days we baked.
            </p>

        </div>
    </div>
</section>

<!-- ── Filter tabs ───────────────────────────────────────────────── -->
<div class="bg-white border-b border-[#e4deda] sticky top-0 z-10">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] py-5 flex items-center justify-between gap-4">

        <div class="flex items-center gap-2 lg:gap-3 flex-wrap" role="group" aria-label="Filter photos by category">
            <button class="gc-gallery__tab is-active font-['Lato',sans-serif] font-bold text-[12px] tracking-[1.44px] uppercase h-[37px] px-5 rounded-[100px] border cursor-pointer transition-colors border-rust bg-rust text-white"
                    data-tab="all" type="button">All</button>
            <button class="gc-gallery__tab font-['Lato',sans-serif] font-bold text-[12px] tracking-[1.44px] uppercase h-[37px] px-5 rounded-[100px] border cursor-pointer transition-colors border-[#ded8d4] bg-white text-[#4e4e4e] hover:border-rust hover:text-rust"
                    data-tab="the-tubs" type="button">The tubs</button>
            <button class="gc-gallery__tab font-['Lato',sans-serif] font-bold text-[12px] tracking-[1.44px] uppercase h-[37px] px-5 rounded-[100px] border cursor-pointer transition-colors border-[#ded8d4] bg-white text-[#4e4e4e] hover:border-rust hover:text-rust"
                    data-tab="behind-the-layers" type="button">Behind the layers</button>
            <button class="gc-gallery__tab font-['Lato',sans-serif] font-bold text-[12px] tracking-[1.44px] uppercase h-[37px] px-5 rounded-[100px] border cursor-pointer transition-colors border-[#ded8d4] bg-white text-[#4e4e4e] hover:border-rust hover:text-rust"
                    data-tab="yours" type="button">Yours</button>
        </div>

        <span class="gc-gallery__count font-['Lato',sans-serif] font-normal text-[12px] text-[#838383] tracking-[1.2px] uppercase whitespace-nowrap hidden sm:block">
            <?php echo esc_html( $total ); ?> photos
        </span>

    </div>
</div>

<!-- ── Gallery grid ──────────────────────────────────────────────── -->
<section class="w-full py-8 lg:py-12">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px]">

        <div class="gc-gallery__grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-[18px]">

        <?php foreach ( $gallery_items as $item ) :
            $span_map   = [ 1 => 'lg:col-span-1', 2 => 'sm:col-span-2 lg:col-span-2', 3 => 'sm:col-span-2 lg:col-span-3' ];
            $span_class = $span_map[ $item['span'] ] ?? 'lg:col-span-1';
            $img_url    = ! empty( $item['img'] ) ? $theme_uri . '/assets/images/' . $item['img'] : '';
            $tall       = ! empty( $item['tall'] );
        ?>
        <div class="gc-gallery__card relative overflow-hidden rounded-[18px] bg-[#efe9e5] h-[220px] sm:h-[280px] lg:h-[324px] <?php echo esc_attr( $span_class ); ?>"
             data-category="<?php echo esc_attr( $item['cat'] ); ?>"
             data-img="<?php echo esc_attr( $img_url ); ?>"
             data-label="<?php echo esc_attr( $item['label'] ); ?>"
             data-title="<?php echo esc_attr( $item['title'] ); ?>"
             role="button"
             tabindex="0"
             aria-label="<?php echo esc_attr( 'View: ' . $item['title'] ); ?>">

            <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>"
                 alt="<?php echo esc_attr( $item['title'] ); ?>"
                 class="absolute inset-0 w-full h-full object-cover">
            <?php endif; ?>

            <div class="gc-gallery__caption absolute bottom-0 left-0 right-0 <?php echo $tall ? 'h-[128px]' : 'h-[104px]'; ?> flex flex-col justify-end px-5 pb-5"
                 style="background:linear-gradient(to top,rgba(44,26,14,.86) 0%,rgba(44,26,14,0) 100%)">
                <div class="gc-gallery__caption-inner">
                    <span class="font-['Lato',sans-serif] font-bold text-[10px] text-[#f2b705] tracking-[1.8px] uppercase leading-none mb-[7px] block">
                        <?php echo esc_html( $item['label'] ); ?>
                    </span>
                    <p class="font-primary font-medium text-[17px] lg:text-[19px] text-white leading-[1.3] m-0">
                        <?php echo esc_html( $item['title'] ); ?>
                    </p>
                </div>
            </div>

        </div>
        <?php endforeach; ?>

        </div>

    </div>
</section>

<!-- ── CTA ───────────────────────────────────────────────────────── -->
<section class="bg-white border-t border-[#e4deda] w-full">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[100px] py-[60px] lg:py-[80px] flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">

        <div class="max-w-[500px]">
            <h2 class="font-['Cormorant_Garamond',serif] font-semibold italic text-[30px] lg:text-[38px] text-[#2c1a0e] leading-[1.25] m-0 mb-5">
                Tag us and your tub ends up here
            </h2>
            <p class="font-['Lato',sans-serif] font-normal text-[16px] text-[#4e4e4e] leading-[27.2px] m-0">
                Post your first spoon with
                <span class="font-bold text-rust">#Grahamisu</span>
                — we feature a new one every week.
            </p>
        </div>

        <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
           class="shrink-0 inline-flex items-center justify-center h-[55px] px-10 bg-rust text-white rounded-[100px] font-['Lato',sans-serif] font-bold text-[14px] tracking-[0.84px] no-underline transition-colors hover:bg-[#8b3515] hover:text-white whitespace-nowrap"
           style="box-shadow:inset 0 0 0 2px #fff">
            Order a tub
        </a>

    </div>
</section>

<?php get_footer(); ?>
