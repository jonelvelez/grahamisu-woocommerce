<?php

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_filter( 'woocommerce_show_page_title', '__return_false' );

add_filter( 'woocommerce_add_to_cart_fragments', 'grahamisu_cart_count_fragment' );
function grahamisu_cart_count_fragment( $fragments ) {
    $count   = WC()->cart->get_cart_contents_count();
    $content = $count > 0 ? esc_html( $count ) : '';
    $fragments['span.cart-count'] = '<span class="cart-count">' . $content . '</span>';
    return $fragments;
}

function grahamisu_assets() {
    wp_enqueue_style(
        'grahamisu-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=Kaushan+Script&family=Cormorant+Garamond:ital,wght@1,500;1,700&family=Lato:ital,wght@0,400;0,600;0,700;1,700&family=Inter:wght@400;600&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'grahamisu-style',
        get_stylesheet_uri(),
        array( 'grahamisu-fonts' ),
        wp_get_theme()->get( 'Version' )
    );

    wp_enqueue_style(
        'grahamisu-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array( 'grahamisu-style' ),
        '2.3.0'
    );

    wp_enqueue_script(
        'grahamisu-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'grahamisu_assets' );

// Force woocommerce.php for checkout + order-received — bypasses WooCommerce Blocks / page.php
add_filter( 'template_include', function( $template ) {
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_checkout() || is_account_page() ) ) {
        $wc_template = locate_template( 'woocommerce.php' );
        if ( $wc_template ) {
            return $wc_template;
        }
    }
    return $template;
}, 99 );

// Remove default WooCommerce order-details table on thank-you page — we render our own
add_action( 'wp', function() {
    remove_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );
} );

// Apply Tailwind classes to primary nav links and list items
add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {
    $link_class = "font-['Inter'] font-semibold text-xl text-white no-underline whitespace-nowrap hover:text-gold";
    if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
        $atts['class'] = $link_class;
    }
    return $atts;
}, 10, 4 );

add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && in_array( $args->theme_location, array( 'primary', 'footer' ), true ) ) {
        return array( 'list-none p-0 m-0' );
    }
    return $classes;
}, 10, 4 );

function grahamisu_setup() {
    add_theme_support( 'custom-logo' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'navigation-widgets' ) );
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'grahamisu' ),
        'footer'  => __( 'Footer Menu', 'grahamisu' ),
    ) );
}
add_action( 'after_setup_theme', 'grahamisu_setup' );

function grahamisu_widgets() {
    register_sidebar( array(
        'name'          => __( 'Footer', 'grahamisu' ),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget__title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'grahamisu_widgets' );

// City and State are not shown in the checkout design — make them optional
add_filter( 'woocommerce_checkout_fields', function( $fields ) {
    $fields['billing']['billing_city']['required']  = false;
    $fields['billing']['billing_state']['required'] = false;
    return $fields;
} );
