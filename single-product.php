<?php

/**
 * Single Product Template
 * Chi tiết sản phẩm với gallery ảnh động và sản phẩm liên quan
 *
 * @package Exclusive
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        global $product;

        // Get product data
        $product_id = get_the_ID();
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $stock_status = $product->get_stock_status();
        $average_rating = $product->get_average_rating();
        $rating_count = $product->get_rating_count();

        // Get product gallery images
        $attachment_ids = $product->get_gallery_image_ids();

        // Get product categories for breadcrumb
        $categories = get_the_terms($product_id, 'product_cat');
        $category_name = ($categories && !is_wp_error($categories)) ? $categories[0]->name : 'Shop';
?>

        <main class="product-details-page">
            <!-- Breadcrumb Section -->
            <section class="page-hero">
                <div class="container">
                    <p class="breadcrumb">
                        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Account</a> /
                        <a
                            href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><?php echo esc_html($category_name); ?></a>
                        /
                        <span><?php the_title(); ?></span>
                    </p>
                </div>
            </section>

            <!-- Product Details Section -->
            <section class="container py-5">
                <div class="row g-4 g-lg-5">
                    <!-- Product Gallery Column -->
                    <div class="col-lg-7">
                        <div class="product-gallery d-flex gap-3">
                            <!-- Thumbnails Vertical -->
                            <div class="thumbs-vertical d-flex flex-column gap-2">
                                <?php
                                // Featured image thumbnail
                                if (has_post_thumbnail()) :
                                    $featured_id = get_post_thumbnail_id();
                                    $featured_url = wp_get_attachment_image_url($featured_id, 'full');
                                ?>
                                    <button class="thumb active" data-image="<?php echo esc_url($featured_url); ?>">
                                        <?php echo wp_get_attachment_image($featured_id, 'thumbnail'); ?>
                                    </button>
                                <?php endif; ?>

                                <?php
                                // Gallery thumbnails
                                if (!empty($attachment_ids)) :
                                    foreach ($attachment_ids as $attachment_id) :
                                        $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                                ?>
                                        <button class="thumb" data-image="<?php echo esc_url($image_url); ?>">
                                            <?php echo wp_get_attachment_image($attachment_id, 'thumbnail'); ?>
                                        </button>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>

                            <!-- Main Image -->
                            <div class="main-image flex-grow-1">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large', array('id' => 'mainProductImage', 'class' => 'img-fluid')); ?>
                                <?php else : ?>
                                    <img id="mainProductImage" src="<?php echo wc_placeholder_img_src('large'); ?>"
                                        alt="<?php the_title(); ?>" class="img-fluid">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info Column -->
                    <div class="col-lg-5">
                        <div class="product-info">
                            <!-- Product Title -->
                            <h2 class="product-title">
                                <?php the_title(); ?>
                            </h2>

                            <!-- Rating & Stock -->
                            <div class="rating-stock-row">
                                <div class="product-rating">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="<?php echo $i <= $average_rating ? 'fas' : 'far'; ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="review-count">(<?php echo $rating_count; ?> Reviews)</span>
                                <span class="divider">|</span>
                                <span
                                    class="stock-status <?php echo $stock_status === 'instock' ? 'in-stock' : 'out-stock'; ?>">
                                    <?php echo $stock_status === 'instock' ? 'In Stock' : 'Out of Stock'; ?>
                                </span>
                            </div>

                            <!-- Price -->
                            <h2 class="product-price">
                                <?php if ($sale_price) : ?>
                                    $<?php echo number_format($sale_price, 2); ?>
                                <?php else : ?>
                                    $<?php echo number_format($regular_price, 2); ?>
                                <?php endif; ?>
                            </h2>

                            <!-- Short Description -->
                            <div class="product-description">
                                <?php echo wpautop($product->get_short_description()); ?>
                            </div>

                            <hr class="product-divider">

                            <!-- Product Options -->
                            <div class="product-options">
                                <!-- Colors -->
                                <div class="option-row">
                                    <span class="option-label">Colours:</span>
                                    <div class="color-options">
                                        <button class="color-dot active" style="background: #A0BCE0;"
                                            data-color="Light Blue"></button>
                                        <button class="color-dot" style="background: #E07575;" data-color="Red"></button>
                                    </div>
                                </div>

                                <!-- Size -->
                                <div class="option-row">
                                    <span class="option-label">Size:</span>
                                    <div class="size-options">
                                        <button class="size-pill">XS</button>
                                        <button class="size-pill">S</button>
                                        <button class="size-pill active">M</button>
                                        <button class="size-pill">L</button>
                                        <button class="size-pill">XL</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions Wrapper -->
                            <div class="product-actions-wrapper">
                                <!-- Quantity, Buy Now, Wishlist -->
                                <div class="actions-row">
                                    <!-- Quantity Selector -->
                                    <div class="quantity-selector">
                                        <button type="button" class="qty-btn minus-btn">-</button>
                                        <span class="qty-value" id="product-quantity">1</span>
                                        <button type="button" class="qty-btn plus-btn">+</button>
                                    </div>

                                    <!-- Buy Now Button -->
                                    <button class="btn-buy-now" id="buyNowBtn" data-product-id="<?php echo $product_id; ?>">
                                        Buy Now
                                    </button>

                                    <!-- Wishlist Button -->
                                    <button class="btn-wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>

                                <!-- Delivery Info -->
                                <div class="delivery-info-box">
                                    <div class="delivery-item">
                                        <i class="fas fa-truck"></i>
                                        <div class="delivery-content">
                                            <strong>Free Delivery</strong>
                                            <p>Enter your postal code for Delivery Availability.</p>
                                        </div>
                                    </div>
                                    <div class="delivery-item">
                                        <i class="fas fa-undo"></i>
                                        <div class="delivery-content">
                                            <strong>Return Delivery</strong>
                                            <p>Free 30 Days Delivery Returns. <a href="#">Details</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Related Items Section -->
            <section class="container my-5 py-5">
                <div class="section-header">
                    <div class="section-tag"></div>
                    <h3 class="section-title">Related Item</h3>
                </div>

                <?php
                // Get related products
                $related_args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'post__not_in' => array($product_id),
                    'orderby' => 'rand',
                    'post_status' => 'publish'
                );

                $related_query = new WP_Query($related_args);

                if ($related_query->have_posts()) :
                ?>
                    <div class="row g-4">
                        <?php while ($related_query->have_posts()) : $related_query->the_post();
                            $related_product = wc_get_product(get_the_ID());
                            if (!$related_product) {
                                continue;
                            }

                            $related_regular_price = $related_product->get_regular_price();
                            $related_sale_price = $related_product->get_sale_price();
                            $discount_percent = 0;

                            if ($related_sale_price && $related_regular_price) {
                                $discount_percent = round((($related_regular_price - $related_sale_price) / $related_regular_price) * 100);
                            }

                            $related_rating = $related_product->get_average_rating();
                            $related_count = $related_product->get_rating_count();
                        ?>
                            <div class="col-md-3 col-sm-6 col-6">
                                <div class="product-card">
                                    <div class="product-img-box">
                                        <?php if ($discount_percent > 0) : ?>
                                            <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
                                        <?php endif; ?>

                                        <div class="action-icons">
                                            <div class="action-icon wishlist-icon"><i class="far fa-heart"></i></div>
                                            <div class="action-icon"><i class="far fa-eye"></i></div>
                                        </div>

                                        <a href="<?php the_permalink(); ?>" class="product-image-link">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('woocommerce_thumbnail'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo wc_placeholder_img_src('woocommerce_thumbnail'); ?>"
                                                    alt="<?php the_title(); ?>">
                                            <?php endif; ?>
                                        </a>

                                        <a class="add-to-cart-btn" href="<?php echo esc_url($related_product->add_to_cart_url()); ?>">
                                            Add To Cart
                                        </a>
                                    </div>

                                    <div class="mt-3">
                                        <h6 class="product-name">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h6>
                                        <div class="product-price-text">
                                            <?php if ($related_sale_price) : ?>
                                                $<?php echo number_format($related_sale_price, 2); ?>
                                                <span class="old-price">$<?php echo number_format($related_regular_price, 2); ?></span>
                                            <?php else : ?>
                                                $<?php echo number_format($related_regular_price, 2); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-rating-stars">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <i class="<?php echo $i <= $related_rating ? 'fas' : 'far'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                            <span class="rating-count">(<?php echo $related_count; ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata(); ?>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info text-center">
                        <p class="mb-0">No related products found.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <style>
            /* ===================================
   PAGE HERO - BREADCRUMB
==================================== */
            .page-hero {
                padding: 80px 0 20px;
                background: #fff;
            }

            .breadcrumb {
                font-size: 14px;
                color: #666;
                margin: 0;
            }

            .breadcrumb a {
                color: #666;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .breadcrumb a:hover {
                color: #DB4444;
            }

            .breadcrumb span {
                color: #000;
            }

            /* ===================================
   PRODUCT GALLERY
==================================== */
            .product-gallery {
                display: flex;
                gap: 16px;
                max-width: 100%;
            }

            .thumbs-vertical {
                display: flex;
                flex-direction: column;
                gap: 16px;
                max-width: 140px;
                width: 140px;
                flex-shrink: 0;
            }

            .thumb {
                width: 140px;
                height: 114px;
                border: 2px solid transparent;
                border-radius: 4px;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.3s ease;
                background: #F5F5F5;
                padding: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .thumb img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .thumb:hover,
            .thumb.active {
                border-color: #DB4444;
            }

            .main-image {
                background: #F5F5F5;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 30px;
                height: 500px;
                width: 100%;
                flex: 1;
            }

            .main-image img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            /* ===================================
   PRODUCT INFO
==================================== */
            .product-info {
                padding: 39px;
            }

            .product-title {
                font-size: 24px;
                font-weight: 600;
                line-height: 1.2;
                letter-spacing: 0.03em;
                margin-bottom: 16px;
                color: #000;
            }

            /* Rating & Stock Row */
            .rating-stock-row {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 16px;
                font-size: 14px;
            }

            .product-rating {
                display: flex;
                gap: 4px;
                color: #FFAD33;
            }

            .product-rating i {
                font-size: 14px;
            }

            .review-count {
                color: rgba(0, 0, 0, 0.5);
            }

            .divider {
                color: rgba(0, 0, 0, 0.5);
            }

            .stock-status {
                font-weight: 400;
            }

            .stock-status.in-stock {
                color: #00FF66;
            }

            .stock-status.out-stock {
                color: #DB4444;
            }

            .product-price {
                font-size: 24px;
                font-weight: 400;
                letter-spacing: 0.03em;
                margin-bottom: 24px;
                color: #000;
            }

            .product-description {
                font-size: 14px;
                line-height: 21px;
                color: #000;
                margin-bottom: 24px;
            }

            .product-description p {
                margin-bottom: 0;
            }

            .product-divider {
                border: none;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
                margin: 24px 0;
            }

            /* ===================================
   PRODUCT OPTIONS
==================================== */
            .product-options {
                margin-bottom: 24px;
            }

            .option-row {
                display: flex;
                align-items: center;
                margin-bottom: 24px;
            }

            .option-label {
                font-size: 20px;
                font-weight: 400;
                color: #000;
                margin-right: 24px;
                letter-spacing: 0.03em;
                min-width: 80px;
            }

            /* Colors */
            .color-options {
                display: flex;
                gap: 8px;
            }

            .color-dot {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: none;
                cursor: pointer;
                transition: all 0.2s ease;
                position: relative;
            }

            .color-dot.active {
                box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
            }

            .color-dot:hover:not(.active) {
                transform: scale(1.1);
                opacity: 0.8;
            }

            /* Sizes */
            .size-options {
                display: flex;
                gap: 16px;
            }

            .size-pill {
                min-width: 32px;
                height: 32px;
                border: 1px solid rgba(0, 0, 0, 0.5);
                border-radius: 4px;
                background: transparent;
                color: #000;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.2s ease;
                padding: 0 12px;
            }

            .size-pill:hover:not(.active) {
                border-color: #DB4444;
            }

            .size-pill.active {
                background: #DB4444;
                color: #fff;
                border-color: #DB4444;
            }

            /* ===================================
   ACTIONS WRAPPER
==================================== */
            .product-actions-wrapper {
                max-width: fit-content;
            }

            .actions-row {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 40px;
            }

            /* Quantity Selector */
            .quantity-selector {
                display: flex;
                align-items: center;
                border: 1px solid rgba(0, 0, 0, 0.5);
                border-radius: 4px;
                overflow: hidden;
                height: 44px;
                background: #fff;
            }

            .qty-btn {
                width: 40px;
                height: 44px;
                border: none;
                background: transparent;
                color: #000;
                font-size: 24px;
                font-weight: 300;
                cursor: pointer;
                transition: all 0.2s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .qty-btn:hover {
                background: #DB4444;
                color: #fff;
            }

            .qty-btn.minus-btn {
                padding-bottom: 4px;
            }

            .qty-value {
                width: 80px;
                height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                font-weight: 500;
                border-left: 1px solid rgba(0, 0, 0, 0.5);
                border-right: 1px solid rgba(0, 0, 0, 0.5);
            }

            /* Buy Now Button */
            .btn-buy-now {
                background: #DB4444;
                color: #fff;
                border: none;
                border-radius: 4px;
                font-size: 16px;
                font-weight: 500;
                padding: 10px 48px;
                height: 44px;
                min-width: 165px;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-buy-now:hover {
                background: #C13333;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(219, 68, 68, 0.3);
            }

            /* Wishlist Button */
            .btn-wishlist {
                width: 40px;
                height: 44px;
                border: 1px solid rgba(0, 0, 0, 0.5);
                border-radius: 4px;
                background: #fff;
                color: #000;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.2s ease;
            }

            .btn-wishlist:hover {
                border-color: #DB4444;
            }

            .btn-wishlist i {
                font-size: 20px;
            }

            .btn-wishlist.active {
                background: #DB4444;
                border-color: #DB4444;
                color: #fff;
            }

            /* ===================================
   DELIVERY INFO BOX
==================================== */
            .delivery-info-box {
                border: 1px solid rgba(0, 0, 0, 0.5);
                border-radius: 4px;
                overflow: hidden;
                width: 100%;
            }

            .delivery-item {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                padding: 24px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.5);
            }

            .delivery-item:last-child {
                border-bottom: none;
            }

            .delivery-item i {
                font-size: 40px;
                color: #000;
                width: 40px;
                height: 40px;
                flex-shrink: 0;
            }

            .delivery-content {
                flex: 1;
            }

            .delivery-content strong {
                font-size: 16px;
                font-weight: 500;
                display: block;
                margin-bottom: 8px;
                color: #000;
            }

            .delivery-content p {
                font-size: 12px;
                line-height: 18px;
                color: #000;
                margin: 0;
            }

            .delivery-content a {
                color: #000;
                text-decoration: underline;
            }

            .delivery-content a:hover {
                color: #DB4444;
            }

            /* ===================================
   RELATED PRODUCTS
==================================== */
            .section-header {
                display: flex;
                align-items: center;
                gap: 16px;
                margin-bottom: 40px;
            }

            .section-tag {
                width: 20px;
                height: 40px;
                background: #DB4444;
                border-radius: 4px;
            }

            .section-title {
                font-size: 36px;
                font-weight: 600;
                margin: 0;
                color: #000;
            }

            .product-card {
                transition: all 0.3s ease;
            }

            .product-img-box {
                position: relative;
                background: #F5F5F5;
                border-radius: 4px;
                overflow: hidden;
                height: 250px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .product-image-link {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
            }

            .product-img-box img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
            }

            .discount-badge {
                position: absolute;
                top: 12px;
                left: 12px;
                background: #DB4444;
                color: #fff;
                padding: 4px 12px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 600;
                z-index: 2;
            }

            .action-icons {
                position: absolute;
                top: 12px;
                right: 12px;
                display: flex;
                flex-direction: column;
                gap: 8px;
                z-index: 2;
            }

            .action-icon {
                width: 34px;
                height: 34px;
                background: #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .action-icon:hover,
            .action-icon.active {
                background: #DB4444;
                color: #fff;
            }

            .add-to-cart-btn {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: #000;
                color: #fff;
                text-align: center;
                padding: 12px;
                font-size: 14px;
                font-weight: 500;
                text-decoration: none;
                opacity: 0;
                transform: translateY(100%);
                transition: all 0.3s ease;
                z-index: 2;
            }

            .product-card:hover .add-to-cart-btn {
                opacity: 1;
                transform: translateY(0);
            }

            .add-to-cart-btn:hover {
                background: #DB4444;
                color: #fff;
            }

            .product-name {
                font-size: 16px;
                font-weight: 500;
                margin-bottom: 8px;
            }

            .product-name a {
                color: #000;
                text-decoration: none;
            }

            .product-name a:hover {
                color: #DB4444;
            }

            .product-price-text {
                font-size: 16px;
                color: #DB4444;
                font-weight: 600;
                margin-bottom: 8px;
            }

            .old-price {
                color: #999;
                text-decoration: line-through;
                font-weight: 400;
                margin-left: 8px;
            }

            .product-rating-stars {
                color: #FFAD33;
                font-size: 14px;
            }

            .product-rating-stars .rating-count {
                color: rgba(0, 0, 0, 0.5);
                margin-left: 8px;
            }

            /* ===================================
   RESPONSIVE
==================================== */
            @media (max-width: 991px) {
                .main-image {
                    height: 400px;
                    padding: 20px;
                }

                .thumbs-vertical {
                    max-width: 100px;
                    width: 100px;
                }

                .thumb {
                    width: 100px;
                    height: 82px;
                }

                .section-title {
                    font-size: 28px;
                }
            }

            @media (max-width: 767px) {
                .page-hero {
                    padding: 40px 0 15px;
                }

                .product-gallery {
                    flex-direction: column-reverse;
                    gap: 12px;
                }

                .thumbs-vertical {
                    flex-direction: row;
                    max-width: 100%;
                    width: 100%;
                    overflow-x: auto;
                    gap: 8px;
                }

                .thumb {
                    min-width: 70px;
                    width: 70px;
                    height: 70px;
                    padding: 5px;
                }

                .main-image {
                    height: 300px;
                    padding: 20px;
                }

                .product-title {
                    font-size: 20px;
                }

                .option-label {
                    font-size: 16px;
                    min-width: 60px;
                }

                .actions-row {
                    flex-wrap: wrap;
                }

                .quantity-selector {
                    flex: 1;
                    min-width: 140px;
                }

                .btn-buy-now {
                    flex: 1;
                    min-width: 140px;
                }

                .delivery-item {
                    padding: 16px;
                }

                .delivery-item i {
                    font-size: 32px;
                }

                .section-title {
                    font-size: 24px;
                }
            }
        </style>


        <script>
            jQuery(document).ready(function($) {
                // ===================================
                // Thumbnail Gallery Navigation
                // ===================================
                $('.thumb').on('click', function() {
                    const imageUrl = $(this).data('image');
                    $('#mainProductImage').attr('src', imageUrl);
                    $('.thumb').removeClass('active');
                    $(this).addClass('active');
                });

                // ===================================
                // Quantity Controls
                // ===================================
                let quantity = 1;

                $('.plus-btn').on('click', function() {
                    quantity++;
                    $('#product-quantity').text(quantity);
                });

                $('.minus-btn').on('click', function() {
                    if (quantity > 1) {
                        quantity--;
                        $('#product-quantity').text(quantity);
                    }
                });

                // ===================================
                // Color Selection
                // ===================================
                $('.color-dot').on('click', function() {
                    $('.color-dot').removeClass('active');
                    $(this).addClass('active');
                });

                // ===================================
                // Size Selection
                // ===================================
                $('.pill').on('click', function() {
                    $('.pill').removeClass('active');
                    $(this).addClass('active');
                });

                // ===================================
                // Wishlist Toggle
                // ===================================
                $('.btn-wishlist').on('click', function() {
                    const icon = $(this).find('i');

                    if (icon.hasClass('far')) {
                        icon.removeClass('far').addClass('fas');
                        $(this).addClass('active');
                    } else {
                        icon.removeClass('fas').addClass('far');
                        $(this).removeClass('active');
                    }
                });

                // ===================================
                // Buy Now - Add to Cart & Redirect
                // ===================================
                $('#buyNowBtn').on('click', function() {
                    const btn = $(this);
                    const productId = btn.data('product-id');
                    const qty = quantity;

                    btn.prop('disabled', true).text('Adding...');

                    $.ajax({
                        url: exclusiveAjax.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'add_to_cart',
                            product_id: productId,
                            quantity: qty,
                            nonce: exclusiveAjax.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                // Redirect to checkout
                                window.location.href = '<?php echo wc_get_checkout_url(); ?>';
                            } else {
                                alert('Error adding to cart');
                                btn.prop('disabled', false).text('Buy Now');
                            }
                        },
                        error: function() {
                            alert('Error adding to cart');
                            btn.prop('disabled', false).text('Buy Now');
                        }
                    });
                });

                // ===================================
                // Related Products - Wishlist Toggle
                // ===================================
                $(document).on('click', '.wishlist-icon', function() {
                    const icon = $(this);

                    if (icon.hasClass('active')) {
                        icon.removeClass('active');
                        icon.find('i').removeClass('fas').addClass('far');
                    } else {
                        icon.addClass('active');
                        icon.find('i').removeClass('far').addClass('fas');
                    }
                });
            });
        </script>

<?php
    endwhile;
endif;

get_footer();
?>