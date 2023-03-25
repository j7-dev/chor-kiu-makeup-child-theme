<?php
defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: wcmp_before_main_content.
 *
 */

do_action('wcmp_before_main_content');



global $WCMp;
$vendor_id = wcmp_find_shop_page_vendor();  //5
$vendor = get_wcmp_vendor($vendor_id);
$display_name = $vendor->user_data->first_name;

$meta_keys = ['vendor_profile_image', 'vendor_exp', 'vendor_jobtitle', 'vendor_school', 'vendor_phone', 'vendor_fb_profile', 'vendor_twitter_profile', 'vendor_linkdin_profile', 'vendor_youtube', 'vendor_instagram', 'vendor_country_code', 'vendor_country', 'vendor_address_1', 'vendor_other', 'vendor_charge'];
foreach ($meta_keys as $meta_key) {
	$$meta_key = get_user_meta($vendor_id, '_' . $meta_key, true) ?? '';
}
$vendor_img_url = wp_get_attachment_image_url($vendor_profile_image, 'full', true) ?: $WCMp->plugin_url . 'assets/images/WP-stdavatar.png';
$address = $vendor_country . $vendor_address_1;

$vendor_email = $vendor->user_data->user_email;
$page_title = get_user_meta($vendor_id, '_vendor_page_title', true);

$wcmp_vendor_page_text_color = get_option(
	'wcmp_vendor_page_text_color',
	'#f1bb97'
);
?>
<style>
	.shop-page-title.category-page-title.page-title {
		display: none;
	}

	.row {
		max-width: unset;
	}

	.vendor_content i {
		color: #fff;
		width: 36px;
		height: 36px;
		background-color: #fafafa;
		border-radius: 100%;
		margin: 0rem 0.5rem;
		line-height: 36px;
	}

	.avatarVendor {
		width: 300px;
		height: 300px;
		border-radius: 100%;
		object-fit: cover;
	}

	.title_line {
		position: relative;
	}

	.title_line::after {
		content: '';
		height: 1px;
		width: 100%;
		background-color: #ccc;
		display: block;
		position: absolute;
		top: calc(50% + 1px);
		left: 0px;
		z-index: 1;
	}

	.title_line h2 {
		background-color: #fff;
		display: inline;
		z-index: 2;
		position: relative;
		padding: 0px 2rem;
	}

	.portfolio article img {
		aspect-ratio: 1/1;
		width: 100%;
		object-fit: cover;
	}

	.accordionVendor .accordion-title {
		text-decoration: unset;
		border-color: #444;
	}

	.accordionVendor .accordion-item {
		border: none;
	}

	.accordionVendor .accordion-title.active,
	.accordionVendor .accordion-title.active .icon-angle-down,
	.accordionVendor .accordion-title.active span,
	.portfolio article a,
	.portfolio article a h5 {
		color: <?= $wcmp_vendor_page_text_color ?>;
		font-family: inherit;
	}

	.accordionVendor .accordion-title.active {
		border-color: <?= $wcmp_vendor_page_text_color ?>;
	}

	.mt-3 {
		margin-top: 1rem;
	}

	.fw-bold {
		font-weight: bold;
	}
</style>


