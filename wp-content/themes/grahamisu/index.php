<?php
/**
 * The main template file.
 *
 * @package Grahamisu
 */
get_header();
?>

<main class="site-main">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <div class="posts-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__thumbnail">
                                <?php the_post_thumbnail( 'medium' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="post-card__body">
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-card__excerpt"><?php the_excerpt(); ?></div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php echo paginate_links( array(
                'prev_text' => esc_html__( '&larr; Prev', 'grahamisu' ),
                'next_text' => esc_html__( 'Next &rarr;', 'grahamisu' ),
            ) ); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'No posts found.', 'grahamisu' ); ?></p>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
