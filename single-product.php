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

        // Get product attributes for colors and sizes
        $attributes = $product->get_attributes();
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
        <div class="row g-5">
            <!-- Product Gallery Column -->
            <div class="col-lg-6">
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
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Product Title -->
                    <h2 class="mb-3" style="font-size: 28px; font-weight: 600; line-height: 1.2;">
                        <?php the_title(); ?>
                    </h2>

                    <!-- Rating & Stock -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="text-warning me-2">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <i class="<?php echo $i <= $average_rating ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted" style="font-size: 14px;">
                            (<?php echo $rating_count; ?> Reviews) |
                            <span style="color: <?php echo $stock_status === 'instock' ? '#00FF66' : '#DB4444'; ?>;">
                                <?php echo $stock_status === 'instock' ? 'In Stock' : 'Out of Stock'; ?>
                            </span>
                        </small>
                    </div>

                    <!-- Price -->
                    <h2 class="mb-3" style="font-size: 28px; font-weight: 600;">
                        <?php if ($sale_price) : ?>
                        $<?php echo number_format($sale_price, 2); ?>
                        <?php else : ?>
                        $<?php echo number_format($regular_price, 2); ?>
                        <?php endif; ?>
                    </h2>

                    <!-- Short Description -->
                    <p class="text-muted mb-4 product-description" style="font-size: 14px; line-height: 1.6;">
                        <?php echo wpautop($product->get_short_description()); ?>
                    </p>

                    <hr class="my-4">

                    <!-- Product Options -->
                    <div class="product-options mb-4">
                        <?php
                                // Hiển thị màu sắc và size (giả lập như design)
                                // Bạn có thể thay đổi logic này tùy theo cấu hình sản phẩm
                                ?>

                        <!-- Colors (Static - có thể customize) -->
                        <div class="option-group mb-3">
                            <span class="option-label fw-bold mb-2 d-block">Colours:</span>
                            <div class="color-options">
                                <button class="color-dot active" style="background: #87CEEB;"
                                    data-color="Light Blue"></button>
                                <button class="color-dot" style="background: #FFB6C1;" data-color="Pink"></button>
                            </div>
                        </div>

                        <!-- Size (Static - có thể customize) -->
                        <div class="option-group mb-4">
                            <span class="option-label fw-bold mb-2 d-block">Size:</span>
                            <div class="pill-options">
                                <button class="pill">XS</button>
                                <button class="pill">S</button>
                                <button class="pill active">M</button>
                                <button class="pill">L</button>
                                <button class="pill">XL</button>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity, Buy Now, Wishlist -->
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                        <!-- Quantity Selector -->
                        <div class="quantity-selector-custom">
                            <button type="button" class="qty-btn-custom minus-btn">-</button>
                            <span class="quantity-value" id="product-quantity">1</span>
                            <button type="button" class="qty-btn-custom plus-btn">+</button>
                        </div>

                        <!-- Buy Now Button -->
                        <button class="btn btn-buy-now" id="buyNowBtn" data-product-id="<?php echo $product_id; ?>"
                            style="background: #DB4444; color: white; padding: 10px 24px; width: 165px; flex: none;">
                            Buy Now
                        </button>

                        <!-- Wishlist Button -->
                        <button class="btn btn-wishlist" style="padding: 12px; width: 44px; height: 44px;">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <!-- Delivery Info -->
                    <div class="delivery-info-clean mt-5 pt-4">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-truck" style="font-size: 24px; color: #000;"></i>
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">Free Delivery</strong>
                                <p class="delivery-text mb-0" style="font-size: 14px;">
                                    Enter your postal code for Delivery Availability.
                                </p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-undo" style="font-size: 24px; color: #000;"></i>
                            <div>
                                <strong style="display: block; margin-bottom: 8px;">Return Delivery</strong>
                                <p class="delivery-text mb-0" style="font-size: 14px;">
                                    Free 30 Days Delivery Returns.
                                    <a
                                        href="<?php echo get_permalink(get_page_by_path('return-policy')); ?>">Details</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Items Section -->
    <section class="container my-5 py-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div style="width: 20px; height: 40px; background: #DB4444; border-radius: 4px;"></div>
            <h3 class="mb-0 fw-bold">Related Item</h3>
        </div>

        <?php
                // Lấy 4 sản phẩm bất kỳ, loại trừ sản phẩm hiện tại
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
                            if (!$related_product) continue;

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
                        <h6 class="fw-bold product-title-link">
                            <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: #000;">
                                <?php the_title(); ?>
                            </a>
                        </h6>
                        <div class="text-danger fw-bold mb-2">
                            <?php if ($related_sale_price) : ?>
                            $<?php echo number_format($related_sale_price, 2); ?>
                            <span class="text-muted text-decoration-line-through fw-normal ms-2">
                                $<?php echo number_format($related_regular_price, 2); ?>
                            </span>
                            <?php else : ?>
                            $<?php echo number_format($related_regular_price, 2); ?>
                            <?php endif; ?>
                        </div>
                        <div class="text-warning" style="font-size: 14px;">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <i class="<?php echo $i <= $related_rating ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                            <span class="text-muted ms-1">(<?php echo $related_count; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile;
                        wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <div class="alert alert-info text-center">
            <p class="mb-0">Không có sản phẩm liên quan. Vui lòng thêm sản phẩm vào WooCommerce.</p>
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

