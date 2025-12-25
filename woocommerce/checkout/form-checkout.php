<?php

/**
 * Checkout Form
 *
 * @package Exclusive
 */

defined('ABSPATH') || exit;

// Check if cart is empty
if (WC()->cart->is_empty() && !is_customize_preview() && apply_filters('woocommerce_checkout_redirect_empty_cart', true)) {
    return;
}

do_action('woocommerce_before_checkout_form', WC()->checkout());

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!WC()->checkout()->is_registration_enabled() && WC()->checkout()->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<div class="custom-checkout-wrapper">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo esc_url(wc_get_cart_url()); ?>">Cart</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
            action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <?php if (WC()->checkout()->get_checkout_fields()) : ?>

            <?php do_action('woocommerce_checkout_before_customer_details'); ?>

            <div class="row g-5">
                <!-- Left Column - Billing Details -->
                <div class="col-lg-7">
                    <div class="checkout-billing">
                        <h3 class="checkout-title">Billing Details</h3>

                        <div class="woocommerce-billing-fields__field-wrapper">
                            <?php
                                $fields = WC()->checkout()->get_checkout_fields('billing');

                foreach ($fields as $key => $field) {
                    woocommerce_form_field($key, $field, WC()->checkout()->get_value($key));
                }
                ?>
                        </div>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                </div>

                <!-- Right Column - Order Review -->
                <div class="col-lg-5">
                    <div class="checkout-review">
                        <h3 class="checkout-title" id="order_review_heading">Your order</h3>

                        <?php do_action('woocommerce_checkout_before_order_review'); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">

                            <!-- Order Items -->
                            <div class="order-items-wrapper">
                                <?php
                    do_action('woocommerce_review_order_before_cart_contents');

                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        ?>
                                <div class="checkout-order-item">
                                    <div class="item-info">
                                        <div class="item-thumbnail">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(array(54, 54)), $cart_item, $cart_item_key);
                        echo $thumbnail;
                        ?>
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name">
                                                <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
                                            </div>
                                            <div class="item-qty">× <?php echo $cart_item['quantity']; ?></div>
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

                            <!-- Coupon Code -->
                            <?php if (wc_coupons_enabled()) : ?>
                            <div class="coupon-wrapper">
                                <details class="coupon-details">
                                    <summary>Add coupon code</summary>
                                    <div class="coupon-form">
                                        <input type="text" name="coupon_code" class="input-text"
                                            placeholder="Coupon code" id="coupon_code" value="" />
                                        <button type="button" class="button btn-apply-coupon" name="apply_coupon"
                                            value="Apply coupon">Apply</button>
                                    </div>
                                </details>
                            </div>
                            <?php endif; ?>

                            <!-- Order Totals -->
                            <div class="checkout-totals">
                                <div class="totals-row">
                                    <span>Subtotal:</span>
                                    <span><?php wc_cart_totals_subtotal_html(); ?></span>
                                </div>

                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                <div class="totals-row coupon-row">
                                    <span>
                                        <?php wc_cart_totals_coupon_label($coupon); ?>
                                    </span>
                                    <span>
                                        <?php wc_cart_totals_coupon_html($coupon); ?>
                                    </span>
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
                            <div class="checkout-payment">
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
                                echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>';
                            }
?>
                                    </ul>
                                    <?php endif; ?>

                                    <div class="form-row place-order">
                                        <noscript>
                                            <?php esc_html_e('Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order.', 'woocommerce'); ?>
                                            <br /><button type="submit" class="button alt"
                                                name="woocommerce_checkout_update_totals"
                                                value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
                                        </noscript>

                                        <?php wc_get_template('checkout/terms.php'); ?>

                                        <?php do_action('woocommerce_review_order_before_submit'); ?>

                                        <button type="submit" class="button alt btn-place-order"
                                            name="woocommerce_checkout_place_order" id="place_order"
                                            value="<?php esc_attr_e('Place order', 'woocommerce'); ?>"
                                            data-value="<?php esc_attr_e('Place order', 'woocommerce'); ?>">
                                            <?php esc_html_e('Place order', 'woocommerce'); ?>
                                        </button>

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

            <?php endif; ?>

        </form>

        <?php do_action('woocommerce_after_checkout_form', WC()->checkout()); ?>
    </div>
</div>

<style>
/* ===================================
   CUSTOM CHECKOUT STYLES
==================================== */
.custom-checkout-wrapper {
    font-family: 'Poppins', sans-serif;
    background: #fff;
}

