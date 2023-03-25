<?php

/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    )
);

$product_cats = get_the_terms($product->get_id(), 'product_cat');
$product_cat_array = array();
foreach ($product_cats as $product_cat) {
    array_push($product_cat_array, $product_cat->slug);
}
$is_before_after = (in_array('before-after' , $product_cat_array)) ? true : false;

if($is_before_after):

    $before = rwmb_meta( 'before', ['size' => 'large'] );
    $after = rwmb_meta( 'after', ['size' => 'large'] );

?>
<style>
    .twentytwenty-container img {
        width: 100% !important;
        aspect-ratio: 1/1 !important;
        object-fit: cover !important;
        /* height: 300px; */
    }
</style>
<div class="twentytwenty-container">
    <!-- The before image is first -->
    <img src="<?= $before['url'] ?>" />
    <!-- The after image is last -->
    <img src="<?= $after['url'] ?>" />
</div>

<script>
    (function($) {
        $(document).ready(function() {
            $(".twentytwenty-container").twentytwenty();
        });
    })(jQuery)
</script>

<?php else: ?>

<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper">
        <?php
        if ($post_thumbnail_id) {
            $html = wc_get_gallery_image_html($post_thumbnail_id, true);
        } else {
            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'woocommerce'));
            $html .= '</div>';
        }

        echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

        do_action('woocommerce_product_thumbnails');
        ?>
    </figure>
</div>

<?php endif; ?>