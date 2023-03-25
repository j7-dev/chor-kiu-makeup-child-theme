<?php

/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;


$meta_keys = ['vendor_page_title', 'vendor_img_id', 'vendor_exp', 'vendor_jobtitle', 'vendor_school', 'vendor_phone', 'vendor_fb_profile', 'vendor_twitter_profile', 'vendor_linkdin_profile', 'vendor_youtube', 'vendor_instagram', 'vendor_country_code', 'vendor_address_1', 'vendor_charge', 'vendor_other'];



// update


foreach ($_POST as $key => $value) {
    if($key == '_vendor_country_code' && $value == 'HK'){
      update_user_meta($vendor_id, '_vendor_country', '香港');
    }
    update_user_meta($vendor_id, $key, $value);
}


$vendor = get_wcmp_vendor($vendor_id);



foreach($meta_keys as $meta_key){
  $$meta_key = get_user_meta($vendor_id, '_' . $meta_key, true) ?? '';
}


do_action('woocommerce_before_edit_vendor_form');

?>



<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action('woocommerce_edit_vendor_form_tag'); ?>>

    <?php do_action('woocommerce_edit_vendor_form_start'); ?>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_page_title"><?php esc_html_e('商店名稱', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_page_title" id="vendor_page_title" value="<?= $vendor_page_title ?>" />
        <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_description"><?php esc_html_e('商店描述', 'woocommerce'); ?></label>
        <?php
        wp_editor(wpautop($vendor->description), '_vendor_description');
        ?>
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_phone"><?php esc_html_e('商店電話', 'woocommerce'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_phone" id="vendor_phone" autocomplete="phone" value="<?= $vendor_phone ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_address_1"><?php esc_html_e('商店地址', 'woocommerce'); ?></label>

        <select disabled name="_vendor_country_code" id="vendor_country_code" class="country_to_state vendors_sort_shipping_fields form-control regular-select" rel="vendor_country_code">
            <option value="HK">香港</option>
            <?php
            // $country_code = $vendor_country_code;
            // foreach (WC()->countries->get_allowed_countries() as $key => $value) {
            //     echo '<option value="' . esc_attr($key) . '"' . selected(esc_attr($country_code), esc_attr($key), false) . '>' . esc_html($value) . '</option>';
            // }
            ?>
        </select>

        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_address_1" id="vendor_address_1" value="<?= $vendor_address_1 ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_school"><?php esc_html_e('畢業學校', 'woocommerce'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_school" id="vendor_school" value="<?= $vendor_school ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_jobtitle"><?php esc_html_e('職稱', 'woocommerce'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_jobtitle" id="vendor_jobtitle" value="<?= $vendor_jobtitle ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_exp"><?php esc_html_e('職稱', 'woocommerce'); ?></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_exp" id="vendor_exp" value="<?= $vendor_exp ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_charge"><?php esc_html_e('服務收費', 'woocommerce'); ?></label>
        <?php
        wp_editor(wpautop($vendor_charge), '_vendor_charge');
        ?>
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_other"><?php esc_html_e('其他詳情', 'woocommerce'); ?></label>
        <?php
        wp_editor(wpautop($vendor_other), '_vendor_other');
        ?>
    </p>
    <div class="clear"></div>

    <legend>社群連結</legend>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_fb_profile"><?php esc_html_e('Facebook', 'woocommerce'); ?></label>
        <input type="url" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_fb_profile" id="vendor_fb_profile" value="<?= $vendor_fb_profile ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_twitter_profile"><?php esc_html_e('Twitter', 'woocommerce'); ?></label>
        <input type="url" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_twitter_profile" id="vendor_twitter_profile" value="<?= $vendor_twitter_profile ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_linkdin_profile"><?php esc_html_e('LinkedIn', 'woocommerce'); ?></label>
        <input type="url" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_linkdin_profile" id="vendor_linkdin_profile" value="<?= $vendor_linkdin_profile ?>" />
    </p>
    <div class="clear"></div>

    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_youtube"><?php esc_html_e('Youtube', 'woocommerce'); ?></label>
        <input type="url" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_youtube" id="vendor_youtube" value="<?= $vendor_youtube ?>" />
    </p>
    <div class="clear"></div>




    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="vendor_instagram"><?php esc_html_e('Instagram', 'woocommerce'); ?></label>
        <input type="url" class="woocommerce-Input woocommerce-Input--text input-text" name="_vendor_instagram" id="vendor_instagram" value="<?= $vendor_instagram ?>" />
    </p>
    <div class="clear"></div>




    <?php do_action('woocommerce_edit_vendor_form'); ?>

    <p>
        <?php wp_nonce_field('save_vendor_details', 'save-vendor-details-nonce'); ?>
        <button type="submit" class="woocommerce-Button button" name="save_vendor_details" value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
        <input type="hidden" name="action" value="save_vendor_details" />
    </p>

    <?php do_action('woocommerce_edit_vendor_form_end'); ?>
</form>

<?php do_action('woocommerce_after_edit_vendor_form'); ?>