<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if(class_exists('k7_Menu_Option_Church'));

class k7_Menu_Option_Church
{

    private $my_plugin_screen_name;
    private static $instance;

    /*......*/

    public static function GetInstance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof k7_Menu_Option_Church)) {
            $kunut = self::$instance = new self();
            $kunut->init();
            $kunut->InitPlugin();
        }
        return self::$instance;
    }


    public function PluginMenu()
    {

        $this->my_plugin_screen_name = add_menu_page(
            'Church Admin ',
            'Church Admin ',
            'manage_options',
            __FILE__,
            array($this, 'RenderPage_callback'),
            plugins_url('../../assets/images/icon/icon.ico', __FILE__),
            200
        );
        add_submenu_page(__FILE__, 'Custom', 'Custom', 'manage_options', __FILE__ . '/custom', array($this, 'k7_render_custom_page_callback'));
        add_submenu_page(__FILE__, 'setting', 'setting', 'manage_options', __FILE__ . '/setting', array($this, 'k7_setting_callback'));
        add_submenu_page(__FILE__, 'About', 'About', 'manage_options', __FILE__ . '/about', array($this, 'k7_render_about_page_callback'));

    }

    public function RenderPage_callback()
    {
        require_once EDD_PLUGIN_DIR . 'templates/admin/html-admin-settings-home.php';


    }

    function k7_render_custom_page_callback()
    {
        ?>
        <div class='wrap'>
            <h2>Knut Medicis custom</h2>
        </div>
        <?php
    }

    function k7_setting_callback()
    {
        require_once EDD_PLUGIN_DIR . 'templates/admin/html-admin-settings.php';
        $db = new K7_Database();

        if(array_key_exists('dop_database', $_POST)) {

            $db->on_delete_blog();

        }

    }

    function k7_render_about_page_callback()
    {
        ?>
        <div class='wrap teste'>
            <h2>Church Admin About</h2>
            <em>If you like this plugin, please <a href="http://wordpress.org/extend/plugins/Knut-Medicis">vote</a> .
                Author : <a href="https://github.com/zebedeu">Máecio Zebedeu</a>
                You can <a href="https://github.com/knut7/church-Admin">for bugs,</a> thanks.</em>

        </div>
        </div>
        <?php
    }



    function wpa3396_page_template($page_template)
    {
        if (is_page('my-custom-page-slug')) {
            $page_template = dirname(__FILE__) . '/custom-page-template.php';
        }
        return $page_template;
    }

    private function InitPlugin()
    {

        $post_type = new K7_Custom_Type_Post();
        add_action('admin_menu', array($this, 'PluginMenu'));
        add_filter('page_template', array($this, 'wpa3396_page_template'));


    }

    private function init()
    {
        add_shortcode('example', array($this, 'k7_Example_function'));
        add_action('wp_head', array($this, 'k7_display_header_scripts'));


    }


    public static function k7_display_header_scripts()
    {
        $header_scripts = get_option('k7_header_scripts', 'none');
        print $header_scripts;
    }


    private function k7_display_footer_scripts()
    {
        $footer_scripts = get_option('k7_header_scripts', 'none');
        print $footer_scripts;
    }

}