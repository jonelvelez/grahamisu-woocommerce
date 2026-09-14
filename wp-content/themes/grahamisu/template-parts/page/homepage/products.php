<?php
$img     = get_template_directory_uri() . '/assets/images/';
$uploads = home_url( '/wp-content/uploads/2026/08/' );

$shop_url = function_exists( 'wc_get_page_id' )
    ? get_permalink( wc_get_page_id( 'shop' ) )
    : home_url( '/shop' );

$products = array(
    array(
        'image' => $img . 'product-solo-tub.png',
        'name'  => 'Solo Tub',
        'size'  => '250 ML',
        'price' => '₱79.00',
        'slug'  => 'solo-tub',
    ),
    array(
        'image' => $uploads . 'product-cravings.png',
        'name'  => "Craving's",
        'size'  => '500 ML',
        'price' => '₱149.00',
        'slug'  => 'cravings',
    ),
    array(
        'image' => $uploads . 'product-satiesfied.png',
        'name'  => 'Satisfied',
        'size'  => '1350 ML',
        'price' => '₱349.00',
        'slug'  => 'satiesfied',
    ),
    array(
        'image' => $uploads . 'product-super-cravings.png',
        'name'  => 'Super Cravings',
        'size'  => '2300 ML',
        'price' => '₱549.00',
        'slug'  => 'super-cravings',
    ),
);
?>

<section class="bg-surface w-full">
    <div class="max-w-[1440px] mx-auto pt-[60px] lg:pt-[103px] pb-[60px] lg:pb-[120px] px-6 lg:px-[100px]">

        <div class="flex items-end justify-between mb-6 lg:mb-[50px]">
            <div>
                <p class="font-['Lato',sans-serif] font-bold text-[12px] text-brown tracking-[2.4px] uppercase leading-none m-0 mb-3">
                    Choose your size
                </p>
                <h2 class="font-primary font-normal text-[36px] lg:text-[44px] text-[#2c1a0e] leading-[1.1] m-0">
                    Four tubs, one obsession
                </h2>
            </div>
            <a href="<?php echo esc_url( $shop_url ); ?>"
               class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[2.04px] uppercase border-b border-brown pb-0.5 no-underline hover:text-[#2c1a0e] hover:border-[#2c1a0e] whitespace-nowrap transition-colors hidden lg:block">
                View all →
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
            <?php foreach ( $products as $product ) :
                $url = home_url( '/product/' . $product['slug'] . '/' );
            ?>
            <a href="<?php echo esc_url( $url ); ?>"
               class="flex flex-col items-center bg-white border border-[#e7e1dd] rounded-[20px] pt-7 pb-7 no-underline group transition-shadow hover:shadow-[0_4px_24px_rgba(0,0,0,0.08)]">

                <div class="w-[120px] h-[120px] lg:w-[148px] lg:h-[148px] rounded-full bg-surface flex items-center justify-center mb-6 shrink-0 overflow-hidden">
                    <img
                        src="<?php echo esc_url( $product['image'] ); ?>"
                        alt="<?php echo esc_attr( 'Grahamisu ' . $product['name'] . ' tiramisu' ); ?>"
                        width="148"
                        height="148"
                        class="w-full h-full object-cover block rounded-full"
                    >
                </div>

                <p class="font-primary font-semibold text-[21px] text-[#2c1a0e] text-center leading-[1.25] m-0 mb-1">
                    <?php echo esc_html( $product['name'] ); ?>
                </p>

                <p class="font-['Lato',sans-serif] font-bold text-[12px] text-[#838383] tracking-[1.92px] uppercase text-center m-0">
                    <?php echo esc_html( $product['size'] ); ?>
                </p>

                <div class="w-7 h-px bg-[#e7e1dd] my-[18px]"></div>

                <p class="font-['Lato',sans-serif] font-bold text-[19px] text-rust text-center leading-none m-0">
                    <?php echo esc_html( $product['price'] ); ?>
                </p>

            </a>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center mt-6 lg:hidden">
            <a href="<?php echo esc_url( $shop_url ); ?>"
               class="font-['Lato',sans-serif] font-normal text-[12px] text-brown tracking-[2.04px] uppercase border-b border-brown pb-0.5 no-underline hover:text-[#2c1a0e] hover:border-[#2c1a0e] transition-colors">
                View all →
            </a>
        </div>

    </div>
</section>
