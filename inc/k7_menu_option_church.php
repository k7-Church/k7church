<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if(class_exists('k7_Menu_Option_Church'));

class k7_Menu_Option_Church
{

    private $my_plugin_screen_name;
    private static $instance;

    public static function GetInstance()
    {

        if (!isset(self::$instance) && !(self::$instance instanceof k7_Menu_Option_Church)) {
            $kunut = self::$instance = new self();
            $kunut->init();
            $kunut->k7_display_header_scripts();
            $kunut->k7_display_footer_scripts();
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
            array($this, 'RenderPage_callback'), '', //
            200
        );
         add_submenu_page(__FILE__, 'Custom', 'Custom', 'manage_options', __FILE__ . '/custom', array($this, 'k7_render_custom_page_callback'));
         add_submenu_page(__FILE__, 'setting', 'setting', 'manage_options', __FILE__ . '/setting', array($this, 'k7_setting_callback'));
         add_submenu_page(__FILE__, 'About', 'About', 'manage_options', __FILE__ . '/about', array($this, 'k7_render_about_page_callback'));

    }

    public function RenderPage_callback()
    {
        require_once K7_PLUGIN_DIR . 'templates/admin/html-admin-settings-home.php';

    }

    function k7_render_custom_page_callback()
    {
        ?>
        <div class='wrap'>
            <h2></h2>
        </div>
        <?php
    }

    function k7_setting_callback()
    {
        require_once K7_PLUGIN_DIR . 'templates/admin/html-admin-settings.php';
        $db = new K7_Database();
        if (current_user_can('manage_options')) {

            if (isset($_POST['dop_database']) || isset($_POST['k7_nonce_drop'])) {
                if (wp_verify_nonce($_POST['k7_nonce_drop'], 'k7_nonce_field_drop')) {
                    $db->on_delete_blog();

                    ?>

                    <div id="setting-error-settings-updated" class="updated settings-error notice is-dismissible ">
                        <strong>Table have been dropped..</strong></div>
                    <?php

                }
            }
        }

    }

    function k7_render_about_page_callback()
    {
        ?>
        <div class='wrap teste'>
            <h2>Church Admin About</h2>
            <p>
            <h2>1. Place [k7_contact_form] on the page you want the form displayed,</h2></p>
            <p>
            <h2>2. Place [k7_custom_registration] on the page you want the users registration form displayed.</h2></p>
            <p><h2>3. Go to Settings » Permalinks, and simply click on Save Changes button.</h2></p>
            <em>If you like this plugin, please <a href="http://wordpress.org/extend/plugins/k7church">vote</a> .
                Author : <a href="https://github.com/zebedeu">Máecio Zebedeu</a>
                You can <a href="https://github.com/knut7/k7church">for bugs,</a> thanks.</em>

        </div>
        </div>
        <?php
    }

    function k7_page_template($page_template)
    {
        if (is_page('my-custom-page-slug')) {
            $page_template = dirname(__FILE__) . '/custom-page-template.php';
        }
        return $page_template;
    }

    private function InitPlugin()
    {
        add_action('admin_menu', array($this, 'PluginMenu'));
        add_filter('page_template', array($this, 'k7_page_template'));

    }

    private function init()
    {
        add_shortcode('example', array($this, 'k7_Example_function'));
        add_action('wp_head', array($this, 'k7_display_header_scripts'));

    }

    public static function k7_display_header_scripts()
    {
        $header_scripts = get_option('k7_header_scripts', 'none');
        return $header_scripts;
    }

    private function k7_display_footer_scripts()
    {
        $footer_scripts = get_option('k7_header_scripts', 'none');
        return $footer_scripts;
    }

}