<section>
	<div class="container" style="padding-top:3rem">
		<div class="row">
			<div class="col medium-6 small-12 large-5 text-center flex-column ">
				<img src="<?= $vendor_img_url ?>" class="avatarVendor">
				<h2 class=" mt-3"><?= $display_name ?></h2>
				<p class=""><?= $vendor_jobtitle ?></p>
			</div>
			<style>
				@import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap');

				.vendor_page_title {
					font-family: 'Dancing Script', cursive !important;
					font-size: 2.5rem;
					color: #d31a82;
					font-weight: 700;
				}
			</style>
			<div class="col medium-6 small-12 large-7 text-center vendor_content">
				<p class="vendor_page_title"><?= $page_title ?></p>
				<p><?= !empty($display_name) ? ('Chief MUA：' . $display_name) : '' ?></p>
				<p><?= !empty($vendor_school) ? ('畢業學校：' . $vendor_school) : '' ?></p>
				<p><?= !empty($vendor_phone) ? ('Tel：' . $vendor_phone) : '' ?></p>
				<p><?= !empty($vendor_email) ? ('Email：<a href="mailto:' . $vendor_email . '" target="_blank">' . $vendor_email) . '</a>' : '' ?></p>
				<p><?= !empty($address) ? ('Address：' . $address) : '' ?></p>
				<em><?= !empty($vendor_exp) ? (' I am a makeup artist since ' . $vendor_exp) : '' ?> </em>


				<div class="mt-3">
					✨睇下我更多作品:
					<?php if (!empty($vendor_fb_profile)) : ?>
						<a href="<?= $vendor_fb_profile ?>" target="_blank"><i class="icon-facebook" style="background-color:#1877f2"></i></a>
					<?php endif; ?>
					<?php if (!empty($vendor_twitter_profile)) : ?>
						<a href="<?= $vendor_twitter_profile ?>" target="_blank"><i class="icon-twitter" style="background-color:#1da1f2"></i></a>
					<?php endif; ?>
					<?php if (!empty($vendor_linkdin_profile)) : ?>
						<a href="<?= $vendor_linkdin_profile ?>" target="_blank"><i class="icon-linkedin" style="background-color:#0077b5"></i></a>
					<?php endif; ?>
					<?php if (!empty($vendor_youtube)) : ?>
						<a href="<?= $vendor_youtube ?>" target="_blank"><i class="icon-youtube" style="background-color:#ff0000"></i></a>
					<?php endif; ?>
					<?php if (!empty($vendor_instagram)) : ?>
						<a href="<?= $vendor_instagram ?>" target="_blank"><i class="icon-instagram" style="background-color:#c13584"></i></a>
					<?php endif; ?>
				</div>

				<hr style="width:3rem;display: inline-block;" />
				<p>🙂 為了保障雙方利益，麻煩講聲係 MUA 網站睇到我 🙂</p>




			</div>

		</div>
	</div>
</section>

<section>
	<div class="container" style="margin-top:8rem">
		<div class="row">
			<div class="title_line text-center">
				<h2 class="fw-bold">我的作品集</h2>
			</div>
		</div>
		<div class="row portfolio" style="margin-top:3rem">
			<?php
			$args = array(
				'posts_per_page' => -1,
				'author' => $vendor_id,
				'post_type' => 'product',
				'post_status' => 'publish',
			);
			$get_vendor_products = get_posts($args);
			foreach ($get_vendor_products as $product) :

			?>
				<div class="col medium-6 small-6 large-3">
					<article id="product-<?= $product->ID ?>">
						<a href="<?= get_the_permalink($product->ID); ?>"><img src="<?= get_the_post_thumbnail_url($product->ID, 'full') ?>"></a>
						<a href="<?= get_the_permalink($product->ID); ?>">
							<h5><?= get_the_title($product->ID); ?></h5>
						</a>

					</article>
				</div>

			<?php endforeach; ?>

		</div>
	</div>

</section>


<section>
	<div class="container" style="margin-top:6rem">
		<div class="accordion accordionVendor" rel="">

			<?php if (!empty($vendor_charge)) : ?>
				<div class="accordion-item"><a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span>服務收費</span></a>
					<div class="accordion-inner">
						<?= wpautop($vendor_charge); ?>
						<p>＊＊ 為保障雙方利益，麻煩同化妝師講聲係 MUA 揾到呢個化妝價錢 ＊＊</p>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!empty($vendor_other)) : ?>
				<div class="accordion-item"><a href="#" class="accordion-title plain"><button class="toggle"><i class="icon-angle-down"></i></button><span>其他詳情</span></a>
					<div class="accordion-inner">
						<?= wpautop($vendor_other); ?>
						<p>＊＊ 為保障雙方利益，麻煩同化妝師講聲係 MUA 揾到呢個化妝價錢 ＊＊</p>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>

<br>
<br>
<br>
<br>
<?php

/**
 * Hook: wcmp_store_tab_contents.
 *
 * Output wcmp store widget
 */

//do_action('wcmp_store_tab_widget_contents');


/**
 * Hook: wcmp_after_main_content.
 *
 */
do_action('wcmp_after_main_content');

/**
 * Hook: wcmp_sidebar.
 *
 */
// deprecated since version 3.0.0 with no alternative available
// do_action( 'wcmp_sidebar' );


get_footer('shop');
