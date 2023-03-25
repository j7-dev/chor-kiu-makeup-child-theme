<?php

?>
<p class="text-end">
	<span class="mua-post-job button primary mx-0 my-0"> <span>刊登新工作</span> </span>
</p>

<div class="overflow-auto">
	<table class="table table-hover mt-3r" style="min-width:40rem;">
		<thead>
			<tr>

				<th scope="col" class="d-none d-md-table-cell">姓名</th>

				<th scope="col" style="width:200px;">工作</th>

				<th scope="col">狀態</th>

				<th scope="col"><?php JobBoard::the_job_table_order([
													'meta_key' => 'job_budget',
													'name' => '預算',
												]); ?></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php





			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$posts_per_page = 20;
			$args = array(
				'post_type'              => 'mua_jobs',
				'paged' => $paged,
				'post_status'            => array('publish', 'filled', 'pending', 'cancel'), // Also
				'posts_per_page'         => $posts_per_page, // use -1 for all post
				'orderby'                => 'date', // Also support: none,
				'order'                  => 'DESC', // Also support: ASC
				'author'                => get_current_user_id(),
			);


			if (!empty($_GET['order']) && !empty($_GET['orderby'])) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = $_GET['orderby'];
				$args['order'] = $_GET['order'];
				if ($_GET['orderby'] == 'id') {
					$args['orderby'] = 'ID';
					unset($args['meta_key']);
				}
			}

			$query = new WP_Query($args);
			$index = 0;

			if ($query->have_posts()) :
				while ($query->have_posts()) :
					$query->the_post();
					$index++;
					$post_id = get_the_ID();
					$date = JobBoard::get_job_date($post_id);
					$time = JobBoard::get_job_time($post_id);
					$budget = get_post_meta($post_id, 'job_budget', true);
					$status = JobBoard::get_job_status($post_id);


			?>
					<tr>
						<td class="py-1r d-none d-md-table-cell"><?= get_post_meta($post_id, 'job_name', true); ?></td>

						<td class="py-1r"><?= get_the_title() ?></td>

						<td class="py-1r"><?= $status ?></td>
						<td class="py-1r">HKD <?= $budget ?></th>
						<td class="py-1r">


							<?php MUA\MyAccount::get_action($post_id) ?>
							</th>
					</tr>

				<?php endwhile; ?>
			<?php else : ?>
				<tr>
					<td class="py-1r text-center" colspan='6'>
						<p><span class="dashicons dashicons-warning" style="font-size:3rem;width:3rem;height:3rem;"></span></p>
						<p>No Jobs Found.</p>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php
if ($query->max_num_pages > 1) { // check if the max number of pages is greater than 1
?>
	<ul class="page-numbers nav-pagination links text-center">
		<?php for ($i = 1; $i <= $query->max_num_pages; $i++) :
			if ($paged == $i) : ?>
				<li><span aria-current="page" class="page-number current"><?= $paged ?></span></li>
			<?php else : ?>
				<li><a class="page-number" href="<?= get_pagenum_link($i) ?>"><?= $i ?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
	</ul>
<?php } ?>
</div>


<?php
wp_reset_postdata();
