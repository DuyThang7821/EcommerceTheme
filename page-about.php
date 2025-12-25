<?php

/**
 * Template Name: About Page
 *
 * @package Exclusive
 */

get_header();
?>

<main class="about-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Our Story Section -->
    <section class="our-story-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="story-content">
                        <h1 class="story-title mb-4">Our Story</h1>
                        <p class="story-text mb-4">
                            Launced in 2015, Exclusive is South Asia's premier online shopping makterplace with an
                            active presense in Bangladesh. Supported by wide range of tailored marketing, data and
                            service solutions, Exclusive has 10,500 sallers and 300 brands and serves 3 millions
                            customers across the region.
                        </p>
                        <p class="story-text mb-0">
                            Exclusive has more than 1 Million products to offer, growing at a very fast. Exclusive
                            offers a diverse assotment in categories ranging from consumer.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="story-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/imgAbout.jpg"
                            alt="Our Story" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Sellers -->
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h3 class="stat-number mb-2">10.5k</h3>
                        <p class="stat-label mb-0">Sallers active our site</p>
                    </div>
                </div>

                <!-- Monthly Sale -->
                <div class="col-6 col-md-3">
                    <div class="stat-card stat-card-highlight">
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="stat-number mb-2">33k</h3>
                        <p class="stat-label mb-0">Mopnthly Produduct Sale</p>
                    </div>
                </div>

                <!-- Customers -->
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h3 class="stat-number mb-2">45.5k</h3>
                        <p class="stat-label mb-0">Customer active in our site</p>
                    </div>
                </div>

                <!-- Annual Gross -->
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                        <h3 class="stat-number mb-2">25k</h3>
                        <p class="stat-label mb-0">Anual gross sale in our site</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <div class="team-slider-wrapper position-relative">
                <!-- Preload container - Hide until ready -->
                <div class="swiper teamSwiper team-swiper-ready">
                    <div class="swiper-wrapper">
                        <?php
                        // Query team members
                        $team_args = array(
                            'post_type' => 'team',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        );

$team_query = new WP_Query($team_args);

if ($team_query->have_posts()) :
    while ($team_query->have_posts()) : $team_query->the_post();
        $position = get_post_meta(get_the_ID(), '_team_position', true);
        $twitter = get_post_meta(get_the_ID(), '_team_twitter', true);
        $instagram = get_post_meta(get_the_ID(), '_team_instagram', true);
        $linkedin = get_post_meta(get_the_ID(), '_team_linkedin', true);
        ?>
                        <div class="swiper-slide">
                            <div class="team-card">
                                <div class="team-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('large'); ?>
                                    <?php else : ?>
                                    <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="team-info">
                                    <h4 class="team-name mb-1"><?php the_title(); ?></h4>
                                    <p class="team-position mb-3"><?php echo esc_html($position); ?></p>
                                    <div class="team-social">
                                        <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
    endwhile;
wp_reset_postdata();
else :
    // Default team members
    $default_team = array(
        array('name' => 'Tom Cruise', 'position' => 'Founder & Chairman', 'image' => 'member1.png'),
        array('name' => 'Emma Watson', 'position' => 'Managing Director', 'image' => 'member2.png'),
        array('name' => 'Will Smith', 'position' => 'Product Designer', 'image' => 'member3.png'),
    );

    foreach ($default_team as $member) :
        ?>
                        <div class="swiper-slide">
                            <div class="team-card">
                                <div class="team-image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/team/<?php echo $member['image']; ?>"
                                        alt="<?php echo $member['name']; ?>">
                                </div>
                                <div class="team-info">
                                    <h4 class="team-name mb-1"><?php echo $member['name']; ?></h4>
                                    <p class="team-position mb-3"><?php echo $member['position']; ?></p>
                                    <div class="team-social">
                                        <a href="#"><i class="fab fa-twitter"></i></a>
                                        <a href="#"><i class="fab fa-instagram"></i></a>
                                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;
endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination team-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Services Section (Reuse from home) -->
    <?php get_template_part('template-parts/services'); ?>
</main>

<style>
/* Breadcrumb */
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
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

.breadcrumb-item.active {
    color: #000;
}

.breadcrumb-item+.breadcrumb-item::before {
    content: "/";
    color: #666;
    padding: 0 8px;
}

/* Our Story Section */
.our-story-section {
    background: #fff;
}

.story-title {
    font-size: 54px;
    font-weight: 600;
    color: #000;
    line-height: 1.2;
}

