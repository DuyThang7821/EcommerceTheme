<?php

/**
 * Best Selling Products Section
 * Load more functionality với AJAX
 */

require_once get_template_directory() . '/inc/section-tag.php';

// Initial products to show
$products_per_page = 8;
$paged = isset($_GET['product_page']) ? absint($_GET['product_page']) : 1;

$best_selling_args = array(
    'post_type' => 'product',
    'posts_per_page' => $products_per_page,
    'paged' => $paged,
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
);

$best_selling_query = new WP_Query($best_selling_args);
$total_products = $best_selling_query->found_posts;
$total_pages = $best_selling_query->max_num_pages;
?>

<section class="best-selling-section py-5">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between flex-wrap mb-4">
            <?php exclusive_section_tag('This Month', 'Best Selling Products'); ?>

            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary-custom px-4 py-2">
                View All
            </a>
        </div>

        <?php if ($best_selling_query->have_posts()) : ?>
        <div class="row g-4" id="best-selling-products">
            <?php while ($best_selling_query->have_posts()) : $best_selling_query->the_post();
                    global $product;
                    $product_id = get_the_ID();
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $discount_percent = $sale_price ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
                    ?>
            <div class="col-6 col-md-4 col-lg-3">
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
                                class="regular-price text-muted text-decoration-line-through ms-2"><?php echo number_format($regular_price, 0, ',', '.'); ?>$</span>
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
                    </div>
                </div>
            </div>
            <?php endwhile;
wp_reset_postdata(); ?>
        </div>

        <!-- Load More Button -->
        <?php if ($paged < $total_pages) : ?>
        <div class="text-center mt-5">
            <button id="load-more-btn" class="btn btn-primary-custom px-5 py-3" data-page="<?php echo $paged; ?>"
                data-max-pages="<?php echo $total_pages; ?>">
                <span class="btn-text">View All Products</span>
                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status">
                    <span class="visually-hidden">Loading...</span>
                </span>
            </button>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <p class="text-center"><?php _e('No products found.', 'exclusive'); ?></p>
        <?php endif; ?>
    </div>
</section>

<script>
jQuery(document).ready(function($) {
    let isLoading = false;

    $('#load-more-btn').on('click', function() {
        if (isLoading) return;

        isLoading = true;
        const btn = $(this);
        const currentPage = parseInt(btn.data('page'));
        const maxPages = parseInt(btn.data('max-pages'));
        const nextPage = currentPage + 1;

        // Show loading spinner
        btn.find('.btn-text').text('Loading...');
        btn.find('.spinner-border').removeClass('d-none');
        btn.prop('disabled', true);

        $.ajax({
            url: exclusiveAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_more_products',
                page: nextPage,
                nonce: exclusiveAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#best-selling-products').append(response.data.html);
                    btn.data('page', nextPage);

                    // Hide button if last page
                    if (nextPage >= maxPages) {
                        btn.parent().fadeOut();
                    }
                } else {
                    alert('Error loading products');
                }
            },
            error: function() {
                alert('Error loading products');
            },
            complete: function() {
                btn.find('.btn-text').text('View All Products');
                btn.find('.spinner-border').addClass('d-none');
                btn.prop('disabled', false);
                isLoading = false;
            }
        });
    });

    // Add to cart AJAX
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        const btn = $(this);
        const productId = btn.data('product-id');
        const originalText = btn.text();

        btn.text('Adding...').prop('disabled', true);

        $.ajax({
            url: exclusiveAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                nonce: exclusiveAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    btn.text('Added!').css('background', '#28a745');

                    // Update cart count if exists
                    if ($('.cart-count').length) {
                        const currentCount = parseInt($('.cart-count').text()) || 0;
                        $('.cart-count').text(currentCount + 1);
                    }

                    setTimeout(function() {
                        btn.text(originalText).css('background', '');
                        btn.prop('disabled', false);
                    }, 2000);
                }
            },
            error: function() {
                btn.text('Error').css('background', '#dc3545');
                setTimeout(function() {
                    btn.text(originalText).css('background', '');
                    btn.prop('disabled', false);
                }, 2000);
            }
        });
    });

    // Wishlist toggle
    $(document).on('click', '.wishlist-btn', function() {
        const btn = $(this);
        const icon = btn.find('i');

        if (icon.hasClass('far')) {
            icon.removeClass('far').addClass('fas');
            btn.css('color', '#DB4444');
        } else {
            icon.removeClass('fas').addClass('far');
            btn.css('color', '');
        }
    });
});
</script>