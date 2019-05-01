<?php
/**
 * @version 1.0.14
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;



class Church_EventController extends Church_BaseController

{

    public function ch_register()
    {

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_EventCallbacks();


        add_action('init', array($this, 'ch_eventposts'));
        add_action('pre_get_posts', array($this, 'ch_event_query'));
        add_action('admin_init', array($this, 'ch_eventposts_metaboxes'));
        add_action('save_post', array($this, 'ch_eventposts_save_meta'), 1, 2);
        add_filter('the_content', array($this, 'ch_prepend_event_meta_to_content')); //gets our meta data and dispayed it before the content
        add_shortcode('events', array($this, 'ch_event_shortcode_output'));


        $this->ch_setSettings();
        $this->ch_setSections();
        $this->ch_setFields();
        $this->ch_setSubpages();

    }

    public function ch_setSubpages()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=event',
                'page_title' => 'Settings',
                'menu_title' => 'Settings',
                'capability' => 'manage_options',
                'menu_slug' => 'church_event_settings',
                'callback' => array($this->callbacks, 'ch_event_settings')
            )
        );

        $this->settings->ch_addSubPages($subpage)->ch_register();
    }

    public function ch_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'church_event_options_group',
                'option_name' => 'event_border_color',
                'callback'    => array( $this->callbacks, 'ch_event_sanitize')

            ),
            array(
                'option_group' => 'church_event_options_group',
                'option_name' => 'event_status_started'
            ),
            array(


                'option_group' => 'church_event_options_group',
                'option_name' => 'event_status_finished'
            ),
            array(
                'option_group' => 'church_event_options_group',
                'option_name' => 'event_status_soon'
            ),          
            array(
                'option_group' => 'church_event_options_group',
                'option_name' => 'event_status_button'
            )
        );
        $this->settings->ch_setSettings($args);
    }

    public function ch_setSections()
    {
         $args = array(
            array(
                'id' => 'church_event_id',
                'title' => 'Settings',
                'callback'    => array( $this->callbacks, 'ch_event_section'),
                'page' => 'church_event_settings'

            )
        );

        $this->settings->ch_setSections($args);
    }

    public function ch_setFields()
    {
         $args = array(
            array(
                'id' => 'event_border_color',
                'title' => 'Border color',
                'callback'    => array( $this->callbacks, 'ch_event_textFields_border'),
                'page' => 'church_event_settings',
                'section' => 'church_event_id',
                'args' => array(
                    'laber_for' => 'event_border_color',
                    'class' => 'example-class'
                ),

            ),
            array(
                'id' => 'event_status_started',
                'title' => 'Event Started',
                'callback'    => array( $this->callbacks, 'ch_event_textFields_status_started'),
                'page' => 'church_event_settings',
                'section' => 'church_event_id',
                'args' => array(
                    'laber_for' => 'event_status_started',
                    'class' => 'example-class'
                ),

            ),
            array(
                'id' => 'event_status_finished',
                'title' => 'Event finished',
                'callback'    => array( $this->callbacks, 'ch_event_textFields_status_soon_finished'),
                'page' => 'church_event_settings',
                'section' => 'church_event_id',
                'args' => array(
                    'laber_for' => 'event_status_finished',
                    'class' => 'example-class'
                ),

            ),
            array(
                'id' => 'event_status_soon',
                'title' => 'Status color',
                'callback'    => array( $this->callbacks, 'ch_event_textFields_status_soon'),
                'page' => 'church_event_settings',
                'section' => 'church_event_id',
                'args' => array(
                    'laber_for' => 'event_status_soon',
                    'class' => 'example-class'
                ),
            ),
            array(
                'id' => 'event_status_button',
                'title' => 'button class',
                'callback'    => array( $this->callbacks, 'ch_event_button_class'),
                'page' => 'church_event_settings',
                'section' => 'church_event_id',
                'args' => array(
                    'laber_for' => 'event_status_button',
                    'class' => 'example-class'
                ),

            )
        );

        $this->settings->ch_setFields($args);
    }

    public function ch_eventposts()
    {
        /**
         * Enable the event custom post type
         * http://codex.wordpress.org/Function_Reference/register_post_type
         */
        $labels = array(
            'name' => __('Events', 'k7'),
            'singular_name' => __('Event', 'k7'),
            'add_new' => __('Add New Event', 'k7'),
            'add_new_item' => __('Add New Event', 'k7'),
            'edit_item' => __('Edit Event', 'k7'),
            'new_item' => __('Add New Event', 'k7'),
            'view_item' => __('View Event', 'k7'),
            'search_items' => __('Search Events', 'k7'),
            'not_found' => __('No events found', 'k7'),
            'not_found_in_trash' => __('No events found in trash', 'k7')
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'comments'),
            'capability_type' => 'post',
            'rewrite' => array("slug" => "event"), // Permalinks format
            'hierarchical' => true,
            'menu_position' => 5,
            'has_archive' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav' => true,
            'query_var' => true,
            'hierarchical' => true,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-calendar-alt',  // Icon Path

        );
        register_post_type('event', $args);
    }

    /**
     * Adds event post metaboxes for start time and end time
     * http://codex.wordpress.org/Function_Reference/add_meta_box
     *
     * We want two time event metaboxes, one for the start time and one for the end time.
     * Two avoid repeating code, we'll just pass the $identifier in a callback.
     * If you wanted to add this to regular posts instead, just swap 'event' for 'post' in add_meta_box.
     */
    public function ch_eventposts_metaboxes()
    {
        add_meta_box('church_event_date_start', 'Start Date and Time', array($this, 'church_event_date'), 'event', 'side', 'default', array('id' => '_start'));
        add_meta_box('church_event_date_end', 'End Date and Time', array($this, 'church_event_date'), 'event', 'side', 'default', array('id' => '_end'));
        
        add_meta_box(
            'church_event_location',
            'Event Location', 
            array($this, 'church_event_location'), 
            'event', 'normal', 
            'default', array('id' => '_end'));
    }

    // Metabox HTML
    public function church_event_date($post, $args)
    {
        $metabox_id = $args['args']['id'];
        global $post, $wp_locale, $postEvent;

        $postEvent = $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'ch_eventposts_nonce');
        $time_adj = current_time('timestamp');
        $month = get_post_meta($post->ID, $metabox_id . '_month', true);
        if (empty($month)) {
            $month = gmdate('m', $time_adj);
        }
        $day = get_post_meta($post->ID, $metabox_id . '_day', true);
        if (empty($day)) {
            $day = gmdate('d', $time_adj);
        }
        $year = get_post_meta($post->ID, $metabox_id . '_year', true);
        if (empty($year)) {
            $year = gmdate('Y', $time_adj);
        }

        $hour = get_post_meta($post->ID, $metabox_id . '_hour', true);

        if (empty($hour)) {
            $hour = gmdate('H', $time_adj);
        }

        $min = get_post_meta($post->ID, $metabox_id . '_minute', true);

        if (empty($min)) {
            $min = '00';
        }
        $month_s = '<select name="' . $metabox_id . '_month">';
        for ($i = 1; $i < 13; $i = $i + 1) {
            $month_s .= "\t\t\t" . '<option value="' . zeroise($i, 2) . '"';
            if ($i == $month)
                $month_s .= ' selected="selected"';
            $month_s .= '>' . $wp_locale->get_month_abbrev($wp_locale->get_month($i)) . "</option>\n";
        }
        $month_s .= '</select>';
        echo $month_s;
        echo '<input type="text" name="' . $metabox_id . '_day" value="' . $day . '" size="2" maxlength="2" />';
        echo '<input type="text" name="' . $metabox_id . '_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
        echo '<input type="text" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
        echo '<input type="text" name="' . $metabox_id . '_minute" value="' . $min . '" size="2" maxlength="2" />';


    }

    public function church_event_location()
    {
        global $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'ch_eventposts_nonce');
        // The metabox HTML
        $event_location = get_post_meta($post->ID, '_event_location', true);
        $event_city = get_post_meta($post->ID, '_event_city', true);
        $event_address = get_post_meta($post->ID, '_event_address', true);
        $event_email = get_post_meta($post->ID, '_event_email', true);
        $event_phone = get_post_meta($post->ID, '_event_phone', true); ?>


        <div class="field-container">

            <div class="field">
                <label for="_event_location"><?php _e('Location:');?></label><br>
                <small><?php _e( 'location where the event will take place', 'k7' );?></small>
                <input type="text" name="_event_location" value="<?php echo $event_location;?>" />
            </div>
            
            <hr>
            <div class="field">
                <label for="_event_city"><?php _e('City:');?></label><br>
                <small><?php _e( 'City/Province where the event will take place', 'k7' );?></small>
                <input type="text" name="_event_city" value="<?php echo $event_city;?>" />
            </div>
            
            <hr>        
            <div class="field">
                <label for="_event_address"><?php _e('Adress:');?></label><br>
                <small><?php _e( 'Event Address', 'k7' );?></small>
                <input type="text" name="_event_address" value="<?php echo $event_address;?>" />
            </div>
            
            <hr>
            <div class="field">
                <label for="_event_email"><?php _e('Email:');?></label><br>
                <small><?php _e( 'Event or organizer email', 'k7' );?></small>
                <input type="email" name="_event_email" value="<?php echo $event_email;?>" />
            </div>
            <hr>
            <div class="field">
                <label for="_event_phone"><?php _e('Phone:');?></label><br>
                <small><?php _e( 'Event Phone', 'k7' );?></small>
                <input type="tel" name="_event_phone" value="<?php echo $event_phone;?>" />
            </div>
        </div>
        <?php
    }

    // Save the Metabox Data
    public function ch_eventposts_save_meta($post_id, $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['ch_eventposts_nonce']))
            return;
        if (!wp_verify_nonce($_POST['ch_eventposts_nonce'], plugin_basename(__FILE__)))
            return;
        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID))
            return;
        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though

        $metabox_ids = array('_start', '_end');
        foreach ($metabox_ids as $key) {

            $aa = $_POST[$key . '_year'];
            $mm = $_POST[$key . '_month'];
            $jj = $_POST[$key . '_day'];
            $hh = $_POST[$key . '_hour'];
            $mn = $_POST[$key . '_minute'];

            $aa = ($aa <= 0) ? date('Y') : $aa;
            $mm = ($mm <= 0) ? date('n') : $mm;
            $jj = sprintf('%02d', $jj);
            $jj = ($jj > 31) ? 31 : $jj;
            $jj = ($jj <= 0) ? date('j') : $jj;
            $hh = sprintf('%02d', $hh);
            $hh = ($hh > 23) ? 23 : $hh;
            $mn = sprintf('%02d', $mn);
            $mn = ($mn > 59) ? 59 : $mn;

            $events_meta[$key . '_year'] = $aa;
            $events_meta[$key . '_month'] = $mm;
            $events_meta[$key . '_day'] = $jj;
            $events_meta[$key . '_hour'] = $hh;
            $events_meta[$key . '_minute'] = $mn;
            $events_meta[$key . '_eventtimestamp'] = $aa . '-' . $mm . '-' . $jj . ' ' . $hh . ':' . $mn;

        }

        // Save Locations Meta



        $events_meta['_event_location'] = $_POST['_event_location'];
        $events_meta['_event_city'] = $_POST['_event_city'];
        $events_meta['_event_address'] = $_POST['_event_address'];
        $events_meta['_event_email'] = $_POST['_event_email'];
        $events_meta['_event_phone'] = $_POST['_event_phone'];


        // Add values of $events_meta as custom fields
        foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
            if ($post->post_type == 'revision') return; // Don't store custom data twice
            $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
            if (get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
                update_post_meta($post->ID, $key, $value);
            } else { // If the custom field doesn't have a value
                add_post_meta($post->ID, $key, $value);
            }
            if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
        }
    }
    /**
     * Helpers to display the date on the front end
     */
    // Get the Month Abbreviation

    public function ch_get_the_month_abbr($month)
    {
        global $wp_locale;
        for ($i = 1; $i < 13; $i = $i + 1) {
            if ($i == $month)
                $monthabbr = $wp_locale->get_month_abbrev($wp_locale->get_month($i));
        }
        return $monthabbr;
    }

    // Display the date

    public function ch_get_the_event_date()
    {
        global $post;


        $eventdate = '';
        $month = get_post_meta($post->ID, '_month', true);
        $eventdate = ch_get_the_month_abbr($month);
        $eventdate .= ' ' . get_post_meta($post->ID, '_day', true) . ',';
        $eventdate .= ' ' . get_post_meta($post->ID, '_year', true);
        $eventdate .= ' at ' . get_post_meta($post->ID, '_hour', true);
        $eventdate .= ':' . get_post_meta($post->ID, '_minute', true);
        echo $eventdate;
    }


    /**
     * Customize Event Query using Post Meta
     *
     * @link http://www.billerickson.net/customize-the-wordpress-query/
     * @param object $query data
     *
     */
    public function ch_event_query($query)
    {


        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
        $current_timestamp = $today_year . $today_month . $today_day . $hour . $minute;
        global $wp_the_query;

        if ($wp_the_query === $query && !is_admin() && is_post_type_archive('events')) {
            $meta_query = array(
                array(
                    'key' => '_start_eventtimestamp',
                    'value' => $current_timestamp,
                    'compare' => '>'
                )
            );
            $query->set('meta_query', $meta_query);
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_start_eventtimestamp');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', '2');
        }


    }

    //shortcode display
    public function ch_event_shortcode_output($atts, $content = '', $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'event_id' => '',
                'number_of_event' => -1)
          , $atts, $tag);


        //uses the main output function of the location class
        return $this->ch_get_event_output($arguments);

    }


    public function ch_prepend_event_meta_to_content($content)
    {
        global $post, $post_type, $wp_locale;

        $event = $post;


        $current_time = current_time('mysql');
        list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;


        if ($post_type == 'event' && is_singular('event')) {?>
        	<body onload='verHora()'><h3 id='relogio'></h3></body>
<?php

?>
                    <img src="<?php echo $this->plugin_url."/assets/icon/compose.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Publish date:', 'k7');?></strong><?php echo the_date('M d Y'); ?><br>
                    <?php
                    // Gets the event start month from the meta field
                    $month = get_post_meta($event->ID, '_start_month', true);
                    // Converts the month number to the month name
                    $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
                    // Gets the event start day
                    $day = get_post_meta($event->ID, '_start_day', true);
                    // Gets the event start year
                    $year = get_post_meta($event->ID, '_start_year', true);
                    ?>

                     <?php
                    $started = "";
                    $end = get_post_meta($event->ID, '_end_eventtimestamp', true);
                    $current_timestamp = $current_timestamp;

                    if (get_post_meta($event->ID, '_start_eventtimestamp', true) <= $end && $end > $current_timestamp && $end) {

                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_started').";'>\t\n" . esc_html__('Has started', 'k7'). '</b>';
                    }
                    if ($end < $current_timestamp) {
                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_finished').";'>\t\n" . esc_html__('Has finished', 'k7') . '</b>';

                    }
                    if ($end > $current_timestamp && $current_timestamp < get_post_meta($event->ID, '_start_eventtimestamp', true)) {
                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_soon') ?? 'black' .";'>\t\n" . esc_html__('It will start soon', 'k7') . '</b>';

                    } ?>
               <img src="<?php echo $this->plugin_url."/assets/icon/clock.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Event start date:', 'k7');?></strong><?php echo "\t\n".$month . ' ' . $day . ' ' . $year; ?><br>
                   <img src="<?php echo $this->plugin_url."/assets/icon/timestampdate.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Start event timestamp:', 'k7');?></strong><?php echo "\t\n".get_post_meta($event->ID, '_start_eventtimestamp', true) . $started; ?><br>
                   <img src="<?php echo $this->plugin_url."/assets/icon/finish.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('End event timestamp:', 'k7');?></strong><?php echo "\t\n".$end; ?><br>

                    <div class="ch-col-12">
                        <div class="ch-row">
                            <div class="ch-col-6">

                                <label class="ch-center"><img src="<?php echo $this->plugin_url."/assets/icon/location.svg";?>" style="width:40px; height:40px;"><h2><?php _e(' Location', 'k7');?></h2></label>  

                                <hr>
                                <strong><?php _e('Event Location:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_location', true );?></small><br>
                                <strong><?php _e('Event City/Province:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_city', true );?></small><br>

                                <hr>
                                <strong><?php _e('Event Address:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_address', true );?></small><br>
                                <strong><?php _e('Event Location:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_address', true );?></small><br>

                            </div>
                            <div class="ch-col-6">

                                <label class="ch-center"><img src="<?php echo $this->plugin_url."/assets/icon/contacteditor.svg";?>" style="width:40px; height:40px;"><h2><?php _e(' Contact', 'k7');?></h2></label>  
                                <hr>
                                <strong><?php _e('Event Phone:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_phone', true );?></small><br>
                                <strong><?php _e('Event Phone:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_phone', true );?></small><br>

                                <hr>
                                <strong><?php _e('Event Email:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_email', true );?></small><br>
                                <strong><?php _e('Event or organizer email:', 'k7');?></strong><small><?php echo "\t\n".get_post_meta( $event->ID, '_event_email', true );?></small><br>
                            </div>
                        </div>
                    </div>

                    <?php $event_permalink = get_permalink($event);
                    $html = '';
                    $html .= '<h2 class="ch-title">';
                    $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view location', 'k7') . '">';
                    $html .= '</a>';
                    $html .= '</h2>'; ?>

            <?php
            $html .= $content;


            return $html;

        } else {
            return $content;

        }
    }

    public function ch_get_event_output($arguments)
    {

        global $wp_locale;

        //default args
        $default_args = array(
            'event_id' => '',
            'number_of_event' => -1,
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
        $event_args = array(
            'post_type' => 'event',
            'posts_per_page' => $default_args['number_of_event'],
            'post_name' => '',
            'post_status' => 'publish',
            'meta_key' => '_start_eventtimestamp',
            'orderby' => 'meta_value_num',
        );
        //if we passed in a single location to display
        if (!empty($default_args['event_id'])) {
            $event_args['include'] = $default_args['event_id'];
        }

        //output
        $html = '';
        $events = get_posts($event_args);
        //if we have locations
        

        if ($events) {
            $html .= '<section class="ch-col-12 location" style="background-color: '. get_option( 'event_border_color') .'">';?>
<body onload=display_ct();>
<span id='ct' ></span>
<?php
            $events = get_posts($event_args);

            foreach ($events as $event) {
                $html .= '<article class="ch-col-12 location_list cf" style="background-color: #fff;">';

                $event_id = $event->ID;
                $event_permalink = get_permalink($event);
                $event_title = get_the_title($event);

                $event_thumbnail = get_the_post_thumbnail($event_id, 'thumbnail'); ?>

                <div class="ch-col-1 image_content"><?php

                $event_content = apply_filters('the_content', $event->post_content); ?> </div><?php
                if (!empty($event_content)) {
                    $event_content = strip_shortcodes(wp_trim_words($event_content, 50, '...'));
                }

                // http://codex.wordpress.org/Function_Reference/current_time
                $current_time = current_time('mysql');
                list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
                $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;


                $html .= '<h2 class="title">';
                $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event', 'k7') . '">';
                $html .= $event_title;
                $html .= '</a>';
                $html .= '</h2>';
                echo $html; ?>
                        <body onload='verHora()'><h3 id='relogio'></h3></body>


                    <img src="<?php echo $this->plugin_url."/assets/icon/compose.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Publish date:', 'k7');?></strong><?php the_date('M d Y'); ?><br>
                    <?php
                    // Gets the event start month from the meta field
                    $month = get_post_meta($event->ID, '_start_month', true);
                    // Converts the month number to the month name
                    $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
                    // Gets the event start day
                    $day = get_post_meta($event->ID, '_start_day', true);
                    // Gets the event start year
                    $year = get_post_meta($event->ID, '_start_year', true);
                    ?>

                    <?php
                    $started = "";
                    $end = get_post_meta($event->ID, '_end_eventtimestamp', true);
                    $current_timestamp = $current_timestamp;

                    if (get_post_meta($event->ID, '_start_eventtimestamp', true) <= $end && $end > $current_timestamp && $end) {

                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_started').";'>\t\n" . esc_html__('Has started', 'k7'). '</b>';
                    }
                    if ($end < $current_timestamp) {
                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_finished').";'>\t\n" . esc_html__('Has finished', 'k7') . '</b>';

                    }
                    if ($end > $current_timestamp && $current_timestamp < get_post_meta($event->ID, '_start_eventtimestamp', true)) {
                        $started = "\t\n<b style='border: 6px solid ". get_option('event_status_soon') ?? 'black' .";'>\t\n" . esc_html__('It will start soon', 'k7') . '</b>';

                    } ?>

                    <img src="<?php echo $this->plugin_url."/assets/icon/clock.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Event start date:', 'k7');?></strong><?php echo "\t\n".$month . ' ' . $day . ' ' . $year; ?><br>
                   
                   <img src="<?php echo $this->plugin_url."/assets/icon/timestampdate.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('Start event timestamp:', 'k7');?></strong><?php echo "\t\n".get_post_meta($event->ID, '_start_eventtimestamp', true) . $started; ?><br>
                   
                   <img src="<?php echo $this->plugin_url."/assets/icon/finish.svg";?>" style="width:20px; height:20px;"><strong><?php echo "\t\n". __('End event timestamp:', 'k7');?></strong><?php echo "\t\n".$end; ?><br>

                       
                <?php


                $html = '';

                //image & content
                if (!empty($event_thumbnail) || !empty($event_content)) {

                    if (!empty($event_thumbnail)) {
                        $html .= '<p class="ch-col-4 image_content">';
                        $html .= $event_thumbnail;
                        $html .= '</p>';
                    }
                    if (!empty($event_content)) {
                        $html .= '<p>';
                        $html .= $event_content;
                        $html .= '</p>';
                    }

                }
                /** end imge */

                //(lets third parties hook into the HTML output to output data)

                //readmore
                $html .= '<a class="link" href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event', 'k7') . '">' . __('View Event', 'k7') . '</a>';
                $html .= '</section>';

            }

            $html .= '</article>';
            $html .= '<div class="cf"></div>';
        }

        do_action('pre_get_posts', $arg = '');

        return $html;
    }

}