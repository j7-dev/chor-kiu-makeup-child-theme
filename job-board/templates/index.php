<?php
$qo = get_queried_object();
if ($qo->taxonomy === 'job_cat') {
	$is_job_cat_page = true;
} else {
	$is_job_cat_page = false;
}
?>

<?php if ($is_job_cat_page) : ?>
	<div class="px-1r container section-title-container">
		<h1 class="text-center">分類: <?= $qo->name ?></h1>
		<a class="btn btn-link" href="<?= site_url('job-board') ?>">
			<i class="icon-angle-left"></i> 返回工作列表
		</a>
	</div>
<?php endif; ?>

<div class="px-1r container section-title-container">
	<h3 class="section-title section-title-normal">
		<span class="section-title-main border-0">Job Board</span>
		<a class="mua-post-job" style="cursor:pointer">
			刊登工作<i class="icon-angle-right"></i>
		</a>
	</h3>
	<?php if (!is_user_logged_in()) : ?>
		<p class="mt-2r">♡ 刊登工作前請先登入 ♡</p>
		<p class="mb-0">♡ 如未有會員帳號，我們可替你刊登工作，請 <a target="_blank" href="https://wa.me/852924094 17/?text=你好我係 MUA 睇到你，我想找化妝師">WHATSAPP 92409417 ♡</a></p>
	<?php endif; ?>
</div>


<?php
$args = array(
	'taxonomy' => 'job_cat',
	'hide_empty' => false,
	'meta_key' => 'is_show',
	'meta_value' => '1',
	'orderby' => 'term_id',
);

$terms = get_terms($args);

?>
<div class="row row-full-width align-equal align-center mx-0 mt-3r">
	<?php foreach ($terms as $key => $term) :
		$image = get_term_meta($term->term_id, 'image', true);
		$src = wp_get_attachment_image_src($image, 'full');
	?>
		<div class="col medium-4 small-12 large-4 col-hover-focus">
			<div class="col-inner ratio-3x4">
				<div class="banner has-hover bg-blur h-100">
					<div class="banner-inner fill">
						<div class="banner-bg fill">
							<div class="bg fill bg-fill bg-loaded"></div>
							<div class="overlay"></div>
						</div>
						<div class="banner-layers container">
							<a href="<?= get_term_link($term, 'job_cat'); ?>" class="fill">
								<div class="fill banner-link bg-image-cover" style="background-image:url('<?= $src[0] ?>')"></div>
							</a>
							<div class="text-box banner-layer x50 md-x50 lg-x50 y75 md-y75 lg-y75 res-text w-100">
								<div class="text dark">
									<div class="text-inner text-center">
										<h2 class="uppercase shadow"><strong><?= $term->name ?></strong></h2>
										<h4 class="uppercase shadow"><?= $term->description ?></h4>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<div class="row">
	<div class="col text-center">
		<a class="button primary is-large mua-post-job" style="border-radius:99px;cursor:pointer">
			刊登工作
		</a>
	</div>
</div>

<?php
echo do_shortcode('[job_board_table]');
