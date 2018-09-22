<?php


defined( 'ABSPATH' ) or die( 'Nope, not accessing this' );

if(class_exists('K7_Form'));
class K7_Form
{
    public function __construct()
    {
        add_shortcode('k7_contact_form', array($this, 'k7_form'));
                    add_action('wp_head', array($this, 'k7_form_capture'));


    }


    public function k7_form()
    {

        $content = '';

        $content .= '<form method="post" action="' . esc_url($_SERVER['REQUEST_URI']) .'" >';
        $content .= '<input type="text" name="full_name" placeholder="you full name" />';
        $content .= '<br/>';

        $content .= '<input type="text" name="emil_address" placeholder="Email Adress" />';
        $content .= '<br/>';

        $content .= '<input type="text" name="phone_number" placeholder="Phone Number" />';
        $content .= '<br/>';

        $content .= '<textarea name="comments" placeholder="Give us your comments"></textarea>';
        $content .= '<br/>';

        $content .= '<input type="submit" name="k7_submit_form" value="SUBMIT INFORMATION" />';

        $content .= '</form>';
        return $content;
    }


    public function register_validators_form($name, $email, $phone, $comments)
    {
        global  $reg_errors;
        $reg_errors = new WP_Error;

        $name = sanitize_text_field($_POST['full_name']);
        $email = sanitize_email($_POST['emil_address']);
        $phone = sanitize_text_field($_POST['phone_number']);
        $comments = esc_textarea($_POST['full_name']);

        if (empty($name) || empty($email) || empty($comments) || empty($phone)) {
            $reg_errors->add('field', 'Required form field is missing');
        }

        if (4 > strlen($name)) {
            $reg_errors->add('username_length', 'Username too short. At least 4 characters is required');
        }

        if (1 > strlen($phone)) {
            $reg_errors->add('username_length', 'Phone Number too short. At least 4 characters is required');
        }

        if (1 > strlen($comments)) {
            $reg_errors->add('username_length', 'Comments too short. At least 1 characters is required');
        }

        if (!validate_username($name)) {
            $reg_errors->add('username_invalid', 'Sorry, the username you entered is not valid');
        }


        if (!is_email($email)) {
            $reg_errors->add('email_invalid', 'Email is not valid');
        }

        if (is_wp_error($reg_errors)) {

                    foreach ($reg_errors->get_error_messages() as $error) {

                        echo '<div>';
                        echo '<strong>ERROR</strong>:';
                        echo $error . '<br/>';
                        echo '</div>';

                    }

                }
            }

    public function k7_form_capture(){

        global $post, $wpdb, $name, $email, $comments, $phone;

        if (array_key_exists('k7_submit_form', $_POST)) {

            $this->register_validators_form(

                $_POST['full_name'],
                $_POST['emil_address'],
                $_POST['phone_number'],
                $_POST['comments']
            );

                 $name = sanitize_text_field($_POST['full_name']);
                $email = sanitize_email($_POST['emil_address']);
                $phone = sanitize_text_field($_POST['phone_number']);
                $comments = esc_textarea($_POST['comments']);
            

            $to = get_option('admin_email');
            $subject = 'Iea pro exemaple site form submission';
            $body = '';
            $body .= 'Name: ' . $name . '<br/>';
            $body .= 'Email: ' . $email . '<br/>';
            $body .= 'Phone: ' . $phone . '<br/>';
            $body .= 'Comments: ' . $comments . '<br/>';
            $headers = "From:  $name  <$email>" . "\r\n";


            add_filter('wp_mail_content_type', function ($content_type) {
                return 'text/html';
            });
// If email has been process for sending, display a success message
            if (wp_mail($to, $subject, $body, $headers)) {
                $query = "INSERT INTO  " . $wpdb->prefix . "form_submissions (data)  VALUES( '" . $body . "')";
                $insertData = $wpdb->get_results($query);

                remove_filter('wp_mail_content_type', 'set_html_content_type');

            } else {

                

            }


        }
    }
}

new K7_Form();
