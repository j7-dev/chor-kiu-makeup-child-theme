<?php
do_action('flatsome_before_blog');
?>

<div class="row align-center">
  <div class="large-10 col">
  <p class="mb-3r"><a class="text-primary " href="<?= site_url('job-board') ?>">《 返回工作列表</a></p>
    <?php
    if (is_single()) :
      get_template_part('template-parts/posts/single','job');
      comments_template('/comments-jobs.php');
    endif;  ?>
  </div>

</div>

<?php do_action('flatsome_after_blog');
