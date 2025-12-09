<?php

/**
 * Template part for displaying posts
 *
 * @package My_Custom_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('my-theme-thumbnail'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="post-content">
        <header class="post-header">
            <?php the_title('<h2 class="post-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

            <div class="post-meta">
                <span class="post-date">
                    <?php echo get_the_date(); ?>
                </span>
                <span class="post-author">
                    <?php esc_html_e('By', 'my-theme'); ?> <?php the_author(); ?>
                </span>
                <span class="post-categories">
                    <?php the_category(', '); ?>
                </span>
            </div>
        </header>

        <div class="post-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <footer class="post-footer">
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php esc_html_e('Read More', 'my-theme'); ?>
            </a>
        </footer>
    </div>
</article>