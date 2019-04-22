<?php 
/**
 * @package  K7Church
 */
namespace Inc\Controller;

class Church_BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public $managers = array();

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/church-plugin.php';

		$this->managers = array(
			'cpt_manager' => esc_html__('Activate CPT Manager', 'k7'),
			'taxonomy_manager' => esc_html__('Activate Taxonomy Manager', 'k7'),
			'location_manager' => esc_html__('Activate Localion Manager', 'k7'),
			'location_widget' => esc_html__('Activate Location Widget', 'k7'),
			'media_widget' => esc_html__('Activate Media Widget', 'k7'),
			'testimonial_manager' => esc_html__('Activate Testimonial Manager', 'k7'),
			'templates_manager' => esc_html__('Activate Custom Templates', 'k7'),
			// 'gallery_manager' => esc_html__('Activate Gallery Manager', 'k7'),
			// 'login_manager' => esc_html__('Activate Ajax Login/Signup', 'k7'),
			// 'membership_manager' => esc_html__('Activate Membership Manager', 'k7'),
			// 'chat_manager' => esc_html__('Activate Chat Manager', 'k7')
		);
	}

	public function ch_activated( string $key )
	{
		$option = get_option( 'church_plugin' );

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}
}