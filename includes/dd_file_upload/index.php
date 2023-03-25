<?php

/**
 * Drag & Drop File Upload
 * @see https://www.jqueryscript.net/form/drag-drop-upload.html
 */
class DD_File_Upload
{

    /**
     * @see https://carlalexander.ca/designing-class-wordpress-ajax-handler/
     * Action hook used by the AJAX class.
     *
     * @var string
     */
    const ACTION = 'handle_file_upload';


    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var string
     */
    const NONCE = 'dd_file_upload';



    /**
     * Register the AJAX handler class with all the appropriate WordPress hooks.
     */
    public static function register()
    {
        /**
         * 案需載入，非指定頁面返回
         */
        if (strpos($_SERVER['REQUEST_URI'], 'my-account') == false) {
            return;
        }

        $handler = new self();

        // Assets
        add_action('wp_enqueue_scripts', array($handler, 'register_script'));
        add_action('wp_head', array($handler, 'add_head'));

        // Shotcode
        add_shortcode('dd_file_upload', array($handler, 'dd_file_upload_function'));

        add_action('wp_ajax_' . self::ACTION, array($handler, 'handle'));
        add_action('wp_ajax_nopriv_' . self::ACTION, array($handler, 'handle'));

        // override user avartar
        add_filter('get_avatar_url',  array($handler, 'override_avatar_url'), 300, 3);
    }

    function override_avatar_url($url, $id_or_email, $args){
      if(is_email($id_or_email)){
        $user = get_user_by('email', $id_or_email);
      }else{
        $user = get_user_by('id', $id_or_email);
      }
      $user_avatar = get_user_meta($user->data->ID, 'user_avatar', true);
        $url = wp_get_attachment_image_src($user_avatar, 'thumbnail')[0];
        $avatars = empty($avatars) ? ['http://1.gravatar.com/avatar/1d78a79a5ceb2653148a60cae7fe22d7?s=70&d=mm&r=g'] : [$url];

      return $avatars[0];
    }

    /**
     * Register our AJAX JavaScript.
     */
    public function register_script()
    {

        // If is in My account dashboard page
        if (is_account_page()) {
            wp_enqueue_script('dd_file_upload', get_stylesheet_directory_uri() . '/includes/dd_file_upload/index.js', array(), THEME_VER, true);

            wp_enqueue_script('jquery-ui-dialog');

            wp_localize_script(
                'dd_file_upload',
                'userData',
                self::get_ajax_data()
            );
        }
    }

