<?php
add_action('init', 'mua_register_job_cat_taxonomy');
function mua_register_job_cat_taxonomy()
{
  $labels = [
    'name'                       => esc_html__('工作分類', 'mua'),
    'singular_name'              => esc_html__('工作分類', 'mua'),
    'menu_name'                  => esc_html__('工作分類', 'mua'),
    'search_items'               => esc_html__('搜尋工作分類', 'mua'),
    'popular_items'              => esc_html__('熱門工作分類', 'mua'),
    'all_items'                  => esc_html__('全部工作分類', 'mua'),
    'parent_item'                => esc_html__('父工作分類', 'mua'),
    'parent_item_colon'          => esc_html__('父工作分類', 'mua'),
    'edit_item'                  => esc_html__('編輯工作分類', 'mua'),
    'view_item'                  => esc_html__('查看工作分類', 'mua'),
    'update_item'                => esc_html__('更新工作分類', 'mua'),
    'add_new_item'               => esc_html__('新增工作分類', 'mua'),
    'new_item_name'              => esc_html__('新增工作分類名稱', 'mua'),
    'separate_items_with_commas' => esc_html__('用逗點分隔工作分類', 'mua'),
    'add_or_remove_items'        => esc_html__('新增或移除工作分類', 'mua'),
    'choose_from_most_used'      => esc_html__('選擇常用工作分類', 'mua'),
    'not_found'                  => esc_html__('找不到工作分類', 'mua'),
    'no_terms'                   => esc_html__('沒有工作分類', 'mua'),
    'filter_by_item'             => esc_html__('用工作分類篩選', 'mua'),
    'items_list_navigation'      => esc_html__('工作分類列表導航', 'mua'),
    'items_list'                 => esc_html__('工作分類列表', 'mua'),
    'most_used'                  => esc_html__('最常用', 'mua'),
    'back_to_items'              => esc_html__('回到工作分類', 'mua'),
    'text_domain'                => esc_html__('mua', 'mua'),
  ];
  $args = [
    'label'              => esc_html__('工作分類', 'mua'),
    'labels'             => $labels,
    'description'        => '',
    'public'             => true,
    'publicly_queryable' => true,
    'hierarchical'       => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'show_in_nav_menus'  => true,
    'show_in_rest'       => true,
    'show_tagcloud'      => true,
    'show_in_quick_edit' => true,
    'show_admin_column'  => true,
    'query_var'          => true,
    'sort'               => false,
    'meta_box_cb'        => 'post_tags_meta_box',
    'rest_base'          => '',
    'rewrite'            => [
      'with_front'   => false,
      'hierarchical' => false,
    ],
  ];
  register_taxonomy('job_cat', ['mua_jobs'], $args);
}


