<?php

/**
 * Cart Page Template
 *
 * @package Exclusive
 */

get_header();
?>

<main class="cart-page">
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Cart Content -->
    <section class="cart-content-section py-5">
        <div class="container">
            <?php if (WC()->cart->is_empty()) : ?>
            <!-- Empty Cart -->
            <div class="empty-cart text-center py-5">
                <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h3 class="mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">You have no items in your shopping cart.</p>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="btn btn-primary-custom px-5 py-3">
                    Continue Shopping
                </a>
            </div>
            <?php else : ?>
            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <!-- Cart Table -->
                <div class="cart-table-wrapper mb-4">
                    <div class="table-responsive">
                        <table class="cart-table">
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
                                <?php
                                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0) :
                                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                            ?>
                                <tr class="cart-item">
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
                                    <td class="product-name">
                                        <?php
                                        if (!$product_permalink) {
                                            echo wp_kses_post($_product->get_name());
                                        } else {
                                            echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                        }
                                        ?>
                                    </td>

                                    <!-- Product Price -->
                                    <td class="product-price">
                                        <?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?>
                                    </td>

                                    <!-- Quantity -->
                                    <td class="product-quantity">
                                        <div class="quantity-selector">
                                            <button type="button" class="qty-btn minus-btn"
                                                data-cart-key="<?php echo $cart_item_key; ?>">-</button>
                                            <input type="number" class="qty-input"
                                                name="cart[<?php echo $cart_item_key; ?>][qty]"
                                                value="<?php echo esc_attr($cart_item['quantity']); ?>" min="0"
                                                max="<?php echo esc_attr($_product->get_max_purchase_quantity()); ?>"
                                                data-cart-key="<?php echo $cart_item_key; ?>">
                                            <button type="button" class="qty-btn plus-btn"
                                                data-cart-key="<?php echo $cart_item_key; ?>">+</button>
                                        </div>
                                    </td>

                                    <!-- Subtotal -->
                                    <td class="product-subtotal">
                                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                    </td>

                                    <!-- Remove -->
                                    <td class="product-remove">
                                        <button type="button" class="btn-remove"
                                            data-cart-key="<?php echo $cart_item_key; ?>" title="Remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php
                                        endif;
                                    endforeach;
?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Cart Actions -->
                <div class="cart-actions d-flex justify-content-between align-items-center flex-wrap gap-3 mb-5">
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"
                        class="btn btn-outline-dark px-4 py-2">
                        Return To Shop
                    </a>
                    <button type="submit" name="update_cart" class="btn btn-outline-dark px-4 py-2">
                        Update Cart
                    </button>
                </div>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </form>

            <!-- Cart Totals -->
            <div class="row justify-content-end">
                <div class="col-lg-5">
                    <div class="cart-totals-box">
                        <h4 class="totals-title mb-4">Cart Total</h4>

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

                        <div class="totals-row total">
                            <span>Total:</span>
                            <span><?php echo WC()->cart->get_total(); ?></span>
                        </div>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn btn-checkout w-100 mt-4">
                            Proceed to checkout
                        </a>
                    </div>
                </div>
            </div>
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
   CART CONTENT
==================================== */
.cart-content-section {
    background: #fff;
}

/* Empty Cart */
.empty-cart {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* ===================================
   CART TABLE
==================================== */
.cart-table-wrapper {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    overflow: hidden;
}

.cart-table {
    width: 100%;
    margin: 0;
}

.cart-table thead {
    background: #f8f9fa;
}

.cart-table thead th {
    padding: 16px;
    font-size: 16px;
    font-weight: 500;
    color: #000;
    border-bottom: 1px solid #e5e5e5;
}

.cart-table tbody tr {
    border-bottom: 1px solid #e5e5e5;
}

.cart-table tbody tr:last-child {
    border-bottom: none;
}

.cart-table td {
    padding: 24px 16px;
    vertical-align: middle;
}

/* Product Thumbnail */
.product-thumbnail {
    width: 100px;
}

.product-thumbnail img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 4px;
    background: #f5f5f5;
    padding: 8px;
}

