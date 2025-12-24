<?php

/**
 * Cart Page Template
 * Custom design for WooCommerce cart
 *
 * @package Exclusive
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="custom-cart-page">
    <!-- Breadcrumb -->
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
            <?php do_action('woocommerce_before_cart_table'); ?>

            <?php if (!WC()->cart->is_empty()) : ?>
            <!-- Cart Table -->
            <div class="cart-table-wrapper">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">
                    <thead>
                        <tr>
                            <th class="product-thumbnail">Product</th>
                            <th class="product-name"></th>
                            <th class="product-price">Price</th>
                            <th class="product-quantity">Quantity</th>
                            <th class="product-subtotal">Subtotal</th>
                            <th class="product-remove"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php do_action('woocommerce_before_cart_contents'); ?>

                        <?php
                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            ?>
                        <tr
                            class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                            <!-- Product Image -->
                            <td class="product-thumbnail">
                                <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                            if (!$product_permalink) {
                                                echo $thumbnail;
                                            } else {
                                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                            }
                                            ?>
                            </td>

                            <!-- Product Name -->
                            <td class="product-name" data-title="Product">
                                <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                            } else {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }

                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                            // Meta data
                                            echo wc_get_formatted_cart_item_data($cart_item);

                                            // Backorder notification
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                            }
                                            ?>
                            </td>

                            <!-- Product Price -->
                            <td class="product-price" data-title="Price">
                                <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                            ?>
                            </td>

                            <!-- Product Quantity -->
                            <td class="product-quantity" data-title="Quantity">
                                <?php
                                            if ($_product->is_sold_individually()) {
                                                $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                            } else {
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                            }

                                            echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                            ?>
                            </td>

                            <!-- Product Subtotal -->
                            <td class="product-subtotal" data-title="Subtotal">
                                <?php
                                            echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                            ?>
                            </td>

                            <!-- Remove Button -->
                            <td class="product-remove">
                                <?php
                                            echo apply_filters(
                                                'woocommerce_cart_item_remove_link',
                                                sprintf(
                                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fas fa-times"></i></a>',
                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                    esc_html__('Remove this item', 'woocommerce'),
                                                    esc_attr($product_id),
                                                    esc_attr($_product->get_sku())
                                                ),
                                                $cart_item_key
                                            );
                                            ?>
                            </td>
                        </tr>
                        <?php
                                }
                            }
                            ?>

                        <?php do_action('woocommerce_cart_contents'); ?>

                        <tr>
                            <td colspan="6" class="actions">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"
                                        class="btn btn-outline-dark">
                                        Return To Shop
                                    </a>

                                    <button type="submit" class="btn btn-outline-dark" name="update_cart"
                                        value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                                        <?php esc_html_e('Update cart', 'woocommerce'); ?>
                                    </button>

                                    <?php do_action('woocommerce_cart_actions'); ?>
                                    <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                </div>
                            </td>
                        </tr>

                        <?php do_action('woocommerce_after_cart_contents'); ?>
                    </tbody>
                </table>
            </div>

            <?php do_action('woocommerce_after_cart_table'); ?>

            <!-- Cart Totals & Coupon -->
            <div class="row mt-5">
                <!-- Coupon Code -->
                <div class="col-lg-6 mb-4">
                    <?php if (wc_coupons_enabled()) : ?>
                    <div class="coupon-wrapper">
                        <input type="text" name="coupon_code" class="form-control" id="coupon_code"
                            placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
                        <button type="submit" class="btn btn-primary-custom" name="apply_coupon"
                            value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                            <?php esc_html_e('Apply Coupon', 'woocommerce'); ?>
                        </button>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Cart Totals -->
                <div class="col-lg-6">
                    <div class="cart-totals-wrapper">
                        <?php wc_cart_totals_shipping_html(); ?>

                        <h4 class="cart-totals-title mb-4">Cart Total</h4>

                        <div class="cart-totals-content">
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
                            <div class="totals-row">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            <?php endif; ?>

                            <div class="totals-row total-row">
                                <span>Total:</span>
                                <span><?php wc_cart_totals_order_total_html(); ?></span>
                            </div>
                        </div>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
                            class="btn btn-primary-custom w-100 mt-4">
                            Proceed to checkout
                        </a>
                    </div>
                </div>
            </div>

            <?php else : ?>
            <!-- Empty Cart Message -->
            <div class="cart-empty text-center py-5">
                <i class="fas fa-shopping-cart mb-4" style="font-size: 80px; color: #ddd;"></i>
                <p class="cart-empty-message mb-4"><?php esc_html_e('Your cart is currently empty.', 'woocommerce'); ?>
                </p>

                <?php do_action('woocommerce_cart_is_empty'); ?>

                <a class="btn btn-primary-custom px-5"
                    href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
                    <?php esc_html_e('Return to shop', 'woocommerce'); ?>
                </a>
            </div>
            <?php endif; ?>
        </form>

        <?php do_action('woocommerce_after_cart'); ?>
    </div>
</div>

<style>
/* ===================================
   BREADCRUMB
==================================== */
.breadcrumb-section {
    padding: 30px 0 20px;
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
   CART TABLE
==================================== */
.cart-table-wrapper {
    overflow-x: auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.08);
    border-radius: 4px;
}

