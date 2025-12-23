<?php

/**
 * Archive Product Template - Products/Shop Page
 *
 * @package Exclusive
 */

get_header();
?>

<main class="products-archive-page">
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <?php
                    if (is_product_category()) {
                        $current_cat = get_queried_object();
                        echo '<li class="breadcrumb-item active">' . esc_html($current_cat->name) . '</li>';
                    } else {
                        echo '<li class="breadcrumb-item active">Products</li>';
                    }
?>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Products Content -->
    <section class="products-content-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Sidebar Filter -->
                <div class="col-lg-3">
                    <div class="products-sidebar">
                        <!-- Category Filter -->
                        <div class="filter-widget mb-4">
                            <h5 class="widget-title">Categories</h5>
                            <ul class="category-list">
                                <?php
            $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => 0,
            ));

if (!empty($categories) && !is_wp_error($categories)) :
    foreach ($categories as $category) :
        $current_cat = is_product_category($category->slug) ? 'active' : '';
        ?>
                                <li class="category-item <?php echo $current_cat; ?>">
                                    <a href="<?php echo get_term_link($category); ?>">
                                        <?php echo esc_html($category->name); ?>
                                        <span class="count">(<?php echo $category->count; ?>)</span>
                                    </a>
                                </li>
                                <?php
    endforeach;
endif;
?>
                            </ul>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-widget mb-4">
                            <h5 class="widget-title">Filter by Price</h5>
                            <form class="price-filter-form" method="get">
                                <div class="price-inputs mb-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="number" name="min_price" class="form-control" placeholder="Min"
                                                value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : ''; ?>">
                                        </div>
                                        <div class="col-6">
                                            <input type="number" name="max_price" class="form-control" placeholder="Max"
                                                value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-filter w-100">Apply Filter</button>
                            </form>
                        </div>

                        <!-- Rating Filter -->
                        <div class="filter-widget mb-4">
                            <h5 class="widget-title">Filter by Rating</h5>
                            <ul class="rating-list">
                                <?php for ($i = 5; $i >= 1; $i--) : ?>
                                <li class="rating-item">
                                    <a href="<?php echo add_query_arg('rating_filter', $i); ?>">
                                        <div class="stars text-warning">
                                            <?php for ($j = 1; $j <= 5; $j++) : ?>
                                            <i class="<?php echo $j <= $i ? 'fas' : 'far'; ?> fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="rating-text"><?php echo $i; ?> Stars & Up</span>
                                    </a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </div>

                        <!-- On Sale Filter -->
                        <div class="filter-widget">
                            <h5 class="widget-title">Special Offers</h5>
                            <ul class="special-list">
                                <li>
                                    <a href="<?php echo add_query_arg('on_sale', '1'); ?>">
                                        <i class="fas fa-tag text-danger me-2"></i>
                                        On Sale Products
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo add_query_arg('featured', '1'); ?>">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        Featured Products
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="col-lg-9">
                    <!-- Toolbar -->
                    <div class="products-toolbar mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="showing-results">
                                <?php