/* Product Name */
.product-name {
    font-size: 16px;
    font-weight: 500;
}

.product-name a {
    color: #000;
    text-decoration: none;
}

.product-name a:hover {
    color: #DB4444;
}

/* Product Price */
.product-price {
    font-size: 16px;
    font-weight: 500;
    color: #000;
}

/* Quantity Selector */
.quantity-selector {
    display: inline-flex;
    align-items: center;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    overflow: hidden;
}

.qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #fff;
    color: #000;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-btn:hover {
    background: #DB4444;
    color: #fff;
}

.qty-input {
    width: 60px;
    height: 36px;
    border: none;
    border-left: 1px solid #e5e5e5;
    border-right: 1px solid #e5e5e5;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
}

.qty-input:focus {
    outline: none;
}

/* Product Subtotal */
.product-subtotal {
    font-size: 16px;
    font-weight: 600;
    color: #000;
}

/* Remove Button */
.btn-remove {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    color: #999;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 4px;
}

.btn-remove:hover {
    background: #DB4444;
    color: #fff;
}

/* ===================================
   CART ACTIONS
==================================== */
.cart-actions .btn {
    min-width: 160px;
    font-weight: 500;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-outline-dark {
    border: 1px solid #000;
    color: #000;
}

.btn-outline-dark:hover {
    background: #000;
    color: #fff;
}

/* ===================================
   CART TOTALS
==================================== */
.cart-totals-box {
    border: 1px solid #000;
    border-radius: 4px;
    padding: 32px;
}

.totals-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 24px;
}

.totals-row {
    display: flex;
    justify-content: space-between;
    padding: 16px 0;
    border-bottom: 1px solid #e5e5e5;
    font-size: 16px;
}

.totals-row:last-of-type {
    border-bottom: none;
}

.totals-row.total {
    font-size: 18px;
    font-weight: 600;
    padding-top: 20px;
    border-top: 2px solid #000;
}

.btn-checkout {
    background: #DB4444;
    color: #fff;
    border: none;
    padding: 16px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.btn-checkout:hover {
    background: #C13333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.3);
    color: #fff;
}

/* ===================================
   RESPONSIVE
==================================== */
@media (max-width: 991px) {
    .cart-table thead th {
        padding: 12px;
        font-size: 14px;
    }

    .cart-table td {
        padding: 16px 12px;
    }

    .product-thumbnail {
        width: 80px;
    }

    .product-thumbnail img {
        width: 60px;
        height: 60px;
    }
}

@media (max-width: 767px) {
    .breadcrumb-section {
        padding: 40px 0 15px;
    }

    .cart-table-wrapper {
        overflow-x: auto;
    }

    .cart-table {
        min-width: 700px;
    }

    .cart-actions {
        flex-direction: column;
    }

    .cart-actions .btn {
        width: 100%;
    }

    .cart-totals-box {
        padding: 24px;
        margin-top: 30px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Quantity buttons
    $('.qty-btn').on('click', function() {
        const btn = $(this);
        const input = btn.siblings('.qty-input');
        const currentVal = parseInt(input.val());
        const max = parseInt(input.attr('max'));

        if (btn.hasClass('plus-btn')) {
            if (!max || currentVal < max) {
                input.val(currentVal + 1).trigger('change');
            }
        } else {
            if (currentVal > 1) {
                input.val(currentVal - 1).trigger('change');
            }
        }
    });

    // Auto update cart on quantity change
    $('.qty-input').on('change', function() {
        $('[name="update_cart"]').trigger('click');
    });

    // Remove item from cart
    $('.btn-remove').on('click', function() {
        if (confirm('Are you sure you want to remove this item?')) {
            const cartKey = $(this).data('cart-key');
            const row = $(this).closest('tr');

            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'remove_cart_item',
                    cart_item_key: cartKey
                },
                beforeSend: function() {
                    row.css('opacity', '0.5');
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        }
    });
});
</script>

<?php get_footer(); ?>