<?php
/**
 * The blog template file.
 *
 * @package flatsome
 */

get_header();

?>
<div id="content" class="blog-wrapper blog-single page-wrapper">

	<?php get_template_part( 'template-parts/posts/mua_job' ); ?>
</div>

<?php get_footer();
