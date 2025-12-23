<?php

/**
 * Checkout Form Template
 * Custom design for WooCommerce checkout
 *
 * @package Exclusive
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<div class="custom-checkout-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo wc_get_cart_url(); ?>">Cart</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
            action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="row g-5">
                <!-- Billing Details -->
                <div class="col-lg-7">
                    <div class="billing-details-wrapper">
                        <h3 class="checkout-section-title mb-4">Billing Details</h3>

                        <?php if ($checkout->get_checkout_fields()) : ?>

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="checkout-fields">
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="col-lg-5">
                    <div class="order-review-wrapper">
                        <h3 id="order_review_heading" class="checkout-section-title mb-4">
                            <?php esc_html_e('Your order', 'woocommerce'); ?>
                        </h3>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">

                            <!-- Order Items -->
                            <div class="order-items-list">
                                <?php
                                do_action('woocommerce_review_order_before_cart_contents');

foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
        ?>
                                <div class="order-item">
                                    <div class="item-info">
                                        <div class="item-thumbnail">
                                            <?php echo $_product->get_image(array(54, 54)); ?>
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name">
                                                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
                                            </div>
                                            <div class="item-quantity">
                                                × <?php echo $cart_item['quantity']; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item-total">
                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                    </div>
                                </div>
                                <?php
    }
}

do_action('woocommerce_review_order_after_cart_contents');
?>
                            </div>

                            <!-- Order Totals -->
                            <div class="order-totals">
                                <div class="totals-row">
                                    <span>Subtotal:</span>
                                    <span><?php wc_cart_totals_subtotal_html(); ?></span>
                                </div>

                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                <div class="totals-row coupon-row">
                                    <span>Coupon: <?php echo esc_html($code); ?></span>
                                    <span><?php wc_cart_totals_coupon_html($coupon); ?></span>
                                </div>
                                <?php endforeach; ?>

                                <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                <?php do_action('woocommerce_review_order_before_shipping'); ?>
                                <div class="totals-row">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                <?php do_action('woocommerce_review_order_after_shipping'); ?>
                                <?php endif; ?>

                                <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                <div class="totals-row">
                                    <span><?php echo esc_html($fee->name); ?></span>
                                    <span><?php wc_cart_totals_fee_html($fee); ?></span>
                                </div>
                                <?php endforeach; ?>

                                <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                <div class="totals-row">
                                    <span><?php echo esc_html($tax->label); ?></span>
                                    <span><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                                </div>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <div class="totals-row">
                                    <span><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
                                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php endif; ?>

                                <div class="totals-row total-row">
                                    <span>Total:</span>
                                    <span><?php wc_cart_totals_order_total_html(); ?></span>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="payment-methods-wrapper">
                                <?php do_action('woocommerce_review_order_before_payment'); ?>

                                <div id="payment" class="woocommerce-checkout-payment">
                                    <?php if (WC()->cart->needs_payment()) : ?>
                                    <ul class="wc_payment_methods payment_methods methods">
                                        <?php
            if (!empty($available_gateways = WC()->payment_gateways()->get_available_payment_gateways())) {
                foreach ($available_gateways as $gateway) {
                    wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                }
            } else {
                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>';
            }
?>
                                    </ul>
                                    <?php endif; ?>

                                    <div class="form-row place-order">
                                        <noscript>
                                            <?php esc_html_e('Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'); ?>
                                            <br /><button type="submit" class="button alt"
                                                name="woocommerce_checkout_update_totals"
                                                value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
                                        </noscript>

                                        <?php wc_get_template('checkout/terms.php'); ?>

                                        <?php do_action('woocommerce_review_order_before_submit'); ?>

                                        <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="btn btn-primary-custom w-100 place-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); ?>

                                        <?php do_action('woocommerce_review_order_after_submit'); ?>

                                        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
                                    </div>
                                </div>

                                <?php do_action('woocommerce_review_order_after_payment'); ?>
                            </div>
                        </div>

                        <?php do_action('woocommerce_checkout_after_order_review'); ?>
                    </div>
                </div>
            </div>
        </form>

        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
    </div>
</div>

<style>
/* ===================================
   CHECKOUT PAGE STYLES
==================================== */
.custom-checkout-page {
    background: #fff;
}

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

/* ===================================
   SECTION TITLES
==================================== */
.checkout-section-title {
    font-size: 36px;
    font-weight: 500;
    color: #000;
    margin-bottom: 48px;
}

/* ===================================
   BILLING DETAILS
==================================== */
.billing-details-wrapper {
    background: #fff;
}

/* Form Fields */
.woocommerce-billing-fields .form-row {
    margin-bottom: 32px;
}

.woocommerce-billing-fields label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: rgba(0, 0, 0, 0.6);
}

.woocommerce-billing-fields .required {
    color: var(--primary-color);
}

