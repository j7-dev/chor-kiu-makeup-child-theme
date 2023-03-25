<?php

/**
 * @example [mua_register_form]
 */

namespace MUA;

class Register_Form
{


    // const ACTION = 'handle_file_upload';
    // const NONCE = 'register_form';



    /**
     * Register the AJAX handler class with all the appropriate WordPress hooks.
     */
    public static function register()
    {
        /**
         * 案需載入，非指定頁面返回
         */
        // if (strpos($_SERVER['REQUEST_URI'], 'my-account') == false) {
        //     return;
        // }

        $handler = new self();

        // Assets
        add_action('wp_enqueue_scripts', array($handler, 'register_script'));
        add_action('wp_head', array($handler, 'add_head'));

        // Shotcode
        add_shortcode('mua_register_form', array($handler, 'register_form_function'));

        //add_action('wp_ajax_' . self::ACTION, array($handler, 'handle'));
        //add_action('wp_ajax_nopriv_' . self::ACTION, array($handler, 'handle'));
    }

    /**
     * Register our AJAX JavaScript.
     */
    public function register_script()
    {
        global $post;
        // If has shortcode
        if (has_shortcode($post->post_content, 'mua_register_form')) {
            wp_enqueue_script('intl-tel-input', get_stylesheet_directory_uri() . '/includes/register_form/js/intlTelInput.min.js', array('jquery'));
            wp_enqueue_style('intl-tel-input', get_stylesheet_directory_uri() . '/includes/register_form/css/intlTelInput.min.css');
            wp_enqueue_script('register_form', get_stylesheet_directory_uri() . '/includes/register_form/index.js', array('jquery'), THEME_VER, true);


            // wp_enqueue_script('jquery-ui-dialog');

            // wp_localize_script(
            //     'register_form',
            //     'userData',
            //     self::get_ajax_data()
            // );
        }
    }

    /**
     * Get the AJAX data that WordPress needs to output.
     *
     * @return array
     */
    // private function get_ajax_data()
    // {
    //     $avatars = get_user_meta(get_current_user_id(), '_vendor_profile_image', true);

    //     $avatars_url = wp_get_attachment_image_url($avatars, 'full');
    //     $avatars = empty($avatars) ? ['http://1.gravatar.com/avatar/1d78a79a5ceb2653148a60cae7fe22d7?s=70&d=mm&r=g'] : [$avatars_url];

    //     return array(
    //         'ajax_url' => admin_url('admin-ajax.php'),
    //         'nonce' => wp_create_nonce(self::NONCE),
    //         'action' => self::ACTION,
    //         'avatars' => $avatars,
    //         'user_id' => get_current_user_id(),
    //     );
    // }

    /**
     * Handles the AJAX request for my plugin.
     */
    // public function handle()
    // {
    //     check_ajax_referer('register_form', 'nonce');
    //     // it allows us to use wp_handle_upload() function
    //     require_once(ABSPATH . 'wp-admin/includes/file.php');

    //     // you can add some kind of validation here
    //     if (empty($_FILES['user_avatar'])) {
    //         wp_die('No files selected.');
    //     }

    //     $upload = wp_handle_upload(
    //         $_FILES['user_avatar'],
    //         array('test_form' => false)
    //     );

    //     if (!empty($upload['error'])) {
    //         wp_die($upload['error']);
    //     }

    //     // it is time to add our uploaded image into WordPress media library
    //     $attachment_id = wp_insert_attachment(
    //         array(
    //             'guid'           => $upload['url'],
    //             'post_mime_type' => $upload['type'],
    //             'post_title'     => basename($upload['file']),
    //             'post_content'   => '',
    //             'post_status'    => 'inherit',
    //         ),
    //         $upload['file']
    //     );

    //     if (is_wp_error($attachment_id) || !$attachment_id) {
    //         wp_die('Upload error.');
    //     }

    //     // update medatata, regenerate image sizes
    //     require_once(ABSPATH . 'wp-admin/includes/image.php');

    //     wp_update_attachment_metadata(
    //         $attachment_id,
    //         wp_generate_attachment_metadata($attachment_id, $upload['file'])
    //     );
    //     update_user_meta($_POST['user_id'], '_vendor_profile_image', $attachment_id);
    //     update_user_meta($_POST['user_id'], '_vendor_image', $attachment_id);

    //     die();
    // }



    function add_head()
    {
?>
        <style>
.iti--allow-dropdown{
    width: 100%;
}
        </style>
    <?php
    }

    static function register_form_function($atts = array())
    {

        // set up default parameters
        extract(shortcode_atts(array(
            'class' => '',
            'value' => "",
        ), $atts));

        $html = '';
        ob_start();
    ?>

        <div class="row row-main align-center">
            <div class="col medium-6 small-12 large-6">
            <h2><?php esc_html_e('Register', 'woocommerce'); ?></h2>

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="first_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="first_name" autocomplete="first_name" value="<?php echo (!empty($_POST['first_name'])) ? esc_attr(wp_unslash($_POST['first_name'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                                        ?>
                    </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                                        ?>
                    </p>

                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="billing_phone"><?php esc_html_e('Phone', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="billing_phone" autocomplete="billing_phone" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                                        ?>
                    </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide" style="margin-top:2rem;">
                    <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                        ?>
                </p>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                    </p>

                <?php else : ?>

                    <p><?php esc_html_e('A link to set a new password will be sent to your email address.', 'woocommerce'); ?></p>

                <?php endif; ?>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button style="width:100%;" type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>
            </div>
        </div>

<?php
        $html .= ob_get_clean();
        return $html;
    }
}

Register_Form::register();