/* Breadcrumb */
.breadcrumb-section {
    padding: 80px 0 20px;
    background: #fff;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    list-style: none;
    display: flex;
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

.breadcrumb-item+.breadcrumb-item::before {
    content: "/";
    padding: 0 8px;
    color: #666;
}

.breadcrumb-item.active {
    color: #000;
}

/* Checkout Title */
.checkout-title {
    font-size: 36px;
    font-weight: 500;
    color: #000;
    margin-bottom: 48px;
    font-family: 'Poppins', sans-serif;
}

/* Billing Fields */
.woocommerce-billing-fields__field-wrapper {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.form-row {
    margin: 0 !important;
}

.form-row label {
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    color: rgba(0, 0, 0, 0.6);
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
}

.form-row label .required {
    color: #DB4444;
}

.form-row input[type="text"],
.form-row input[type="email"],
.form-row input[type="tel"],
.form-row textarea,
.form-row select {
    width: 100%;
    height: 50px;
    padding: 0 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    font-size: 16px;
    background: #f5f5f5;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s ease;
}

.form-row textarea {
    height: 100px;
    padding: 16px;
    resize: vertical;
}

.form-row input:focus,
.form-row textarea:focus,
.form-row select:focus {
    outline: none;
    border-color: #DB4444;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(219, 68, 68, 0.1);
}

.form-row select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    padding-right: 40px;
}

/* Two columns for first/last name */
.form-row-first,
.form-row-last {
    width: 48%;
    display: inline-block;
}

.form-row-first {
    margin-right: 4% !important;
}

.form-row-wide {
    width: 100%;
    clear: both;
}

/* Order Items */
.order-items-wrapper {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 24px;
    margin-bottom: 24px;
    max-height: 400px;
    overflow-y: auto;
}

.checkout-order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #e5e5e5;
}

.checkout-order-item:first-child {
    padding-top: 0;
}

.checkout-order-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
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
    background: #f5f5f5;
    padding: 8px;
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
    font-family: 'Poppins', sans-serif;
}

.item-qty {
    font-size: 14px;
    color: #666;
    font-family: 'Poppins', sans-serif;
}

.item-total {
    font-size: 16px;
    font-weight: 500;
    color: #000;
    font-family: 'Poppins', sans-serif;
}

/* Coupon */
.coupon-wrapper {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    padding: 16px 24px;
    margin-bottom: 24px;
}

.coupon-details {
    font-family: 'Poppins', sans-serif;
}

.coupon-details summary {
    cursor: pointer;
    font-size: 16px;
    color: #000;
    list-style: none;
}

.coupon-details summary::-webkit-details-marker {
    display: none;
}

.coupon-form {
    display: flex;
    gap: 12px;
    margin-top: 16px;
}

.coupon-form .input-text {
    flex: 1;
    height: 50px;
    padding: 0 16px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    font-size: 16px;
    background: #f5f5f5;
    font-family: 'Poppins', sans-serif;
}

.btn-apply-coupon {
    height: 50px;
    padding: 0 24px;
    background: #DB4444;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-apply-coupon:hover {
    background: #C13333;
}

/* Totals */
.checkout-totals {
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
    font-family: 'Poppins', sans-serif;
}

.totals-row:first-child {
    padding-top: 0;
}

.totals-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.total-row {
    font-weight: 600;
    font-size: 18px;
}

/* Payment */
.checkout-payment {
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
    margin: 0;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    transition: background 0.3s ease;
}

.wc_payment_method label:hover {
    background: #f5f5f5;
}

.wc_payment_method input[type="radio"] {
    margin-right: 12px;
}

.payment_box {
    padding: 16px;
    background: #f5f5f5;
    border-top: 1px solid #e5e5e5;
    font-family: 'Poppins', sans-serif;
}

/* Place Order Button */
.btn-place-order {
    width: 100%;
    height: 56px;
    background: #DB4444 !important;
    color: #fff !important;
    border: none !important;
    border-radius: 4px;
    font-size: 16px !important;
    font-weight: 500 !important;
    font-family: 'Poppins', sans-serif !important;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-place-order:hover {
    background: #C13333 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.4);
}

/* Responsive */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .checkout-title {
        font-size: 28px;
        margin-bottom: 32px;
    }

    .form-row-first,
    .form-row-last {
        width: 100%;
        margin-right: 0 !important;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .checkout-title {
        font-size: 24px;
        margin-bottom: 24px;
    }

    .woocommerce-billing-fields__field-wrapper {
        gap: 24px;
    }

    .form-row input[type="text"],
    .form-row input[type="email"],
    .form-row input[type="tel"],
    .form-row select {
        height: 44px;
        font-size: 14px;
    }

    .order-items-wrapper {
        padding: 16px;
    }

    .checkout-order-item {
        padding: 12px 0;
    }

    .item-thumbnail {
        width: 40px;
        height: 40px;
    }

    .item-name {
        font-size: 14px;
    }

    .item-qty,
    .item-total {
        font-size: 13px;
    }

    .checkout-totals,
    .checkout-payment {
        padding: 16px;
    }

    .totals-row {
        font-size: 14px;
        padding: 12px 0;
    }

    .btn-place-order {
        height: 48px;
        font-size: 14px !important;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Apply coupon
    $('.btn-apply-coupon').on('click', function(e) {
        e.preventDefault();

        var couponCode = $('#coupon_code').val();

        if (!couponCode) {
            alert('Please enter a coupon code');
            return;
        }

        $.ajax({
            url: wc_checkout_params.ajax_url,
            type: 'POST',
            data: {
                action: 'woocommerce_apply_coupon',
                security: wc_checkout_params.apply_coupon_nonce,
                coupon_code: couponCode
            },
            success: function(response) {
                $('.woocommerce-error, .woocommerce-message').remove();

                if (response) {
                    $(document.body).trigger('update_checkout');
                }
            }
        });
    });
});
</script>