add_action('init', 'register_mua_job_post_type');
function register_mua_job_post_type()
{
  $labels = [
    'name'                     => esc_html__('刊登工作', 'mua'),
    'singular_name'            => esc_html__('刊登工作', 'mua'),
    'add_new'                  => esc_html__('刊登工作', 'mua'),
    'add_new_item'             => esc_html__('刊登工作', 'mua'),
    'edit_item'                => esc_html__('編輯工作', 'mua'),
    'new_item'                 => esc_html__('刊登工作', 'mua'),
    'view_item'                => esc_html__('查看工作', 'mua'),
    'view_items'               => esc_html__('查看工作', 'mua'),
    'search_items'             => esc_html__('搜尋工作', 'mua'),
    'not_found'                => esc_html__('找不到工作', 'mua'),
    'not_found_in_trash'       => esc_html__('在垃圾桶中找不到工作', 'mua'),
    'parent_item_colon'        => esc_html__('父工作:', 'mua'),
    'all_items'                => esc_html__('全部刊登工作', 'mua'),
    'archives'                 => esc_html__('工作彙整頁', 'mua'),
    'attributes'               => esc_html__('工作屬性', 'mua'),
    'insert_into_item'         => esc_html__('插入致工作', 'mua'),
    'uploaded_to_this_item'    => esc_html__('上傳到這個工作', 'mua'),
    'featured_image'           => esc_html__('封面圖', 'mua'),
    'set_featured_image'       => esc_html__('設定封面圖', 'mua'),
    'remove_featured_image'    => esc_html__('移除封面圖', 'mua'),
    'use_featured_image'       => esc_html__('用作封面圖', 'mua'),
    'menu_name'                => esc_html__('刊登工作', 'mua'),
    'filter_items_list'        => esc_html__('篩選工作列表', 'mua'),
    'filter_by_date'           => esc_html__('', 'mua'),
    'items_list_navigation'    => esc_html__('工作列表導航', 'mua'),
    'items_list'               => esc_html__('工作列表', 'mua'),
    'item_published'           => esc_html__('工作已發佈', 'mua'),
    'item_published_privately' => esc_html__('工作已私人發佈', 'mua'),
    'item_reverted_to_draft'   => esc_html__('工作退回為草稿', 'mua'),
    'item_scheduled'           => esc_html__('工作刊登排程', 'mua'),
    'item_updated'             => esc_html__('刊登工作已更新', 'mua'),
    'text_domain'              => esc_html__('mua', 'mua'),
  ];
  $args = [
    'label'               => esc_html__('刊登工作', 'mua'),
    'labels'              => $labels,
    'description'         => '',
    'public'              => true,
    'hierarchical'        => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'show_in_rest'        => true,
    'query_var'           => true,
    'can_export'          => true,
    'delete_with_user'    => true,
    'has_archive'         => true,
    'rest_base'           => '',
    'show_in_menu'        => true,
    'menu_position'       => 6,
    'menu_icon'           => 'dashicons-hammer',
    'capability_type'     => 'post',
    'supports'            => ['title', 'editor', 'author'],
    'taxonomies'          => ['job_cat'],
    'rewrite'             => [
      'with_front' => false,
    ],
  ];

  register_post_type('mua_jobs', $args);
}
function my_custom_status_creation()
{
  register_post_status('cancel', array(
    'label'                     => _x('取消', 'post'),
    'label_count'               => _n_noop('取消 <span class="count">(%s)</span>', '取消 <span
  class="count">(%s)</span>'),
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true
  ));

  register_post_status('filled', array(
    'label'                     => _x('已徵到', 'post'),
    'label_count'               => _n_noop('已徵到 <span class="count">(%s)</span>', '已徵到 <span
  class="count">(%s)</span>'),
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true
  ));
}
add_action('init', 'my_custom_status_creation');

function add_to_post_status_dropdown()
{
  global $post;
  if ($post->post_type != 'mua_jobs')
    return false;
    switch ($post->post_status) {
      case 'cancel':
        $status_text = '取消';
        $status = "jQuery( '#post-status-display' ).text( '" . $status_text . "' );
  jQuery( 'select[name=\"post_status\"]' ).val('" . $post->post_status . "');";

        break;
        case 'filled':
          $status_text = '已徵到';
          $status = "jQuery( '#post-status-display' ).text( '" . $status_text . "' );
          jQuery( 'select[name=\"post_status\"]' ).val('" . $post->post_status . "');";
          break;

      default:
      $status_text = '';
      $status = '';
        break;
    }

    echo "<script>
    jQuery(document).ready( function() {
    jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"cancel\">取消</option><option value=\"filled\">已徵到</option>' );
    " . $status . "
    });
    </script>";
}
add_action('post_submitbox_misc_actions', 'add_to_post_status_dropdown');
function custom_status_add_in_quick_edit()
{
  global $post;
  if ($post->post_type != 'mua_jobs')
    return false;
  echo "<script>
  jQuery(document).ready( function() {
  jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"cancel\">取消</option><option value=\"filled\">已徵到</option>' );

  });
  </script>";
}
add_action('admin_footer-edit.php', 'custom_status_add_in_quick_edit');
function display_archive_state($states)
{
  global $post;
  $arg = get_query_var('post_status');
  if ($arg != 'cancel') {
    if ($post->post_status == 'cancel') {
      echo "<script>
  jQuery(document).ready( function() {
  jQuery( '#post-status-display' ).text( '取消' );
  });
  </script>";
      return array('取消');
    }
  }elseif($arg != 'filled') {
    if ($post->post_status == 'filled') {
      echo "<script>
  jQuery(document).ready( function() {
  jQuery( '#post-status-display' ).text( '已徵到' );
  });
  </script>";
      return array('已徵到');
    }
  }
  return $states;
}
add_filter('display_post_states', 'display_archive_state');

include_once 'mb-setting.php';
include_once 'mb-taxonomy.php';
