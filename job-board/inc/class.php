<?php

class JobBoard
{

	private $vesion = '1.0.0';

	public function __construct()
	{
		add_shortcode('job_board', [$this, 'job_board_view']);
		add_shortcode('job_card', [$this, 'job_card']);

		add_shortcode('job_board_table', [$this, 'job_table_view']);

		add_action('wp_enqueue_scripts', [$this, 'enqueue_css_js']);

		add_action('save_post_mua_jobs', [$this, 'change_post_name_after_created'], 300, 3);

		add_filter('comments_open', [$this, 'set_comments_open'], 10, 2);

		add_action('wp_footer', [$this, 'add_modal_html'], 10);

		add_shortcode('mua_post_job_button', [$this, 'mua_post_job_button_function']);
	}

	function change_post_name_after_created($post_id, $post, $update)
	{
		if (is_admin() && current_user_can('administrator')) return;
		//$update Whether this is an existing post being updated.
		if (!wp_is_post_revision($post_id) && !$update) {

			// unhook this function so it doesn't loop infinitely
			remove_action('save_post_mua_jobs', [$this, 'change_post_name_after_created'], 300, 3);


			$date = JobBoard::get_job_date($post_id);
			$time = JobBoard::get_job_time($post_id);
			$type = JobBoard::get_job_cat($post_id);
			$detail = get_post_meta($post_id, 'job_detail', true) ?: '';


			$job_setting = get_option('job-setting', ["need_approve" => 0]);
			$need_approve = $job_setting['need_approve'];

			$post_data = array(
				'ID'           => $post_id,
				'post_title'   => $date . ' ' . $type['text'] . ' ' . $time['text'] . ' ' . $detail,
				'post_name' => 'makeup-job',
				'post_status'       => ($need_approve) ? 'pending' : 'publish' // pending, publish
			);

			// Update the post into the database
			wp_update_post($post_data);

			$this->job_publish_notification($post_id);


			// re-hook this function
			add_action('save_post_mua_jobs', [$this, 'change_post_name_after_created'], 300, 3);
		}
	}

	function job_publish_notification($post_id)
	{
		$author_id = get_post_field('post_author', $post_id);
		$author_name = get_the_author_meta('display_name', $author_id);
		$author = '<a href="' . get_edit_user_link($author_id) . '" target="_blank">' . $author_name . ' (id: ' . $author_id . ') </a>';
		$to = get_option('admin_email');
		$subject = get_the_title($post_id);
		$body = '
    ' . $author . ' 刊登了新的工作 <a href="' . get_edit_post_link($post_id) . '" target="_blank">《' . get_the_title($post_id) . '》</a>' .
			'請前往審批: ' . get_edit_post_link($post_id);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($to, $subject, $body, $headers, array(''));
	}

	function enqueue_css_js()
	{
		wp_enqueue_style('dashicons');
		//global $post;
		//if (has_shortcode($post->post_content, 'job_board')) {

		// jquery-confirm
		// wp_enqueue_style('jquery-confirm', get_stylesheet_directory_uri() . '/job-board/assets/jquery-confirm/jquery-confirm.min.css');
		//wp_enqueue_script('jquery-confirm', get_stylesheet_directory_uri() . '/job-board/assets/jquery-confirm/jquery-confirm.min.js', array('jquery'));

		// custom
		wp_enqueue_script('post-job', get_stylesheet_directory_uri() . '/job-board/assets/js/post-job.js', array('jquery'), $this->vesion, true);

		// bootstrap
		wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/job-board/assets/bootstrap/dist/css/bootstrap.min.css', array(), $this->vesion, 'all');
	}



	function set_comments_open($open, $post_id)
	{

		$post = get_post($post_id);

		if ('mua_jobs' == $post->post_type) {
			$open = true;
		}

		return $open;
	}

	static function is_author($post_id, $user_id = '')
	{
		if (empty($user_id)) {
			$user_id = get_current_user_id();
		}
		$author_id = get_post_field('post_author', $post_id);

		return $author_id == $user_id;
	}

