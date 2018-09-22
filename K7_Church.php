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
    public static function setInstance($instance): void
    {
        self::$instance = $instance;
    }

    private function includes()
    {

        if (!defined('EDD_PLUGIN_DIR')) {
            define('EDD_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        //include User Register
        require_once EDD_PLUGIN_DIR . 'inc/admin/user/K7_user_register.php';
        //include Widget
        require_once EDD_PLUGIN_DIR . 'inc/admin/Location/k7_location_widget.php';
        //include Form
        require_once EDD_PLUGIN_DIR . 'inc/K7_Form.php';
        //include Menu Option
        require_once EDD_PLUGIN_DIR . '/inc/k7_menu_option_church.php';
        //include shortcodes
        require_once EDD_PLUGIN_DIR . 'inc/admin/Location/k7_location_shortcode.php';
        // include Database
        require_once EDD_PLUGIN_DIR . 'inc/admin/Database/k7-database.php';
        //include  Post Type
        require_once EDD_PLUGIN_DIR . 'inc/admin/Post-Type/K7_Custom_Type_Post.php';
        //include Location
        require_once EDD_PLUGIN_DIR . 'inc/admin/Location/K7_Location.php';
        require_once EDD_PLUGIN_DIR . 'inc/admin/K7_PageTemplater.php';

        $local = new  K7_Location();
        new K7_Custom_Type_Post();
        $local->init();

        k7_Menu_Option_Church::GetInstance();

        $this->db = new K7_Database();
               $this->db->init();
    }


    public function init()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles')); //admin scripts and styles
        add_action('k7_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles')); //public scripts and styles

        register_activation_hook(__FILE__, array($this, 'plugin_activate')); //activate hook
        register_deactivation_hook(__FILE__, array($this, 'plugin_deactivate')); //deactivate hook

    }


    //triggered on activation of the plugin (called only once)
    public function plugin_activate()
    {
        //call our custom content type function
             $local = new  K7_Location();
        $local->register_location_content_type(false);

        //flush permalinks
        flush_rewrite_rules();
    }

    //trigered on deactivation of the plugin (called only once)
    public function plugin_deactivate()
    {
        //flush permalinks
        flush_rewrite_rules();
    }


    //enqueus scripts and stles on the back end
    public function enqueue_admin_scripts_and_styles()
    {
        wp_enqueue_style('k7_location_admin_styles', plugin_dir_url(__FILE__) . 'assets/css/k7_location_admin_styles.css');
    }

//enqueues scripts and styled on the front end
    public function enqueue_public_scripts_and_styles()
    {
        wp_enqueue_style('k7_location_public_styles', plugin_dir_url(__FILE__) . 'assets/css/k7_location_public_styles.css');

    }

}
function add(){

    K7_Church::getInstance();
}

add();

