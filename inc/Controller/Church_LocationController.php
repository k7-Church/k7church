<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;

class Church_LocationController extends Church_BaseController

{
    public $settings;

    public $callbacks;

    private static $location_trading_hour_days = array();

    public function ch_register()
    {
        if ( ! $this->ch_activated( 'location_manager' ) ) return;

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_LocationCallbacks();

 
        add_action('init', array($this, 'ch_set_location_trading_hour_days')); //sets the default trading hour days (used by the content type)
        add_action('init', array($this, 'ch_Location_cpt')); //register location content type
        add_action('add_meta_boxes', array($this, 'ch_add_location_meta_boxes')); //add meta boxes
        add_action('save_post_locations', array($this, 'ch_save_location')); //save location
        add_filter('the_content', array($this, 'ch_prepend_location_meta_to_content')); //gets our meta data and dispayed it before the content
                $this->ch_setLocationSettingsPage();

        add_shortcode('locations', array($this, 'ch_location_shortcode_output'));
    }


    //shortcode display
    public function ch_location_shortcode_output($atts, $content = '', $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'location_id' => '',
                'number_of_locations' => -1)
            , $atts, $tag);



        //uses the main output function of the location class
        return $this->ch_get_locations_output($arguments);

    }

    public function ch_setLocationSettingsPage()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=locations',
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'church_location_settings',
                'callback' => array( $this->callbacks, 'ch_locationSettings' )
            )
        );

        $this->settings->ch_addSubPages( $subpage )->ch_register();
    }


    /**
     * @param array $location_trading_hour_days
     */
    public static function ch_set_location_trading_hour_days()
    {
        self::$location_trading_hour_days = apply_filters('location_trading_hours_days',
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

    public static function ch_Location_cpt()
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
        register_post_type('locations', $args);
    }

    //adding meta boxes for the location content type*/
    public function ch_add_location_meta_boxes()
    {

        add_meta_box(
            'location_meta_box', //id
            __( 'Location Information', 'k7'), //name
            array($this, 'ch_location_meta_box_display'), //display function
            'locations', //post type
            'normal', //location
            'default' //priority
        );
    }

    //display function used for our custom location meta box*/
    public function ch_location_meta_box_display($post)
    {

        //set nonce field
        wp_nonce_field('location_nonce', 'location_nonce_field');

        //collect variables
        $location_phone = get_post_meta($post->ID, 'location_phone', true);
        $location_email = get_post_meta($post->ID, 'location_email', true);
        $location_address = get_post_meta($post->ID, 'location_address', true);

        ?>
        <p><?php _e('Enter additional information about your location', 'k7');?></p>
        <div class="field-container">
            <?php
            //before main form elementst hook
            do_action('location_admin_form_start');
            ?>
            <div class="field">
                <label for="location_phone"><?php _e('Contact Phone', 'k7');?></label><br/>
                <small><?php _e('main contact number', 'k7');?></small>
                <input type="tel" name="location_phone" spellcheck="true" id="location_phone"
                       value="<?php echo $location_phone; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="location_email"><?php _e('Contact Email', 'k7');?></label><br/>
                <small><?php _e('Email contact', 'k7');?></small>
                <input type="email" name="location_email" id="location_email"
                       value="<?php echo $location_email; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="location_address"><?php _e('Address', 'k7');?></label><br/>
                <small><?php _e('Physical address of your location', 'k7');?></small>
                <textarea name="location_address"
                          id="location_address"><?php echo $location_address; ?></textarea>
            </div>
            <?php
            //trading hours
            if (!empty(self::$location_trading_hour_days)) {
                echo '<div class="field">';
                echo '<label>Trading Hours </label>';
                echo '<small>'. __('Trading hours for the location (e.g 9am - 5pm) ', 'k7').'</small>';
                //go through all of our registered trading hour days
                foreach (self::$location_trading_hour_days as $day_key => $day_value) {
                    //collect trading hour meta data
                    $location_trading_hour_value = get_post_meta($post->ID, 'location_trading_hours_' . $day_key, true);
                    //dsiplay label and input
                    echo '<br>';
                    echo '<label for="location_trading_hours_' . $day_key . '">' .ucfirst($day_key) . '</label>';
                    echo '<input type="text" name="location_trading_hours_' . $day_key . '" id="location_trading_hours_' . $day_key . '" value="' . $location_trading_hour_value . '" autocomplete="off"/>';
                }
                echo '</div>';
            }
            ?>
            <?php
            //after main form elementst hook
            do_action('location_admin_form_end');
            ?>
        </div>
        <?php

    }

    public function ch_prepend_location_meta_to_content($content)
    {

        global $post, $post_type;

        //display meta only on our locations (and if its a single location)
        if ($post_type == 'locations' && is_singular('locations')) {

            //collect variables
            $location_id = $post->ID;
            $location_phone = get_post_meta($post->ID, 'location_phone', true);
            $location_email = get_post_meta($post->ID, 'location_email', true);
            $location_address = get_post_meta($post->ID, 'location_address', true);

            //display
            $html = '';

            $html .= '<section class="ch-col-12 meta-data">';

            //hook for outputting additional meta data (at the start of the form)
            do_action('location_meta_data_output_start', $location_id);

            $html .= '<p classs="ch-row ch-col-12"><br>';
            //phone
            if (!empty($location_phone)) {
                $html .= '<b>' . __( 'Location Phone:', 'k7') . '</b> ' . __($location_phone) . '</br>';
            }
            //email
            if (!empty($location_email)) {
                $html .= '<b>' . __( 'Location Email:', 'k7') . '</b> ' . __($location_email) . '</br>';
            }
            //address
            if (!empty($location_address)) {
                $html .= '<b class="teste">' . __( 'Location Address:', 'k7') . '</b> ' . __($location_address) . '</br>';
            }
            $html .= '</p>';

            //location
            if (!empty(self::$location_trading_hour_days)) {
                $html .= '<p>';
                $html .= '<b>' . __( 'Location Trading Hours', 'k7') . ' </b></br>';
                foreach (self::$location_trading_hour_days as $day_key => $day_value) {
                    $trading_hours = get_post_meta($post->ID, 'location_trading_hours_' . $day_key, true);
                    $html .= '<span class="day">' . __($day_key) . "\t". '</span><span class="hours">' . ucfirst( __($trading_hours)) . '</span></br>';
                }
                $html .= '</p>';
            }

            //hook for outputting additional meta data (at the end of the form)
            do_action('location_meta_data_output_end', $location_id);

            $html .= '</section>';
            $html .= $content;

            return $html;


        } else {
            return $content;
        }

    }

    //main function for displaying locations (used for our shortcodes and widgets)
    public function ch_get_locations_output($arguments = "")
    {


        //default args
        $default_args = array(
            'location_id' => '',
            'number_of_locations' => -1,
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
            'post_type' => 'locations',
            'posts_per_page' => $default_args['number_of_locations'],
            'post_name' => '',
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
            $html .= '<article class="ch-col-3 location_list cf">';
            //foreach location
            foreach ($locations as $location) {
                $html .= '<section class="ch-col-3 location">';
                //collect location data
                $location_id = $location->ID;
                $location_title = get_the_title($location_id);
                $location_thumbnail = get_the_post_thumbnail($location_id, 'thumbnail');
                $location_content = apply_filters('the_content', $location->post_content);
                if (!empty($location_content)) {
                    $location_content = strip_shortcodes(wp_trim_words($location_content, 40, '...'));
                }
                $location_permalink = get_permalink($location_id);
                $location_phone = get_post_meta($location_id, 'location_phone', true);
                $location_email = get_post_meta($location_id, 'location_email', true);

                //apply the filter before our main content starts
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('location_before_main_content', $html);

                //title
                $html .= '<h2 class="ch-title">';
                $html .= '<a href="' . esc_url($location_permalink) . '" title="' . esc_attr__( 'view location', 'k7') . '">';
                $html .= $location_title;
                $html .= '</a>';
                $html .= '</h2>';


                //image & content
                if (!empty($location_thumbnail) || !empty($location_content)) {

                    if (!empty($location_thumbnail)) {
                        $html .= '<p class="image_content">';
                        $html .= $location_thumbnail;
                        $html .= '</p>';
                    }
                    if (!empty($location_content)) {
                        $html .= '<p>';
                        $html .= $location_content;
                        $html .= '</p>';
                    }

                }

                //phone & email output
                if (!empty($location_phone) || !empty($location_email)) {
                    $html .= '<p class="phone_email">';
                    if (!empty($location_phone)) {
                        $html .= '<b>' . __('Phone', 'k7') .': </b>' . $location_phone . '</br>';
                    }
                    if (!empty($location_email)) {
                        $html .= '<b>' . __('Email', 'k7') .': </b>' . $location_email;
                    }
                    $html .= '</p>';
                }

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('location_after_main_content', $html);

                //readmore
                $html .= '<a class="link" href="' . esc_url($location_permalink) . '" title="' . esc_attr__( 'view location', 'k7') . '">' . __('View Location', 'k7') .'</a>';
                $html .= '</section>';
            }
            $html .= '</article>';
            $html .= '<div class="cf"></div>';
        }

        return $html;
    }

    //triggered when adding or editing a location
    public function ch_save_location($post_id)
    {

        //check for nonce
        if (!isset($_POST['location_nonce_field'])) {
            return $post_id;
        }
        //verify nonce
        if (!wp_verify_nonce($_POST['location_nonce_field'], 'location_nonce')) {
            return $post_id;
        }
        //check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        //get our phone, email and address fields
        $location_phone = isset($_POST['location_phone']) ? sanitize_text_field($_POST['location_phone']) : '';
        $location_email = isset($_POST['location_email']) ? sanitize_text_field($_POST['location_email']) : '';
        $location_address = isset($_POST['location_address']) ? sanitize_text_field($_POST['location_address']) : '';

        //update phone, memil and address fields
        update_post_meta($post_id, 'location_phone', $location_phone);
        update_post_meta($post_id, 'location_email', $location_email);
        update_post_meta($post_id, 'location_address', $location_address);

        //search for our trading hour data and update
        foreach ($_POST as $key => $value) {
            //if we found our trading hour data, update it
            if (preg_match('/^location_trading_hours_/', $key)) {
                update_post_meta($post_id, $key, $value);
            }
        }

        //location save hook
        //used so you can hook here and save additional post fields added via 'location_meta_data_output_end' or 'location_meta_data_output_end'
        do_action('location_admin_save', $post_id, $_POST);


    }

}