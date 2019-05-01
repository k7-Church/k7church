<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_Enqueue extends Church_BaseController
{
    public function ch_register()
    {
        add_action('admin_enqueue_scripts', array($this, 'ch_enqueue'));
        add_action('wp_head', array($this, 'ch_enqueue_public'));
    }

    public function ch_enqueue()
    {
        // enqueue all our scripts
        wp_enqueue_script('media-upload');
        wp_enqueue_media();

        wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/mystyle.css');
        wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/myscript.js');
    }

    public function ch_enqueue_public()
    {

        wp_enqueue_style('my_css3', $this->plugin_url . 'assets/css/my-account.css');
        wp_enqueue_script('custom_jsww', $this->plugin_url . 'assets/js/my-account.js');
    }
}