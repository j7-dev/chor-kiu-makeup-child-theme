<?php

// WCMP update function
function wcmp_custom_update()
{
  $vendor_id = $_POST['vendor_id'];
  foreach ($_POST as $key => $value) {
    update_user_meta($vendor_id, '_' . $key, $value);
  }
  wp_send_json( [
    'status' => 'success',
    'message' => '更新成功',
  ] );

}

add_action('wp_ajax_wcmp_custom_update', 'wcmp_custom_update');
// add_action('wp_ajax_nopriv_wcmp_custom_update', 'wcmp_custom_update');


function wcmp_vendor_page_text_color_update()
{
  if (empty($_POST['vendor_page_text_color'])) return;
  update_option('wcmp_vendor_page_text_color', $_POST['vendor_page_text_color']);
  wp_send_json( [
    'status' => 'success',
    'message' => '更新成功',
  ] );
}
add_action('wp_ajax_wcmp_vendor_page_text_color_update', 'wcmp_vendor_page_text_color_update');
 // add_action('wp_ajax_nopriv_wcmp_vendor_page_text_color_update', 'wcmp_vendor_page_text_color_update');




//後台引入JS, AJAX
function wcmp_page_vendors_custom_field_enqueue($hook)
{
  // Only add to the edit.php admin page.
  // See WP docs.
  // var_dump($hook);

  if ('wcmp_page_vendors' !== $hook && 'wcmp_page_wcmp-setting-admin' !== $hook) {
    return;
  }

  wp_enqueue_style('custom_wcmp_field', get_stylesheet_directory_uri() . '/assets/css/wcmp-custom-field.css', array(), THEME_VER);

  wp_enqueue_script('custom_wcmp_field', get_stylesheet_directory_uri() . '/assets/js/wcmp-custom-field.js', array(), THEME_VER, true);

  // iris - color picker
  wp_enqueue_script('iris');

  // custom field init
  $meta_keys = [
    'vendor_school',
    'vendor_jobtitle',
    'vendor_exp',
    'vendor_charge',
    'vendor_other'
  ];
  foreach ($meta_keys as $meta_key) {
    add_user_meta($_GET['ID'], '_' . $meta_key, '', true);
  }

  $vendor_other_content = (isset($_POST['vendor_other'])) ? ($_POST['vendor_other']) : (get_user_meta($_GET['ID'], '_vendor_other', true));

  $vendor_charge_content = (isset($_POST['vendor_charge'])) ? ($_POST['vendor_charge']) : (get_user_meta($_GET['ID'], '_vendor_charge', true));

  wp_localize_script('custom_wcmp_field', 'ajax_object', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'ajaxnonce' => wp_create_nonce('ajax_post_validation'),
    'vendor_id' => $_GET['ID'],
    'vendor' => get_user_meta($_GET['ID']),
    'vendor_other_editor' => get_wp_editor(wpautop($vendor_other_content), 'vendor_other'),
    'vendor_charge_editor' => get_wp_editor(wpautop($vendor_charge_content), 'vendor_charge'),
    'wcmp_vendor_page_text_color' => get_option('wcmp_vendor_page_text_color',
  '#f1bb97'),
  ));
}

add_action('admin_enqueue_scripts', 'wcmp_page_vendors_custom_field_enqueue');


/**
 * Get the wp_editor through AJAX Call
 * @see https://developer.wordpress.org/reference/functions/wp_editor/
 */
function get_wp_editor($content = '', $editor_id, $options = array('textarea_rows' => 7))
{
  ob_start();

  wp_editor($content, $editor_id, $options);

  $temp = ob_get_clean();
  // 已經有用，就不用再載入
  $temp .= \_WP_Editors::enqueue_scripts();
  // $temp .= print_footer_scripts();
  $temp .= \_WP_Editors::editor_js();

  return $temp;
}