.story-text {
    font-size: 16px;
    color: #000;
    line-height: 1.6;
}

.story-image img {
    width: 100%;
    height: auto;
    border-radius: 4px;
}

/* Statistics Section */
.statistics-section {
    background: #fff;
}

.stat-card {
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    background: #fff;
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-5px);
}

.stat-card-highlight {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: #fff;
}

.stat-icon {
    width: 80px;
    height: 80px;
    background: rgba(0, 0, 0, 0.05);
    border: 8px solid rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.stat-card-highlight .stat-icon {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.stat-icon i {
    font-size: 32px;
    color: #000;
}

.stat-card-highlight .stat-icon i {
    color: #fff;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #000;
}

.stat-card-highlight .stat-number {
    color: #fff;
}

.stat-label {
    font-size: 16px;
    color: #000;
}

.stat-card-highlight .stat-label {
    color: #fff;
}

/* Team Section */
.team-section {
    background: #fff;
}

.team-slider-wrapper {
    position: relative;
    min-height: 600px;
}

/* Hide Swiper until it's initialized */
.team-swiper-ready {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.team-swiper-ready.swiper-initialized {
    opacity: 1;
    visibility: visible;
}

.teamSwiper {
    padding-bottom: 50px;
}

.team-card {
    border-radius: 0;
    overflow: hidden;
    padding-bottom: 20px;
}

.team-image {
    width: 100%;
    height: 430px;
    overflow: hidden;
    background: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 20px;
}

.team-info {
    padding: 24px 20px 0;
}

.team-name {
    font-size: 32px;
    font-weight: 500;
    color: #000;
}

.team-position {
    font-size: 16px;
    color: #000;
}

.team-social {
    display: flex;
    gap: 16px;
}

.team-social a {
    color: #000;
    font-size: 20px;
    transition: all 0.3s ease;
}

.team-social a:hover {
    color: var(--primary-color);
    transform: translateY(-2px);
}

/* Team Pagination */
.team-pagination {
    position: relative !important;
    margin-top: 30px;
}

.team-pagination .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: #D9D9D9;
    opacity: 1;
}

.team-pagination .swiper-pagination-bullet-active {
    background: var(--primary-color);
    width: 16px;
    height: 16px;
    border: 2px solid var(--primary-color);
    box-shadow: 0 0 0 2px #fff;
}

/* Responsive */
@media (max-width: 991px) {
    .story-title {
        font-size: 40px;
    }

    .story-text {
        font-size: 15px;
    }

    .stat-number {
        font-size: 28px;
    }

    .stat-label {
        font-size: 14px;
    }

    .team-image {
        height: 350px;
    }

    .team-name {
        font-size: 24px;
    }

    .team-slider-wrapper {
        min-height: 500px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .story-title {
        font-size: 32px;
    }

    .story-text {
        font-size: 14px;
    }

    .stat-card {
        padding: 20px 15px;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-width: 6px;
    }

    .stat-icon i {
        font-size: 24px;
    }

    .stat-number {
        font-size: 24px;
    }

    .stat-label {
        font-size: 13px;
    }

    .team-image {
        height: 300px;
    }

    .team-name {
        font-size: 20px;
    }

    .team-position {
        font-size: 14px;
    }

    .team-social a {
        font-size: 18px;
    }

    .team-slider-wrapper {
        min-height: 400px;
    }

    /* Fix Swiper on mobile */
    .teamSwiper {
        overflow: visible;
    }

    .swiper-slide {
        display: flex;
    }
}
</style>

<script>
// Initialize Team Swiper IMMEDIATELY when DOM is ready
(function() {
    'use strict';

    function initTeamSwiper() {
        if (typeof Swiper === 'undefined') {
            // Retry if Swiper not loaded yet
            setTimeout(initTeamSwiper, 100);
            return;
        }

        const swiperElement = document.querySelector('.team-swiper-ready');

        if (!swiperElement) {
            return;
        }

        // Check if already initialized
        if (swiperElement.classList.contains('swiper-initialized')) {
            return;
        }

        // Initialize Swiper
        const swiper = new Swiper('.teamSwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.team-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
            // Critical settings to prevent layout shift
            autoHeight: false,
            watchSlidesProgress: true,
            speed: 300,
        });

        // Mark as initialized and show
        swiperElement.classList.add('swiper-initialized');
    }

    // Execute as soon as DOM is interactive
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTeamSwiper);
    } else {
        initTeamSwiper();
    }
})();
</script>

<?php get_footer(); ?>