<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_Activate
{
    public static function ch_activate()
    {
        flush_rewrite_rules();

        $default = array();

        if (!get_option('church_plugin')) {
            update_option('church_plugin', $default);
        }

        if (!get_option('church_plugin_cpt')) {
            update_option('church_plugin_cpt', $default);
        }

        if (!get_option('church_plugin_tax')) {
            update_option('church_plugin_tax', $default);
        }

        add_action( 'admin_init', array( 'Church_Activate', 'add_sub_caps' ) );
    }

    /**
     * Add read_private_posts capability to subscriber
     * Note this is saves capability to the database on admin_init, so consider doing this once on theme/plugin activation
     */

    public static function add_sub_caps(string $roles = 'church_member')
    {
        global $wp_role;


        var_dump($wp_role); die;

        // if( !$wp_role ) {

        //     $wp_role = new WP_Role;
        // }
        $result = $wp_role->add_role('church_role', __('Member', 'k7-church'),
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