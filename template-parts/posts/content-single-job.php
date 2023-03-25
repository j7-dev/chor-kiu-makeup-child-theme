

<?php
$post_id = get_the_ID();
JobBoard::get_job_overall_view($post_id);
the_content(); ?>

  <?php
  wp_link_pages();
  ?>

  <?php if (get_theme_mod('blog_share', 1)) {
    // SHARE ICONS
    // echo '<div class="blog-share text-center">';
    // echo '<div class="is-divider medium"></div>';
    // echo do_shortcode('[share]');
    // echo '</div>';
  } ?>
</div>



<?php if (get_theme_mod('blog_single_next_prev_nav', 1)) :
  flatsome_content_nav('nav-below');
endif; ?>
