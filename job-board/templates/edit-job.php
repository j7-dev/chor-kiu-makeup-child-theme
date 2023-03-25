<?php
$is_author = JobBoard::is_author($_GET['post_id']);
if(!$is_author){
  echo '你不是這個職缺的作者';
  wp_die();
}
if(!empty($_GET['cancel'])):

  if($_GET['cancel'] == 1 ):
$postarr = array(
  'ID' => $_GET['post_id'],
  'post_status' => 'cancel'
);
wp_update_post( $postarr );
?>
<p class="text-black"><span class="mt-1 dashicons dashicons-warning me-2"></span>已經下架此工作</p>
<?php else: ?>
  <?php endif; ?>
<?php else: ?>
<p class="mb-3r"><a class="text-primary " href="<?= wc_get_account_endpoint_url('mua_jobs') ?>">《 返回工作列表</a></p>
<p class="text-danger"><span class="mt-1 dashicons dashicons-warning me-2"></span> 編輯過後的工作需要重新審核</a></p>
<?php
$post_id = $_GET['post_id'];
echo do_shortcode("[mb_frontend_form confirmation='已經更新您的修改，請靜待審核通知' id='job' post_id='" . $post_id . "' ]");
?>

<a href="<?php
echo add_query_arg(
  array(
    'cancel' => '1',
  )
);
?>">
<p class="text-danger"><span class="mt-1 dashicons dashicons-warning me-2"></span>下架此份工作，下架後此工作將被封存</p>
</a>

<script>
  (function($) {
    $('.woocommerce-MyAccount-navigation-link--mua_jobs').addClass('is-active active');

    const inputHtml = `<input value="pending" type="hidden" name="post_status">`

    $('#job').after(inputHtml)
  })(jQuery)
</script>

<?php endif; ?>