	static function job_board_view()
	{
		$html = '';
		ob_start();
		if (!empty($_GET['rwmb-form-submitted'])) {
			get_template_part('job-board/templates/submitted', null, []);
		} else {
			get_template_part('job-board/templates/index', null, []);
		}
		$html .= ob_get_clean();
		return $html;
	}

	public function job_card($atts = array())
	{

		extract(shortcode_atts(array(
			'num' => '3',
			'col' => '3'
		), $atts));

		switch ($col) {
			case '1':
				$col_class = 'small-12';
				break;
			case '2':
				$col_class = 'medium-6 small-12 large-6';
				break;
			case '3':
				$col_class = 'medium-4 small-12 large-4';
				break;
			case '4':
				$col_class = 'medium-6 small-12 large-3';
				break;

			default:
				$col_class = 'medium-4 small-12 large-4';
				break;
		}


		$jobs = get_posts(array(
			'post_type' => 'mua_jobs',
			'post_status' => 'publish',
			'posts_per_page' => $num,
			'orderby' => 'date',
			'order' => 'DESC',
		));
		$html = '';

		ob_start();
?>
		<div class="row row-full-width align-equal align-center mx-0 mt-3r">
			<?php foreach ($jobs as $job) : ?>
				<div class="col col-hover-focus <?= $col_class ?>">
					<?php self::get_job_overall_view($job->ID); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		$html .= ob_get_clean();

		return $html;
	}

	static function job_table_view()
	{
		$html = '';
		ob_start();
		get_template_part('job-board/templates/table', null, []);
		$html .= ob_get_clean();
		return $html;
	}
	static function add_new_job_view($type = 'modal')
	{
		if ($type == 'modal') : ?>
			<div id="job-modal" class="modal">
				<div class="container relative bg-white p-2r">
					<span class="dashicons dashicons-no-alt close-modal"></span>
					<div class="inner last-reset">
						<div class="row align-middle align-center">
							<div class="col">
								<?php
								// 未登入就顯示登入畫面
								if (!is_user_logged_in()) : ?>

									<div class="row align-equal">
										<div class="col justify-content-center">
											<img src="<?= flatsome_option('site_logo') ?>" style="max-width:15rem" class="header_logo header-logo" />
										</div>
									</div>

									<hr class="mb-3r">

									<div class="row align-equal">
										<div class="col large-6">
											<div class="col-inner d-flex flex-column justify-content-center align-items-lg-center">
												<p>刊登工作前請先登入</p>
												<p>如未有會員帳號，我們可替你刊登工作</p>
												<p>按此 <a target="_blank" href="https://wa.me/852924094 17/?text=你好我係 MUA 睇到你，我想找化妝師">WhatsApp 92409417</a></p>
											</div>
										</div>
										<div class="col large-6">
											<div class="col-inner">
												<?php
												if (class_exists('WooCommerce', false)) {
													// 載入woocommerce登入畫面
													wc_get_template('myaccount/form-login.php');
												} else {
													// 載入wordpress登入畫面
													wp_login_form();
												} ?>
											</div>
										</div>
									</div>
								<?php else : ?>
									<h2 class="uppercase text-center mb-3r">Job Board</h2>
									<?= do_shortcode("[mb_frontend_form id='job']") ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php elseif ($type == 'button') : ?>
			<span class="mua-post-job button primary mx-0 my-0"> <span>刊登新工作</span> </span>
		<?php endif;
	}
	static function get_job_overall_view($post_id)
	{

		$job_cats = get_the_terms($post_id, 'job_cat');
		$job_cat = $job_cats[0]->name;
		?>

		<div class="entry-content single-page w-100">
			<div class="w-100 bg-white text-center border border-gray-200 py-1r" style="border-radius: 0.625rem 0.625rem 0rem 0rem">
				<h1 class="h4"><?= get_the_title($post_id) ?></h1>
			</div>
			<div class="bg-gray-200 w-100 p-2r" style="border-radius: 0rem 0rem 0.625rem 0.625rem">

				<p class="mb-2">
					<span class="me-2 mt-1 dashicons dashicons-businessperson"></span>POSTED BY : <?= get_the_author() ?>
				</p>
				<p class="mb-2">
					<span class="me-2 mt-1 dashicons dashicons-tag"></span>TYPE : <a href="<?= get_term_link($job_cats[0], 'job_cat') ?>"><?= $job_cats[0]->name ?></a>
				</p>
				<p class="mb-2">
					<span class="me-2 mt-1 dashicons dashicons-list-view"></span>JOB DETAILS : <?= get_post_meta($post_id, 'job_detail', true) ?>
				</p>
				<p class="mb-2">
					<span class="me-2 mt-1 dashicons dashicons-calendar-alt"></span>DATE : <?= JobBoard::get_job_date($post_id) ?>
				</p>
				<p class="mb-2">
					<span class="me-2 mt-1 dashicons dashicons-money-alt"></span>BUDGET : HKD <?= get_post_meta($post_id, 'job_budget', true) ?>
				</p>
			</div>
		</div>
	<?php
	}
	static function get_job_time($post_id)
	{
		$time = get_post_meta($post_id, 'job_time', true);
		switch ($time) {
			case 'fullday':
				$time_text = '全日';
				break;
			case 'halfday':
				$time_text = '半日';
				break;
			default:
				$time_text = '';
				break;
		}
		return [
			'slug' => $time,
			'text' => $time_text,
		];
	}
	static function get_job_cat($post_id)
	{
		$type = get_the_terms($post_id, 'job_cat');

		return [
			'slug' => $type[0]->slug,
			'text' => $type[0]->name,
		];
	}

