<?php
/**
 * Template Name: Homepage
 */

 get_header(); ?>

<div class="homepage">

<?php get_template_part( 'template-parts/page/homepage/banner' ); ?>

<?php get_template_part( 'template-parts/page/homepage/social-proof' ); ?>

<?php get_template_part( 'template-parts/page/homepage/products' ); ?>

<?php get_template_part( 'template-parts/page/homepage/gallery' ); ?>

<?php get_template_part( 'template-parts/page/homepage/testimonial' ); ?>

<?php get_template_part( 'template-parts/page/homepage/newsletter' ); ?>

</div>

<?php get_footer(); ?>
