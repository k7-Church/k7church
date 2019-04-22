<?php 
/**
 * @package  K7Church
 */
namespace Inc\Controller;

use Inc\Api\Church_SettingsApi;
use Inc\Controller\Church_BaseController;
use Inc\Api\Callbacks\Church_AdminCallbacks;

/**
* 
*/
class Church_GalleryController extends Church_BaseController
{
	public $callbacks;

	public $subpages = array();

	public function ch_register()
	{
		if ( ! $this->ch_activated( 'gallery_manager' ) ) return;

		$this->settings = new Church_SettingsApi();

		$this->callbacks = new Church_AdminCallbacks();

		$this->ch_setSubpages();

		$this->settings->ch_addSubPages( $this->subpages )->ch_register();
	}

	public function ch_setSubpages()
	{
		$this->subpages = array(
			array(
				'parent_slug' => esc_html__( 'church_plugin', 'k7'), 
				'page_title' => esc_html__( 'Gallery Manager', 'k7'), 
				'menu_title' => esc_html__( 'Gallery Manager', 'k7'), 
				'capability' => 'manage_options', 
				'menu_slug' => 'church_gallery', 
				'callback' => array( $this->callbacks, 'ch_adminGallery' )
			)
		);
	}
}