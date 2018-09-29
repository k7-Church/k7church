<?php

/**
 *  Plugin Name: K7 Church
/*	Description:  This is a plugin for church administration and religious ministries.
/*	Version:      1.0.0
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
        if ( ! defined( 'K7_VERSION' ) ) {
            define( 'K7_VERSION', '1.0.0' );
        }
        //include User Register
        require_once K7_PLUGIN_DIR . 'inc/admin/user/K7_user_register.php';
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
        require_once K7_PLUGIN_DIR . 'inc/admin/K7_PageTemplater.php';
        require_once K7_PLUGIN_DIR . 'inc/k7_Panel_User.php';

        $local = new  K7_Location();
        $local->init();

        new K7_Message_Post();
        k7_Menu_Option_Church::GetInstance();
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles')); //admin scripts and styles


        $this->db = new K7_Database();
        $this->db->init();
    }

    public function init()
    {

//        add_action('admin_enqueue_scripts', array($this, 'enqueue_public_scripts_and_js')); //public scripts and styles
        add_action('init', array($this, 'myplugin_load_textdomain'));
        add_action( 'after_gill_arrives' , array($this, 'send_gill_to_get_paint'), 10 , 2  );
        add_filter( 'jacks_boast' , array($this, 'cut_the_boasting'));

        register_activation_hook(__FILE__, array($this, 'plugin_activate')); //activate hook
        register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate')); //deactivate hook

    }


    //trig

    //triggered on activation of the plugin (called only once)
    public function plugin_activate()
    {
        global $wpdb, $edd_options;



        $current_version = get_option( 'k7_version' );
        if ( $current_version ) {
            update_option( 'edd_version_upgraded_from', $current_version );
        }

        // Setup some default options
        $options = array();

        // Pull options from WP, not EDD's global
        $current_options = get_option( 'k7_settings', array() );

        // Checks if the purchase page option exists
        $register_page = array_key_exists( 'register_page', $current_options ) ? get_post( $current_options['register_page'] ) : false;
        if ( empty( $register_page ) ) {
            // Checkout Page
            $user_register = wp_insert_post(
                array(
                    'post_title'     => __( 'Register', 'k7' ),
                    'post_content'   => '[k7_custom_registration]',
                    'post_status'    => 'publish',
                    'post_author'    => 1,
                    'post_type'      => 'page',
                    'comment_status' => 'closed'
                )
            );

            $options['register_page'] = $user_register;
        }

        $user_register = isset( $user_register ) ? $user_register : $current_options['register_page'];

        $success_page = array_key_exists( 'success_page', $current_options ) ? get_post( $current_options['success_page'] ) : false;
        if ( empty( $success_page ) ) {
            // Purchase Confirmation (Success) Page
            $success = wp_insert_post(
                array(
                    'post_title'     => __( 'Register Confirmation', 'k7' ),
                    'post_content'   => __( 'Thank you for your Register! ', 'k7' ),
                    'post_status'    => 'publish',
                    'post_author'    => 1,
                    'post_parent'    => $user_register,
                    'post_type'      => 'page',
                    'comment_status' => 'closed'
                )
            );

            $options['success_page'] = $success;
        }

        $login_page = array_key_exists( 'login_page', $current_options ) ? get_post( $current_options['login_page'] ) : false;
        if ( empty( $login_page ) ) {
            // Checkout Page
            $login_register = wp_insert_post(
                array(
                    'post_title'     => __( 'Login', 'k7' ),
                    'post_content'   => '[k7_contact_form]',
                    'post_status'    => 'publish',
                    'post_author'    => 1,
                    'post_type'      => 'page',
                    'comment_status' => 'closed'
                )
            );

            $options['register_page'] = $login_register;
        }

        $login_register = isset( $login_register ) ? $login_register : $current_options['login_page'];

        $success_page = array_key_exists( 'success_page', $current_options ) ? get_post( $current_options['success_page'] ) : false;
        if ( empty( $success_page ) ) {
            // Purchase Confirmation (Success) Page
            $success = wp_insert_post(
                array(
                    'post_title'     => __( 'Login Confirmation', 'k7' ),
                    'post_content'   => __( 'Thank you for your Login! ', 'k7' ),
                    'post_status'    => 'publish',
                    'post_author'    => 1,
                    'post_parent'    => $login_register,
                    'post_type'      => 'page',
                    'comment_status' => 'closed'
                )
            );

            $options['success_page'] = $success;
        }

        function k7_get_option( $key = '', $default = false ) {
            global $edd_options;
            $value = ! empty( $edd_options[ $key ] ) ? $edd_options[ $key ] : $default;
            $value = apply_filters( 'k7_get_option', $value, $key, $default );
            return apply_filters( 'k7_get_option_' . $key, $value, $key, $default );
        }


        // Populate some default values
//        foreach( edd_get_registered_settings() as $tab => $sections ) {
//            foreach( $sections as $section => $settings) {
//
//                // Check for backwards compatibility
//                $tab_sections = edd_get_settings_tab_sections( $tab );
//                if( ! is_array( $tab_sections ) || ! array_key_exists( $section, $tab_sections ) ) {
//                    $section = 'main';
//                    $settings = $sections;
//                }
//
//                foreach ( $settings as $option ) {
//
//                    if( ! empty( $option['type'] ) && 'checkbox' == $option['type'] && ! empty( $option['std'] ) ) {
//                        $options[ $option['id'] ] = '1';
//                    }
//
//                }
//            }
//
//        }

        $merged_options = array_merge( $edd_options, $options );
        $edd_options    = $merged_options;

        update_option( 'k7_settings', $merged_options );
        update_option( 'k7_version', k7_VERSION );

        //call our custom content type function
        $local = new  K7_Location();
        $local->register_location_content_type();
        new K7_Message_Post();
        add_action('init', array($this, 'myplugin_load_textdomain'));

        //flush permalinks
        flush_rewrite_rules();
    }

    function edd_get_option( $key = '', $default = false ) {
        global $edd_options;
        $value = ! empty( $edd_options[ $key ] ) ? $edd_options[ $key ] : $default;
        $value = apply_filters( 'k7_get_option', $value, $key, $default );
        return apply_filters( 'k7_get_option_' . $key, $value, $key, $default );
    }

    //trigered on deactivation of the plugin (called only once)
    public function plugin_deactivate()
    {
        //flush permalinks

        /** Delete All the Terms & Taxonomies */


        /** Delete the Plugin Pages */
        $k7_created_pages = array( 'register_page', 'success_page', 'login_page' );
        foreach ( $k7_created_pages as $p ) {


            $page = $this->edd_get_option( $p, false );
            if ( $page ) {
                wp_delete_post( $page, true );
                break;
            }
        }

        /** Delete all the Plugin Options */
        delete_option( 'k7_settings' );
        delete_option( 'k7_version' );


        flush_rewrite_rules();
    }


    //enqueus scripts and stles on the back end
    public function enqueue_admin_scripts_and_styles()
    {
        $my_css_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/css/k7_location_admin_styles.css'));

        wp_enqueue_style('k7_location_public_styles', plugin_dir_url(__FILE__) . 'assets/css/k7_location_public_styles.css');
        wp_register_style('my_css', plugins_url('assets/css/k7_location_admin_styles.css', __FILE__), false, $my_css_ver);

        // create my own version codes
        $my_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/js/k7_location_public_styles.js'));

        //
        wp_enqueue_script('custom_js', plugins_url('assets/js/k7_location_public_styles.js', __FILE__), array(), $my_js_ver);
        wp_enqueue_style('my_css');

    }


    function myplugin_load_textdomain()
    {
        load_plugin_textdomain('k7', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }



    function send_gill_to_get_paint( $gill_has_keys, $gill_has_car ) {
        // If $gill_has_keys and $gill_has_car are both true
        if ( $gill_has_keys && $gill_has_car ) {
            echo 'Gill, please go to the store and get some paint. Thank you!';
        }
    }

    function cut_the_boasting($boast1) {
        // Replace "best" with "second-best"
        $boast = str_replace ( "best" , "second-best" , $boast1 );
        // Append another phrase at the end of his boast
        $boast = $boast . ' However, Gill can outshine me any day.';
        return $boast;
    }


}
function add(){

    K7_Church::getInstance();
}

add();

