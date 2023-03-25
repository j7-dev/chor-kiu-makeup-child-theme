<?php

namespace MUA;

class MyAccount
{

	public function __construct()
	{
		add_filter('woocommerce_account_menu_items', [$this, 'muajb_add_my_account_links'], 50);

		add_action('init', [$this, 'mua_add_endpoint']);

		add_action('woocommerce_account_jobs-record_endpoint', [$this, 'muajb_my_account_jobsRecord_endpoint_content']);
		add_action('woocommerce_account_mua_jobs_endpoint', [$this, 'muajb_my_account_muajobs_endpoint_content']);
		add_action('woocommerce_account_edit-job_endpoint', [$this, 'muajb_my_account_editjob_endpoint_content']);
	}
	/**
	 * 移除選單 & 改名
	 */

	function muajb_add_my_account_links($menu_links)
	{
		$menu_links['jobs-record'] = '工作申請記錄';

		$menu_links['mua_jobs'] = 'Job Board 刊登工作';


		return $menu_links;
	}


	function mua_add_endpoint()
	{
		add_rewrite_endpoint('jobs-record', EP_PAGES);
		add_rewrite_endpoint('mua_jobs', EP_PAGES);
		add_rewrite_endpoint('edit-job', EP_PAGES);
	}


	function muajb_my_account_jobsRecord_endpoint_content()
	{
		get_template_part('job-board/templates/jobs-record', null, []);
	}


	function muajb_my_account_muajobs_endpoint_content()
	{
		get_template_part('job-board/templates/my-account', null, []);
	}

	function muajb_my_account_editjob_endpoint_content()
	{
		get_template_part('job-board/templates/edit-job', null, []);
	}

	static function get_action($post_id)
	{
		$status = get_post_status($post_id);

?>
		<?php if ($status !== 'filled' && $status !== 'cancel') : ?>
			<a title="編輯" href="<?php
													echo add_query_arg(
														[
															'post_id' => $post_id,
														],
														wc_get_account_endpoint_url('edit-job')
													);
													?>" class="button primary mx-0 my-0 "> <span class="dashicons dashicons-edit"></span> </a>
		<?php endif; ?>
		<a title="查看" target="_blank" href="<?= get_the_permalink($post_id); ?>" class="button primary mx-0 my-0"> <span class="dashicons dashicons-visibility"></span> </a>
<?php
	}
}

new MyAccount();
