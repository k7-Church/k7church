<?php


if( ! defined('ABSPATH') ) exit;

if(class_exists('K7_Location'));

class K7_Location
{

    private static $k7_location_trading_hour_days = array();

    public function init()
    {
        add_action('init', array($this, 'set_location_trading_hour_days')); //sets the default trading hour days (used by the content type)
        add_action('init', array($this, 'register_location_content_type')); //register location content type
        add_action('add_meta_boxes', array($this, 'add_location_meta_boxes')); //add meta boxes
        add_action('save_post_k7_locations', array($this, 'save_location')); //save location
        add_filter('the_content', array($this, 'prepend_location_meta_to_content')); //gets our meta data and dispayed it before the content


    }

    /**
     * @param array $k7_location_trading_hour_days
     */
    public static function set_location_trading_hour_days()
    {
        self::$k7_location_trading_hour_days = apply_filters('k7_location_trading_hours_days',
            array('monday' => __('Monday', 'k7'),
                'tuesday' => __('Tuesday', 'k7'),
                'wednesday' => __('Wednesday', 'k7'),
                'thursday' => __('Thursday', 'k7'),
                'friday' => __('Friday', 'k7'),
                'saturday' => __('Saturday', 'k7'),
                'sunday' => __('Sunday', 'k7'),
            )
        );

    }


    public static function register_location_content_type()
    {

        //Labels for post type
        $labels = array(
            'name' => __('Location', 'k7'),
            'singular_name' => __('Location', 'k7'),
            'menu_name' => __('Locations', 'k7'),
            'name_admin_bar' => __('Location', 'k7'),
            'add_new' => __('Add New', 'k7'),
            'add_new_item' => __('Add New Location', 'k7'),
            'new_item' => __('New Location', 'k7'),
            'edit_item' => __('Edit Location', 'k7'),
            'view_item' => __('View Location', 'k7'),
            'all_items' => __('All Locations', 'k7'),
            'search_items' => __('Search Locations', 'k7'),
            'parent_item_colon' => __('Parent Location:', 'k7'),
            'not_found' => 'No Locations found.',
            'not_found_in_trash' => __('No Locations found in Trash.', 'k7'),
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
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-location-alt',
            'rewrite' => array('slug' => 'locations', 'with_front' => 'true')
        );
        //register post type
        register_post_type('k7_locations', $args);
    }

    //adding meta boxes for the location content type*/
    public function add_location_meta_boxes()
    {

        add_meta_box(
            'k7_location_meta_box', //id
            'Location Information', //name
            array($this, 'location_meta_box_display'), //display function
            'k7_locations', //post type
            'normal', //location
            'default' //priority
        );
    }

    //display function used for our custom location meta box*/
    public function location_meta_box_display($post)
    {

        //set nonce field
        wp_nonce_field('k7_location_nonce', 'k7_location_nonce_field');

        //collect variables
        $k7_location_phone = get_post_meta($post->ID, 'k7_location_phone', true);
        $k7_location_email = get_post_meta($post->ID, 'k7_location_email', true);
        $k7_location_address = get_post_meta($post->ID, 'k7_location_address', true);

        ?>
        <p>Enter additional information about your location </p>
        <div class="field-container">
            <?php
            //before main form elementst hook
            do_action('k7_location_admin_form_start');
            ?>
            <div class="field">
                <label for="k7_location_phone">Contact Phone</label><br/>
                <small>main contact number</small>
                <input type="tel" name="k7_location_phone" spellcheck="true" id="k7_location_phone"
                       value="<?php echo $k7_location_phone; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="k7_location_email">Contact Email</label><br/>
                <small>Email contact</small>
                <input type="email" name="k7_location_email" id="k7_location_email"
                       value="<?php echo $k7_location_email; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="k7_location_address">Address</label><br/>
                <small>Physical address of your location</small>
                <textarea name="k7_location_address"
                          id="k7_location_address"><?php echo $k7_location_address; ?></textarea>
            </div>
            <?php
            //trading hours
            if (!empty(self::$k7_location_trading_hour_days)) {
                echo '<div class="field">';
                echo '<label>Trading Hours </label>';
                echo '<small> Trading hours for the location (e.g 9am - 5pm) </small>';
                //go through all of our registered trading hour days
                foreach (self::$k7_location_trading_hour_days as $day_key => $day_value) {
                    //collect trading hour meta data
                    $k7_location_trading_hour_value = get_post_meta($post->ID, 'k7_location_trading_hours_' . $day_key, true);
                    //dsiplay label and input
                    echo '<br>';
                    echo '<label for="k7_location_trading_hours_' . $day_key . '">' . $day_key . '</label>';
                    echo '<input type="text" name="k7_location_trading_hours_' . $day_key . '" id="k7_location_trading_hours_' . $day_key . '" value="' . $k7_location_trading_hour_value . '" autocomplete="off"/>';
                }
                echo '</div>';
            }
            ?>
            <?php
            //after main form elementst hook
            do_action('k7_location_admin_form_end');
            ?>
        </div>
        <?php

    }