$total = wc_get_loop_prop('total');
$per_page = wc_get_loop_prop('per_page');
$current = wc_get_loop_prop('current_page');
$from = ($current - 1) * $per_page + 1;
$to = min($current * $per_page, $total);
?>
                                <span class="text-muted">Showing <?php echo $from; ?>–<?php echo $to; ?> of
                                    <?php echo $total; ?> results</span>
                            </div>

                            <div class="sorting-options">
                                <form method="get" class="d-flex align-items-center gap-2">
                                    <label for="orderby" class="mb-0 text-muted">Sort by:</label>
                                    <select name="orderby" id="orderby" class="form-select form-select-sm"
                                        onchange="this.form.submit()">
                                        <option value="menu_order"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'menu_order'); ?>>
                                            Default</option>
                                        <option value="popularity"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'popularity'); ?>>
                                            Popularity</option>
                                        <option value="rating"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'rating'); ?>>
                                            Average rating</option>
                                        <option value="date"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>>
                                            Latest</option>
                                        <option value="price"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price'); ?>>
                                            Price: Low to High</option>
                                        <option value="price-desc"
                                            <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price-desc'); ?>>
                                            Price: High to Low</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <?php if (woocommerce_product_loop()) : ?>
                    <div class="row g-4" id="products-grid">
                        <?php
                            while (have_posts()) :
                                the_post();
                                global $product;
                                $product_id = get_the_ID();
                                $regular_price = $product->get_regular_price();
                                $sale_price = $product->get_sale_price();
                                $discount_percent = 0;

                                if ($sale_price && $regular_price) {
                                    $discount_percent = round((($regular_price - $sale_price) / $regular_price) * 100);
                                }

                                $average_rating = $product->get_average_rating();
                                $rating_count = $product->get_rating_count();
                                ?>
                        <div class="col-md-6 col-lg-4 product-grid-item">
                            <div class="product-card">
                                <div class="product-img-box">
                                    <?php if ($discount_percent > 0) : ?>
                                    <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
                                    <?php endif; ?>

                                    <!-- Action Icons -->
                                    <div class="action-icons">
                                        <div class="action-icon wishlist-icon">
                                            <i class="far fa-heart"></i>
                                        </div>
                                        <div class="action-icon">
                                            <i class="far fa-eye"></i>
                                        </div>
                                    </div>

                                    <a href="<?php the_permalink(); ?>" class="product-image-link">
                                        <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('woocommerce_thumbnail'); ?>
                                        <?php else : ?>
                                        <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title(); ?>">
                                        <?php endif; ?>
                                    </a>

                                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                        class="add-to-cart-btn" data-product-id="<?php echo $product_id; ?>">
                                        Add To Cart
                                    </a>
                                </div>

                                <div class="product-info mt-3">
                                    <h6 class="product-name">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h6>

                                    <div class="product-price-text mb-2">
                                        <?php if ($sale_price) : ?>
                                        <span class="sale-price">$<?php echo number_format($sale_price, 2); ?></span>
                                        <span class="old-price">$<?php echo number_format($regular_price, 2); ?></span>
                                        <?php else : ?>
                                        <span class="price">$<?php echo number_format($regular_price, 2); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="product-rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <i class="<?php echo $i <= $average_rating ? 'fas' : 'far'; ?> fa-star"></i>
                                        <?php endfor; ?>
                                        <span class="rating-count">(<?php echo $rating_count; ?>)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <div class="products-pagination mt-5">
                        <?php
                                echo paginate_links(array(
                                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                                    'type' => 'list',
                                    'end_size' => 2,
                                    'mid_size' => 1,
                                ));
?>
                    </div>

                    <?php else : ?>
                    <div class="no-products-found text-center py-5">
                        <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                        <h3>No products found</h3>
                        <p class="text-muted">Try adjusting your filters or browse our categories.</p>
                        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"
                            class="btn btn-primary-custom mt-3">
                            View All Products
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Override global add-to-cart style for archive pages */
.archive.post-type-archive-product .add-to-cart-btn,
.archive.post-type-archive-product a.add-to-cart-btn,
.tax-product_cat .add-to-cart-btn,
.tax-product_cat a.add-to-cart-btn {
    top: auto !important;
    /* Remove fixed top */
}

/* Ensure proper positioning on archive pages */
.archive.post-type-archive-product .product-img-box,
.tax-product_cat .product-img-box {
    position: relative !important;
    overflow: hidden !important;
}

.archive.post-type-archive-product .add-to-cart-btn,
.tax-product_cat .add-to-cart-btn {
    position: absolute !important;
    bottom: -50px !important;
    left: 0 !important;
    right: 0 !important;
    top: auto !important;
    width: 100% !important;
    height: 44px !important;
}

.archive.post-type-archive-product .product-card:hover .add-to-cart-btn,
.tax-product_cat .product-card:hover .add-to-cart-btn {
    bottom: 0 !important;
}

/* ===================================
   BREADCRUMB SECTION
==================================== */
.breadcrumb-section {
    padding: 80px 0 20px;
    background: #fff;
}

