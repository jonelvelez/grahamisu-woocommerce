<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Grahamisu
 */
get_header();
?>

<main class="site-main">
    <div class="container">
        <div class="error-404">
            <h1><?php esc_html_e( '404 — Page Not Found', 'grahamisu' ); ?></h1>
            <p><?php esc_html_e( 'The page you are looking for does not exist.', 'grahamisu' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                <?php esc_html_e( 'Back to Home', 'grahamisu' ); ?>
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
