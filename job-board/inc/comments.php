<?php


add_filter('get_avatar', 'link_to_vendor_page_on_avatar', 300, 2);
function link_to_vendor_page_on_avatar($avatar, $comment)
{
  if(is_numeric($comment) || is_string($comment)) return $avatar;
    $vendor = get_wcmp_vendor($comment->user_id);
    if ($vendor == false) return $avatar;
    $shop_url = $vendor->permalink;
    return '<a href="' . $shop_url . '">' . $avatar . '</a>';

}


add_filter('get_comment_author_link', 'link_to_vendor_page_on_name', 300, 3);
function link_to_vendor_page_on_name($return, $author, $comment_ID)
{
  $comment = get_comment($comment_ID);

  $vendor = get_wcmp_vendor($comment->user_id);
  if ($vendor == false) return $return;
  $shop_url = $vendor->permalink;
  return '<a href="' . $shop_url . '">' . $return . '</a>';
}
