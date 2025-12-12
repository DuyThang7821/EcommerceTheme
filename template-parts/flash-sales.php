<?php

/**
 * Flash Sales Section
 * Hiển thị sản phẩm đang sale với countdown timer
 */

// Query sản phẩm đang sale
$flash_sale_args = array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => '_sale_price',
            'value' => '',
            'compare' => '!='
        )
    ),
    'orderby' => 'date',
    'order' => 'DESC'
);

$flash_sale_query = new WP_Query($flash_sale_args);

// Tính thời gian kết thúc flash sale (ví dụ: kết thúc vào cuối ngày hôm nay)
$end_time = strtotime('today 23:59:59') * 1000; // Convert to milliseconds for JavaScript
?>

<section class="flash-sales-section py-5">
    <div class="container">
        <!-- Section Header with Timer -->
        <div class="d-flex align-items-end justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-end gap-5 flex-wrap">
                <!-- Section Tag -->
                <div class="section-tag-group">
                    <div class="section-tag">
                        Today's
                    </div>
                    <h2 class="section-title">Flash Sales</h2>
                </div>

                <!-- Countdown Timer -->
                <div class="countdown-timer d-flex align-items-center gap-3">
                    <div class="timer-block text-center">
                        <div class="timer-label">Days</div>
                        <div class="timer-value h3 mb-0" id="flash-days">03</div>
                    </div>
                    <span class="timer-separator">:</span>
                    <div class="timer-block text-center">
                        <div class="timer-label">Hours</div>
                        <div class="timer-value h3 mb-0" id="flash-hours">23</div>
                    </div>
                    <span class="timer-separator">:</span>
                    <div class="timer-block text-center">
                        <div class="timer-label">Minutes</div>
                        <div class="timer-value h3 mb-0" id="flash-minutes">19</div>
                    </div>
                    <span class="timer-separator">:</span>
                    <div class="timer-block text-center">
                        <div class="timer-label">Seconds</div>
                        <div class="timer-value h3 mb-0" id="flash-seconds">56</div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <div class="flash-nav-arrows d-flex gap-2">
                <button class="nav-arrow flash-prev" aria-label="Previous">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="nav-arrow flash-next" aria-label="Next">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Products Slider -->
        <?php if ($flash_sale_query->have_posts()) : ?>
        <div class="flash-products-wrapper position-relative">
            <!-- Loading Spinner -->
            <div class="flash-loading"
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div class="swiper flashSwiper" style="visibility: hidden; opacity: 0; transition: opacity 0.3s ease;">
                <div class="swiper-wrapper">
                    <?php while ($flash_sale_query->have_posts()) : $flash_sale_query->the_post();
                            global $product;
                            $product_id = get_the_ID();
                            $regular_price = $product->get_regular_price();
                            $sale_price = $product->get_sale_price();
                            $discount_percent = $sale_price ? round((($regular_price - $sale_price) / $regular_price) * 100) : 0;
                            ?>
                    <div class="swiper-slide">
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
                                    <div class="action-icon quick-view-btn"
                                        data-product-id="<?php echo $product_id; ?>">
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
                                        class="price"><?php echo number_format($regular_price, 0, ',', '.'); ?>$</span>
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
            </div>
        </div>

        <!-- View All Products Button -->
        <div class="text-center mt-5">
            <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary-custom px-5 py-3">
                View All Products
            </a>
        </div>
        <?php else : ?>
        <p class="text-center"><?php _e('No flash sale products found.', 'exclusive'); ?></p>
        <?php endif; ?>
    </div>
</section>

<style>
/* Flash Sales Section */
.flash-sales-section {
    background: #fff;
}

/* Section Tag */
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

/* Countdown Timer */
.countdown-timer {
    display: flex;
    align-items: center;
    gap: 16px;
}

.timer-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.timer-label {
    font-size: 12px;
    font-weight: 500;
    color: #000;
    text-transform: capitalize;
}

