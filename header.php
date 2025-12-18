<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<style>
/* FORCE FIX: Add To Cart Button - Slide Up from Bottom */
.add-to-cart-btn,
a.add-to-cart-btn {
    position: absolute !important;
    bottom: -50px !important;
    top: 204px;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    height: 44px !important;
    background: #000 !important;
    color: #fff !important;
    text-align: center !important;
    padding: 10px !important;
    font-size: 16px !important;
    font-weight: 500 !important;
    line-height: 24px !important;
    text-decoration: none !important;
    border: none !important;
    border-radius: 0 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    z-index: 10 !important;
    transition: bottom 0.3s ease, background 0.3s ease !important;
}

/* Khi hover vào product card hoặc product-img-box */
.product-card:hover .add-to-cart-btn,
.product-img-box:hover .add-to-cart-btn {
    bottom: 0 !important;
    /* Trượt lên vị trí đáy */
}

/* Hover effect cho chính nút */
.add-to-cart-btn:hover {
    background: #DB4444 !important;
}

/* Đảm bảo product-img-box có position và overflow */
.product-img-box {
    position: relative !important;
    overflow: hidden !important;
}

/* Đảm bảo product-card cũng có position */
.product-card {
    position: relative !important;
}
</style>
<style id="mobile-slider-fix">
/* Mobile Hero Slider - Full Image Display */
@media (max-width: 767px) {

    /* Hero Section - Keep container padding */
    .hero-section {
        padding: 20px 0 40px !important;
    }

    .hero-section .container {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }

    /* Slider wrapper - Tăng height để hiển thị đầy đủ ảnh */
    .hero-slider-wrapper {
        margin-left: 0 !important;
        height: 200px !important;
        /* Giảm height để ảnh hiển thị full */
        width: 100% !important;
        border-radius: 8px !important;
        overflow: hidden !important;
        background: #000 !important;
    }

    /* Swiper */
    .heroSwiper {
        height: 100% !important;
        width: 100% !important;
        background: #000 !important;
    }

    .heroSwiper .swiper-slide {
        width: 100% !important;
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Slide content - CONTAIN để hiển thị full ảnh */
    .slide-content {
        width: 100% !important;
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .slide-content img,
    .slide-link img {
        width: 100% !important;
        height: 100% !important;
        object-position: center !important;
        display: block !important;
    }

    /* Navigation buttons */
    .heroSwiper .swiper-button-prev,
    .heroSwiper .swiper-button-next {
        width: 32px !important;
        height: 32px !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 50%;
    }

    .heroSwiper .swiper-button-prev:after,
    .heroSwiper .swiper-button-next:after {
        font-size: 14px !important;
        color: #000;
    }

    .heroSwiper .swiper-button-prev {
        left: 10px !important;
    }

    .heroSwiper .swiper-button-next {
        right: 10px !important;
    }

    /* Pagination */
    .heroSwiper .swiper-pagination {
        bottom: 12px !important;
    }

    .heroSwiper .swiper-pagination-bullet {
        width: 8px !important;
        height: 8px !important;
        background: rgba(255, 255, 255, 0.6) !important;
        opacity: 1 !important;
    }

    .heroSwiper .swiper-pagination-bullet-active {
        background: var(--primary-color, #DB4444) !important;
        border: 2px solid #fff !important;
    }
}

/* Smaller phones */
@media (max-width: 375px) {
    .hero-slider-wrapper {
        height: 180px !important;
    }

    .hero-section {
        padding: 15px 0 30px !important;
    }
}

/* Very small phones */
@media (max-width: 360px) {
    .hero-slider-wrapper {
        height: 170px !important;
    }
}
</style>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Top Header -->
    <div class="top-header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="mx-auto text-center">
                <?php echo esc_html(get_theme_mod('top_header_text', 'Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%!')); ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
                    class="text-white fw-bold ms-2"><?php _e('ShopNow', 'exclusive'); ?></a>
            </div>
            <div class="language-select">
                <?php _e('English', 'exclusive'); ?> <i class="fas fa-angle-down"></i>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <?php if (has_custom_logo()) : ?>
            <div class="custom-logo-link">
                <?php the_custom_logo(); ?>
            </div>
            <?php else : ?>
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                Exclusive
            </a>
            <?php endif; ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_class' => 'navbar-nav',
                        'fallback_cb' => false,
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth' => 2,
                        'walker' => new Exclusive_Bootstrap_Nav_Walker(),
                    ));
                } else {
                    // Default menu giống HTML gốc - KHÔNG có Shop
                    echo '<ul class="navbar-nav">';
                    echo '<li class="nav-item"><a class="nav-link' . (is_front_page() ? ' active' : '') . '" href="' . esc_url(home_url('/')) . '">Home</a></li>';

                    // Contact page
                    $contact_page = get_page_by_path('contact');
                    if ($contact_page) {
                        echo '<li class="nav-item"><a class="nav-link' . (is_page('contact') ? ' active' : '') . '" href="' . esc_url(get_permalink($contact_page)) . '">Contact</a></li>';
                    }

                    // About page
                    $about_page = get_page_by_path('about');
                    if ($about_page) {
                        echo '<li class="nav-item"><a class="nav-link' . (is_page('about') ? ' active' : '') . '" href="' . esc_url(get_permalink($about_page)) . '">About</a></li>';
                    }

                    // Sign up
                    if (get_option('users_can_register')) {
                        echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(wp_registration_url()) . '">Sign up</a></li>';
                    } else {
                        // Nếu không cho phép đăng ký, link đến My Account
                        echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))) . '">Sign up</a></li>';
                    }

                    echo '</ul>';
                }
?>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Search Box -->
                <div class="search-box position-relative d-none d-md-block">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <input type="text" name="s" placeholder="<?php _e('What are you looking for?', 'exclusive'); ?>"
                            value="<?php echo get_search_query(); ?>">
                        <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2"></i>
                    </form>
                </div>

                <!-- Wishlist Icon -->
                <?php if (function_exists('YITH_WCWL') && class_exists('YITH_WCWL')) : ?>
                <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" class="position-relative">
                    <i class="far fa-heart fa-lg"></i>
                </a>
                <?php else : ?>
                <a href="#" class="position-relative" title="<?php _e('Wishlist', 'exclusive'); ?>">
                    <i class="far fa-heart fa-lg"></i>
                </a>
                <?php endif; ?>

                <!-- Cart Icon -->
                <?php if (class_exists('WooCommerce')) : ?>
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
                    class="text-decoration-none text-dark position-relative">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <?php
        $cart_count = WC()->cart->get_cart_contents_count();
                    if ($cart_count > 0) :
                        ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="font-size: 10px;">
                        <?php echo $cart_count; ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php else : ?>
                <a href="#" class="text-decoration-none text-dark">
                    <i class="fas fa-shopping-cart fa-lg"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>