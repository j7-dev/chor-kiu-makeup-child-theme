<?php

/**
 * 移除選單 & 改名
 */
add_filter('woocommerce_account_menu_items', 'mua_remove_my_account_links');
function mua_remove_my_account_links($menu_links)
{
	// echo '<pre>';
	// var_dump($menu_links);
	// echo '</pre>';
	unset($menu_links['dashboard']);
	unset($menu_links['orders']);
	unset($menu_links['downloads']);
	unset($menu_links['edit-address']);
	unset($menu_links['payment-methods']);
	$menu_links['edit-account'] = '修改會員資料';
	//unset( $menu_links['edit-account'] );
	//unset( $menu_links[ 'dashboard' ] ); // Remove Dashboard
	//unset( $menu_links[ 'customer-logout' ] ); // Remove Logout link
	return $menu_links;
}





/**
 * @snippet       自訂 my account
 * @see           https://rudrastyh.com/woocommerce/my-account-menu.html#add-custom-tab
 */
add_filter('woocommerce_account_menu_items', 'mua_log_history_link', 40);
function mua_log_history_link($menu_links)
{
	$menu_added = array(
		'edit-vendor' => '編輯商店資料',
		'goto-vendor-shop' => '前往商店',
		'add-new-album' => '刊登作品',
		'edit-product-dashboard' => '修改作品',
		'apply-job' => '申請工作',
		'rent-space' => '預約租場',
	);
	$user = wp_get_current_user();
	if (!is_user_wcmp_vendor($user)) {
		unset($menu_added['edit-vendor']);
		unset($menu_added['goto-vendor-shop']);
	}

	$menu_links = array_slice($menu_links, 0, 5, true)
		+ $menu_added + array_slice($menu_links, 5, NULL, true);

	return $menu_links;
}
// register permalink endpoint
add_action('init', 'mua_add_endpoint');
function mua_add_endpoint()
{

	add_rewrite_endpoint('edit-vendor', EP_PAGES);
	add_rewrite_endpoint('goto-vendor-shop', EP_PAGES);
	add_rewrite_endpoint('add-new-album', EP_PAGES);
	add_rewrite_endpoint('edit-product-dashboard', EP_PAGES);
	add_rewrite_endpoint('apply-job', EP_PAGES);
	add_rewrite_endpoint('rent-space', EP_PAGES);
}




add_action('woocommerce_account_edit-vendor_endpoint', 'mua_my_account_editvendor_endpoint_content');
function mua_my_account_editvendor_endpoint_content()
{
	wc_get_template('myaccount/form-edit-vendor.php', array('vendor_id' => get_current_user_id()));
}

add_action('woocommerce_account_goto-vendor-shop_endpoint', 'mua_my_account_gotoVendorShop_endpoint_content');
function mua_my_account_gotoVendorShop_endpoint_content()
{

	$user = wp_get_current_user();
	$vendor = get_wcmp_vendor($user->ID);
	if (is_user_wcmp_vendor($user)) {
		wp_redirect($vendor->permalink);
		die;
	}
}

// content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
add_action('woocommerce_account_add-new-album_endpoint', 'mua_my_account_addNewAlbum_endpoint_content');
function mua_my_account_addNewAlbum_endpoint_content()
{
	wp_redirect(site_url('add-new-album'));
	die;
}
add_action('woocommerce_account_edit-product-dashboard_endpoint', 'mua_my_account_editProductDashboard_endpoint_content');
function mua_my_account_editProductDashboard_endpoint_content()
{
	wp_redirect(site_url('edit-product-dashboard'));
	die;
}

add_action('woocommerce_account_rent-space_endpoint', 'mua_my_account_rentspace_endpoint_content');
function mua_my_account_rentspace_endpoint_content()
{
	wp_redirect('https://search.mua.com.hk/booking/');
	die;
}

add_action('woocommerce_account_apply-job_endpoint', 'mua_my_account_applyjob_endpoint_content');
function mua_my_account_applyjob_endpoint_content()
{
	wp_redirect('https://search.mua.com.hk/job-board/');
	die;
}




//移除 last name
add_filter('woocommerce_save_account_details_required_fields', 'ts_hide_last_name');
function ts_hide_last_name($required_fields)
{
	unset($required_fields["account_last_name"]);
	return $required_fields;
}


/**
 * 編輯帳號，頁面新增欄位
 */
