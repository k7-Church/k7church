<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_GalleryController extends Church_BaseController
{
    public $callbacks;

    public $subpages = array();

    public function ch_register()
    {
        if (!$this->ch_activated('gallery_manager')) return;

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_AdminCallbacks();

        $this->ch_setSubpages();

        $this->settings->ch_addSubPages($this->subpages)->ch_register();
    }

    public function ch_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => __('church_plugin', 'k7-church'),
                'page_title' => __('Gallery Manager', 'k7-church'),
                'menu_title' => __('Gallery Manager', 'k7-church'),
                'capability' => 'manage_options',
                'menu_slug' => 'church_gallery',
                'callback' => array($this->callbacks, 'ch_adminGallery')
            )
        );
    }
}