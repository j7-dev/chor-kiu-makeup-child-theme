<?php
$post_id = $_GET['post_id'];
JobBoard::get_job_overall_view($post_id);


$args = array(
  'post_id' => $post_id,
  'order' => 'ASC',
);

$comment_query = new WP_Comment_Query($args);
$comments = $comment_query->get_comments();

?>

<h3 class="section-title section-title-center my-3r"><b></b><span class="section-title-main">請挑選工作人選</span><b></b></h3>
<div class="row">
  <?php foreach ($comments as $key => $comment) :
    //var_dump($comment);
    $comment_author = get_user_by('email', $comment->comment_author_email);
    $comment_author_id = $comment_author->data->ID;
    $comment_author_phone = get_user_meta($comment_author_id, 'billing_phone', true);
  ?>
    <div class="col small-12">
      <div class="col-inner">
        <div class="icon-box testimonial-box icon-box-left text-left">
          <div class="icon-box-img testimonial-image circle" style="width: 121px"> <img width="280" height="280" src="<?= get_avatar_url($comment_author_id) ?>" class="attachment-thumbnail size-thumbnail lazy-load-active">
            <div class="testimonial-meta pt-half pt-0 my-1 text-center"> <strong class="testimonial-name test_name"><?= $comment->comment_author ?></strong></div>
<div class="text-center">
<a class="button primary"> <span>決定錄用</span> </a>
</div>
          </div>
          <div class="icon-box-text p-last-0">
            <p>
              <span class="mt-1 dashicons dashicons-email"></span>
              <?= $comment->comment_author_email ?>
              <br>
              <?php if (!empty($comment_author_phone)) : ?>
                <span class="mt-1 dashicons dashicons-phone"></span>
                <?= $comment_author_phone ?>
              <?php endif; ?>
            </p>
            <div class="testimonial-text line-height-small italic test_text first-reset last-reset">
              <?= wpautop($comment->comment_content); ?>

            </div>
          </div>
        </div>
      </div>
    </div>

  <?php endforeach; ?>
</div>