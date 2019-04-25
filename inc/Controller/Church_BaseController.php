<?php 
/**
 * @package  K7Church
 */
 

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
		add_action ('admin_init',array( $this, 'add_sub_caps') );


		$this->managers = array(
			'cpt_manager' => __('Activate CPT Manager', 'k7'),
			'taxonomy_manager' => __('Activate Taxonomy Manager', 'k7'),
			'location_manager' => __('Activate Localion Manager', 'k7'),
			'location_widget' => __('Activate Location Widget', 'k7'),
			'media_widget' => __('Activate Media Widget', 'k7'),
			'testimonial_manager' => __('Activate Testimonial Manager', 'k7'),
			'notify_manager' => __('Activate Notification', 'k7'),
			'templates_manager' => __('Activate Custom Templates', 'k7'),
			// 'gallery_manager' => __('Activate Gallery Manager', 'k7'),
			// 'login_manager' => __('Activate Ajax Login/Signup', 'k7'),
			// 'membership_manager' => __('Activate Membership Manager', 'k7'),
			// 'chat_manager' => __('Activate Chat Manager', 'k7')
		);
	}

	public function ch_activated( string $key )
	{
		$option = get_option( 'church_plugin' );

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
	}

	/**
	* Add read_private_posts capability to subscriber
	* Note this is saves capability to the database on admin_init, so consider doing this once on theme/plugin activation
	*/

	public function add_sub_caps( string $roles = 'church_memeber' ) {

		$result = add_role( 'church_role', __('Member', 'k7' ),
			array(

				'read' => true, // true allows this capability
				'edit_posts' => true, // Allows user to edit their own posts
				'edit_pages' => true, // Allows user to edit pages
				'edit_others_posts' => true, // Allows user to edit others posts not just their own
				'create_posts' => true, // Allows user to create new posts
				'manage_categories' => true, // Allows user to manage post categories
				'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
				'edit_themes' => false, // false denies this capability. User can’t edit your theme
				'install_plugins' => false, // User cant add new plugins
				'update_plugin' => false, // User can’t update any plugins
				'update_core' => false // user cant perform core updates

			)
		);
	}
}