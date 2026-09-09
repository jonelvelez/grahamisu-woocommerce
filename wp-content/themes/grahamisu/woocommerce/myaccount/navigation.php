<?php
/**
 * My Account navigation sidebar — Grahamisu branded.
 *
 * wc_get_account_menu_items() returns an array of endpoint → label:
 *   dashboard       → "Dashboard"
 *   orders          → "Orders"
 *   downloads       → "Downloads"
 *   edit-address    → "Addresses"
 *   edit-account    → "Account details"
 *   customer-logout → "Log out"
 *
 * wc_is_current_account_menu_item() returns true when the customer
 * is currently on that endpoint's page.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_attr_e( 'Account pages', 'woocommerce' ); ?>">
    <ul class="list-none p-0 m-0 pt-8 pb-4">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
            $is_active = wc_is_current_account_menu_item( $endpoint );
            $is_logout = ( 'customer-logout' === $endpoint );
        ?>
        <li class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
               <?php echo $is_active ? 'aria-current="page"' : ''; ?>
               class="flex items-center gap-3 px-8 py-3.5 font-['Lato',sans-serif] font-semibold text-sm no-underline transition-colors
                      <?php if ( $is_active ) : ?>
                          text-white border-l-2 border-[--color-gold] bg-white/[.07]
                      <?php elseif ( $is_logout ) : ?>
                          text-[#7a5a50] hover:text-[--color-rust] mt-4 border-t border-white/[.06] pt-5
                      <?php else : ?>
                          text-[#b8998c] hover:text-white
                      <?php endif; ?>">
                <?php echo esc_html( $label ); ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
