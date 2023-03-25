<?php
add_filter( 'mb_settings_pages', 'job_board_setting' );

function job_board_setting( $settings_pages ) {
	$settings_pages[] = [
        'menu_title'  => __( '設定', 'mua' ),
        'option_name' => 'job-setting',
        'position'    => 25,
        'parent'      => 'edit.php?post_type=mua_jobs',
        'icon_url'    => 'dashicons-admin-generic',
    ];

	return $settings_pages;
}
