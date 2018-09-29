<?php


if( ! defined('ABSPATH') ) exit;

if(class_exists('K7_Church'));


class K7_user_register
{
    public function __construct()
    {

        // Register a new shortcode: [k7_custom_registration]
        add_shortcode('k7_custom_registration', array($this, 'custom_registration_shortcode'));

    }


    private function registration_form($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio)
    {
        ?>

        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
            <div>
                <label for="username"><?php echo __('Username', 'k7'); ?> <strong>*</strong></label>
                <input type="text" name="username" value="<?php echo(isset($_POST['username']) ? $username : null); ?>"
                       autocomplete="off">
            </div>

            <div>
                <label for="firstname"><?php echo __('First Name<', 'k7'); ?>/label>
                    <input type="text" name="fname" value="<?php echo(isset($_POST['fname']) ? $first_name : null); ?>"
                           autocomplete="off">
            </div>

            <div>
                <label for="website"><?php echo __('Last Name', 'k7'); ?></label>
                <input type="text" name="lname" value="<?php echo(isset($_POST['lname']) ? $last_name : null); ?>"
                       autocomplete="off">
            </div>

            <div>
                <label for="nickname"><?php echo __('', 'k7'); ?>Nickname</label>
                <input type="text" name="nickname" value="<?php echo(isset($_POST['nickname']) ? $nickname : null); ?>"
                       autocomplete="off">
            </div>

            <div>
                <label for="password"><?php echo __('Password', 'k7'); ?> <strong>*</strong></label>
                <input type="password" name="password"
                       value="<?php echo(isset($_POST['password']) ? $password : null); ?>" autocomplete="off">
            </div>

            <div>
                <label for="email"><?php echo __('Email', 'k7'); ?><strong>*</strong></label>
                <input type="text" name="email" value="<?php echo(isset($_POST['email']) ? $email : null); ?>"
                       autocomplete="off">
            </div>

            <div>
                <label for="website"><?php echo __('Website', 'k7'); ?></label>
                <input type="text" name="website" value="<?php echo(isset($_POST['website']) ? $website : null); ?>">
            </div>

            <div>
                <label for="bio"><?php echo __('About / Bio', 'k7'); ?></label>
                <textarea name="bio"><?php echo(isset($_POST['bio']) ? $bio : null); ?></textarea>
            </div>
            <input type="hidden" name="k7_nonce_field" value="<?php echo wp_create_nonce('k7_nonce_field_submit'); ?>">
            <br/>
            <input type="submit" name="submit"
                   value="<?php echo apply_filters('k7_submit_value_register', 'REGISTER'); ?>"/>
        </form>
        <?php

    }

    private function registration_validation($username, $password, $email, $website, $first_name, $last_name, $nickname, $bio)
    {


        global $reg_errors;
        $reg_errors = new WP_Error;


        if (empty($username) || empty($password) || empty($email)) {
            $reg_errors->add('field', __('Required form field is missing', 'k7'));
        }

        if (4 > strlen($username)) {
            $reg_errors->add('username_length', __('Username too short. At least 4 characters is required', 'k7'));
        }

        if (username_exists($username))
            $reg_errors->add('user_name', __('Sorry, that username already exists!', 'k7'));

        if (!validate_username($username)) {
            $reg_errors->add('username_invalid', __('Sorry, the username you entered is not valid', 'k7'));
        }
        if (5 > strlen($password)) {
            $reg_errors->add('password', __('Password length must be greater than 5', 'k7'));
        }

        if (!is_email($email)) {
            $reg_errors->add('email_invalid', __('Email is not valid', 'k7'));
        }

        if (email_exists($email)) {
            $reg_errors->add('email', __('Email Already in use', 'k7'));
        }

        if (!empty($website)) {
            if (!filter_var($website, FILTER_VALIDATE_URL)) {
                $reg_errors->add('website', __('Website is not a valid URL', 'k7'));
            }
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

    private function complete_registration()
    {
        global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        if (1 > count($reg_errors->get_error_messages())) {
            $userdata = array(
                'user_login' => $username,
                'user_email' => $email,
                'user_pass' => $password,
                'user_url' => $website,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'nickname' => $nickname,
                'description' => $bio,
            );
            $user = wp_insert_user($userdata);
            echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';
        }
    }

    private function custom_registration_function()
    {
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;


        if (isset($_POST['submit']) || isset($_POST['k7_nonce_field'])) {

            if (wp_verify_nonce($_POST['k7_nonce_field'], 'k7_nonce_field_submit')) {

                $this->registration_validation(
                    $_POST['username'],
                    $_POST['password'],
                    $_POST['email'],
                    $_POST['website'],
                    $_POST['fname'],
                    $_POST['lname'],
                    $_POST['nickname'],
                    $_POST['bio']
                );

                // sanitize user form input
                $username = sanitize_user($_POST['username']);
                $password = sanitize_text_field($_POST['password']);
                $email = sanitize_email($_POST['email']);
                $website = esc_url($_POST['website']);
                $first_name = sanitize_text_field($_POST['fname']);
                $last_name = sanitize_text_field($_POST['lname']);
                $nickname = sanitize_text_field($_POST['nickname']);
                $bio = sanitize_text_field($_POST['bio']);

                // call @function complete_registration to create the user
                // only when no WP_error is found
                $this->complete_registration(
                    $username,
                    $password,
                    $email,
                    $website,
                    $first_name,
                    $last_name,
                    $nickname,
                    $bio
                );
            }
        }

        $this->registration_form(
            $username,
            $password,
            $email,
            $website,
            $first_name,
            $last_name,
            $nickname,
            $bio
        );
    }

// The callback function that will replace [book]
    public function custom_registration_shortcode()
    {
        ob_start();
        $this->custom_registration_function();
        return ob_get_clean();
    }

}

new K7_user_register();