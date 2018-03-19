<?php
/**
 * Template for the PowerGallery post type
 *
 * @link       http://www.elk-lab.com/
 * @since      0.0.1
 *
 * @package    Wordmedia
 * @subpackage Wordmedia/public
 */
?>

<?php get_header(); ?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h2>' ); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?= do_shortcode('[wordmedia_powergallery id="' . get_the_ID() . '"]'); ?>
                    </div><!-- .entry-content -->

                </article>

            <?php endwhile; ?>

        </main><!-- #main -->
    </div><!-- #primary -->
</div><!-- #wrap -->

<?php get_footer(); ?>