.breadcrumb {
    background: transparent;
    padding: 0;
}

.breadcrumb-item {
    font-size: 14px;
    color: #666;
}

.breadcrumb-item a {
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--primary-color, #DB4444);
}

.breadcrumb-item.active {
    color: #000;
    font-weight: 500;
}

.breadcrumb-item+.breadcrumb-item::before {
    content: "/";
    color: #666;
    padding: 0 8px;
}

/* ===================================
   PRODUCTS CONTENT
==================================== */
.products-content-section {
    background: #fff;
}

/* ===================================
   SIDEBAR
==================================== */
.products-sidebar {
    background: #fff;
    padding: 24px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
}

.widget-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 16px;
    color: #000;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--primary-color, #DB4444);
}

/* Category List */
.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-item {
    margin-bottom: 8px;
}

.category-item a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    color: #000;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 15px;
}

.category-item a:hover,
.category-item.active a {
    background: rgba(219, 68, 68, 0.1);
    color: var(--primary-color, #DB4444);
    padding-left: 16px;
}

.category-item .count {
    color: #999;
    font-size: 13px;
}

/* Price Filter */
.price-filter-form .form-control {
    height: 40px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    font-size: 14px;
}

.btn-filter {
    background: var(--primary-color, #DB4444);
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background: #C13333;
    transform: translateY(-2px);
}

/* Rating List */
.rating-list,
.special-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.rating-item,
.special-list li {
    margin-bottom: 8px;
}

.rating-item a,
.special-list li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    color: #000;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.rating-item a:hover,
.special-list li a:hover {
    background: rgba(219, 68, 68, 0.1);
    color: var(--primary-color, #DB4444);
}

.rating-item .stars {
    font-size: 12px;
}

/* ===================================
   TOOLBAR
==================================== */
.products-toolbar {
    background: #f8f9fa;
    padding: 16px 20px;
    border-radius: 4px;
}

.showing-results {
    font-size: 14px;
}

.form-select-sm {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 6px 32px 6px 12px;
    font-size: 14px;
    cursor: pointer;
}

/* ===================================
   PRODUCTS GRID
==================================== */
.product-grid-item {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.product-card {
    transition: all 0.3s ease;
    height: 100%;
}

.product-img-box {
    position: relative;
    background: #F5F5F5;
    border-radius: 4px;
    overflow: hidden;
    height: 280px;
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
    background: var(--primary-color, #DB4444);
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
    background: var(--primary-color, #DB4444);
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
    background: var(--primary-color, #DB4444);
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
    color: var(--primary-color, #DB4444);
}

.product-price-text {
    font-size: 16px;
}

.sale-price {
    color: var(--primary-color, #DB4444);
    font-weight: 600;
}

.old-price {
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
    margin-left: 8px;
}

.price {
    color: #000;
    font-weight: 600;
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
   PAGINATION
==================================== */
.products-pagination {
    display: flex;
    justify-content: center;
}

.products-pagination ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 8px;
}

.products-pagination li {
    display: inline-block;
}

.products-pagination a,
.products-pagination span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    color: #000;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.products-pagination a:hover {
    background: var(--primary-color, #DB4444);
    color: #fff;
    border-color: var(--primary-color, #DB4444);
}

.products-pagination .current {
    background: var(--primary-color, #DB4444);
    color: #fff;
    border-color: var(--primary-color, #DB4444);
}

/* No Products Found */
.no-products-found {
    padding: 80px 20px;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .products-sidebar {
        margin-bottom: 30px;
    }

    .product-img-box {
        height: 240px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .products-toolbar {
        padding: 12px 16px;
    }

    .products-toolbar .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .showing-results {
        font-size: 13px;
        margin-bottom: 12px;
    }

    .product-img-box {
        height: 200px;
    }

    .product-name {
        font-size: 14px;
    }

    .product-price-text {
        font-size: 14px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Wishlist toggle
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

                    // Update cart count
                    if (response.data.cart_count) {
                        $('.cart-count, .badge').text(response.data.cart_count);
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
});
</script>

<?php get_footer(); ?>