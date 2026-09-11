<?php
/**
 * The header for the Grahamisu theme.
 *
 * @package Grahamisu
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="sticky top-0 z-[100] bg-dark w-full">
    <div class="flex items-center h-[83px] max-w-[1440px] mx-auto px-4 lg:pl-[155px] lg:pr-[148px]">

        <div class="shrink-0">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                   class="font-primary font-normal text-[32px] text-white no-underline whitespace-nowrap hover:text-white">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Hamburger — visible on mobile/tablet only via CSS -->
        <button class="menu-toggle ml-auto mr-0 lg:hidden" type="button"
                aria-label="<?php esc_attr_e( 'Toggle menu', 'grahamisu' ); ?>"
                aria-expanded="false">
            <span class="menu-toggle__bar"></span>
            <span class="menu-toggle__bar"></span>
            <span class="menu-toggle__bar"></span>
        </button>

        <nav class="site-nav shrink-0 ml-auto" aria-label="Primary Menu">
            <?php wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'site-nav__list flex items-center gap-[67px] list-none p-0 m-0',
                'fallback_cb'    => false,
            ) ); ?>
        </nav>

        <div class="shrink-0 ml-3 lg:ml-[55px]">
            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="relative text-white flex items-center no-underline hover:text-gold"
                   aria-label="<?php esc_attr_e( 'View cart', 'grahamisu' ); ?>">
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M2 20C1.45 20 0.979167 19.8042 0.5875 19.4125C0.195833 19.0208 0 18.55 0 18V6C0 5.45 0.195833 4.97917 0.5875 4.5875C0.979167 4.19583 1.45 4 2 4H4C4 2.9 4.39167 1.95833 5.175 1.175C5.95833 0.391667 6.9 0 8 0C9.1 0 10.0417 0.391667 10.825 1.175C11.6083 1.95833 12 2.9 12 4H14C14.55 4 15.0208 4.19583 15.4125 4.5875C15.8042 4.97917 16 5.45 16 6V18C16 18.55 15.8042 19.0208 15.4125 19.4125C15.0208 19.8042 14.55 20 14 20H2ZM2 18H14V6H12V8C12 8.28333 11.9042 8.52083 11.7125 8.7125C11.5208 8.90417 11.2833 9 11 9C10.7167 9 10.4792 8.90417 10.2875 8.7125C10.0958 8.52083 10 8.28333 10 8V6H6V8C6 8.28333 5.90417 8.52083 5.7125 8.7125C5.52083 8.90417 5.28333 9 5 9C4.71667 9 4.47917 8.90417 4.2875 8.7125C4.09583 8.52083 4 8.28333 4 8V6H2V18ZM6 4H10C10 3.45 9.80417 2.97917 9.4125 2.5875C9.02083 2.19583 8.55 2 8 2C7.45 2 6.97917 2.19583 6.5875 2.5875C6.19583 2.97917 6 3.45 6 4Z" fill="currentColor"/>
                    </svg>
                    <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    <span class="cart-count absolute -top-[6px] -right-[8px] bg-white text-black font-['Lato'] text-[9px] font-normal w-4 h-4 rounded-full flex items-center justify-center empty:hidden">
                        <?php echo $cart_count > 0 ? esc_html( $cart_count ) : ''; ?>
                    </span>
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>

<div class="site-content">
