<?php

/**
 * Single Product Template
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

        // Get product attributes
        $attributes = $product->get_attributes();
        ?>

<main class="product-details-page py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo home_url('/my-account'); ?>">Account</a></li>
                <li class="breadcrumb-item"><a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>">Gaming</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Product Gallery -->
            <div class="col-lg-7">
                <div class="product-gallery">
                    <!-- Thumbnails Vertical -->
                    <div class="thumbs-vertical">
                        <?php
                                        // Featured image first
                                        if (has_post_thumbnail()) :
                                            $image_id = get_post_thumbnail_id();
                                            $image_url = wp_get_attachment_image_url($image_id, 'full');
                                            ?>
                        <div class="thumb active" data-image="<?php echo esc_url($image_url); ?>">
                            <?php the_post_thumbnail('thumbnail'); ?>
                        </div>
                        <?php endif; ?>

                        <!-- Gallery images -->
                        <?php foreach ($attachment_ids as $attachment_id) :
                                    $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                                    ?>
                        <div class="thumb" data-image="<?php echo esc_url($image_url); ?>">
                            <?php echo wp_get_attachment_image($attachment_id, 'thumbnail'); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Main Image -->
                    <div class="main-image">
                        <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('id' => 'mainProductImage')); ?>
                        <?php else : ?>
                        <img src="<?php echo wc_placeholder_img_src('large'); ?>" alt="<?php the_title(); ?>"
                            id="mainProductImage">
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info">
                    <!-- Title -->
                    <h2 class="mb-3"><?php the_title(); ?></h2>

                    <!-- Rating & Reviews -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="product-rating d-flex align-items-center gap-2">
                            <div class="stars text-warning">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <i class="<?php echo $i <= $average_rating ? 'fas' : 'far'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-muted">(<?php echo $rating_count; ?> Reviews)</span>
                        </div>
                        <span class="text-muted">|</span>
                        <span class="<?php echo $stock_status === 'instock' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $stock_status === 'instock' ? 'In Stock' : 'Out of Stock'; ?>
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="product-price mb-4">
                        <?php if ($sale_price) : ?>
                        <h3 class="mb-0">
                            <span class="text-danger"><?php echo number_format($sale_price, 0, ',', '.'); ?>đ</span>
                        </h3>
                        <?php else : ?>
                        <h3 class="mb-0"><?php echo number_format($regular_price, 0, ',', '.'); ?>đ</h3>
                        <?php endif; ?>
                    </div>

                    <!-- Short Description -->
                    <div class="product-description mb-4">
                        <?php echo wpautop($product->get_short_description()); ?>
                    </div>

                    <hr class="my-4">

                    <!-- Product Options -->
                    <div class="product-options">
                        <!-- Colors -->
                        <?php if ($product->is_type('variable')) :
                                    $available_variations = $product->get_available_variations();
                                    $attributes = $product->get_variation_attributes();

                                    if (isset($attributes['pa_color']) || isset($attributes['color'])) :
                                        $colors = isset($attributes['pa_color']) ? $attributes['pa_color'] : $attributes['color'];
                                        ?>
                        <div class="option-group">
                            <h6 class="option-label">Colours:</h6>
                            <div class="color-options">
                                <?php foreach ($colors as $color) :
                                                    $color_hex = get_term_meta(get_term_by('slug', $color, 'pa_color')->term_id, 'color', true);
                                                    if (!$color_hex) {
                                                        $color_hex = '#' . $color;
                                                    }
                                                    ?>
                                <button class="color-dot" style="background: <?php echo esc_attr($color_hex); ?>;"
                                    data-color="<?php echo esc_attr($color); ?>">
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif;

                                    // Sizes
                                    if (isset($attributes['pa_size']) || isset($attributes['size'])) :
                                        $sizes = isset($attributes['pa_size']) ? $attributes['pa_size'] : $attributes['size'];
                                        ?>
                        <div class="option-group">
                            <h6 class="option-label">Size:</h6>
                            <div class="pill-options">
                                <?php foreach ($sizes as $size) : ?>
                                <button class="pill" data-size="<?php echo esc_attr($size); ?>">
                                    <?php echo esc_html(strtoupper($size)); ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif;
                                endif; ?>
                    </div>

                    <!-- Quantity & Actions -->
                    <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                        <!-- Quantity Selector -->
                        <div class="quantity-selector-custom">
                            <button class="qty-btn-custom" id="decreaseQty">-</button>
                            <input type="number" id="detailQuantity" value="1" min="1" readonly>
                            <button class="qty-btn-custom" id="increaseQty">+</button>
                        </div>

                        <!-- Buy Now Button -->
                        <button class="btn btn-danger" id="buyNowBtn">Buy Now</button>

                        <!-- Wishlist -->
                        <button class="btn btn-wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    <!-- Delivery Info -->
                    <div class="delivery-info-clean">
                        <!-- Free Delivery -->
                        <div class="d-flex align-items-start">
                            <i class="fas fa-truck"></i>
                            <div>
                                <strong>Free Delivery</strong>
                                <p class="delivery-text mb-0">
                                    Enter your postal code for Delivery Availability
                                </p>
                            </div>
                        </div>

                        <!-- Return Delivery -->
                        <div class="d-flex align-items-start">
                            <i class="fas fa-undo-alt"></i>
                            <div>
                                <strong>Return Delivery</strong>
                                <p class="delivery-text mb-0">
                                    Free 30 Days Delivery Returns.
                                    <a href="#">Details</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Items -->
        <div class="section-heading-red">
            <div class="red-block"></div>
            <h3>Related Item</h3>
        </div>

        <?php
                // Get related products
                $related_ids = wc_get_related_products($product_id, 4);
        if ($related_ids) :
            ?>
        <div class="row g-4">
            <?php foreach ($related_ids as $related_id) :
                            $related_product = wc_get_product($related_id);
                            $related_regular_price = $related_product->get_regular_price();
                            $related_sale_price = $related_product->get_sale_price();
                            $discount_percent = $related_sale_price ? round((($related_regular_price - $related_sale_price) / $related_regular_price) * 100) : 0;
                            ?>
            <div class="col-6 col-md-3">
                <div class="product-card">
                    <div class="product-img-box">
                        <?php if ($discount_percent > 0) : ?>
                        <span class="discount-badge">-<?php echo $discount_percent; ?>%</span>
                        <?php endif; ?>

                        <div class="action-icons">
                            <div class="action-icon wishlist-btn">
                                <i class="far fa-heart"></i>
                            </div>
                            <div class="action-icon quick-view-btn">
                                <i class="far fa-eye"></i>
                            </div>
                        </div>

                        <a href="<?php echo get_permalink($related_id); ?>" class="product-link">
                            <?php echo get_the_post_thumbnail($related_id, 'medium'); ?>
                        </a>

                        <a href="<?php echo $related_product->add_to_cart_url(); ?>" class="add-to-cart-btn">
                            Add To Cart
                        </a>
                    </div>

                    <div class="product-info mt-3">
                        <h6 class="product-name mb-2">
                            <a href="<?php echo get_permalink($related_id); ?>">
                                <?php echo get_the_title($related_id); ?>
                            </a>
                        </h6>

                        <div class="product-price mb-2">
                            <?php if ($related_sale_price) : ?>
                            <span
                                class="sale-price text-danger fw-bold"><?php echo number_format($related_sale_price, 0, ',', '.'); ?>đ</span>
                            <span
                                class="regular-price text-muted text-decoration-line-through ms-2"><?php echo number_format($related_regular_price, 0, ',', '.'); ?>đ</span>
                            <?php else : ?>
                            <span
                                class="price fw-bold"><?php echo number_format($related_regular_price, 0, ',', '.'); ?>đ</span>
                            <?php endif; ?>
                        </div>

                        <div class="product-rating d-flex align-items-center gap-2">
                            <?php
                                                $related_rating = $related_product->get_average_rating();
                            $related_count = $related_product->get_rating_count();
                            ?>
                            <div class="stars text-warning">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <i class="<?php echo $i <= $related_rating ? 'fas' : 'far'; ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-count text-muted">(<?php echo $related_count; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
jQuery(document).ready(function($) {
    // Thumbnail click to change main image
    $('.thumb').on('click', function() {
        const imageUrl = $(this).data('image');
        $('#mainProductImage').attr('src', imageUrl);
        $('.thumb').removeClass('active');
        $(this).addClass('active');
    });

    // Quantity controls
    $('#increaseQty').on('click', function() {
        let qty = parseInt($('#detailQuantity').val());
        $('#detailQuantity').val(qty + 1);
    });

    $('#decreaseQty').on('click', function() {
        let qty = parseInt($('#detailQuantity').val());
        if (qty > 1) {
            $('#detailQuantity').val(qty - 1);
        }
    });

    // Color selection
    $('.color-dot').on('click', function() {
        $('.color-dot').removeClass('active');
        $(this).addClass('active');
    });

    // Size selection
    $('.pill').on('click', function() {
        $('.pill').removeClass('active');
        $(this).addClass('active');
    });

    // Buy Now
    $('#buyNowBtn').on('click', function() {
        const qty = $('#detailQuantity').val();
        const productId = <?php echo $product_id; ?>;

        // Add to cart and redirect to checkout
        $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
            action: 'add_to_cart',
            product_id: productId,
            quantity: qty,
            nonce: '<?php echo wp_create_nonce('exclusive-nonce'); ?>'
        }, function(response) {
            if (response.success) {
                window.location.href = '<?php echo wc_get_checkout_url(); ?>';
            }
        });
    });

    // Wishlist toggle
    $('.btn-wishlist').on('click', function() {
        const icon = $(this).find('i');
        if (icon.hasClass('far')) {
            icon.removeClass('far').addClass('fas');
            $(this).css('color', '#DB4444');
        } else {
            icon.removeClass('fas').addClass('far');
            $(this).css('color', '');
        }
    });
});
</script>

<?php
    endwhile;
endif;

get_footer();
?>