.page-hero .breadcrumb {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.page-hero .breadcrumb a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.page-hero .breadcrumb a:hover {
    color: #DB4444;
}

.page-hero .breadcrumb span {
    color: #000;
    font-weight: 500;
}

/* ===================================
   PRODUCT GALLERY
==================================== */
.product-gallery {
    display: flex;
    gap: 20px;
}

.thumbs-vertical {
    display: flex;
    flex-direction: column;
    gap: 16px;
    max-width: 170px;
    flex-shrink: 0;
}

.thumb {
    width: 170px;
    height: 138px;
    border: 2px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #F5F5F5;
    padding: 10px;
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
    padding: 40px;
    min-height: 600px;
    width: 100%;
}

.main-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* ===================================
   PRODUCT OPTIONS
==================================== */
.option-group {
    margin-bottom: 24px;
}

.option-label {
    font-size: 16px;
    font-weight: 600;
    color: #000;
    margin-bottom: 16px;
}

/* Color Options */
.color-options {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.color-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.color-dot:hover,
.color-dot.active {
    transform: scale(1.1);
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #000;
}

/* Size Options */
.pill-options {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.pill {
    min-width: 50px;
    height: 40px;
    border: 1.5px solid rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    background: #fff;
    color: #000;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0 16px;
}

.pill:hover,
.pill.active {
    background: #DB4444;
    color: #fff;
    border-color: #DB4444;
}

/* ===================================
   QUANTITY SELECTOR
==================================== */
.quantity-selector-custom {
    display: flex;
    align-items: center;
    border: 1.5px solid rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    overflow: hidden;
}

.qty-btn-custom {
    width: 40px;
    height: 44px;
    border: none;
    background: #fff;
    color: #000;
    font-size: 20px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-btn-custom:hover {
    background: #DB4444;
    color: #fff;
}

.quantity-value {
    min-width: 80px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 600;
    border-left: 1.5px solid rgba(0, 0, 0, 0.3);
    border-right: 1.5px solid rgba(0, 0, 0, 0.3);
}

/* ===================================
   BUTTONS
==================================== */
.btn-buy-now {
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-buy-now:hover {
    background: #C13939 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.4);
}

.btn-wishlist {
    width: 44px;
    height: 44px;
    border: 1.5px solid rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-wishlist:hover {
    border-color: #DB4444;
    color: #DB4444;
}

.btn-wishlist.active {
    background: #DB4444;
    border-color: #DB4444;
    color: #fff;
}

/* ===================================
   DELIVERY INFO
==================================== */
.delivery-info-clean {
    border-top: 1px solid #e5e5e5;
    padding-top: 24px;
}

.delivery-info-clean .d-flex {
    padding: 16px 0;
}

.delivery-info-clean strong {
    font-size: 16px;
    font-weight: 500;
}

.delivery-text a {
    color: #000;
    text-decoration: underline;
}

.delivery-text a:hover {
    color: #DB4444;
}

/* ===================================
   RELATED PRODUCTS
==================================== */
.product-card {
    transition: all 0.3s ease;
    height: 100%;
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

.product-img-box a.product-image-link {
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

.action-icon:hover {
    background: #DB4444;
    color: #fff;
}

.action-icon.wishlist-icon.active {
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

.product-title-link {
    font-size: 16px;
    line-height: 1.4;
    margin-bottom: 8px;
}

.product-title-link a:hover {
    color: #DB4444 !important;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .main-image {
        min-height: 400px;
        padding: 20px;
    }

    .thumbs-vertical {
        max-width: 120px;
    }

    .thumb {
        width: 120px;
        height: 98px;
    }
}

@media (max-width: 767px) {
    .page-hero {
        padding: 40px 0 15px;
    }

    .product-gallery {
        flex-direction: column-reverse;
    }

    .thumbs-vertical {
        flex-direction: row;
        max-width: 100%;
        overflow-x: auto;
    }

    .thumb {
        min-width: 80px;
        width: 80px;
        height: 80px;
    }

    .main-image {
        min-height: 300px;
    }

    .quantity-selector-custom {
        flex: 1;
    }

    .btn-buy-now {
        flex: 1 !important;
        width: auto !important;
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