.timer-value {
    font-size: 32px;
    font-weight: 700;
    color: #000;
    line-height: 1;
    min-width: 50px;
}

.timer-separator {
    color: #E07575;
    font-size: 32px;
    font-weight: 700;
    margin-top: 20px;
}

/* Flash Products */
.flash-products-wrapper {
    position: relative;
    overflow: hidden;
    min-height: 400px;
}

/* Loading state */
.flashSwiper {
    overflow: visible;
    padding: 10px 0;
}

.flashSwiper[style*="visibility: hidden"] {
    min-height: 400px;
}

/* Prevent layout shift during load */
.flashSwiper .swiper-wrapper {
    display: flex;
}

.flashSwiper .swiper-slide {
    height: auto;
    width: auto;
    flex-shrink: 0;
}

/* Product Card - Fix Image Centering */
.product-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.product-img-box .product-link img {
    max-width: 90%;
    max-height: 180px;
    width: auto;
    height: auto;
    object-fit: contain;
    margin: auto;
}

/* Navigation Arrows */
.flash-nav-arrows .nav-arrow {
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

.flash-nav-arrows .nav-arrow:hover {
    background: var(--primary-color);
    color: #fff;
    transform: scale(1.05);
}

.flash-nav-arrows .nav-arrow:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* Product Name */
.product-name a {
    color: #000;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    display: block;
}

.product-name a:hover {
    color: var(--primary-color);
}

/* Responsive */
@media (max-width: 991px) {
    .countdown-timer {
        gap: 12px;
    }

    .timer-value {
        font-size: 24px;
        min-width: 40px;
    }

    .timer-separator {
        font-size: 24px;
    }

    .section-title {
        font-size: 28px;
    }
}

@media (max-width: 767px) {
    .d-flex.align-items-end.gap-5 {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 1rem !important;
    }

    .countdown-timer {
        gap: 8px;
    }

    .timer-label {
        font-size: 10px;
    }

    .timer-value {
        font-size: 20px;
        min-width: 35px;
    }

    .timer-separator {
        font-size: 20px;
        margin-top: 14px;
    }

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

    .flash-nav-arrows {
        margin-top: 16px;
    }

    .flash-nav-arrows .nav-arrow {
        width: 40px;
        height: 40px;
    }

    .product-img-box .product-link img {
        max-height: 150px;
    }
}
</style>

<script>
// Countdown Timer Function
document.addEventListener('DOMContentLoaded', function() {
    const endTime = <?php echo $end_time; ?>; // Milliseconds

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById('flash-days').textContent = '00';
            document.getElementById('flash-hours').textContent = '00';
            document.getElementById('flash-minutes').textContent = '00';
            document.getElementById('flash-seconds').textContent = '00';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('flash-days').textContent = String(days).padStart(2, '0');
        document.getElementById('flash-hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('flash-minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('flash-seconds').textContent = String(seconds).padStart(2, '0');
    }

    // Update immediately and then every second
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Initialize Swiper - FIX: Scroll by 1 product at a time
    if (typeof Swiper !== 'undefined') {
        const flashSwiper = new Swiper('.flashSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            slidesPerGroup: 1, // Scroll 1 product at a time
            navigation: {
                nextEl: '.flash-next',
                prevEl: '.flash-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    slidesPerGroup: 1,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
            },
            on: {
                init: function() {
                    // Hide loading spinner
                    const loadingEl = document.querySelector('.flash-loading');
                    if (loadingEl) {
                        loadingEl.style.display = 'none';
                    }

                    // Show slider after initialization
                    document.querySelector('.flashSwiper').style.visibility = 'visible';
                    document.querySelector('.flashSwiper').style.opacity = '1';
                }
            }
        });
    }

    // Wishlist toggle
    jQuery(document).on('click', '.wishlist-btn', function() {
        const btn = jQuery(this);
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