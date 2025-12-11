<?php

/**
 * Template part for hero section with sidebar menu
 * Giao diện đẹp như design mẫu
 *
 * @package Exclusive
 */

// Lấy danh mục sản phẩm WooCommerce
$product_categories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'parent'     => 0,
    'orderby'    => 'menu_order',
    'order'      => 'ASC',
    'number'     => 10, // Giới hạn 10 danh mục
));
?>

<section class="hero-section">
    <div class="container">
        <div class="row g-0">
            <!-- Sidebar Categories -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="category-sidebar">
                    <?php if (!empty($product_categories) && !is_wp_error($product_categories)) : ?>
                        <?php foreach ($product_categories as $category) :
                            $category_link = get_term_link($category);

                            // Kiểm tra danh mục con
                            $child_categories = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'parent'     => $category->term_id,
                            ));

                            $has_children = !empty($child_categories) && !is_wp_error($child_categories);
                            ?>
                            <div class="category-item <?php echo $has_children ? 'has-submenu' : ''; ?>">
                                <a href="<?php echo esc_url($category_link); ?>" class="category-link">
                                    <span class="category-name"><?php echo esc_html($category->name); ?></span>
                                    <?php if ($has_children) : ?>
                                        <i class="fas fa-chevron-right"></i>
                                    <?php endif; ?>
                                </a>

                                <?php if ($has_children) : ?>
                                    <div class="submenu-dropdown">
                                        <?php foreach ($child_categories as $child) : ?>
                                            <a href="<?php echo esc_url(get_term_link($child)); ?>" class="submenu-link">
                                                <?php echo esc_html($child->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="category-item">
                            <a href="#" class="category-link">
                                <span class="category-name">Woman's Fashion</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="category-item">
                            <a href="#" class="category-link">
                                <span class="category-name">Men's Fashion</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <div class="category-item">
                            <a href="#" class="category-link">
                                <span class="category-name">Electronics</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Hero Slider -->
            <div class="col-lg-9">
                <div class="hero-slider-wrapper">
                    <div class="swiper heroSwiper">
                        <div class="swiper-wrapper">
                            <?php
                                // Lấy slider từ custom post type
                                $slider_args = array(
                                    'post_type'      => 'slider',
                                    'posts_per_page' => 5,
                                    'orderby'        => 'menu_order',
                                    'order'          => 'ASC',
                                );

$slider_query = new WP_Query($slider_args);

if ($slider_query->have_posts()) :
    while ($slider_query->have_posts()) : $slider_query->the_post();
        $slider_link = get_post_meta(get_the_ID(), '_slider_link', true);
        $slider_btn_text = get_post_meta(get_the_ID(), '_slider_btn_text', true);

        if (has_post_thumbnail()) :
            ?>
                                        <div class="swiper-slide">
                                            <div class="slide-content">
                                                <?php if ($slider_link) : ?>
                                                    <a href="<?php echo esc_url($slider_link); ?>" class="slide-link">
                                                        <?php the_post_thumbnail('full'); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php the_post_thumbnail('full'); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                <?php
                    endif;
    endwhile;
wp_reset_postdata();
else :
    // Fallback slides
    ?>
                                <div class="swiper-slide">
                                    <div class="slide-content">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/slider/slider-banner-1.jpg"
                                            alt="Apple Watch" />
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="slide-content">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/slider/slider-banner-2.jpg"
                                            alt="PlayStation 5" />
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="slide-content">
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/slider/slider-banner-3.jpg"
                                            alt="Headphones" />
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- Navigation -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Hero Section Styling */
    .hero-section {
        padding: 40px 0 80px;
        background: #fff;
    }

    .hero-section .container {
        max-width: 1350px;
    }

    .hero-section .row {
        margin: 0;
    }

    .hero-section .col-lg-3 {
        padding-left: 0;
        padding-right: 0;
    }

    .hero-section .col-lg-9 {
        padding-left: 0;
        padding-right: 0;
    }

    /* Category Sidebar */
    .category-sidebar {
        background: #fff;
        border-right: 1px solid #e5e5e5;
    }

    .category-item {
        position: relative;
        margin-bottom: 4px;
    }

    .category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        color: #000;
        text-decoration: none;
        font-size: 15px;
        font-weight: 400;
        transition: all 0.3s ease;
        border-radius: 4px 0 0 4px;
    }

    .category-link:hover {
        color: var(--primary-color);
        background: rgba(219, 68, 68, 0.05);
        padding-left: 25px;
    }

    .category-name {
        flex: 1;
    }

    .category-link i {
        font-size: 12px;
        opacity: 0.6;
        transition: all 0.3s ease;
    }

    .category-link:hover i {
        opacity: 1;
        transform: translateX(3px);
    }

    /* Submenu Dropdown */
    .submenu-dropdown {
        position: absolute;
        left: 100%;
        top: 0;
        background: #fff;
        min-width: 220px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 12px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .category-item:hover .submenu-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .submenu-link {
        display: block;
        padding: 10px 20px;
        color: #000;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .submenu-link:hover {
        color: var(--primary-color);
        background: rgba(219, 68, 68, 0.05);
        padding-left: 25px;
    }

    /* Hero Slider */
    .hero-slider-wrapper {
        background: transparent;
        border-radius: 0;
        overflow: hidden;
        margin-left: 15px;
        height: 411px;
    }

    .heroSwiper {
        width: 100%;
        height: 100%;
    }

    .heroSwiper .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f5f5;
    }

    .slide-content {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .slide-content img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .slide-link {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* Swiper Pagination */
    .heroSwiper .swiper-pagination {
        bottom: 16px;
        text-align: center;
    }

    .heroSwiper .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background: rgba(0, 0, 0, 0.3);
        opacity: 1;
        margin: 0 4px !important;
        transition: all 0.3s ease;
        border-radius: 50%;
    }

    .heroSwiper .swiper-pagination-bullet-active {
        background: var(--primary-color);
        width: 12px;
        height: 12px;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px var(--primary-color);
    }

    /* Swiper Navigation */
    .heroSwiper .swiper-button-prev,
    .heroSwiper .swiper-button-next {
        color: #000;
        width: 46px;
        height: 46px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .heroSwiper .swiper-button-prev:after,
    .heroSwiper .swiper-button-next:after {
        font-size: 20px;
        font-weight: 900;
    }

    .heroSwiper .swiper-button-prev {
        left: 20px;
    }

    .heroSwiper .swiper-button-next {
        right: 20px;
    }

    .heroSwiper .swiper-button-prev:hover,
    .heroSwiper .swiper-button-next:hover {
        background: #fff;
        color: var(--primary-color);
        transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 991px) {
        .hero-slider-wrapper {
            margin-left: 0;
            border-radius: 0;
            height: 300px;
        }

        .heroSwiper {
            height: 100%;
        }

        .hero-section {
            padding: 20px 0 40px;
        }

        .heroSwiper .swiper-button-prev,
        .heroSwiper .swiper-button-next {
            width: 36px;
            height: 36px;
        }

        .heroSwiper .swiper-button-prev:after,
        .heroSwiper .swiper-button-next:after {
            font-size: 16px;
        }

        .heroSwiper .swiper-button-prev {
            left: 10px;
        }

        .heroSwiper .swiper-button-next {
            right: 10px;
        }
    }

    @media (max-width: 767px) {
        .hero-slider-wrapper {
            height: 250px;
        }

        .heroSwiper .swiper-button-prev,
        .heroSwiper .swiper-button-next {
            width: 32px;
            height: 32px;
        }

        .heroSwiper .swiper-button-prev:after,
        .heroSwiper .swiper-button-next:after {
            font-size: 14px;
        }

        .heroSwiper .swiper-pagination-bullet {
            width: 10px;
            height: 10px;
        }

        .heroSwiper .swiper-pagination-bullet-active {
            width: 10px;
            height: 10px;
        }

        .hero-section {
            padding: 10px 0 30px;
        }
    }
</style>

<script>
    // Initialize Swiper
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            const heroSwiper = new Swiper('.heroSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                speed: 800,
            });
        }
    });
</script>