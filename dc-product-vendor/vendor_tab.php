
<?php

/**
 * The template for displaying single product page vendor tab
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/vendor_tab.php
 *
 * @author 		WC Marketplace
 * @package 	dc-product-vendor/Templates
 * @version   2.2.0
 */
global $WCMp, $product;
$html = '';
$vendor = get_wcmp_product_vendors($product->get_id());
$vendor_id = $vendor->id;
$vendor_avatar = get_user_meta($vendor_id, '_vendor_profile_image', true);
$avatars_url = wp_get_attachment_image_url($vendor_avatar, 'full');
$vendor_avatar = empty($vendor_avatar) ? ['http://1.gravatar.com/avatar/1d78a79a5ceb2653148a60cae7fe22d7?s=70&d=mm&r=g'] : [$avatars_url];


if ($vendor) {
  $html .= '<div class="product-vendor">';
  $html .= '<a href="' . $vendor->permalink . '"><img class="vendor_avatar" src="' . $vendor_avatar[0] . '"></a>';
  $html .= apply_filters('wcmp_before_seller_info_tab', '');
  $html .= '<h2>' . $vendor->page_title . '</h2>';
   $html .= '<p><a href="' . $vendor->permalink . '">' . sprintf(__('查看化妝師 %1$s', 'dc-woocommerce-multi-vendor'), $vendor->page_title) .'</a></p>';
  echo $html; 
  $term_vendor = wp_get_post_terms($product->get_id(), $WCMp->taxonomy->taxonomy_name);
  if (!is_wp_error($term_vendor) && !empty($term_vendor)) {
    $rating_result_array = wcmp_get_vendor_review_info($term_vendor[0]->term_id);
    if (get_wcmp_vendor_settings('is_sellerreview_varified', 'general') == 'Enable') {
      $term_link = get_term_link($term_vendor[0]);
      $rating_result_array['shop_link'] = $term_link;
      echo '<div style="text-align:left; float:left;">';
      $WCMp->template->get_template('review/rating-vendor-tab.php', array('rating_val_array' => $rating_result_array));
      echo "</div>";
      echo '<div style="clear:both; width:100%;"></div>';
    }
  }

  $html = '';
  if ('' != $vendor->description) {
    $html .= apply_filters('the_content', $vendor->description);
  }

  $html .= apply_filters('wcmp_after_seller_info_tab', '');
  $html .= '</div>';
  echo $html;
  do_action('wcmp_after_vendor_tab');
}
?>