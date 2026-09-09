<?php
/**
 * My Account page — Grahamisu custom layout.
 *
 * Rendered when a customer is LOGGED IN and visits /my-account/.
 * Two-column layout: dark sidebar nav (left) + white content area (right).
 *
 * When NOT logged in, WooCommerce renders form-login.php instead of this file.
 *
 * @package Grahamisu
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="max-w-[1440px] mx-auto flex flex-col lg:flex-row min-h-[600px]">

    <!-- ── Left: navigation sidebar ────────────────────────────── -->
    <!-- woocommerce_account_navigation outputs our navigation.php -->
    <div class="w-full lg:w-[240px] lg:shrink-0 bg-[--color-dark]">
        <?php do_action( 'woocommerce_account_navigation' ); ?>
    </div>

    <!-- ── Right: content area ─────────────────────────────────── -->
    <!-- woocommerce_account_content outputs the active endpoint:  -->
    <!--   /my-account/            → dashboard.php                 -->
    <!--   /my-account/orders/     → orders.php                    -->
    <!--   /my-account/view-order/ → view-order.php                -->
    <!--   /my-account/edit-account/ → form-edit-account.php       -->
    <div class="flex-1 bg-white">
        <div class="woocommerce-MyAccount-content px-6 lg:px-14 py-10">
            <?php do_action( 'woocommerce_account_content' ); ?>
        </div>
    </div>

</div>
