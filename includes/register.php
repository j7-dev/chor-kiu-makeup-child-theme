<?php
namespace MUA;
defined('ABSPATH') || exit;

class Register
{


    public function __construct()
    {
        add_action('admin_head', [$this, 'yc_create_default_page']);
        add_action('admin_head', [$this, 'yc_hide_post']);
        add_action('init', [$this, 'yc_add_shortcode']);

        add_action('wp_enqueue_scripts', [$this, 'yc_add_scripts']);

        add_action( 'woocommerce_created_customer', [$this, 'wooc_save_extra_register_fields' ] );

        add_filter( 'woocommerce_min_password_strength', function() {
            return 0; }, 99 );
    }

    function wooc_save_extra_register_fields( $customer_id ) {
        if ( isset( $_POST['billing_phone'] ) ) {
                     update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
              }
          if ( isset( $_POST['gender'] ) ) {
                 update_user_meta( $customer_id, 'gender', sanitize_text_field( $_POST['gender'] ) );
          }
          if ( isset( $_POST['birthday'] ) ) {
                 update_user_meta( $customer_id, 'birthday', sanitize_text_field( $_POST['birthday'] ) );
          }
    }

    function yc_create_default_page()
    {
        //auto create register page
        if (class_exists('TheChampLoginWidget', false)) {
            $register_page_exist = post_exists('Register', '', '', 'page');
            if ($register_page_exist == 0) {
                $postarr = [
                    'post_content'  => '[wc_reg_form_bbloomer]',
                    'post_title'    => 'Register',
                    'post_status'   => 'publish',
                    'post_type'     => 'page',
                ];
                //新增文章
               wp_insert_post($postarr);

            }
        }
    }
    function yc_add_shortcode()
    {
        add_shortcode('wc_reg_form_bbloomer', [$this, 'yc_separate_registration_form']);
    }


    function yc_separate_registration_form()
    {
        ob_start();
        /*
         * custum register field
         * https://www.cloudways.com/blog/add-woocommerce-registration-form-fields/
         */
        /* if (is_admin() || is_user_logged_in()) {
            wp_safe_redirect(site_url());
            exit;
        } */


        // NOTE: THE FOLLOWING <FORM></FORM> IS COPIED FROM woocommerce\templates\myaccount\form-login.php
        // IF WOOCOMMERCE RELEASES AN UPDATE TO THAT TEMPLATE, YOU MUST CHANGE THIS ACCORDINGLY

        $html = '';

?>
<?php if (is_admin() || is_user_logged_in()) : ?>
<script>
    window.location.href = "<?= site_url(); ?>";
</script>
<?php endif; ?>
        <div class="yc_login_form_wrap">
            <div class="yc_login_form">
                <?php
                do_action('woocommerce_before_customer_login_form');

                ?>

                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
                    <h2 class="yc_login_form_title"><?php esc_html_e('REGISTER', 'YC_TECH'); ?></h2>

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php //if ('no' === get_option('woocommerce_registration_generate_username')) : ?>

                        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                            <label for="reg_username"><?php esc_html_e('Username', 'woocommerce'); ?> <span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" /><?php // @codingStandardsIgnoreLine
                                                                                                                                                                                                                                                                            ?>
                        </p>

                    <?php //endif; ?>








                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_email"><?php esc_html_e('Email address', 'woocommerce'); ?> <span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_password"><?php esc_html_e('Password', 'woocommerce'); ?> <span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_phone"><?php esc_html_e('Phone', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_phone" autocomplete="billing_phone" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" />
                    </p>

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_gender"><?php esc_html_e('Gender', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="radio" id="male" name="gender" value="male"> <?php esc_html_e('Male', 'YC_TECH'); ?>
                        <input type="radio" id="female" name="gender" value="female"> <?php esc_html_e('Female', 'YC_TECH'); ?>
                    </p>

                    <!--p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_billing_phone"><?php esc_html_e('Phone', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="reg_billing_phone" autocomplete="billing_phone" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" />
                    </p-->

                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                        <label for="reg_birthday"><?php esc_html_e('Birthday', 'YC_TECH'); ?> <span class="required">*</span></label>
                        <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="birthday" id="reg_birthday" autocomplete="birthday" placeholder="年月日" value="<?php echo (!empty($_POST['birthday'])) ? esc_attr(wp_unslash($_POST['birthday'])) : ''; ?>" />
                    </p>
                    <p class="woocommerce-FormRow form-row mt-3r">
                        <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button btn btn-primary w-100" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>"><?php esc_html_e('Register', 'woocommerce'); ?></button>
                    </p>
                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>
                <script>
                    jQuery(document).ready(($) => {

                        const phoneInputField = document.querySelector("#reg_phone");
                        const phoneInput = window.intlTelInput(phoneInputField, {
                            onlyCountries: ["tw"],
                            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                        });

                        //調整寬度
                        const _phone_input_width = document.querySelector(".iti--allow-dropdown").offsetWidth;
                        const _change_phone_country_dropdownwidth = document.querySelector(".iti__country-list").style.width = _phone_input_width + 'px';


                    });
                </script>
            </div>
        </div>

<?php
$html .= ob_get_clean();
ob_end_flush();
        return $html;
    }


    //防止客人修改註冊頁
    function yc_hide_post()
    {
        $register_page_exist = post_exists('Register', '', '', 'page');
        if ($register_page_exist == 0 || current_user_can( 'administrator' )) return;

        $css = '';
        $css .= '<style>';
        $css .= 'tr#post-' . $register_page_exist . '{';
        $css .=     'display: none !important;';
        $css .= '}';
        $css .= '</style>';

        echo $css;
    }

    function yc_add_scripts()
    {

        //國際電話選單
        global $post;
        if ($post->post_name == 'register') {
            wp_enqueue_style('intlTelInput', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css', array(/* 'jquery' */), '1.0');
            wp_enqueue_script('intlTelInput', 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js', array(/* 'jquery' */), '1.0', false);
        }
    }
}

new Register();