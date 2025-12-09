<?php

/**
 * Exclusive Theme Functions
 * 
 * @package Exclusive
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Setup
function exclusive_theme_setup()
{
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'exclusive'),
        'footer' => __('Footer Menu', 'exclusive'),
    ));

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'exclusive_theme_setup');

// Enqueue Styles and Scripts
function exclusive_enqueue_assets()
{
    // Styles
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap', array(), null);
    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');

    // Theme Styles - theo thứ tự import
    wp_enqueue_style('exclusive-variables', get_template_directory_uri() . '/assets/css/variables.css', array(), '1.0.0');
    wp_enqueue_style('exclusive-header', get_template_directory_uri() . '/assets/css/header.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-hero', get_template_directory_uri() . '/assets/css/hero.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-products', get_template_directory_uri() . '/assets/css/products.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-forms', get_template_directory_uri() . '/assets/css/forms.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-footer', get_template_directory_uri() . '/assets/css/footer.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-responsive', get_template_directory_uri() . '/assets/css/responsive.css', array('exclusive-variables'), '1.0.0');
    wp_enqueue_style('exclusive-style', get_stylesheet_uri(), array('exclusive-responsive'), '1.0.0');

    // Scripts
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array(), '5.3.0', true);
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
    wp_enqueue_script('exclusive-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('exclusive-script', 'exclusiveAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('exclusive-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'exclusive_enqueue_assets');

// Register Widget Areas
function exclusive_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Sidebar', 'exclusive'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'exclusive'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer widgets
    for ($i = 1; $i <= 5; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer Widget %d', 'exclusive'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Add widgets for footer column %d.', 'exclusive'), $i),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5>',
            'after_title'   => '</h5>',
        ));
    }
}
add_action('widgets_init', 'exclusive_widgets_init');

// Custom Post Types
function exclusive_register_post_types()
{
    // Team Members
    register_post_type('team', array(
        'labels' => array(
            'name' => __('Team Members', 'exclusive'),
            'singular_name' => __('Team Member', 'exclusive'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-groups',
        'show_in_rest' => true,
    ));

    // Testimonials
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Testimonials', 'exclusive'),
            'singular_name' => __('Testimonial', 'exclusive'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-testimonial',
        'show_in_rest' => true,
    ));

    // Slider/Banner
    register_post_type('slider', array(
        'labels' => array(
            'name' => __('Sliders', 'exclusive'),
            'singular_name' => __('Slider', 'exclusive'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail'),
        'menu_icon' => 'dashicons-images-alt2',
        'show_in_rest' => true,
    ));
}
add_action('init', 'exclusive_register_post_types');

// Add custom fields for team members
function exclusive_team_meta_boxes()
{
    add_meta_box(
        'team_details',
        __('Team Member Details', 'exclusive'),
        'exclusive_team_meta_box_callback',
        'team',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'exclusive_team_meta_boxes');

function exclusive_team_meta_box_callback($post)
{
    wp_nonce_field('exclusive_team_meta_box', 'exclusive_team_meta_box_nonce');

    $position = get_post_meta($post->ID, '_team_position', true);
    $twitter = get_post_meta($post->ID, '_team_twitter', true);
    $instagram = get_post_meta($post->ID, '_team_instagram', true);
    $linkedin = get_post_meta($post->ID, '_team_linkedin', true);

?>
    <p>
        <label for="team_position"><?php _e('Position:', 'exclusive'); ?></label>
        <input type="text" id="team_position" name="team_position" value="<?php echo esc_attr($position); ?>"
            style="width: 100%;">
    </p>
    <p>
        <label for="team_twitter"><?php _e('Twitter URL:', 'exclusive'); ?></label>
        <input type="url" id="team_twitter" name="team_twitter" value="<?php echo esc_url($twitter); ?>"
            style="width: 100%;">
    </p>
    <p>
        <label for="team_instagram"><?php _e('Instagram URL:', 'exclusive'); ?></label>
        <input type="url" id="team_instagram" name="team_instagram" value="<?php echo esc_url($instagram); ?>"
            style="width: 100%;">
    </p>
    <p>
        <label for="team_linkedin"><?php _e('LinkedIn URL:', 'exclusive'); ?></label>
        <input type="url" id="team_linkedin" name="team_linkedin" value="<?php echo esc_url($linkedin); ?>"
            style="width: 100%;">
    </p>
<?php
}

function exclusive_save_team_meta_box($post_id)
{
    if (!isset($_POST['exclusive_team_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['exclusive_team_meta_box_nonce'], 'exclusive_team_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['team_position'])) {
        update_post_meta($post_id, '_team_position', sanitize_text_field($_POST['team_position']));
    }

    if (isset($_POST['team_twitter'])) {
        update_post_meta($post_id, '_team_twitter', esc_url_raw($_POST['team_twitter']));
    }

    if (isset($_POST['team_instagram'])) {
        update_post_meta($post_id, '_team_instagram', esc_url_raw($_POST['team_instagram']));
    }

    if (isset($_POST['team_linkedin'])) {
        update_post_meta($post_id, '_team_linkedin', esc_url_raw($_POST['team_linkedin']));
    }
}
add_action('save_post', 'exclusive_save_team_meta_box');

// Theme Customizer
function exclusive_customize_register($wp_customize)
{
    // Top Header Section
    $wp_customize->add_section('exclusive_top_header', array(
        'title' => __('Top Header', 'exclusive'),
        'priority' => 30,
    ));

    $wp_customize->add_setting('top_header_text', array(
        'default' => 'Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%!',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('top_header_text', array(
        'label' => __('Header Announcement Text', 'exclusive'),
        'section' => 'exclusive_top_header',
        'type' => 'text',
    ));

    // Contact Info Section
    $wp_customize->add_section('exclusive_contact_info', array(
        'title' => __('Contact Information', 'exclusive'),
        'priority' => 40,
    ));

    $wp_customize->add_setting('contact_phone', array(
        'default' => '+880181112222',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('contact_phone', array(
        'label' => __('Phone Number', 'exclusive'),
        'section' => 'exclusive_contact_info',
        'type' => 'text',
    ));

    $wp_customize->add_setting('contact_email', array(
        'default' => 'customer@exclusive.com',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('contact_email', array(
        'label' => __('Email Address', 'exclusive'),
        'section' => 'exclusive_contact_info',
        'type' => 'email',
    ));

    $wp_customize->add_setting('contact_address', array(
        'default' => '111 Bijoy sarani, Dhaka, DH 1515, Bangladesh.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('contact_address', array(
        'label' => __('Address', 'exclusive'),
        'section' => 'exclusive_contact_info',
        'type' => 'textarea',
    ));

    // Social Media Links
    $wp_customize->add_section('exclusive_social_media', array(
        'title' => __('Social Media', 'exclusive'),
        'priority' => 50,
    ));

    $social_networks = array('facebook', 'twitter', 'instagram', 'linkedin');

    foreach ($social_networks as $network) {
        $wp_customize->add_setting('social_' . $network, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('social_' . $network, array(
            'label' => ucfirst($network) . ' ' . __('URL', 'exclusive'),
            'section' => 'exclusive_social_media',
            'type' => 'url',
        ));
    }
}
add_action('customize_register', 'exclusive_customize_register');

// Breadcrumb Function
function exclusive_breadcrumb()
{
    if (!is_front_page()) {
        echo '<div class="page-hero">';
        echo '<div class="container">';
        echo '<p class="breadcrumb">';
        echo '<a href="' . home_url() . '">Home</a>';

        if (is_category() || is_single()) {
            echo ' / ';
            the_category(' / ');
            if (is_single()) {
                echo ' / ' . get_the_title();
            }
        } elseif (is_page()) {
            echo ' / ' . get_the_title();
        } elseif (is_search()) {
            echo ' / Search Results';
        } elseif (is_404()) {
            echo ' / 404';
        }

        echo '</p>';
        echo '</div>';
        echo '</div>';
    }
}

// Excerpt Length
function exclusive_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', 'exclusive_excerpt_length');

// Excerpt More
function exclusive_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'exclusive_excerpt_more');

// WooCommerce Support
function exclusive_woocommerce_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'exclusive_woocommerce_setup');

// Remove WooCommerce default styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Customize WooCommerce product columns
function exclusive_loop_columns()
{
    return 4;
}
add_filter('loop_shop_columns', 'exclusive_loop_columns');

// Products per page
function exclusive_products_per_page()
{
    return 12;
}
add_filter('loop_shop_per_page', 'exclusive_products_per_page');

// Custom Walker for Bootstrap 5 Navigation
class Exclusive_Bootstrap_Nav_Walker extends Walker_Nav_Menu
{

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item';

        if ($args->walker->has_children) {
            $classes[] = 'dropdown';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = ! empty($item->target) ? $item->target : '';
        $atts['rel']    = ! empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = ! empty($item->url) ? $item->url : '';

        if ($depth === 0) {
            $atts['class'] = 'nav-link';
        } else {
            $atts['class'] = 'dropdown-item';
        }

        if ($args->walker->has_children && $depth === 0) {
            $atts['class'] .= ' dropdown-toggle';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['role'] = 'button';
            $atts['aria-expanded'] = 'false';
        }

        if (in_array('current-menu-item', $classes)) {
            $atts['class'] .= ' active';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element) {
            return;
        }

        $id_field = $this->db_fields['id'];

        if (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