	static function get_job_date($post_id)
	{
		$date = get_post_meta($post_id, 'job_date', true);
		// $date = date('Y-m-d', $date);
		return $date;
	}

	static function get_job_status($post_id, $with_html = true)
	{
		$status = get_post_status($post_id);
		switch ($status) {
			case 'publish':
				$class = 'bg-primary';
				break;
			case 'pending':
				$class = 'bg-warning';
				break;
			case 'filled':
				$class = 'bg-success';
				break;
			case 'cancel':
				$class = 'bg-gray-700';
				break;

			default:
				$class = 'bg-danger';
				break;
		}
		if ($with_html) {
			$status = '<span class="px-2 py-1 rounded-2 text-white is-xsmall ' . $class . '"> <span>' . $status . '</span> </span>';
		}
		return $status;
	}

	static function the_job_table_order($field)
	{
		$meta_key = $field['meta_key'];
		$name = $field['name'];
	?>

		<a href="<?php
							if (!empty($_GET['order']) && !empty($_GET['orderby'])) {

								if ($_GET['order'] == 'desc' && $_GET['orderby'] == $meta_key) {
									echo add_query_arg(
										array(
											'orderby' => $meta_key,
											'order' => 'asc'
										)
									);
								} else {
									echo add_query_arg(
										array(
											'orderby' => $meta_key,
											'order' => 'desc'
										)
									);
								}
							} else {
								echo add_query_arg(
									array(
										'orderby' => $meta_key,
										'order' => 'desc'
									)
								);
							}

							?>"><?= $name ?>
			<?php if (empty($_GET['order']) || $_GET['orderby'] !== $meta_key) : ?>

				<span class="mt-n1 dashicons dashicons-sort"></span>
			<?php else : ?>
				<span class="mt-n1 dashicons dashicons-arrow-<?= ($_GET['order'] == 'asc') ? 'up' : 'down' ?>"></span>
			<?php endif; ?>
		</a>

<?php
	}

	function add_modal_html()
	{
		JobBoard::add_new_job_view();
	}

	function mua_post_job_button_function()
	{
		return JobBoard::add_new_job_view('button');
	}
}

new JobBoard();
