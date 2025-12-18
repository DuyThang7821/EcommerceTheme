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
// =============================================
// INCLUDE HELPER FILES - Add to functions.php
// =============================================

// Include section tag component
require_once get_template_directory() . '/inc/section-tag.php';

// Include other helpers if needed
if (file_exists(get_template_directory() . '/inc/helpers.php')) {
    require_once get_template_directory() . '/inc/helpers.php';
}

if (file_exists(get_template_directory() . '/inc/widgets.php')) {
    require_once get_template_directory() . '/inc/widgets.php';
}

if (file_exists(get_template_directory() . '/inc/customizer.php')) {
    require_once get_template_directory() . '/inc/customizer.php';
}
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

// Custom fields cho Slider
function exclusive_slider_meta_boxes()
{
    add_meta_box(
        'slider_details',
        __('Slider Details', 'exclusive'),
        'exclusive_slider_meta_box_callback',
        'slider',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'exclusive_slider_meta_boxes');

function exclusive_slider_meta_box_callback($post)
{
    wp_nonce_field('exclusive_slider_meta_box', 'exclusive_slider_meta_box_nonce');

    $slider_link = get_post_meta($post->ID, '_slider_link', true);
    $slider_btn_text = get_post_meta($post->ID, '_slider_btn_text', true);
    ?>
<p>
    <label for="slider_link"><?php _e('Link URL:', 'exclusive'); ?></label>
    <input type="url" id="slider_link" name="slider_link" value="<?php echo esc_url($slider_link); ?>"
        style="width: 100%;">
</p>
<p>
    <label for="slider_btn_text"><?php _e('Button Text:', 'exclusive'); ?></label>
    <input type="text" id="slider_btn_text" name="slider_btn_text" value="<?php echo esc_attr($slider_btn_text); ?>"
        style="width: 100%;">
</p>
<?php
}

function exclusive_save_slider_meta_box($post_id)
{
    if (!isset($_POST['exclusive_slider_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['exclusive_slider_meta_box_nonce'], 'exclusive_slider_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['slider_link'])) {
        update_post_meta($post_id, '_slider_link', esc_url_raw($_POST['slider_link']));
    }

    if (isset($_POST['slider_btn_text'])) {
        update_post_meta($post_id, '_slider_btn_text', sanitize_text_field($_POST['slider_btn_text']));
    }
}
add_action('save_post', 'exclusive_save_slider_meta_box');

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
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
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

    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
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
// =============================================
// AJAX HANDLERS - Add to functions.php
// =============================================

/**
 * Load More Products AJAX Handler
 */
function exclusive_load_more_products()
{
    check_ajax_referer('exclusive-nonce', 'nonce');

    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'paged' => $page,
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();

        while ($query->have_posts()) : $query->the_post();
            global $product;
            $product_id = get_the_ID();
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $discount_percent = $sale_price ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
            ?>
<div class="col-6 col-md-4 col-lg-3 product-item-ajax" style="opacity: 0; animation: fadeInUp 0.5s forwards;">
    <div class="product-card">
        <!-- Product Image -->
        <div class="product-img-box">
            <?php if ($discount_percent > 0) : ?>
            <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
            <?php endif; ?>

            <!-- Action Icons -->
            <div class="action-icons">
                <div class="action-icon wishlist-btn" data-product-id="<?php echo $product_id; ?>">
                    <i class="far fa-heart"></i>
                </div>
                <div class="action-icon quick-view-btn" data-product-id="<?php echo $product_id; ?>">
                    <i class="far fa-eye"></i>
                </div>
            </div>

            <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
                <?php else : ?>
                <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title(); ?>">
                <?php endif; ?>
            </a>

            <!-- Add to Cart Button -->
            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="add-to-cart-btn"
                data-product-id="<?php echo $product_id; ?>">
                Add To Cart
            </a>
        </div>

        <!-- Product Info -->
        <div class="product-info mt-3">
            <h6 class="product-name mb-2">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h6>


            <div class="product-price mb-2">
                <?php if ($sale_price) : ?>
                <span
                    class="sale-price text-danger fw-bold"><?php echo number_format($sale_price, 0, ',', '.'); ?>$</span>
                <span
                    class="regular-price text-muted text-decoration-line-through ms-2"><?php echo number_format($regular_price, 0, ',', '.'); ?>đ</span>
                <?php else : ?>
                <span class="price fw-bold"><?php echo number_format($regular_price, 0, ',', '.'); ?>$</span>
                <?php endif; ?>
            </div>

            <!-- Rating -->
            <div class="product-rating d-flex align-items-center gap-2">
                <?php
                                    $average_rating = $product->get_average_rating();
            $rating_count = $product->get_rating_count();
            ?>
                <div class="stars text-warning">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <i class="<?php echo $i <= $average_rating ? 'fas' : 'far'; ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
                <span class="rating-count text-muted">(<?php echo $rating_count; ?>)</span>
            </div>
        </div>
    </div>
</div>
<?php
        endwhile;
        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'page' => $page
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'No more products'
        ));
    }
}
add_action('wp_ajax_load_more_products', 'exclusive_load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'exclusive_load_more_products');

/**
 * Add to Cart AJAX Handler
 */
function exclusive_ajax_add_to_cart()
{
    check_ajax_referer('exclusive-nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    if ($product_id) {
        $added = WC()->cart->add_to_cart($product_id, 1);

        if ($added) {
            wp_send_json_success(array(
                'message' => 'Product added to cart',
                'cart_count' => WC()->cart->get_cart_contents_count()
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'Failed to add product'
            ));
        }
    } else {
        wp_send_json_error(array(
            'message' => 'Invalid product ID'
        ));
    }
}
add_action('wp_ajax_add_to_cart', 'exclusive_ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'exclusive_ajax_add_to_cart');

/**
 * Add fadeInUp animation CSS
 */
function exclusive_add_animation_css()
{
    ?>
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-item-ajax {
    animation-fill-mode: forwards;
}

.product-item-ajax:nth-child(1) {
    animation-delay: 0.1s;
}

.product-item-ajax:nth-child(2) {
    animation-delay: 0.2s;
}

.product-item-ajax:nth-child(3) {
    animation-delay: 0.3s;
}

.product-item-ajax:nth-child(4) {
    animation-delay: 0.4s;
}

.product-item-ajax:nth-child(5) {
    animation-delay: 0.5s;
}

.product-item-ajax:nth-child(6) {
    animation-delay: 0.6s;
}

.product-item-ajax:nth-child(7) {
    animation-delay: 0.7s;
}

.product-item-ajax:nth-child(8) {
    animation-delay: 0.8s;
}
</style>
<?php
}
add_action('wp_head', 'exclusive_add_animation_css');
// =============================================
// CONTACT FORM HANDLER - Add to functions.php
// =============================================

/**
 * Handle Contact Form Submission
 */
function exclusive_submit_contact_form()
{
    // Verify nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
        wp_send_json_error(array(
            'message' => 'Security check failed.'
        ));
    }

    // Sanitize input
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    // Validate
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        wp_send_json_error(array(
            'message' => 'Please fill in all required fields.'
        ));
    }

    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => 'Please enter a valid email address.'
        ));
    }

    // Prepare email
    $to = get_option('admin_email'); // Admin email
    $subject = 'New Contact Form Submission from ' . $name;

    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Phone: $phone\n";
    $email_body .= "Message:\n$message\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    // Send email
    $sent = wp_mail($to, $subject, $email_body, $headers);

    if ($sent) {
        // Optionally save to database
        exclusive_save_contact_form_entry($name, $email, $phone, $message);

        wp_send_json_success(array(
            'message' => 'Thank you! Your message has been sent successfully. We will contact you soon.'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Failed to send message. Please try again later.'
        ));
    }
}
add_action('wp_ajax_submit_contact_form', 'exclusive_submit_contact_form');
add_action('wp_ajax_nopriv_submit_contact_form', 'exclusive_submit_contact_form');

