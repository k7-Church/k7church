<?php

/**
 *  Plugin Name: K7 Church
/*	Description:  This is a plugin for church administration and religious ministries.
/*	Version:      1.0.3
/*	Author:       Márcio Zebedeu
/*	License:      GPL2
/*	License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*   Text Domain:  k7
 *  Domain Path: /languages
*/



if( ! defined('ABSPATH') ) exit;

        if (!defined('K7_PLUGIN_DIR')) {
            define('K7_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }
if(class_exists('K7_Church'));

final class K7_Church
{


    private static $instance;
    private $db;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof K7_Church)) {

            self::$instance = new self();
            self::$instance->includes();
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * @param mixed $instance
     */
    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    private function includes()
    {


        // Plugin version.
        if (!defined('K7_VERSION')) {
            define('K7_VERSION', '1.0.0');
        }
        //include User Register
        require_once K7_PLUGIN_DIR . 'inc/admin/user/K7_user_register.php';
        require_once K7_PLUGIN_DIR . 'inc/admin/user/K7_Login.php';
        //include User Register
        require_once K7_PLUGIN_DIR . 'inc/admin/user/K7_Private_Account.php';
        require_once K7_PLUGIN_DIR . 'inc/admin/user/K7_My_Account.php';
        //include Widget
        require_once K7_PLUGIN_DIR . 'inc/admin/Location/k7_location_widget.php';
        //include Form
        require_once K7_PLUGIN_DIR . 'inc/K7_Form.php';
        //include Menu Option
        require_once K7_PLUGIN_DIR . '/inc/k7_menu_option_church.php';
        //include shortcodes
        require_once K7_PLUGIN_DIR . 'inc/admin/Location/k7_location_shortcode.php';
        // include Database
        require_once K7_PLUGIN_DIR . 'inc/admin/Database/k7-database.php';
        //include  Post Type
        require_once K7_PLUGIN_DIR . 'inc/admin/Post-Type/K7_Message_Post.php';
        //include Location
        require_once K7_PLUGIN_DIR . 'inc/admin/Location/K7_Location.php';
        require_once K7_PLUGIN_DIR . 'inc/k7_Panel_User.php';
        require_once K7_PLUGIN_DIR . 'templates/panel/k7-html-panel.php';


        $local = new  K7_Location();
        $local->init();

        new K7_Message_Post();
        k7_Menu_Option_Church::GetInstance();


        $this->db = new K7_Database();
        $this->db->init();
    }

    public function init()
    {
        add_filter('show_admin_bar', '__return_false');

        add_action('init', array($this, 'k7_myplugin_load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'plugin_activate')); //activate hook
        register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate')); //deactivate hook
        add_action('wp_logout', array($this, 'k7_redirect_to_custom_login'));
        add_action('wp_head', array($this, 'k7_enqueue_admin_scripts_and_styles')); //public scripts and styles
        add_filter('if_menu_conditions', array($this, 'wpb_new_menu_conditions'));


    }

    //trig

    //triggered on activation of the plugin (called only once)
    public function plugin_activate()
    {


        // Information needed for creating the plugin's pages
        $page_definitions = array(
            'member-login' => array(
                'title' => __('Sign In', 'k7'),
                'content' => '[custom-login-form]'
            ),
            'member-account' => array(
                'title' => __('Your Account', 'k7'),
                'content' => '[my_informations]'
            ),
            'member-register' => array(
                'title' => __('Register', 'k7'),
                'content' => '[custom-register-form]'
            ),
            'member-password-lost' => array(
                'title' => __('Forgot Your Password?', 'k7'),
                'content' => '[custom-password-lost-form]'
            ),
            'member-password-reset' => array(
                'title' => __('Pick a New Password', 'k7'),
                'content' => '[custom-password-reset-form]'
            )
        );
        foreach ($page_definitions as $slug => $page) {
            // Check that the page doesn't exist already
            $query = new WP_Query('pagename=' . $slug);
            if (!$query->have_posts()) {
                // Add the page using the data from the array above
                wp_insert_post(
                    array(
                        'post_content' => $page['content'],
                        'post_name' => $slug,
                        'post_title' => $page['title'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'ping_status' => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }

        //call our custom content type function
        $local = new  K7_Location();

        $local->register_location_content_type();
        new K7_Message_Post();
        add_action('init', array($this, 'myplugin_load_textdomain'));

        //flush permalinks
        flush_rewrite_rules();
    }


    //trigered on deactivation of the plugin (called only once)
    public function plugin_deactivate()
    {

        /** Delete the Plugin Pages */
        $k7_created_pages = array('register_page', 'success_page', 'login_page');


//        $user_register_id = get_option("user_register");
//        $register_success_id = get_option("register_success");
//        $login_register_id = get_option("login_page");
//        $login_success_id = get_option("login_success");
//        if (!empty($user_register_id) && !empty($register_success_id) && !empty($login_register_id) && !empty($login_success_id)) {

//            wp_delete_post($user_register_id, true);
//            wp_delete_post($register_success_id, true);
//            wp_delete_post($login_register_id, true);
//            wp_delete_post($login_success_id, true);
//        }


        flush_rewrite_rules();
    }

    function k7_myplugin_load_textdomain()
    {
        load_plugin_textdomain('k7', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    //enqueus scripts and stles on the back end
    function k7_enqueue_admin_scripts_and_styles()
    {
        $my_css_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/css/k7_location_public_styles.css'));
        $my_css_ver2 = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/css/k7_location_admin_styles.css'));
        $my_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/js/k7_location_public_styles.js'));

        wp_enqueue_style('my_css1', plugins_url('/assets/Public/css/bootstrap-theme.css', __FILE__));
        wp_enqueue_style('my_css2', plugins_url('/assets/Public/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('my_css3', plugins_url('/assets/Public/css/bootstrap.css', __FILE__));
        wp_enqueue_style('my_css', plugins_url('/assets/css/k7_location_public_styles.css', __FILE__), false, $my_css_ver);
        wp_enqueue_style('my_css_public', plugins_url('assets/css/k7_location_admin_styles.css', __FILE__), false, $my_css_ver2);
        wp_enqueue_script('custom_js', plugins_url('assets/js/k7_location_public_styles.js', __FILE__), array(), $my_js_ver);


        // $my_css_ver2 = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/css/k7_location_public_styles.css'));
        // wp_register_style('my_css_public', plugins_url('assets/css/k7_location_public_styles.css', __FILE__), false, $my_css_ver2);
        // wp_enqueue_style('my_css_public');

        // $my_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/js/k7_location_public_styles.js'));
        //
        // wp_enqueue_script('custom_js', plugins_url('assets/js/k7_location_public_styles.js', __FILE__), array(), $my_js_ver);

    }

    function wpb_new_menu_conditions($conditions)
    {

        $conditions[] = array(

            'name' => 'If it is Custom Post Type archive', // name of the condition

            'condition' => function ($item) {          // callback – must return TRUE or FALSE

                return is_post_type_archive();
            }

        );
        return $conditions;

    }


    public function k7_redirect_to_custom_login()
    {
        wp_redirect(site_url() . '/login');
        exit();
    }


}
function add(){

    K7_Church::getInstance();
}

add();

