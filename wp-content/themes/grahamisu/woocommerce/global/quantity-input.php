<?php
/**
 * Product quantity input — pill-style qty box.
 * Detects cart vs product-page context to apply correct sizing.
 *
 * @package Grahamisu
 */

defined( 'ABSPATH' ) || exit;

$label = ! empty( $args['product_name'] )
    ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) )
    : esc_html__( 'Quantity', 'woocommerce' );

$is_cart = ! empty( $args['cart_context'] ) || is_cart();

$height    = $is_cart ? 'h-[44px]' : 'h-[58px]';
$width     = $is_cart ? 'w-[150px]' : 'w-[148px]';
$btn_size  = $is_cart ? 'text-[19px]' : 'text-[22px]';
$num_size  = $is_cart ? 'text-[15px]' : 'text-[17px]';
$num_width = $is_cart ? 'w-7' : 'w-8';
?>

<div class="sp-qty-box flex items-center justify-between bg-white border border-[#ded8d4] rounded-[100px] <?php echo esc_attr( "$height $width" ); ?> px-4">

    <button class="sp-qty-btn sp-qty-dec shrink-0 bg-transparent border-none font-['Lato',sans-serif] <?php echo esc_attr( $btn_size ); ?> text-rust leading-none cursor-pointer p-0 flex items-center justify-center"
            type="button"
            aria-label="<?php esc_attr_e( 'Decrease quantity', 'grahamisu' ); ?>">−</button>

    <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>">
        <?php echo esc_attr( $label ); ?>
    </label>

    <input
        type="<?php echo esc_attr( $type ); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
        id="<?php echo esc_attr( $input_id ); ?>"
        class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?> <?php echo esc_attr( "$num_width $num_size" ); ?> min-w-0 text-center border-none outline-none bg-transparent font-['Lato',sans-serif] font-bold text-[#2c1a0e] leading-none p-0"
        name="<?php echo esc_attr( $input_name ); ?>"
        value="<?php echo esc_attr( $input_value ); ?>"
        aria-label="<?php esc_attr_e( 'Product quantity', 'woocommerce' ); ?>"
        min="<?php echo esc_attr( $min_value ); ?>"
        <?php if ( 0 < $max_value ) : ?>max="<?php echo esc_attr( $max_value ); ?>"<?php endif; ?>
        <?php if ( ! $readonly ) : ?>
        step="<?php echo esc_attr( $step ); ?>"
        placeholder="<?php echo esc_attr( $placeholder ); ?>"
        inputmode="<?php echo esc_attr( $inputmode ); ?>"
        autocomplete="<?php echo esc_attr( isset( $autocomplete ) ? $autocomplete : 'on' ); ?>"
        <?php endif; ?>
    >

    <button class="sp-qty-btn sp-qty-inc shrink-0 bg-transparent border-none font-['Lato',sans-serif] <?php echo esc_attr( $btn_size ); ?> text-rust leading-none cursor-pointer p-0 flex items-center justify-center"
            type="button"
            aria-label="<?php esc_attr_e( 'Increase quantity', 'grahamisu' ); ?>">+</button>

</div>
