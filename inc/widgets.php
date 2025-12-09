<?php

/**
 * Widget areas for My Custom Theme
 *
 * @package My_Custom_Theme
 */

function my_theme_widgets_init()
{
    // Main Sidebar
    register_sidebar(array(
        'name'          => __('Sidebar', 'my-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'my-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    // Footer Widgets
    for ($i = 1; $i <= 3; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer %d', 'my-theme'), $i),
            'id'            => 'footer-' . $i,
            'description'   => __('Add widgets here for footer column.', 'my-theme'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
}
add_action('widgets_init', 'my_theme_widgets_init');