/**
 * Save contact form entry to database (optional)
 */
function exclusive_save_contact_form_entry($name, $email, $phone, $message)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_forms';

    // Check if table exists, if not create it
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        message text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Insert entry
    $wpdb->insert(
        $table_name,
        array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
        ),
        array('%s', '%s', '%s', '%s')
    );
}

/**
 * Create Admin Menu to View Contact Form Submissions
 */
function exclusive_contact_forms_menu()
{
    add_menu_page(
        'Contact Forms',
        'Contact Forms',
        'manage_options',
        'contact-forms',
        'exclusive_contact_forms_page',
        'dashicons-email',
        30
    );
}
add_action('admin_menu', 'exclusive_contact_forms_menu');

/**
 * Display Contact Forms Admin Page
 */
function exclusive_contact_forms_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_forms';

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = absint($_GET['id']);
        $wpdb->delete($table_name, array('id' => $id));
        echo '<div class="notice notice-success"><p>Entry deleted successfully.</p></div>';
    }

    // Get all entries
    $entries = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");

    ?>
<div class="wrap">
    <h1>Contact Form Submissions</h1>

    <?php if (empty($entries)) : ?>
    <p>No contact form submissions yet.</p>
    <?php else : ?>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entries as $entry) : ?>
            <tr>
                <td><?php echo $entry->id; ?></td>
                <td><strong><?php echo esc_html($entry->name); ?></strong></td>
                <td><?php echo esc_html($entry->email); ?></td>
                <td><?php echo esc_html($entry->phone); ?></td>
                <td><?php echo esc_html(wp_trim_words($entry->message, 15)); ?></td>
                <td><?php echo date('M d, Y H:i', strtotime($entry->created_at)); ?></td>
                <td>
                    <a href="?page=contact-forms&action=view&id=<?php echo $entry->id; ?>"
                        class="button button-small">View</a>
                    <a href="?page=contact-forms&action=delete&id=<?php echo $entry->id; ?>" class="button button-small"
                        onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
<?php
}