    public function prepend_location_meta_to_content($content)
    {

        global $post, $post_type;

        //display meta only on our locations (and if its a single location)
        if ($post_type == 'k7_locations' && is_singular('k7_locations')) {

            //collect variables
            $k7_location_id = $post->ID;
            $k7_location_phone = get_post_meta($post->ID, 'k7_location_phone', true);
            $k7_location_email = get_post_meta($post->ID, 'k7_location_email', true);
            $k7_location_address = get_post_meta($post->ID, 'k7_location_address', true);

            //display
            $html = '';

            $html .= '<section class="meta-data">';

            //hook for outputting additional meta data (at the start of the form)
            do_action('k7_location_meta_data_output_start', $k7_location_id);

            $html .= '<p>';
            //phone
            if (!empty($k7_location_phone)) {
                $html .= '<b>Location Phone</b> ' . $k7_location_phone . '</br>';
            }
            //email
            if (!empty($k7_location_email)) {
                $html .= '<b>Location Email</b> ' . $k7_location_email . '</br>';
            }
            //address
            if (!empty($k7_location_address)) {
                $html .= '<b class="teste">Location Address</b> ' . $k7_location_address . '</br>';
            }
            $html .= '</p>';

            //location
            if (!empty(self::$k7_location_trading_hour_days)) {
                $html .= '<p>';
                $html .= '<b>Location Trading Hours </b></br>';
                foreach (self::$k7_location_trading_hour_days as $day_key => $day_value) {
                    $trading_hours = get_post_meta($post->ID, 'k7_location_trading_hours_' . $day_key, true);
                    $html .= '<span class="day">' . $day_key . '</span><span class="hours">' . $trading_hours . '</span></br>';
                }
                $html .= '</p>';
            }

            //hook for outputting additional meta data (at the end of the form)
            do_action('k7_location_meta_data_output_end', $k7_location_id);

            $html .= '</section>';
            $html .= $content;

            return $html;


        } else {
            return $content;
        }

    }

