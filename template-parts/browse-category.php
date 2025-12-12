<?php

/**
 * Browse By Category Section
 */

// Get product categories
$categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent' => 0,
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'number' => 6
));
?>

<section class="browse-category-section py-5">
    <div class="container">
        <!-- Section Tag -->
        <div class="section-tag-group mb-5">
            <div class="section-tag">
                Categories
            </div>
            <h2 class="section-title">Browse By Category</h2>
        </div>

        <?php if (!empty($categories) && !is_wp_error($categories)) : ?>
        <div class="row g-4">
            <?php foreach ($categories as $category) :
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                    $category_link = get_term_link($category);

                    // Icon mapping for categories
                    $category_icons = array(
                        'phones' => 'fa-mobile-alt',
                        'computers' => 'fa-desktop',
                        'smartwatch' => 'fa-clock',
                        'camera' => 'fa-camera',
                        'headphones' => 'fa-headphones',
                        'gaming' => 'fa-gamepad',
                    );

                    $slug = $category->slug;
                    $icon = isset($category_icons[$slug]) ? $category_icons[$slug] : 'fa-tag';
                    ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="<?php echo esc_url($category_link); ?>" class="category-box">
                    <?php if ($image) : ?>
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>"
                        class="category-icon mb-3">
                    <?php else : ?>
                    <i class="fas <?php echo $icon; ?> category-icon-fallback"></i>
                    <?php endif; ?>
                    <h6 class="category-name mb-0"><?php echo esc_html($category->name); ?></h6>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <!-- Default Categories -->
        <div class="row g-4">
            <?php
                    $default_categories = array(
                        array('icon' => 'fa-mobile-alt', 'name' => 'Phones'),
                        array('icon' => 'fa-desktop', 'name' => 'Computers'),
                        array('icon' => 'fa-clock', 'name' => 'SmartWatch'),
                        array('icon' => 'fa-camera', 'name' => 'Camera'),
                        array('icon' => 'fa-headphones', 'name' => 'HeadPhones'),
                        array('icon' => 'fa-gamepad', 'name' => 'Gaming'),
                    );

            foreach ($default_categories as $cat) : ?>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="category-box">
                    <i class="fas <?php echo $cat['icon']; ?> category-icon-fallback"></i>
                    <h6 class="category-name"><?php echo $cat['name']; ?></h6>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.browse-category-section {
    border-top: 1px solid #e5e5e5;
    border-bottom: 1px solid #e5e5e5;
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

/* Category Box */
.category-box {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 25px 20px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 145px;
    text-decoration: none;
    color: #000;
    background: #fff;
}

.category-box:hover {
    background: var(--primary-color);
    color: #fff;
    border-color: var(--primary-color);
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(219, 68, 68, 0.3);
}

.category-icon {
    width: 56px;
    height: 56px;
    object-fit: contain;
    margin-bottom: 16px;
}

.category-icon-fallback {
    font-size: 56px;
    margin-bottom: 16px;
    color: #000;
    transition: color 0.3s ease;
}

.category-box:hover .category-icon-fallback {
    color: #fff;
}

.category-name {
    font-size: 16px;
    font-weight: 400;
    margin: 0;
    transition: color 0.3s ease;
}

.category-box:hover .category-name {
    color: #fff;
}

/* Responsive */
@media (max-width: 991px) {
    .section-title {
        font-size: 28px;
    }

    .category-box {
        min-height: 130px;
        padding: 20px 15px;
    }

    .category-icon-fallback {
        font-size: 48px;
        margin-bottom: 12px;
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
        margin-bottom: 2rem !important;
    }

    .category-box {
        padding: 20px 15px;
        min-height: 120px;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        margin-bottom: 12px;
    }

    .category-icon-fallback {
        font-size: 40px;
        margin-bottom: 12px;
    }

    .category-name {
        font-size: 14px;
    }
}
</style>