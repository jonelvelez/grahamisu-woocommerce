<?php
$img      = get_template_directory_uri() . '/assets/images/';
$shop_url = function_exists( 'wc_get_page_id' )
    ? get_permalink( wc_get_page_id( 'shop' ) )
    : home_url( '/shop' );
?>

<section class="bg-white w-full overflow-hidden">
    <div class="max-w-[1440px] mx-auto pt-[82px] pb-[80px]">

        <div class="flex items-end justify-between px-6 lg:pl-[146px] lg:pr-[167px] mb-[23px]">
            <div>
                <p class="font-['Lato',sans-serif] font-normal text-[12px] tracking-[2.04px] text-brown uppercase m-0 mb-2">
                    Every layer tells a story
                </p>
                <h2 class="font-['Cormorant_Garamond',serif] italic font-normal text-[36px] lg:text-[42px] text-[#2c1a0e] leading-[1.2] m-0">
                    Layers of coffee, cream and<br class="hidden lg:block"> pure indulgence
                </h2>
            </div>
            <a href="/gallery"
               class="font-['Lato',sans-serif] font-normal text-[12px] tracking-[2.04px] text-brown underline uppercase whitespace-nowrap decoration-brown hover:text-[#2c1a0e] hover:decoration-[#2c1a0e] hidden lg:block">
                View all →
            </a>
        </div>

        <div class="flex flex-col lg:flex-row items-start gap-[26px] px-6 lg:pl-[143px] lg:pr-[161px]">

            <div class="relative w-full lg:shrink-0 lg:w-[551px] h-[300px] lg:h-[700px] rounded-[20px] overflow-hidden">
                <picture>
                    <source srcset="<?php echo esc_url( $img . 'gallery-main.webp' ); ?>" type="image/webp">
                    <img src="<?php echo esc_url( $img . 'gallery-main.png' ); ?>"
                         alt="Best Seller Grahamisu tiramisu"
                         width="551" height="700"
                         class="w-full h-full object-cover block rounded-[20px]">
                </picture>
                <span class="absolute top-[23px] left-[23px] bg-white rounded-[100px] h-[40px] px-5 flex items-center font-primary font-extrabold text-[18px] leading-none text-brown whitespace-nowrap">
                    Best Seller
                </span>
            </div>

            <div class="w-full lg:flex-1 lg:min-w-0 grid grid-cols-2 gap-4 lg:grid-rows-[335px_335px] lg:gap-x-[31px] lg:gap-y-[30px]">
                <div class="rounded-[20px] overflow-hidden h-[180px] lg:h-full">
                    <picture>
                        <source srcset="<?php echo esc_url( $img . 'gallery-sm-1.webp' ); ?>" type="image/webp">
                        <img src="<?php echo esc_url( $img . 'gallery-sm-1.png' ); ?>"
                             alt="Grahamisu tiramisu cake with cacao dusting"
                             width="263" height="335"
                             class="w-full h-full object-cover block">
                    </picture>
                </div>
                <div class="rounded-[20px] overflow-hidden h-[180px] lg:h-full">
                    <picture>
                        <source srcset="<?php echo esc_url( $img . 'gallery-sm-2.webp' ); ?>" type="image/webp">
                        <img src="<?php echo esc_url( $img . 'gallery-sm-2.png' ); ?>"
                             alt="Grahamisu tiramisu slice served on plate"
                             width="263" height="335"
                             class="w-full h-full object-cover block">
                    </picture>
                </div>
                <div class="rounded-[20px] overflow-hidden h-[180px] lg:h-full">
                    <picture>
                        <source srcset="<?php echo esc_url( $img . 'gallery-sm-3.webp' ); ?>" type="image/webp">
                        <img src="<?php echo esc_url( $img . 'gallery-sm-3.png' ); ?>"
                             alt="Grahamisu tiramisu cake layers closeup"
                             width="263" height="335"
                             class="w-full h-full object-cover block">
                    </picture>
                </div>
                <div class="rounded-[20px] overflow-hidden h-[180px] lg:h-full">
                    <picture>
                        <source srcset="<?php echo esc_url( $img . 'gallery-sm-4.webp' ); ?>" type="image/webp">
                        <img src="<?php echo esc_url( $img . 'gallery-sm-4.png' ); ?>"
                             alt="Grahamisu tiramisu slice close-up fresh"
                             width="263" height="335"
                             class="w-full h-full object-cover block">
                    </picture>
                </div>
            </div>

        </div>

    </div>
</section>
