<?php

/**
 * Front Page Template
 *
 * @package Exclusive
 */

get_header();

// Hero Section with Sidebar
get_template_part('template-parts/hero-sidebar');

// Flash Sales Section
get_template_part('template-parts/flash-sales');

// Line Separator
echo '<div class="container"><hr class="my-5" style="border-color: #e5e5e5;"></div>';

// Browse By Category Section
get_template_part('template-parts/browse-category');

// Line Separator
echo '<div class="container"><hr class="my-5" style="border-color: #e5e5e5;"></div>';

// Best Selling Products Section
get_template_part('template-parts/best-selling');

// Music Banner Section
get_template_part('template-parts/music-banner');

// Explore Our Products Section
get_template_part('template-parts/explore-products');

// New Arrival Section (Bento Grid)
get_template_part('template-parts/new-arrival');

// Services Section
get_template_part('template-parts/services');

// If using Elementor for additional content
if (have_posts()) :
    while (have_posts()) : the_post();
        //[woocommerce_cart]
        the_content();
    endwhile;
endif;

get_footer();