<?php

?>

<section class="new-arrival-section py-5">
    <div class="container">
        <!-- Section Header -->
        <div class="section-tag-group mb-5">
            <div class="section-tag">
                Featured
            </div>
            <h2 class="section-title">New Arrival</h2>
        </div>

        <!-- Bento Grid -->
        <div class="row g-4" style="height: 600px;">
            <!-- PlayStation 5 - Large Left -->
            <div class="col-lg-6" style="height: 100%;">
                <div class="bento-item" style="background: #000;">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/img2.png"
                        alt="PlayStation 5">
                </div>
            </div>

            <!-- Right Side - 2 Rows -->
            <div class="col-lg-6" style="height: 100%;">
                <div class="row g-4" style="height: 100%;">
                    <!-- Women's Collections - Top Right -->
                    <div class="col-12" style="height: 48%;">
                        <div class="bento-item" style="background: #0D0D0D;">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/img4.png"
                                alt="Women's Collections">
                        </div>
                    </div>

                    <!-- Bottom Row - 2 Items -->
                    <div class="col-6" style="height: 48%;">
                        <!-- Speakers -->
                        <div class="bento-item" style="background: #1A1A1A; height: 100%;">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/img3.jpg"
                                alt="Speakers">
                        </div>
                    </div>

                    <div class="col-6" style="height: 48%;">
                        <!-- Perfume -->
                        <div class="bento-item" style="background: #1A1A1A; height: 100%;">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/img1.jpg"
                                alt="Perfume">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.new-arrival-section {
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

/* Bento Grid Items */
.bento-item {
    background: #000;
    color: #fff;
    height: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 4px;
    display: flex;
    align-items: flex-end;
    padding: 0;
}

.bento-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    transition: all 0.5s ease;
    opacity: 0.9;
}

.bento-item:hover img {
    transform: scale(1.05);
    opacity: 0.6;
}

.bento-content {
    position: relative;
    z-index: 2;
    padding: 32px;
    width: 100%;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 100%);
}

.bento-link {
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-block;
}

.bento-link:hover {
    color: var(--primary-color) !important;
    transform: translateX(5px);
}

.bento-link i {
    transition: all 0.3s ease;
}

.bento-link:hover i {
    transform: translateX(5px);
}

/* Responsive */
@media (max-width: 991px) {
    .new-arrival-section .row[style*="height: 600px"] {
        height: auto !important;
    }

    .new-arrival-section .col-lg-6[style*="height: 100%"],
    .new-arrival-section .col-lg-6[style*="height: 100%"] .row[style*="height: 100%"] {
        height: auto !important;
    }

    .bento-item {
        min-height: 250px;
        margin-bottom: 15px;
    }

    .section-title {
        font-size: 28px;
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

    .bento-item {
        min-height: 200px;
    }

    .bento-content {
        padding: 24px;
    }

    .bento-content h3 {
        font-size: 20px !important;
    }

    .bento-content h4 {
        font-size: 18px !important;
    }

    .bento-content p {
        font-size: 13px !important;
    }

    /* Stack bottom items vertically on mobile */
    .new-arrival-section .col-6[style*="height: 48%"] {
        height: auto !important;
    }
}
</style>