.shop_table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 0;
}

.shop_table thead th {
    background: #fff;
    color: #000;
    font-weight: 500;
    font-size: 16px;
    padding: 24px 16px;
    text-align: left;
    border-bottom: 1px solid #e5e5e5;
}

.shop_table tbody tr {
    border-bottom: 1px solid #e5e5e5;
}

.shop_table tbody tr:last-child {
    border-bottom: none;
}

.shop_table td {
    padding: 24px 16px;
    vertical-align: middle;
}

/* Product Thumbnail */
.product-thumbnail img {
    width: 54px;
    height: 54px;
    object-fit: contain;
    padding: 8px;
    background: #f5f5f5;
    border-radius: 4px;
}

/* Product Name */
.product-name {
    font-size: 16px;
    font-weight: 400;
}

.product-name a {
    color: #000;
    text-decoration: none;
}

.product-name a:hover {
    color: var(--primary-color);
}

/* Product Price */
.product-price,
.product-subtotal {
    font-size: 16px;
    font-weight: 400;
    color: #000;
    margin-bottom: 0px;
}

/* Quantity Input */
.quantity {
    display: flex;
    align-items: center;
    border: 1.5px solid rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    overflow: hidden;
    width: fit-content;
}

.quantity input {
    width: 72px;
    height: 44px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
    padding: 0 12px;
}

.quantity input:focus {
    outline: none;
    border: none;
}

.quantity input::-webkit-outer-spin-button,
.quantity input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Remove Button */
.product-remove a {
    color: #000;
    font-size: 18px;
    transition: all 0.3s ease;
}

.product-remove a:hover {
    color: var(--primary-color);
    transform: scale(1.1);
}

/* Actions Row */
.actions {
    background: #fff;
    padding: 24px 16px !important;
}

/* ===================================
   COUPON & CART TOTALS
==================================== */
.coupon-wrapper {
    display: flex;
    gap: 16px;
}

.coupon-wrapper .form-control {
    flex: 1;
    height: 56px;
    border: 1.5px solid rgba(0, 0, 0, 0.3);
    border-radius: 4px;
    padding: 0 24px;
    font-size: 16px;
}

.coupon-wrapper .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(219, 68, 68, 0.1);
}

.coupon-wrapper .btn {
    height: 56px;
    padding: 0 48px;
}

/* Cart Totals */
.cart-totals-wrapper {
    border: 1.5px solid #000;
    border-radius: 4px;
    padding: 32px;
}

.cart-totals-title {
    font-size: 20px;
    font-weight: 500;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e5e5;
}

.cart-totals-content {
    padding-top: 24px;
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
}

.total-row {
    font-weight: 600;
    font-size: 16px;
}

/* ===================================
   EMPTY CART
==================================== */
.cart-empty {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.cart-empty-message {
    font-size: 18px;
    color: #666;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .breadcrumb-section {
        padding: 60px 0 20px;
    }

    .shop_table thead th {
        font-size: 14px;
        padding: 20px 12px;
    }

    .shop_table td {
        padding: 20px 12px;
    }

    .cart-totals-wrapper {
        padding: 24px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    /* Stack table for mobile */
    .shop_table thead {
        display: none;
    }

    .shop_table tbody tr {
        display: block;
        margin-bottom: 24px;
        border: 1px solid #e5e5e5;
        border-radius: 4px;
        padding: 16px;
    }

    .shop_table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border: none;
    }

    .shop_table td::before {
        content: attr(data-title);
        font-weight: 600;
        margin-right: 12px;
    }

    .product-thumbnail {
        justify-content: center;
    }

    .product-thumbnail::before {
        display: none;
    }

    .product-name,
    .product-remove {
        flex-direction: column;
        align-items: flex-start;
    }

    .coupon-wrapper {
        flex-direction: column;
    }

    .cart-totals-wrapper {
        padding: 20px;
    }

    .cart-totals-title {
        font-size: 18px;
    }

    .totals-row {
        font-size: 14px;
    }
}
</style>