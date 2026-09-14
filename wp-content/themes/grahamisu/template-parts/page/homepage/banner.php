<?php
$shop_url = function_exists( 'wc_get_page_id' )
    ? get_permalink( wc_get_page_id( 'shop' ) )
    : home_url( '/shop' );
?>

<section class="bg-dark w-full overflow-hidden">
    <div class="max-w-[1440px] mx-auto flex flex-col-reverse lg:flex-row lg:items-start px-6 pt-10 pb-12 lg:pt-[83px] lg:pr-[193px] lg:pl-0 lg:pb-[94px] gap-8 lg:gap-0">

        <div class="flex-1 min-w-0 lg:pl-[150px] lg:pt-[35px]">

            <div class="inline-flex items-center gap-[10px] bg-[rgba(255,255,255,0.1)] border border-[rgba(255,233,233,0.28)] rounded-[100px] px-3 py-[7px] mb-6 lg:mb-[38px]">
                <span class="w-[6px] h-[6px] rounded-[3px] bg-[#f2b705] shrink-0"></span>
                <span class="font-['Lato',sans-serif] font-bold text-[11px] text-[#ffe9e9] tracking-[1.98px] uppercase leading-none">Handcrafted in Manila</span>
            </div>

            <h1 class="font-primary text-[48px] lg:text-[68px] font-normal text-white leading-[1.04] tracking-[-1.02px] mb-5 lg:mb-[38px]">
                Sweet moments<br>start here.
            </h1>

            <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] font-normal text-[#ffe9e9] leading-[1.65] opacity-[0.92] mb-6 lg:mb-[38px] max-w-[420px]">
                Grahamisu is a classic Italian dessert made with espresso-soaked ladyfingers, mascarpone, and cocoa — layered fresh the day before it reaches you.
            </p>

            <div class="flex items-center gap-5 lg:gap-6">
                <a href="<?php echo esc_url( $shop_url ); ?>"
                   class="inline-flex items-center justify-center h-[56px] px-10 bg-rust text-white border-2 border-white rounded-[100px] font-['Lato',sans-serif] text-[15px] font-bold tracking-[0.3px] no-underline transition-colors hover:bg-[#8b3515] hover:text-white whitespace-nowrap">
                    Order a tub
                </a>
                <a href="<?php echo esc_url( $shop_url ); ?>"
                   class="font-['Lato',sans-serif] font-normal text-[13px] text-[#ffe9e9] tracking-[1.82px] uppercase border-b border-[rgba(255,233,233,0.5)] pb-0.5 no-underline hover:text-white hover:border-white whitespace-nowrap transition-colors">
                    See the menu
                </a>
            </div>
        </div>

        <div class="relative w-full lg:shrink-0 lg:w-[440px]">
            <picture>
                <source srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-tiramisu.webp' ); ?>" type="image/webp">
                <img
                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/banner-tiramisu.png' ); ?>"
                    alt="Grahamisu tiramisu cake"
                    width="440"
                    height="445"
                    class="w-full h-[240px] lg:w-[440px] lg:h-[445px] object-cover rounded-[20px] block shadow-[0px_30px_70px_0px_rgba(0,0,0,0.45)]"
                >
            </picture>
            <div class="hidden lg:block absolute bottom-[10px] -left-[28px] bg-white rounded-[16px] shadow-[0px_14px_17px_rgba(0,0,0,0.3)] px-5 py-[13px] min-w-[183px]">
                <p class="font-primary font-normal text-[22px] leading-[22px] text-rust m-0">₱79</p>
                <p class="font-['Lato',sans-serif] font-bold text-[11px] text-muted tracking-[1.54px] uppercase m-0 mt-[6px]">Solo tub · 250 ml</p>
            </div>
        </div>

    </div>
</section>
