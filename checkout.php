<?php

/**
 * Checkout Page Template
 *
 * @package Exclusive
 */

get_header();
?>

<main class="checkout-page">
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo wc_get_cart_url(); ?>">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Checkout Content -->
    <section class="checkout-content-section py-5">
        <div class="container">
            <?php if (WC()->cart->is_empty()) : ?>
            <div class="empty-checkout text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h3 class="mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">Add items to your cart before checkout.</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary-custom px-5 py-3">
                    Continue Shopping
                </a>
            </div>
            <?php else : ?>
            <?php wc_print_notices(); ?>

            <form name="checkout" method="post" class="checkout woocommerce-checkout"
                action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

                <div class="row g-5">
                    <!-- Billing Details -->
                    <div class="col-lg-7">
                        <div class="billing-details-section">
                            <h3 class="section-title mb-4">Billing Details</h3>

                            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                            <div class="checkout-form">
                                <?php
                                    $checkout = WC()->checkout();

                foreach ($checkout->get_checkout_fields('billing') as $key => $field) :
                    // Skip email if already displayed
                    if ($key === 'billing_email' && is_user_logged_in()) {
                        continue;
                    }

                    $field_html = '';
                    $field_class = isset($field['class']) ? implode(' ', $field['class']) : '';

                    // Add Bootstrap classes
                    if (strpos($field_class, 'form-row-first') !== false) {
                        $col_class = 'col-md-6';
                    } elseif (strpos($field_class, 'form-row-last') !== false) {
                        $col_class = 'col-md-6';
                    } else {
                        $col_class = 'col-12';
                    }
                    ?>
                                <div class="<?php echo $col_class; ?> form-group mb-3">
                                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-5">
                        <div class="order-summary-box">
                            <h4 class="summary-title mb-4">Your Order</h4>

                            <!-- Order Items -->
                            <div class="order-items mb-4">
                                <?php
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0) :
                            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                            ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <?php echo $_product->get_image('thumbnail'); ?>
                                    </div>
                                    <div class="item-details">
                                        <div class="item-name"><?php echo $product_name; ?></div>
                                        <div class="item-quantity">Qty: <?php echo $cart_item['quantity']; ?></div>
                                    </div>
                                    <div class="item-price">
                                        <?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
                                    </div>
                                </div>
                                <?php
                        endif;
                    endforeach;
?>
                            </div>

                            <!-- Order Totals -->
                            <div class="order-totals">
                                <div class="totals-row">
                                    <span>Subtotal:</span>
                                    <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                                </div>

                                <div class="totals-row">
                                    <span>Shipping:</span>
                                    <span>
                                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                        <?php wc_cart_totals_shipping_html(); ?>
                                        <?php else : ?>
                                        Free
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                <div class="totals-row">
                                    <span>Tax:</span>
                                    <span><?php echo WC()->cart->get_cart_tax(); ?></span>
                                </div>
                                <?php endif; ?>

                                <div class="totals-row total">
                                    <span>Total:</span>
                                    <span><?php echo WC()->cart->get_total(); ?></span>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="payment-methods mt-4">
                                <?php if (WC()->cart->needs_payment()) : ?>
                                <div class="payment-method-options">
                                    <?php
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
                                        WC()->payment_gateways()->set_current_gateway($available_gateways);

                                        if (!empty($available_gateways)) :
                                            foreach ($available_gateways as $gateway) :
                                                ?>
                                    <div class="payment-method">
                                        <input type="radio" id="payment_method_<?php echo esc_attr($gateway->id); ?>"
                                            name="payment_method" value="<?php echo esc_attr($gateway->id); ?>"
                                            <?php checked($gateway->chosen, true); ?>>
                                        <label for="payment_method_<?php echo esc_attr($gateway->id); ?>">
                                            <?php echo $gateway->get_title(); ?>
                                            <?php echo $gateway->get_icon(); ?>
                                        </label>
                                        <?php if ($gateway->has_fields() || $gateway->get_description()) : ?>
                                        <div class="payment-method-description">
                                            <?php $gateway->payment_fields(); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                            endforeach;
                                        else :
                                            ?>
                                    <p class="text-muted">No payment methods available.</p>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="terms-conditions mt-4">
                                <?php if (wc_get_page_id('terms') > 0 && apply_filters('woocommerce_checkout_show_terms', true)) : ?>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="terms" id="terms" required>
                                    <span>I have read and agree to the
                                        <a href="<?php echo esc_url(wc_get_page_permalink('terms')); ?>"
                                            target="_blank">
                                            terms and conditions
                                        </a>
                                    </span>
                                </label>
                                <?php endif; ?>
                            </div>

                            <!-- Place Order Button -->
                            <?php do_action('woocommerce_review_order_before_submit'); ?>

                            <button type="submit" class="btn btn-place-order w-100 mt-4"
                                name="woocommerce_checkout_place_order" id="place_order"
                                value="<?php esc_attr_e('Place order', 'woocommerce'); ?>">
                                <?php esc_html_e('Place Order', 'woocommerce'); ?>
                            </button>

                            <?php do_action('woocommerce_review_order_after_submit'); ?>

                            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                        </div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </section>
