<?php

/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package flatsome
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
  return;
}
?>

<?php do_action('flatsome_before_comments'); ?>

<div id="comments" class="comments-area mt-3r large-10 col">

  <?php // You can start editing here -- including this comment!
  ?>

  <?php if (have_comments()) : ?>
    <h3 class="comments-title uppercase">

      <?php
      global $post;
      printf( // WPCS: XSS OK.
        esc_html(_nx('&ldquo;%2$s&rdquo; 的報價', '&ldquo;%2$s&rdquo; 有 %1$s 個報價 ', get_comments_number(), 'comments title', 'flatsome')),
        number_format_i18n(get_comments_number()),
        '<span>' . get_the_title() . '</span>'
      );
      ?>
    </h3>

    <ol class="comment-list">
      <?php
      wp_list_comments(array('callback' => 'flatsome_comment'));
      ?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through?
    ?>
      <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'flatsome'); ?></h2>
        <div class="nav-links nex-prev-nav">
          <div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'flatsome')); ?></div>
          <div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'flatsome')); ?></div>
        </div>
      </nav>
    <?php endif; // Check for comment navigation.
    ?>

  <?php endif; // Check for have_comments().
  ?>

  <?php
  // If comments are closed and there are comments, let's leave a little note, shall we?
  if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
  ?>
    <p class="no-comments">已不接受報價</p>
  <?php endif; ?>

  <?php

  $comments_args = array(
    'class_container' => 'mt-3r',
    'label_submit' => '發表報價',
    'title_reply' => '我要報價',
    'comment_field' => sprintf(
      '<p class="comment-form-comment">%s %s</p>',
      '',
      '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea>'
    ),
  );

  comment_form($comments_args); ?>

</div>