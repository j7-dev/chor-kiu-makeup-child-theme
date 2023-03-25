<?php
add_filter( 'rwmb_meta_boxes', 'job_cat_field' );

function job_cat_field( $meta_boxes ) {
    $prefix = '';

    $meta_boxes[] = [
        'title'      => __( '工作分類', 'mua' ),
        'id'         => 'job_cat_field',
        'taxonomies' => ['job_cat'],
        'fields'     => [
            [
                'name' => __( '顯示圖片', 'mua' ),
                'id'   => $prefix . 'image',
                'type' => 'single_image',
            ],
            [
                'name' => __( '顯示在頁面上', 'mua' ),
                'id'   => $prefix . 'is_show',
                'type' => 'checkbox',
            ],
        ],
    ];

    return $meta_boxes;
}