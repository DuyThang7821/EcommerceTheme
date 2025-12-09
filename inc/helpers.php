<?php

/**
 * Helper functions for My Custom Theme
 *
 * @package My_Custom_Theme
 */

// Fallback menu function
function my_theme_fallback_menu()
{
    echo '<ul id="primary-menu" class="menu">';
    echo '<li><a href="' . home_url() . '">' . __('Home', 'my-theme') . '</a></li>';
    echo '<li><a href="' . admin_url('nav-menus.php') . '">' . __('Add Menu', 'my-theme') . '</a></li>';
    echo '</ul>';
}

// Custom excerpt length
function my_theme_excerpt_length($length)
{
    return 25;
}
add_filter('excerpt_length', 'my_theme_excerpt_length');

// Custom excerpt more
function my_theme_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'my_theme_excerpt_more');
