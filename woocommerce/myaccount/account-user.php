<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
?>
<div class="account-user circle" style="display:flex;">
    <?= do_shortcode('[dd_file_upload]') ?>
    <span class="user-name inline-block" style="align-self: center;">
        <?php
        echo $current_user->display_name;
        ?>
        <em class="user-id op-5"><?php echo '#' . $user_id; ?></em>
    </span>

    <?php do_action('flatsome_after_account_user'); ?>
</div>