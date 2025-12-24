<?php
get_header(); ?>

<main class="site-main py-5">
    <div class="container">
        <?php
        while (have_posts()) : the_post();
            // Lệnh này sẽ gọi nội dung [woocommerce_cart] từ Admin ra
            the_content();
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>