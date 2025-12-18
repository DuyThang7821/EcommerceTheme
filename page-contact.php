<?php

/**
 * Template Name: Contact Page
 *
 * @package Exclusive
 */

get_header();
?>

<main class="contact-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Contact Content -->
    <section class="contact-content-section py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Contact Info -->
                <div class="col-lg-4">
                    <div class="contact-info-wrapper">
                        <!-- Call To Us -->
                        <div class="contact-info-box mb-4">
                            <div class="info-header mb-3">
                                <div class="info-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <h5 class="info-title mb-0">Call To Us</h5>
                            </div>
                            <div class="info-content">
                                <p class="info-text mb-3">We are available 24/7, 7 days a week.</p>
                                <p class="info-contact mb-0">
                                    <strong>Phone:</strong>
                                    <a href="tel:+88016111222222">+880161112222</a>
                                </p>
                            </div>
                        </div>

                        <hr class="divider">

                        <!-- Write To Us -->
                        <div class="contact-info-box mt-4">
                            <div class="info-header mb-3">
                                <div class="info-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h5 class="info-title mb-0">Write To US</h5>
                            </div>
                            <div class="info-content">
                                <p class="info-text mb-3">Fill out our form and we will contact you within 24 hours.</p>
                                <p class="info-contact mb-2">
                                    <strong>Emails:</strong>
                                    <a href="mailto:customer@exclusive.com">customer@exclusive.com</a>
                                </p>
                                <p class="info-contact mb-0">
                                    <strong>Emails:</strong>
                                    <a href="mailto:support@exclusive.com">support@exclusive.com</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form-wrapper">
                        <?php
                        // Display success message if form submitted
                        if (isset($_GET['success']) && $_GET['success'] == '1') :
                            ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            Thank you! Your message has been sent successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <form id="contact-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>">
                            <input type="hidden" name="action" value="submit_contact_form">
                            <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="contact_name"
                                        placeholder="Your Name *" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="email" class="form-control" name="contact_email"
                                        placeholder="Your Email *" required>
                                </div>
                                <div class="col-md-4">
                                    <input type="tel" class="form-control" name="contact_phone"
                                        placeholder="Your Phone *" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <textarea class="form-control" name="contact_message" rows="8"
                                    placeholder="Your Message" required></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary-custom px-5 py-3">
                                    <span class="btn-text">Send Message</span>
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
/* Breadcrumb Section */
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
    color: var(--primary-color);
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

/* Contact Content */
.contact-content-section {
    background: #fff;
}

/* Contact Info Wrapper */
.contact-info-wrapper {
    background: #fff;
    padding: 40px 35px;
    border-radius: 0;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
    height: 100%;
}

.contact-info-box {
    padding: 0;
    border: none !important;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 16px;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
}

.info-title {
    font-size: 16px;
    font-weight: 500;
    color: #000;
    margin: 0;
}

.info-content {
    margin-left: 56px;
}

.info-text {
    font-size: 14px;
    color: #000;
    line-height: 1.6;
}

.info-contact {
    font-size: 14px;
    color: #000;
    line-height: 1.8;
}

.info-contact strong {
    font-weight: 400;
}

.info-contact a {
    color: #000;
    text-decoration: none;
    transition: color 0.3s ease;
}

.info-contact a:hover {
    color: var(--primary-color);
}

.divider {
    border-color: rgba(0, 0, 0, 0.1);
    opacity: 1;
    margin: 32px 0;
}

/* Contact Form Wrapper */
.contact-form-wrapper {
    background: #fff;
    padding: 40px;
    border-radius: 0;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
}

.contact-form-wrapper .form-control {
    height: 50px;
    border: none;
    background: #F5F5F5;
    border-radius: 4px;
    padding: 0 16px;
    font-size: 14px;
    color: #000;
    transition: all 0.3s ease;
}

.contact-form-wrapper .form-control:focus {
    background: #fff;
    border: 1px solid var(--primary-color);
    box-shadow: 0 0 0 3px rgba(219, 68, 68, 0.1);
    outline: none;
}

.contact-form-wrapper textarea.form-control {
    height: auto;
    padding: 16px;
    resize: vertical;
}

.contact-form-wrapper .form-control::placeholder {
    color: rgba(0, 0, 0, 0.4);
}

/* Alert Success */
.alert-success {
    background: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
    border-radius: 4px;
    margin-bottom: 24px;
}

/* Responsive */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .contact-info-wrapper {
        padding: 30px 25px;
        margin-bottom: 30px;
    }

    .contact-form-wrapper {
        padding: 30px 25px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .breadcrumb-item {
        font-size: 13px;
    }

    .contact-info-wrapper {
        padding: 24px 20px;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .info-content {
        margin-left: 52px;
    }

    .info-title {
        font-size: 15px;
    }

    .info-text,
    .info-contact {
        font-size: 13px;
    }

    .divider {
        margin: 24px 0;
    }

    .contact-form-wrapper {
        padding: 24px 20px;
    }

    .contact-form-wrapper .form-control {
        height: 45px;
        font-size: 13px;
    }

    .btn-primary-custom {
        padding: 12px 32px !important;
        font-size: 14px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const btn = form.find('button[type="submit"]');
        const btnText = btn.find('.btn-text');
        const spinner = btn.find('.spinner-border');

        // Disable button and show loading
        btn.prop('disabled', true);
        btnText.text('Sending...');
        spinner.removeClass('d-none');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Show success message
                    form.before(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            ${response.data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                    // Reset form
                    form[0].reset();

                    // Scroll to success message
                    $('html, body').animate({
                        scrollTop: $('.alert-success').offset().top - 100
                    }, 500);
                } else {
                    alert(response.data.message ||
                        'Error sending message. Please try again.');
                }
            },
            error: function() {
                alert('Error sending message. Please try again.');
            },
            complete: function() {
                // Re-enable button
                btn.prop('disabled', false);
                btnText.text('Send Message');
                spinner.addClass('d-none');
            }
        });
    });
});
</script>

<?php get_footer(); ?>