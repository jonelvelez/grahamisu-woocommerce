<?php
$img      = get_template_directory_uri() . '/assets/images/';
$shop_url = function_exists( 'wc_get_page_id' )
    ? get_permalink( wc_get_page_id( 'shop' ) )
    : home_url( '/shop' );
?>

<section class="bg-[#fdf2ec] w-full">
    <div class="max-w-[1440px] mx-auto px-6 lg:px-[146px] pt-[80px] lg:pt-[100px] pb-[80px] lg:pb-[100px]">

        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

            <div class="w-full lg:w-[380px] lg:shrink-0">
                <picture>
                    <source srcset="<?php echo esc_url( $img . 'product-solo-tub.webp' ); ?>" type="image/webp">
                    <img
                        src="<?php echo esc_url( $img . 'product-solo-tub.png' ); ?>"
                        alt="Grahamisu tiramisu tub handcrafted in Manila"
                        width="380"
                        height="380"
                        class="w-full max-w-[320px] lg:max-w-full mx-auto block"
                    >
                </picture>
            </div>

            <div class="flex-1 min-w-0">
                <p class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[2.4px] uppercase leading-none m-0 mb-3">
                    Our Story
                </p>
                <h2 class="font-primary font-normal text-[36px] lg:text-[44px] text-[#2c1a0e] leading-[1.1] m-0 mb-6">
                    What makes Grahamisu different
                </h2>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-4">
                    Grahamisu is Manila's homegrown answer to one of the world's most beloved desserts. Every tub is assembled by hand, one layer at a time, using ingredients chosen not for convenience but for taste.
                </p>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-4">
                    We start with freshly brewed espresso and soak each ladyfinger to exactly the right depth — enough to carry the coffee flavor all the way through, not so much that the texture falls apart. The mascarpone cream is whipped fresh each day to the kind of consistency that holds its shape but melts the moment it hits your palate.
                </p>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-4">
                    No preservatives. No artificial flavors. No batch-produced shortcuts. What you taste in a Grahamisu tub is exactly what we would serve to someone we love. That standard has never changed, and it won't.
                </p>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-4">
                    We offer four sizes to match every kind of occasion. The <a href="<?php echo esc_url( home_url( '/product/solo-tub/' ) ); ?>" class="text-rust underline hover:text-brown transition-colors">Solo Tub</a> is perfect for a personal indulgence. The <a href="<?php echo esc_url( home_url( '/product/cravings/' ) ); ?>" class="text-rust underline hover:text-brown transition-colors">Craving's</a> size is ideal when one tub isn't quite enough. For families or small gatherings, the Satisfied tub delivers. And for celebrations, the Super Cravings is the one that earns the most mentions in the comments.
                </p>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-4">
                    Delivery is available across Metro Manila, and every order is fulfilled within 24 hours of being placed. We don't keep stock in a warehouse — your Grahamisu is made after you order, which means it arrives at peak freshness, never sitting in a chiller for days before it reaches you.
                </p>

                <p class="font-['Lato',sans-serif] text-[16px] lg:text-[17px] text-[#2c1a0e] leading-[1.7] m-0 mb-8">
                    Thousands of customers across the city have made Grahamisu their go-to dessert for balikbayan boxes, birthday surprises, team pasalubong, and simple Friday-night treats. If you're ordering for the first time, the Solo Tub is the perfect place to start. Once you taste what freshly layered tiramisu is supposed to feel like, you'll understand why customers keep coming back.
                </p>

                <a href="<?php echo esc_url( $shop_url ); ?>"
                   class="inline-flex items-center justify-center h-[52px] px-9 bg-rust text-white rounded-[100px] font-['Lato',sans-serif] text-[15px] font-bold tracking-[0.3px] no-underline transition-colors hover:bg-[#8b3515] hover:text-white">
                    Order now
                </a>
            </div>

        </div>

    </div>
</section>
