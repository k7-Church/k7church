<?php


if( ! defined('ABSPATH') ) exit;

if(class_exists('K7_Custom_Type_Post'));

class K7_Message_Post
{


    public function __construct()
    {
        add_action('init', array($this, 'register_message_content_type')); //register location content type

    }

    public static function register_message_content_type()
    {

        //Labels for post type
        $labels = array(
            'name' => _x('Message', 'Post Type General Name', 'k7'),
            'singular_name' => _x('Your Message', 'Post Type Singular Name', 'k7'),
            'menu_name' => __('Yours Messages', 'k7'),
            'name_admin_bar' => __('Your Message', 'k7'),
            'archives' => __('Item Archives', 'k7'),
            'attributes' => __('Item Attributes', 'k7'),
            'parent_item_colon' => __('Parent Message:', 'k7'),
            'all_items' => __('All Messages', 'k7'),
            'add_new_item' => __('Add New Message', 'k7'),
            'add_new' => __('Add New', 'k7'),
            'new_item' => __('New Message', 'k7'),
            'edit_item' => __('Edit Message', 'k7'),
            'update_item' => __('Update Message', 'k7'),
            'view_item' => __('View Message', 'k7'),
            'view_items' => __('View Message', 'k7'),
            'search_items' => __('Search Messages', 'k7'),
            'not_found' => __('Not found', 'k7'),
            'not_found_in_trash' => __('Not found in Trash', 'k7'),
            'featured_image' => __('Featured Image', 'k7'),
            'set_featured_image' => __('Set featured image', 'k7'),
            'remove_featured_image' => __('Remove featured image', 'k7'),
            'use_featured_image' => __('Use as featured image', 'k7'),
            'insert_into_item' => __('Insert into item', 'k7'),
            'uploaded_to_this_item' => __('Uploaded to this Message', 'k7'),
            'items_list' => __('Items list', 'k7'),
            'items_list_navigation' => __('Messages list navigation', 'k7'),
            'filter_items_list' => __('Filter Message list', 'k7'),


        );
        //arguments for post type
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav' => true,
            'query_var' => true,
            'hierarchical' => false,
            'supports' => array('title', 'thumbnail', 'editor'),
            'has_archive' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => false,
            'menu_icon' => 'dashicons-book-alt',
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
            'rewrite' => array('slug' => 'message', 'with_front' => 'true'),
            'supports' => array(
                'title',
                'editor',
                'author',
                'custom-fields',
                'thumbnail')
        );
        //register post type
        register_post_type('messages', $args);
    }

}