    /**
     * Get the AJAX data that WordPress needs to output.
     *
     * @return array
     */
    private function get_ajax_data()
    {
        $user_avatar = get_user_meta(get_current_user_id(), 'user_avatar', true);
        $url = wp_get_attachment_image_src($user_avatar, 'thumbnail')[0];
        $user_avatar = empty($user_avatar) ? ['http://1.gravatar.com/avatar/1d78a79a5ceb2653148a60cae7fe22d7?s=70&d=mm&r=g'] : [$url];

        return array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(self::NONCE),
            'action' => self::ACTION,
            'avatars' => $user_avatar,
            'user_id' => get_current_user_id(),
        );
    }

    /**
     * Handles the AJAX request for my plugin.
     */
    public function handle()
    {
        check_ajax_referer('dd_file_upload', 'nonce');
        // it allows us to use wp_handle_upload() function
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        // you can add some kind of validation here
        if (empty($_FILES['user_avatar'])) {
            wp_die('No files selected.');
        }

        $upload = wp_handle_upload(
            $_FILES['user_avatar'],
            array('test_form' => false)
        );

        if (!empty($upload['error'])) {
            wp_die($upload['error']);
        }

        // it is time to add our uploaded image into WordPress media library
        $attachment_id = wp_insert_attachment(
            array(
                'guid'           => $upload['url'],
                'post_mime_type' => $upload['type'],
                'post_title'     => basename($upload['file']),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ),
            $upload['file']
        );

        if (is_wp_error($attachment_id) || !$attachment_id) {
            wp_die('Upload error.');
        }

        // update medatata, regenerate image sizes
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        wp_update_attachment_metadata(
            $attachment_id,
            wp_generate_attachment_metadata($attachment_id, $upload['file'])
        );
        update_user_meta($_POST['user_id'], '_vendor_profile_image', $attachment_id);
        update_user_meta($_POST['user_id'], 'user_avatar', $attachment_id);

        die();
    }

    /**
     * Sends a JSON response with the details of the given error.
     *
     * @param WP_Error $error
     */
    // private function send_error(\WP_Error $error)
    // {
    //     wp_send_json(array(
    //         'code' => $error->get_error_code(),
    //         'message' => $error->get_error_message()
    //     ));
    // }









    function add_head()
    {
?>
        <style>
            .simple-upload-dragover {
                background-color: #eef
            }

            .simple-upload-filename {
                margin-right: .5em
            }

            .dropZone {
                width: 70px;
                height: 70px;
                border-radius: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                position: relative;
                cursor: pointer;
                position: relative;
                overflow: hidden;
                border: 1px solid #ccc;
                /* border: 2px dashed rgba(234, 90, 50, 0.6); */
            }

            .dropZone p {
                margin-bottom: 0px;
            }

            .dropZone input[type="file"] {
                position: absolute;
                width: 70px;
                height: 70px;
                opacity: 0;
                z-index: 4;
            }

            .dropZone.has-image {
                background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='10' ry='10' stroke='%23909090' stroke-width='2' stroke-dasharray='20' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            }

            .dropZone .preview {
                position: absolute;
                height: 70px;
                width: 70px;
                top: 0px;
                left: 0px;
                border-radius: 100%;
                overflow: hidden;
            }

            .dropZone .preview img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }




            .dropZone input[type="file"] {
                /* opacity: 0; */
                cursor: pointer;
            }

            .dd_file_upload .alert-danger {
                opacity: 0;
            }



            .simple-upload-dragover {
                background-color: #eef;
            }

            .simple-upload-filename {
                margin-right: 0.5em;
            }
        </style>
    <?php
    }

    static function dd_file_upload_function($atts = array())
    {

        // set up default parameters
        extract(shortcode_atts(array(
            'class' => '',
            'value' => "",
        ), $atts));

        $html = '';
        ob_start();
    ?>
        <div class="dd_file_upload">
            <div class="dropZone <?= $class ?>">
                <input type="hidden" name="ddfu_value[]" value="<?= $value ?>">
                <input type="file" name="ddfu[]">
                <div class="modify">變更</div>
                <div class="preview"></div>
            </div>
        </div>

<?php
        $html .= ob_get_clean();
        return $html;
    }
}

DD_File_Upload::register();




function handle_file_upload()
{

    check_ajax_referer('dd_file_upload', 'nonce');
    // it allows us to use wp_handle_upload() function
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    // you can add some kind of validation here
    if (empty($_FILES['user_avatar'])) {
        wp_die('No files selected.');
    }

    $upload = wp_handle_upload(
        $_FILES['user_avatar'],
        array('test_form' => false)
    );

    if (!empty($upload['error'])) {
        wp_die($upload['error']);
    }

    // it is time to add our uploaded image into WordPress media library
    $attachment_id = wp_insert_attachment(
        array(
            'guid'           => $upload['url'],
            'post_mime_type' => $upload['type'],
            'post_title'     => basename($upload['file']),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ),
        $upload['file']
    );

    if (is_wp_error($attachment_id) || !$attachment_id) {
        wp_die('Upload error.');
    }

    // update medatata, regenerate image sizes
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    wp_update_attachment_metadata(
        $attachment_id,
        wp_generate_attachment_metadata($attachment_id, $upload['file'])
    );
    update_user_meta($_POST['user_id'], '_vendor_profile_image', $attachment_id);
    update_user_meta($_POST['user_id'], 'user_avatar', $attachment_id);

    die();
}

add_action('wp_ajax_handle_file_upload', 'handle_file_upload');
add_action('wp_ajax_nopriv_handle_file_upload', 'handle_file_upload');