.woocommerce-billing-fields input[type="text"],
.woocommerce-billing-fields input[type="email"],
.woocommerce-billing-fields input[type="tel"],
.woocommerce-billing-fields textarea,
.woocommerce-billing-fields select {
    width: 100%;
    height: 50px;
    padding: 0 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    font-size: 16px;
    background: #f5f5f5;
    transition: all 0.3s ease;
}

.woocommerce-billing-fields textarea {
    height: 100px;
    padding: 16px;
    resize: vertical;
}

.woocommerce-billing-fields input:focus,
.woocommerce-billing-fields textarea:focus,
.woocommerce-billing-fields select:focus {
    outline: none;
    border-color: var(--primary-color);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(219, 68, 68, 0.1);
}

.woocommerce-billing-fields .form-row-first,
.woocommerce-billing-fields .form-row-last {
    width: 48%;
    float: left;
}

.woocommerce-billing-fields .form-row-first {
    margin-right: 4%;
}

.woocommerce-billing-fields .form-row-wide {
    clear: both;
}

/* ===================================
   ORDER REVIEW
==================================== */
.order-review-wrapper {
    position: sticky;
    top: 100px;
}

/* Order Items List */
.order-items-list {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 24px;
    margin-bottom: 24px;
    max-height: 400px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #e5e5e5;
}

.order-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.order-item:first-child {
    padding-top: 0;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1;
}

.item-thumbnail {
    width: 54px;
    height: 54px;
    flex-shrink: 0;
}

.item-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 8px;
    background: #f5f5f5;
    border-radius: 4px;
}

.item-details {
    flex: 1;
}

.item-name {
    font-size: 16px;
    font-weight: 400;
    color: #000;
    margin-bottom: 4px;
}

.item-quantity {
    font-size: 14px;
    color: #666;
}

.item-total {
    font-size: 16px;
    font-weight: 500;
    color: #000;
    margin-left: 16px;
}

/* Order Totals */
.order-totals {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 24px;
    margin-bottom: 24px;
}

.totals-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    font-size: 16px;
    border-bottom: 1px solid #e5e5e5;
}

.totals-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.totals-row:first-child {
    padding-top: 0;
}

.total-row {
    font-weight: 600;
    font-size: 16px;
}

/* ===================================
   PAYMENT METHODS
==================================== */
.payment-methods-wrapper {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 24px;
}

.wc_payment_methods {
    list-style: none;
    padding: 0;
    margin: 0 0 24px 0;
}

.wc_payment_method {
    margin-bottom: 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    overflow: hidden;
}

.wc_payment_method label {
    display: flex;
    align-items: center;
    padding: 16px;
    cursor: pointer;
    margin: 0;
    transition: all 0.3s ease;
}

.wc_payment_method label:hover {
    background: #f5f5f5;
}

.wc_payment_method input[type="radio"] {
    margin-right: 12px;
}

.wc_payment_method .payment_box {
    padding: 16px;
    background: #f5f5f5;
    border-top: 1px solid #e5e5e5;
    display: none;
}

.wc_payment_method.checked .payment_box {
    display: block;
}

/* Place Order Button */
.place-order-btn {
    height: 56px;
    font-size: 16px;
    font-weight: 500;
    border: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.place-order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.4);
}

/* Terms & Conditions */
.woocommerce-terms-and-conditions-wrapper {
    margin-bottom: 24px;
}

.woocommerce-terms-and-conditions-checkbox-text {
    font-size: 14px;
    color: #666;
}

.woocommerce-terms-and-conditions-checkbox-text a {
    color: var(--primary-color);
    text-decoration: underline;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .checkout-section-title {
        font-size: 28px;
        margin-bottom: 32px;
    }

    .order-review-wrapper {
        position: static;
        margin-top: 40px;
    }

    .woocommerce-billing-fields .form-row-first,
    .woocommerce-billing-fields .form-row-last {
        width: 100%;
        float: none;
        margin-right: 0;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .checkout-section-title {
        font-size: 24px;
        margin-bottom: 24px;
    }

    .woocommerce-billing-fields .form-row {
        margin-bottom: 24px;
    }

    .woocommerce-billing-fields input[type="text"],
    .woocommerce-billing-fields input[type="email"],
    .woocommerce-billing-fields input[type="tel"],
    .woocommerce-billing-fields select {
        height: 44px;
        font-size: 14px;
    }

    .order-items-list {
        padding: 16px;
    }

    .order-item {
        padding: 12px 0;
    }

    .item-thumbnail {
        width: 40px;
        height: 40px;
    }

    .item-name {
        font-size: 14px;
    }

    .item-quantity,
    .item-total {
        font-size: 13px;
    }

    .order-totals,
    .payment-methods-wrapper {
        padding: 16px;
    }

    .totals-row {
        font-size: 14px;
        padding: 12px 0;
    }

    .place-order-btn {
        height: 48px;
        font-size: 14px;
    }
}
</style>