<?php
/**
 * The template for displaying pages.
 *
 * @package Grahamisu
 */
get_header();
?>

<main class="site-main">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="page-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
