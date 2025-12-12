<?php

/**
 * Music Banner Section
 * Featured product banner with countdown
 */

// Static text
$banner_title = "Enhance Your Music Experience";
$banner_category = "Categories";

// Set countdown end time (example: 47 hours from now)
$end_time = strtotime('+47 hours') * 1000; // Convert to milliseconds


/* ============================
   Lấy 1 sản phẩm trong category Speaker
=============================== */

$product_args = array(
    'post_type'      => 'product',
    'posts_per_page' => 1,
    'orderby'        => 'rand',
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => 'speaker', // đổi nếu slug khác
        ),
    ),
);

$product_query = new WP_Query($product_args);

$product_img = '';

if ($product_query->have_posts()) {
    while ($product_query->have_posts()) {
        $product_query->the_post();
        $product = wc_get_product(get_the_ID());

        // Lấy featured image
        $product_img = wp_get_attachment_image_url($product->get_image_id(), 'large');
    }
}
wp_reset_postdata();

// Fallback nếu không tìm thấy sản phẩm
if (!$product_img) {
    $product_img = get_template_directory_uri() . '/images/jbl-speaker.png';
}

?>

<section class="music-banner-section py-5">
    <div class="container">
        <div class="music-banner">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content">
                        <p class="banner-category text-success fw-semibold mb-3">
                            <?php echo esc_html($banner_category); ?></p>
                        <h1 class="banner-title text-white mb-4"><?php echo esc_html($banner_title); ?></h1>

                        <!-- Countdown Timer -->
                        <div class="music-countdown d-flex gap-3 mb-4">
                            <div class="timer-circle">
                                <span id="music-hours">23</span>
                                <div>Hours</div>
                            </div>
                            <div class="timer-circle">
                                <span id="music-days">05</span>
                                <div>Days</div>
                            </div>
                            <div class="timer-circle">
                                <span id="music-minutes">59</span>
                                <div>Minutes</div>
                            </div>
                            <div class="timer-circle">
                                <span id="music-seconds">35</span>
                                <div>Seconds</div>
                            </div>
                        </div>

                        <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-buy-now">
                            Buy Now!
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="banner-image text-center">

                        <!-- Ảnh sản phẩm động -->
                        <img src="<?php echo esc_url($product_img); ?>" alt="Music Product" class="img-fluid">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Music Banner Section */
.music-banner-section {
    background: #fff;
}

.music-banner {
    background: #000;
    background-image: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
    border-radius: 4px;
    padding: 60px;
    position: relative;
    overflow: hidden;
}

.music-banner::before {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(217, 217, 217, 0.3) 0%, transparent 70%);
    border-radius: 50%;
    top: 50%;
    left: 30%;
    transform: translate(-50%, -50%);
    filter: blur(100px);
    opacity: 0.5;
}

.banner-content {
    position: relative;
    z-index: 2;
}

.banner-category {
    color: #00FF66 !important;
    font-size: 16px;
    letter-spacing: 0.5px;
}

.banner-title {
    font-size: 48px;
    font-weight: 600;
    line-height: 1.2;
    letter-spacing: 1px;
}

/* Music Countdown */
.music-countdown {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
}

.timer-circle {
    background: #fff;
    color: #000;
    width: 62px;
    height: 62px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 600;
}

.timer-circle span {
    font-size: 16px;
    font-weight: 700;
    line-height: 1;
}

/* Buy Now Button */
.btn-buy-now {
    background: #00FF66;
    color: #fff;
    border: none;
    padding: 16px 48px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn-buy-now:hover {
    background: #00CC52;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 255, 102, 0.4);
}

/* Banner Image */
.banner-image {
    position: relative;
    z-index: 2;
}

.banner-image img {
    max-width: 100%;
    height: auto;
    filter: drop-shadow(0 20px 40px rgba(255, 255, 255, 0.1));
}

/* Responsive */
@media (max-width: 991px) {
    .music-banner {
        padding: 40px 30px;
    }

    .banner-title {
        font-size: 36px;
    }

    .timer-circle {
        width: 56px;
        height: 56px;
    }

    .timer-circle span {
        font-size: 14px;
    }

    .banner-image {
        margin-top: 30px;
    }
}

@media (max-width: 767px) {
    .music-banner {
        padding: 30px 20px;
        text-align: center;
    }

    .banner-category {
        font-size: 14px;
    }

    .banner-title {
        font-size: 28px;
        margin-bottom: 24px !important;
    }

    .music-countdown {
        justify-content: center;
        gap: 16px;
    }

    .timer-circle {
        width: 52px;
        height: 52px;
        font-size: 10px;
    }

    .timer-circle span {
        font-size: 14px;
    }

    .btn-buy-now {
        padding: 12px 36px;
        font-size: 14px;
    }

    .banner-image {
        margin-top: 24px;
    }

    .banner-image img {
        max-width: 80%;
    }
}

@media (max-width: 480px) {
    .music-banner {
        padding: 24px 16px;
    }

    .banner-title {
        font-size: 24px;
    }

    .music-countdown {
        gap: 12px;
    }

    .timer-circle {
        width: 48px;
        height: 48px;
        font-size: 9px;
    }

    .timer-circle span {
        font-size: 13px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const endTime = <?php echo $end_time; ?>; // Milliseconds

    function updateMusicCountdown() {
        const now = new Date().getgetTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById('music-hours').textContent = '00';
            document.getElementById('music-days').textContent = '00';
            document.getElementById('music-minutes').textContent = '00';
            document.getElementById('music-seconds').textContent = '00';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('music-hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('music-days').textContent = String(days).padStart(2, '0');
        document.getElementById('music-minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('music-seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateMusicCountdown();
    setInterval(updateMusicCountdown, 1000);
});
</script>