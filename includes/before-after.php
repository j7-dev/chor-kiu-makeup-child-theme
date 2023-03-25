<?php


function enqueue_twentytwenty_assets()
{
    global $post;
    if( get_post_type( $post ) !== 'product') return;
    $product_cats = get_the_terms($post->ID, 'product_cat');
    foreach ($product_cats as $product_cat) {
        if ($product_cat->slug == 'before-after') {
            // 有 before-after
            wp_enqueue_style('twentytwenty', get_stylesheet_directory_uri() . '/assets/twentytwenty/css/twentytwenty.css', false);

            wp_enqueue_script('jquery-event-move', get_stylesheet_directory_uri() . '/assets/twentytwenty/js/jquery.event.move.js', false);

            wp_enqueue_script('twentytwenty', get_stylesheet_directory_uri() . '/assets/twentytwenty/js/jquery.twentytwenty.js', false);
            break;
        }
    }
}

add_action('wp_enqueue_scripts', 'enqueue_twentytwenty_assets');
