<?php

/**
 * Content template for homepage blog posts
 */
?>

<article class="blog-card">
    <?php if (has_post_thumbnail()) : ?>
        <div class="blog-card-image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('my-theme-thumbnail'); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="blog-card-content">
        <h3 class="blog-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="blog-card-meta">
            <span class="post-date"><?php echo get_the_date(); ?></span>
            <span class="post-category"><?php the_category(', '); ?></span>
        </div>

        <div class="blog-card-excerpt">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
    </div>
</article>