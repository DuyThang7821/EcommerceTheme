<?php

/**
 * Explore Our Products Section
 * Grid layout with 2 rows of products
 */

// Query for featured or recent products
$explore_args = array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'orderby' => 'date',
    'order' => 'DESC'
);

$explore_query = new WP_Query($explore_args);
?>

<section class="explore-products-section py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="d-flex align-items-end justify-content-between flex-wrap mb-4">
            <div class="section-tag-group">
                <div class="section-tag">
                    Our Products
                </div>
                <h2 class="section-title">Explore Our Products</h2>
            </div>

            <!-- Navigation Arrows -->
            <div class="explore-nav-arrows d-flex gap-2">
                <button class="nav-arrow explore-prev" aria-label="Previous">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="nav-arrow explore-next" aria-label="Next">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <?php if ($explore_query->have_posts()) : ?>
        <!-- Products Grid -->
        <div class="row g-4 mb-5">
            <?php while ($explore_query->have_posts()) : $explore_query->the_post();
                    global $product;
                    $product_id = get_the_ID();
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $discount_percent = $sale_price ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;

                    // Check if product is new (created within last 30 days)
                    $is_new = (time() - strtotime($product->get_date_created())) < (30 * 24 * 60 * 60);
                    ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="product-card">
                    <!-- Product Image -->
                    <div class="product-img-box">
                        <?php if ($discount_percent > 0) : ?>
                        <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
                        <?php elseif ($is_new) : ?>
                        <span class="new-badge">NEW</span>
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

                        <a href="<?php the_permalink(); ?>" class="product-link">
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
                            <span
                                class="price fw-bold"><?php echo number_format($regular_price, 0, ',', '.'); ?>$</span>
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

                        <!-- Color Variants (if available) -->
                        <?php
                                $available_variations = $product->get_type() === 'variable' ? $product->get_available_variations() : array();
                    if (!empty($available_variations) && count($available_variations) <= 3) :
                        ?>
                        <div class="product-colors d-flex gap-2 mt-2">
                            <?php
                                $colors = array('#FB1314', '#000000'); // Example colors
                        foreach (array_slice($colors, 0, 2) as $color) :
                            ?>
                            <span class="color-dot" style="background: <?php echo $color; ?>;"></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile;
wp_reset_postdata(); ?>
        </div>

        <!-- View All Products Button -->
        <div class="text-center">
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary-custom px-5 py-3">
                View All Products
            </a>
        </div>
        <?php else : ?>
        <p class="text-center"><?php _e('No products found.', 'exclusive'); ?></p>
        <?php endif; ?>
    </div>
</section>

<style>
/* Explore Products Section */
.explore-products-section {
    background: #fff;
}

/* Section Tag - Reuse styles */
.section-tag-group {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.section-tag {
    display: flex;
    align-items: center;
    color: var(--primary-color);
    font-weight: 600;
    font-size: 16px;
    position: relative;
}

.section-tag::before {
    content: "";
    width: 20px;
    height: 40px;
    background: var(--primary-color);
    display: inline-block;
    margin-right: 16px;
    border-radius: 4px;
}

.section-title {
    font-size: 36px;
    font-weight: 600;
    margin: 0;
    color: #000;
    line-height: 1.2;
}

/* Navigation Arrows */
.explore-nav-arrows .nav-arrow {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: #F5F5F5;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #000;
}

.explore-nav-arrows .nav-arrow:hover {
    background: var(--primary-color);
    color: #fff;
    transform: scale(1.05);
}

/* NEW Badge */
.new-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #00FF66;
    color: #fff;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* Color Dots */
.product-colors {
    display: flex;
    gap: 8px;
    align-items: center;
}

.color-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-dot:hover {
    transform: scale(1.2);
    box-shadow: 0 0 0 2px var(--primary-color);
}

/* Responsive */
@media (max-width: 991px) {
    .section-title {
        font-size: 28px;
    }

    .explore-nav-arrows .nav-arrow {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 767px) {
    .section-tag::before {
        width: 16px;
        height: 32px;
        margin-right: 12px;
    }

    .section-tag {
        font-size: 14px;
    }

    .section-title {
        font-size: 24px;
    }

    .section-tag-group {
        gap: 16px;
    }

    .explore-nav-arrows {
        margin-top: 16px;
    }
}
</style>