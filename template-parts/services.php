<?php

/**
 * Services Section
 * Free Delivery, Customer Service, Money Back Guarantee
 */
?>

<section class="services-section py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <!-- Free Delivery -->
            <div class="col-md-4">
                <div class="service-item text-center">
                    <div class="service-icon-wrapper mx-auto mb-3">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h6 class="service-title mb-2">FREE AND FAST DELIVERY</h6>
                    <p class="service-text text-muted mb-0">Free delivery for all orders over $140</p>
                </div>
            </div>

            <!-- Customer Service -->
            <div class="col-md-4">
                <div class="service-item text-center">
                    <div class="service-icon-wrapper mx-auto mb-3">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h6 class="service-title mb-2">24/7 CUSTOMER SERVICE</h6>
                    <p class="service-text text-muted mb-0">Friendly 24/7 customer support</p>
                </div>
            </div>

            <!-- Money Back -->
            <div class="col-md-4">
                <div class="service-item text-center">
                    <div class="service-icon-wrapper mx-auto mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h6 class="service-title mb-2">MONEY BACK GUARANTEE</h6>
                    <p class="service-text text-muted mb-0">We return money within 30 days</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Services Section */
.services-section {
    background: #fff;
    padding: 80px 0;
}

.service-item {
    padding: 20px;
}

/* Service Icon Wrapper - Double Circle Design */
.service-icon-wrapper {
    width: 80px;
    height: 80px;
    background: #C1C0C1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.3s ease;
}

.service-icon-wrapper::before {
    content: '';
    position: absolute;
    width: 58px;
    height: 58px;
    background: #000;
    border-radius: 50%;
    z-index: 1;
}

.service-icon-wrapper i {
    font-size: 32px;
    color: white;
    position: relative;
    z-index: 2;
}

/* Hover Effect */
.service-item:hover .service-icon-wrapper {
    transform: scale(1.05);
}

.service-item:hover .service-icon-wrapper::before {
    background: rgba(0, 0, 0, 0.8);
}

/* Service Text */
.service-title {
    font-size: 20px;
    font-weight: 600;
    color: #000;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.service-text {
    font-size: 14px;
    color: #666 !important;
}

/* Responsive */
@media (max-width: 991px) {
    .services-section {
        padding: 60px 0;
    }

    .service-icon-wrapper {
        width: 70px;
        height: 70px;
    }

    .service-icon-wrapper::before {
        width: 50px;
        height: 50px;
    }

    .service-icon-wrapper i {
        font-size: 28px;
    }

    .service-title {
        font-size: 18px;
    }
}

@media (max-width: 767px) {
    .services-section {
        padding: 40px 0;
    }

    .service-item {
        margin-bottom: 24px;
    }

    .service-icon-wrapper {
        width: 60px;
        height: 60px;
    }

    .service-icon-wrapper::before {
        width: 44px;
        height: 44px;
    }

    .service-icon-wrapper i {
        font-size: 24px;
    }

    .service-title {
        font-size: 16px;
    }

    .service-text {
        font-size: 13px;
    }
}
</style>