    //main function for displaying locations (used for our shortcodes and widgets)
    public function get_locations_output($arguments = "")
    {

        //default args
        $default_args = array(
            'location_id' => '',
            'number_of_locations' => -1
        );

        //update default args if we passed in new args
        if (!empty($arguments) && is_array($arguments)) {
            //go through each supplied argument
            foreach ($arguments as $arg_key => $arg_val) {
                //if this argument exists in our default argument, update its value
                if (array_key_exists($arg_key, $default_args)) {
                    $default_args[$arg_key] = $arg_val;
                }
            }
        }

        //find locations
        $location_args = array(
            'post_type' => 'k7_locations',
            'posts_per_page' => $default_args['number_of_locations'],
            'post_status' => 'publish'
        );
        //if we passed in a single location to display
        if (!empty($default_args['location_id'])) {
            $location_args['include'] = $default_args['location_id'];
        }

        //output
        $html = '';
        $locations = get_posts($location_args);
        //if we have locations
        if ($locations) {
            $html .= '<article class="location_list cf">';
            //foreach location
            foreach ($locations as $location) {
                $html .= '<section class="location">';
                //collect location data
                $k7_location_id = $location->ID;
                $k7_location_title = get_the_title($k7_location_id);
                $k7_location_thumbnail = get_the_post_thumbnail($k7_location_id, 'thumbnail');
                $k7_location_content = apply_filters('the_content', $location->post_content);
                if (!empty($k7_location_content)) {
                    $k7_location_content = strip_shortcodes(wp_trim_words($k7_location_content, 40, '...'));
                }
                $k7_location_permalink = get_permalink($k7_location_id);
                $k7_location_phone = get_post_meta($k7_location_id, 'k7_location_phone', true);
                $k7_location_email = get_post_meta($k7_location_id, 'k7_location_email', true);

                //apply the filter before our main content starts
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('k7_location_before_main_content', $html);

                //title
                $html .= '<h2 class="title">';
                $html .= '<a href="' . $k7_location_permalink . '" title="view location">';
                $html .= $k7_location_title;
                $html .= '</a>';
                $html .= '</h2>';


                //image & content
                if (!empty($k7_location_thumbnail) || !empty($k7_location_content)) {

                    $html .= '<p class="image_content">';
                    if (!empty($k7_location_thumbnail)) {
                        $html .= $k7_location_thumbnail;
                    }
                    if (!empty($k7_location_content)) {
                        $html .= $k7_location_content;
                    }

                    $html .= '</p>';
                }

                //phone & email output
                if (!empty($k7_location_phone) || !empty($k7_location_email)) {
                    $html .= '<p class="phone_email">';
                    if (!empty($k7_location_phone)) {
                        $html .= '<b>Phone: </b>' . $k7_location_phone . '</br>';
                    }
                    if (!empty($k7_location_email)) {
                        $html .= '<b>Email: </b>' . $k7_location_email;
                    }
                    $html .= '</p>';
                }

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('k7_location_after_main_content', $html);

                //readmore
                $html .= '<a class="link" href="' . $k7_location_permalink . '" title="view location">View Location</a>';
                $html .= '</section>';
            }
            $html .= '</article>';
            $html .= '<div class="cf"></div>';
        }

        return $html;
    }

    //triggered when adding or editing a location
    public function save_location($post_id)
    {

        //check for nonce
        if (!isset($_POST['k7_location_nonce_field'])) {
            return $post_id;
        }
        //verify nonce
        if (!wp_verify_nonce($_POST['k7_location_nonce_field'], 'k7_location_nonce')) {
            return $post_id;
        }
        //check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        //get our phone, email and address fields
        $k7_location_phone = isset($_POST['k7_location_phone']) ? sanitize_text_field($_POST['k7_location_phone']) : '';
        $k7_location_email = isset($_POST['k7_location_email']) ? sanitize_text_field($_POST['k7_location_email']) : '';
        $k7_location_address = isset($_POST['k7_location_address']) ? sanitize_text_field($_POST['k7_location_address']) : '';

        //update phone, memil and address fields
        update_post_meta($post_id, 'k7_location_phone', $k7_location_phone);
        update_post_meta($post_id, 'k7_location_email', $k7_location_email);
        update_post_meta($post_id, 'k7_location_address', $k7_location_address);

        //search for our trading hour data and update
        foreach ($_POST as $key => $value) {
            //if we found our trading hour data, update it
            if (preg_match('/^k7_location_trading_hours_/', $key)) {
                update_post_meta($post_id, $key, $value);
            }
        }

        //location save hook
        //used so you can hook here and save additional post fields added via 'k7_location_meta_data_output_end' or 'k7_location_meta_data_output_end'
        do_action('k7_location_admin_save', $post_id, $_POST);


    }
}
?>