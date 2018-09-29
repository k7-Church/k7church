<?php


defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

if(class_exists('K7_Form'));
class K7_Form
{
    public function __construct()
    {
        add_shortcode('k7_contact_form', array($this, 'k7_form'));
        add_action('error_form_capture', array($this, 'k7_form_capture'));

    }


    public function k7_form()
    { ?>

        <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" >
            <input type="text" name="full_name" placeholder="<?php echo __('you full name','k7');?>" />
            <br/>
            <input type="text" name="emil_address" placeholder="<?php echo __('Email Adress','k7');?>" />
            <br/>
            <input type="text" name="phone_number" placeholder="<?php echo __('Phone Number','k7');?>" />
            <input type="hidden" name="k7_nonce_form" value="<?php echo wp_create_nonce('k7_nonce_field_form'); ?>" />
            <br/>
            <textarea name="comments" placeholder="<?php echo __('Give us your comments','k7');?>"></textarea>
            <br/>
            <input type="submit" name="submit_form" value="<?php printf( __( apply_filters('k7_submit_value_ok', 'SUBMIT INFORMATION'), 'k7') ); ?>" />

        </form>
        <?php
         do_action('error_form_capture');

    }


    public function register_validators_form($name, $email, $phone, $comments)
    {
        global $reg_errors;
        $reg_errors = new WP_Error;


        if (empty($name) || empty($email) || empty($comments) || empty($phone)) {
            $reg_errors->add('field', __('Required form field is missing', 'k7' ));
        }

        if (4 > strlen($name)) {
            $reg_errors->add('username_length', __('Username too short. At least 4 characters is required', 'k7'));
        }

        if (1 > strlen($phone)) {
            $reg_errors->add('username_length', __('Phone Number too short. At least 4 characters is required', 'k7'));
        }

        if (1 > strlen($comments)) {
            $reg_errors->add('username_length', __('Comments too short. At least 1 characters is required', 'k7'));
        }

        if (!validate_username($name)) {
            $reg_errors->add('username_invalid', __('Sorry, the username you entered is not valid', 'k7'));
        }


        if (!is_email($email)) {
            $reg_errors->add('email_invalid', __('Email is not valid', 'k7') );
        }

        if (is_wp_error($reg_errors)) {

            foreach ($reg_errors->get_error_messages() as $errors) {

                $error = "";
                $error .= '<div>';
                $error.= "<strong>". __( apply_filters('error_display_message', 'ERROR' ), 'k7'). "</strong>: ";
                $error .= '<br/>' . $errors;
                $error .='</div>';

                echo $error;


            }


        }
    }

    public function k7_form_capture()
    {

        global $wpdb, $name, $email, $comments, $phone;


        if (isset($_POST['submit_form']) || isset($_POST['k7_nonce_form'])) {

            if (wp_verify_nonce($_POST['k7_nonce_form'], 'k7_nonce_field_form')) {

                $this->register_validators_form(

                    $_POST['full_name'],
                    $_POST['emil_address'],
                    $_POST['phone_number'],
                    $_POST['comments']
                );

                $name = sanitize_text_field($_POST['full_name']);
                $email = sanitize_email($_POST['emil_address']);
                $phone = sanitize_text_field($_POST['phone_number']);
                $comments = sanitize_text_field($_POST['comments']);

                $to = get_option('admin_email');
                $subject = __('Iea pro exemaple site form submission', 'k7');
                $body = '';
                $body .= 'Name: '. $name . '<br/>';
                $body .= 'Email: ' . $email. '<br/>';
                $body .=  'Phone:  ' . $phone . '<br/>';
                $body .=  'Comments' .  $comments . '<br/>';
                $headers = "From: '  $name  <$email>" . "\r\n";


                add_filter('wp_mail_content_type', function ($content_type) {
                    return 'text/html';
                });
// If email has been process for sending, display a success message
                if (wp_mail($to, $subject, $body, $headers)) {
                    $query = "INSERT INTO  " . $wpdb->prefix . "form_submissions (data)  VALUES( '" . $body . "')";
                    $wpdb->get_results($query);

                    remove_filter('wp_mail_content_type', 'set_html_content_type');

                }
            }


        }
    }

}

new K7_Form();