</main>

<style>
/* ===================================
   BREADCRUMB
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
}

.breadcrumb-item a:hover {
    color: #DB4444;
}

.breadcrumb-item.active {
    color: #000;
}

/* ===================================
   CHECKOUT CONTENT
==================================== */
.checkout-content-section {
    background: #fff;
}

/* Section Title */
.section-title {
    font-size: 28px;
    font-weight: 600;
    color: #000;
}

/* ===================================
   BILLING FORM
==================================== */
.billing-details-section {
    background: #fff;
}

.checkout-form {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.checkout-form .form-group {
    padding: 0;
}

.checkout-form label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #000;
}

.checkout-form label .required {
    color: #DB4444;
}

.checkout-form input[type="text"],
.checkout-form input[type="email"],
.checkout-form input[type="tel"],
.checkout-form select,
.checkout-form textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.checkout-form input:focus,
.checkout-form select:focus,
.checkout-form textarea:focus {
    outline: none;
    border-color: #DB4444;
    box-shadow: 0 0 0 3px rgba(219, 68, 68, 0.1);
}

/* ===================================
   ORDER SUMMARY
==================================== */
.order-summary-box {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 32px;
    position: sticky;
    top: 100px;
}

.summary-title {
    font-size: 24px;
    font-weight: 600;
    color: #000;
    margin-bottom: 24px;
}

/* Order Items */
.order-items {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 8px;
}

.order-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f0f0f0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 60px;
    height: 60px;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 4px;
    background: #f5f5f5;
    padding: 4px;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 14px;
    font-weight: 500;
    color: #000;
    margin-bottom: 4px;
}

.item-quantity {
    font-size: 13px;
    color: #666;
}

.item-price {
    font-size: 16px;
    font-weight: 600;
    color: #000;
}

/* Order Totals */
.order-totals {
    padding-top: 16px;
    border-top: 2px solid #e5e5e5;
}

.totals-row {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    font-size: 16px;
}

.totals-row.total {
    font-size: 18px;
    font-weight: 600;
    padding-top: 16px;
    border-top: 2px solid #000;
    margin-top: 8px;
}

/* Payment Methods */
.payment-method {
    margin-bottom: 16px;
    padding: 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: #DB4444;
    background: rgba(219, 68, 68, 0.05);
}

.payment-method input[type="radio"] {
    margin-right: 12px;
}

.payment-method label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 15px;
    font-weight: 500;
    margin: 0;
}

.payment-method-description {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    font-size: 14px;
    color: #666;
}

/* Terms & Conditions */
.terms-conditions {
    padding: 16px;
    background: #f8f9fa;
    border-radius: 4px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    font-size: 14px;
    margin: 0;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
    margin-top: 3px;
    flex-shrink: 0;
}

.checkbox-label a {
    color: #DB4444;
    text-decoration: underline;
}

/* Place Order Button */
.btn-place-order {
    background: #DB4444;
    color: #fff;
    border: none;
    padding: 16px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-place-order:hover {
    background: #C13333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.3);
}

/* ===================================
   WOOCOMMERCE OVERRIDES
==================================== */
.woocommerce-error,
.woocommerce-info,
.woocommerce-message {
    padding: 16px 20px;
    margin-bottom: 24px;
    border-left: 4px solid;
    border-radius: 4px;
}

.woocommerce-error {
    background: #f8d7da;
    border-color: #DC3545;
    color: #721c24;
}

.woocommerce-info {
    background: #d1ecf1;
    border-color: #0dcaf0;
    color: #0c5460;
}

.woocommerce-message {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .order-summary-box {
        position: static;
        margin-top: 40px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .section-title {
        font-size: 24px;
    }

    .order-summary-box {
        padding: 24px;
    }

    .summary-title {
        font-size: 20px;
    }

    .checkout-form .col-md-6 {
        width: 100%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Form validation
    $('#place_order').on('click', function(e) {
        const form = $(this).closest('form');
        let isValid = true;

        // Check required fields
        form.find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });

        // Check terms if exists
        if ($('#terms').length && !$('#terms').is(':checked')) {
            alert('Please accept the terms and conditions');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.error').first().offset().top - 100
            }, 500);
        }
    });

    // Remove error class on input
    $('input, select, textarea').on('change input', function() {
        $(this).removeClass('error');
    });
});
</script>

<?php get_footer(); ?>