<?php
/**
 * @version 1.0.15
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;



class Church_EventController extends Church_BaseController

{

    public function ch_register()
    {

        $this->settings = new Church_SettingsApi();

        $this->callbacks = new Church_EventCallbacks();

        add_action('init' , array($this , 'ch_eventposts'));
        add_action('pre_get_posts' , array($this , 'ch_query'));
        add_action('admin_init' , array($this , 'ch_eventposts_metaboxes'));
        add_action('save_post' , array($this , 'ch_eventposts_save_meta') , 1 , 2);
        //         add_action('manage_church_posts_columns', array($this, 'ch_set_custom_columns'));
        // add_action('manage_church_posts_custom_column', array($this, 'ch_set_custom_columns_data'), 10, 2);
        // add_filter('manage_edit_church_sortable_columns', array($this, 'ch_set_church_custom_columns_sortable'));

        add_filter('the_content' , array($this , 'ch_prepend_church_meta_to_content')); //gets our meta data and dispayed it before the content
        add_shortcode('events' , array($this , 'ch_shortcode_output'));

        $this->ch_setSections();
        $this->ch_setFields();
        $this->ch_setSettings();
        $this->ch_setSubpages();

    }

    public function ch_setSubpages()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=event' ,
                'page_title' => 'Settings' ,
                'menu_title' => 'Settings' ,
                'capability' => 'manage_options' ,
                'menu_slug' => 'event_settings' ,
                'callback' => array($this->callbacks , 'ch_settings')
            ),

        );

        $this->settings->ch_addSubPages($subpage)->ch_register();
    }


    public function ch_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_border_color' ,
                'callback' => array($this->callbacks , 'ch_sanitize_color')

            ),
            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_status_started'
            ) ,
            array(


                'option_group' => 'church_options_group' ,
                'option_name' => 'church_status_finished'
            ) ,
            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_status_soon'
            ),
            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_status_button'
            ),

            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_currency',
                'callback' => array($this->callbacks , 'ch_validate_currency')

            ),
            array(
                'option_group' => 'church_options_group' ,
                'option_name' => 'church_background_color_button_show_form',
                'callback' => array($this->callbacks , 'ch_sanitize_background_color')
            ),

            array('option_group' => 'church_options_group',
                'option_name' => 'church_text_color_button_show_form',
                'callback' => array($this->callbacks , 'ch_sanitize_text_color')

        ),


        );
        $this->settings->ch_setSettings($args);
    }

    public function ch_setSections()
    {
        $args = array(
            array(
                'id' => 'church_id' ,
                'title' => __( 'Settings', 'k7-church') ,
                'callback' => array($this->callbacks , 'ch_section') ,
                'page' => 'church_settings'

            ),
            array(
                'id' => 'church_color' ,
                'title' => __( 'Color Control', 'k7-church') ,
                'callback' => array($this->callbacks , 'ch_section_color') ,
                'page' => 'colors'

            )
        );

        $this->settings->ch_setSections($args);
    }

    public function ch_setFields()
    {
        $args = array(

            array(
                'id' => 'church_currency' ,
                'title' => __('Currency ', 'k7-church') ,
                'callback' => array($this->callbacks , 'ch_currency') ,
                'page' => 'church_settings' ,
                'section' => 'church_id' ,
                'args' => array(
                    'laber_for' => 'church_currency' ,
                    'class' => 'example-class'
                ),

            ),

            array(
                'id' => 'church_border_color' ,
                'title' => __('Border color', 'k7-church') ,
                'callback' => array($this->callbacks , 'ch_textFields_border') ,
                'page' => 'colors' ,
                'section' => 'church_color' ,
                'args' => array(
                    'laber_for' => 'church_border_color' ,
                    'class' => 'example-class'
                ),

            ),
            array(
                'id'=> 'church_background_color_button_show_form',
                 'title' => __('Color background button', 'k7-church'),
                 'callback' => array($this->callbacks, 'ch_chanche_background_color_button'),
                 'page' => 'colors',
                 'section'=> 'church_color',
                 'args' => array(
                    'label' => 'church_background_color_button_show_form',
                    'class' => 'exemple-class'
                 ),
            ),

            array(
                'id'=> 'church_text_color_button_show_form',
                 'title' => __('Button text color', 'k7-church'),
                 'callback' => array($this->callbacks, 'ch_chanche_text_color_button'),
                 'page' => 'colors',
                 'section'=> 'church_color',
                 'args' => array(
                    'label' => 'church_text_color_button_show_form',
                    'class' => 'exemple-class'
                 ),
            ),

        );

        $this->settings->ch_setFields($args);
    }

    public function ch_eventposts()
    {
        /**
         * Enable the event custom post type
         * http://codex.wordpress.org/Function_Reference/register_post_type
         */
        //Labels for post type
        $labels = array(
            'name' => __('Event' , 'k7-church') ,
            'singular_name' => __('Event' , 'k7-church') ,
            'menu_name' => __('Events' , 'k7-church') ,
            'name_admin_bar' => __('Event' , 'k7-church') ,
            'add_new' => __('Add new' , 'k7-church') ,
            'add_new_item' => __('Add new event' , 'k7-church') ,
            'new_item' => __('New event' , 'k7-church') ,
            'edit_item' => __('Edit event' , 'k7-church') ,
            'view_item' => __('View event' , 'k7-church') ,
            'all_items' => __('All events' , 'k7-church') ,
            'search_items' => __('Search events' , 'k7-church') ,
            'parent_item_colon' => __('Parent event:' , 'k7-church') ,
            'not_found' => __('No Event found.', 'k7-church') ,
            'not_found_in_trash' => __('No events found in trash.' , 'k7-church') ,
        );
        //arguments for post type
        $args = array(
            'labels' => $labels ,
            'public' => true ,
            'publicly_queryable' => true ,
            'show_ui' => true ,
            'show_in_nav' => true ,
            'query_var' => true ,
            'hierarchical' => true ,
            'supports' => array('title' , 'thumbnail' , 'editor') ,
            'has_archive' => true ,
            'menu_position' => 20 ,
            'show_in_admin_bar' => true ,
            'menu_icon' => 'dashicons-calendar-alt' ,
            'rewrite' => array('slug' => 'event' , 'with_front' => 'true')
        );
        //register post type
        register_post_type('event' , $args);
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
        add_meta_box('church_date_start' , __( 'Start Date and Time', 'k7-church') , array($this , 'church_date') , 'event' , 'side' , 'default' , array('id' => '_start'));
        add_meta_box('church_date_end' , __('End Date and Time', 'k7-church') , array($this , 'church_date') , 'event' , 'side' , 'default' , array('id' => '_end'));

        add_meta_box(
            'church_location' ,
            __( 'Event Location', 'k7-church'),
            array($this , 'church_location') ,
            'event' , 'normal' ,
            'default' , array('id' => '_end'));
    }

    // Metabox HTML
    public function church_date($post , $args)
    {
        $metabox_id = '_ch'. $args['args']['id'];

        global $post , $wp_locale , $postEvent;

        $postEvent = $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__) , 'ch_eventposts_nonce');
        $time_adj = current_time('timestamp');
        $month = get_post_meta($post->ID , $metabox_id . '_month' , true);
        if (empty($month)) {
            $month = gmdate('m' , $time_adj);
        }
        $day = get_post_meta($post->ID , $metabox_id . '_day' , true);
        if (empty($day)) {
            $day = gmdate('d' , $time_adj);
        }
        $year = get_post_meta($post->ID , $metabox_id . '_year' , true);
        if (empty($year)) {
            $year = gmdate('Y' , $time_adj);
        }

        $hour = get_post_meta($post->ID , $metabox_id . '_hour' , true);

        if (empty($hour)) {
            $hour = gmdate('H' , $time_adj);
        }

        $min = get_post_meta($post->ID , $metabox_id . '_minute' , true);

        if (empty($min)) {
            $min = '00';
        }
        $month_s = '<select name="' . $metabox_id . '_month">';
        for ($i = 1; $i < 13; $i = $i + 1) {
            $month_s .= "\t\t\t" . '<option value="' . zeroise($i , 2) . '"';
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

    public function church_location()
    {
        global $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__) , 'ch_eventposts_nonce');
        // The metabox HTML
        $event_country = get_post_meta($post->ID , '_ch_event_country' , true);
        $event_city = get_post_meta($post->ID , '_ch_event_city' , true);
        $event_address = get_post_meta($post->ID , '_ch_event_address' , true);
        $event_email = get_post_meta($post->ID , '_ch_event_email' , true);
        $event_organizer = get_post_meta($post->ID , '_ch_event_organizer' , true);
        $event_phone = get_post_meta($post->ID , '_ch_event_phone' , true);
        $event_phone_2 = get_post_meta($post->ID , '_ch_event_phone_2' , true);
        $event_street = get_post_meta($post->ID , '_ch_event_street' , true);
        $event_partici = get_post_meta($post->ID , '_ch_event_partic_limit' , true); 
        $event_price = get_post_meta($post->ID , '_ch_event_price' , true); ?>


        <div class="field-container">

            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-2">
                        <label for="_ch_event_price"><?php _e('Event price :'); ?></label>
                        <input type="text" name="_ch_event_price" placeholder="99.00" value="<?php echo $event_price; ?>" />

                    </div>
                    <div class="ch-col-3">
                        <label for="_ch_event_currency"><?php _e('Event Currency :'); ?></label>
                            <?php echo $value = esc_attr( get_option( 'church_currency' ) );?>
                    </div>
                    <div class="ch-col-12">
                         <label for="_ch_event_partic_limit"><?php _e('Event Participe Limits:'); ?></label>
                        <input type="text" name="_ch_event_partic_limit" placeholder="10" value="<?php echo $event_partici; ?>"/>
            
                    </div>
                </div>
            </div>
            <hr>
            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-5">
                        <label for="_ch_event_country"><?php _e('Event Country:'); ?></label>
                        <input type="text" name="_ch_event_country" value="<?php echo $event_country; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ch_event_city"><?php _e('Event City:'); ?></label>
                        <input type="text" name="_ch_event_city" value="<?php echo $event_city; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ch_event_address"><?php _e('Event Address:'); ?></label>
                        <input type="text" name="_ch_event_address" value="<?php echo $event_address; ?>"/>
                    </div>
                    <div class="ch-col-5">
                         <label for="_ch_event_street"><?php _e('Event Street:'); ?></label>
                         <input type="text" name="_ch_event_street" value="<?php echo $event_street; ?>"/>
                    </div>

                </div>
            </div>
            <hr>
            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-5">
                        <label for="_ch_event_email"><?php _e('Event Email:'); ?></label>
                        <input type="email" name="_ch_event_email" value="<?php echo $event_email; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ch_event_organizer"><?php _e('Event Organizers email:'); ?></label>
                        <input type="email" name="_ch_event_organizer" value="<?php echo $event_organizer; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ch_event_phone"><?php _e('Event Phone:'); ?></label>
                        <input type="tel" name="_ch_event_phone" value="<?php echo $event_phone; ?>"/>
                    </div>
                </div>
            <div class="field">
                <label for="_ch_event_phone_2"><?php _e('Event Phone 2:'); ?></label>
                <input type="tel" name="_ch_event_phone_2" value="<?php echo $event_phone_2; ?>"/>
            </div>
        </div>
        </div>
        <?php
    }

   // Save the Metabox Data
    public function ch_eventposts_save_meta($post_id , $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['ch_eventposts_nonce']))
            return;
        if (!wp_verify_nonce($_POST['ch_eventposts_nonce'] , plugin_basename(__FILE__)))
            return;
        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post' , $post->ID))
            return;
        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though

        $metabox_ids = array('_ch_start' , '_ch_end');
        foreach ($metabox_ids as $key) {

            $aa = $_POST[$key . '_year'];
            $mm = $_POST[$key . '_month'];
            $jj = $_POST[$key . '_day'];
            $hh = $_POST[$key . '_hour'];
            $mn = $_POST[$key . '_minute'];

            $aa = ($aa <= 0) ? date('Y') : $aa;
            $mm = ($mm <= 0) ? date('n') : $mm;
            $jj = sprintf('%02d' , $jj);
            $jj = ($jj > 31) ? 31 : $jj;
            $jj = ($jj <= 0) ? date('j') : $jj;
            $hh = sprintf('%02d' , $hh);
            $hh = ($hh > 23) ? 23 : $hh;
            $mn = sprintf('%02d' , $mn);
            $mn = ($mn > 59) ? 59 : $mn;

            $events_meta[$key . '_year'] = $aa;
            $events_meta[$key . '_month'] = $mm;
            $events_meta[$key . '_day'] = $jj;
            $events_meta[$key . '_hour'] = $hh;
            $events_meta[$key . '_minute'] = $mn;
            $events_meta[$key . '_eventtimestamp'] = $aa . '-' . $mm . '-' . $jj . ' ' . $hh . ':' . $mn;

        }

        // Save Locations Meta

        $events_meta['_ch_event_country'] = sanitize_text_field($_POST['_ch_event_country']);
        $events_meta['_ch_event_city'] = sanitize_text_field($_POST['_ch_event_city']);
        $events_meta['_ch_event_address'] = sanitize_text_field($_POST['_ch_event_address']);
        $events_meta['_ch_event_email'] = sanitize_email($_POST['_ch_event_email']);
        $events_meta['_ch_event_organizer'] = sanitize_text_field($_POST['_ch_event_organizer']);
        $events_meta['_ch_event_phone'] = $this->callbacks->ch_sanitize_number($_POST['_ch_event_phone']);
        $events_meta['_ch_event_phone_2'] = $this->callbacks->ch_sanitize_number($_POST['_ch_event_phone_2']);
        $events_meta['_ch_event_street'] = sanitize_text_field($_POST['_ch_event_street']);
        $events_meta['_ch_event_partic_limit'] = $this->callbacks->ch_sanitize_number($_POST['_ch_event_partic_limit']);
        $events_meta['_ch_event_price'] = $this->callbacks->ch_sanitize_number($_POST['_ch_event_price']);


        // Add values of $events_meta as custom fields
        foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
            if ($post->post_type == 'revision') return; // Don't store custom data twice
            $value = implode(',' , (array)$value); // If $value is an array, make it a CSV (unlikely)
            if (get_post_meta($post->ID , $key , FALSE)) { // If the custom field already has a value
                update_post_meta($post->ID , $key , $value);
            } else { // If the custom field doesn't have a value
                add_post_meta($post->ID , $key , $value);
            }
            if (!$value) delete_post_meta($post->ID , $key); // Delete if blank
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

    public function ch_get_the_church_date()
    {
        global $post;


        $eventdate = '';
        $month = get_post_meta($post->ID , '_month' , true);
        $eventdate = ch_get_the_month_abbr($month);
        $eventdate .= ' ' . get_post_meta($post->ID , '_day' , true) . ',';
        $eventdate .= ' ' . get_post_meta($post->ID , '_year' , true);
        $eventdate .= ' at ' . get_post_meta($post->ID , '_hour' , true);
        $eventdate .= ':' . get_post_meta($post->ID , '_minute' , true);
        return $eventdate;
    }

    /**
     * Customize Event Query using Post Meta
     *
     * @link http://www.billerickson.net/customize-the-wordpress-query/
     * @param object $query data
     *
     */
    public function ch_query($query)
    {

        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . $today_month . $today_day . $hour . $minute;
        global $wp_the_query;

        if ($wp_the_query === $query && !is_admin() && is_post_type_archive('events')) {
            $meta_query = array(
                array(
                    'key' => '_ch_start_eventtimestamp' ,
                    'value' => $current_timestamp ,
                    'compare' => '>'
                )
            );
            $query->set('meta_query' , $meta_query);
            $query->set('orderby' , 'meta_value_num');
            $query->set('meta_key' , '_ch_start_eventtimestamp');
            $query->set('order' , 'ASC');
            $query->set('posts_per_page' , '2');
        }

    }

    //shortcode display
    public function ch_shortcode_output($atts , $content = '' , $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'church_id' => '' ,
                'number_of_event' => -1)
            , $atts , $tag);

        //uses the main output function of the location class
        return $this->ch_get_church_output($arguments);

    }

    public function ch_prepend_church_meta_to_content($content)
    {
        global $post , $post_type , $wp_locale;

        $event = $post;


        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;


        if ($post_type == 'event' && is_singular('event')) { ?>
            <body onload='verHora()'><h3 id='relogio'></h3>
            <?php

            ?>
            <div class="ch-col-12">
                <div class="ch-row">
                        <div class="ch-col-7">

            <img src="<?php echo $this->plugin_url . "/assets/icon/compose.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('Publish date:' , 'k7-church'); ?></strong><?php echo the_date('M d Y'); ?><br>
            <?php
            // Gets the event start month from the meta field
            $month = get_post_meta($event->ID , '_ch_start_month' , true);
            // Converts the month number to the month name
            $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
            // Gets the event start day
            $day = get_post_meta($event->ID , '_ch_start_day' , true);
            // Gets the event start year
            $year = get_post_meta($event->ID , '_ch_start_year' , true);
            $event_partici = get_post_meta($event->ID , '_ch_event_partic_limit' , true);
            ?>

            <?php

            $endEvent = get_post_meta($event->ID , '_ch_end_eventtimestamp' , true);

            ?>
            <img src="<?php echo $this->plugin_url . "/assets/icon/clock.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('Event start date:' , 'k7-church'); ?></strong><?php echo "\t\n" . $month . ' ' . $day . ' ' . $year; ?>
            <br>
            <img src="<?php echo $this->plugin_url . "/assets/icon/timestampdate.svg"; ?>"
                 style="width:20px; height:20px;">

            <strong><?php echo "\t\n" . __('Start event timestamp:' , 'k7-church'); ?></strong><?php echo "\t\n" . $this->callbacks->formatDate(get_post_meta($event->ID , '_ch_start_eventtimestamp' , true)); ?>
            <br>
            <img src="<?php echo $this->plugin_url . "/assets/icon/finish.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('End event timestamp:' , 'k7-church'); ?></strong><?php echo "\t\n" . $this->callbacks->formatDate($endEvent); ?><br>
                </div>
                    <div class="ch-col-5">
                        <?php $price = get_post_meta($event->ID , '_ch_event_price' , true);

                             $currency = get_option( 'church_currency', true );

                             if( empty($price) ){
                                $price = __('Free', 'k7-church');
                                $currency = null;
                             }
                        ?>
                        <strong><?php printf( __('Price: %s %s ', 'k7-church'), $price, $currency );?>  </strong>
                    </div>
                    <hr>
                    <div class="ch-col-5">
                        <?php 

                            $all_post_ids = get_posts(array(
                                'fields'          => 'post_id',
                                'posts_per_page'  => -1,
                                'post_type' => 'participant'
                            ));
                            $total = [];
                        foreach ($all_post_ids as $k => $v) {
                            $count = get_post_meta( $v->ID, '_event_participant_key', false );
                                foreach ($count as $key => $value) {
                                        if($value['post_event_id'] == $event->ID && $value['approved'] == 1){
                                            $total[] = $value['post_event_id'];
                                    ?>
                                <?php 
                                } }
                            }

                            $number = 0;
                            if($event_partici != 0){
                                $number = ($event_partici - count($total));
                            }else{
                                $number = 0;
                            }
                            ?>

                            <strong><?php printf( __('Nº of places available:  %s ', 'k7-church'), $number); ?></strong><br><hr>
                                <strong><?php printf( 
                                    __('Nº of participants: %s', 'k7-church'), count($total) 
                                    ); ?></strong>
                    </div>
                </div>
            </div>

            <div class="ch-col-12">
                <div class="ch-row">
                    <div class="ch-col-6">

                        <label class="ch-center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/location.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Location' , 'k7-church'); ?></h2></label>

                        <hr>
                        <strong><?php _e('Event County:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_country' , true); ?></small>
                        <br>
                        <strong><?php _e('Event City/Province:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_city' , true); ?></small>
                        <br>

                        <hr>
                        <strong><?php _e('Event Address:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_address' , true); ?></small>
                        <br>
                        <strong><?php _e('Event Street:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_street' , true); ?></small>
                        <br>

                    </div>
                    <div class="ch-col-6">

                        <label class="ch-center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/contacteditor.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Contact' , 'k7-church'); ?></h2></label>
                        <hr>
                        <strong><?php _e('Event Phone:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_phone' , true); ?></small>
                        <br>
                        <strong><?php _e('Event Phone 2:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_phone_2' , true); ?></small>
                        <br>

                        <hr>
                        <strong><?php _e('Event Email:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_email' , true); ?></small>
                        <br>
                        <strong><?php _e('Event or organizer email:' , 'k7-church'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ch_event_organizer' , true); ?></small>
                        <br>
                    </div>

                </div>
            </div>

            
            <?php $event_permalink = get_permalink($event);
            $html = '';
            $html .= '<h2 class="ch-title">';
            $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-church') . '">';
            $html .= '</a>';
            $html .= '</h2>'; ?>

            <?php

            $html .= '<div class="ch-row"><div class="ch-col-6">';
            $html .= $content;
            $html .= '</div><div class="ch-col-6">';
            $html .= get_the_post_thumbnail($event->ID , 'thumbnail');
            $html .= "</div></div>"; 

            echo  $html; ?>
        

            <div class="ch-col-12">
                <div class="ch-row">
                    <hr>
                    <div class="ch-col-12">
                        <?php 
                        if($event_partici == count($total)){?>
                            <header class="header">
                                <button class="ch-tab" style="background: red;" onclick="myFunction()"><?php esc_html_e( 'INSCRIPTIONS ARE CLOSED! We have reached the maximum number of members, and that is why registration is closed.!', 'k7-church' );?></button>
                            </header>
                        <?php }else{
                            $this->get_participe_church_form();
                        } ?>
                    </div>
                </div>
            </div>
            <?php 


        } else {
            return $content;

        }
    }

    public function ch_get_church_output($arguments = "")
    {

        global $post;

        $wp_locale = new WP_Locale();

        add_image_size( 'church-thumb', 270, 175, false );



        //default args
        $default_args = array(
            'church_id' => '' ,
            'number_of_event' => -1
        );

        //update default args if we passed in new args
        if (!empty($arguments) && is_array($arguments)) {
            //go through each supplied argument
            foreach ($arguments as $arg_key => $arg_val) {
                //if this argument exists in our default argument, update its value
                if (array_key_exists($arg_key , $default_args)) {
                    $default_args[$arg_key] = $arg_val;
                }
            }
        }


        //find event
        $event_args = array(
            'post_type' => 'event' ,
            'posts_per_page' => $default_args['number_of_event'] ,
            'post_status' => 'publish' ,
            'meta_key' => '_ch_start_eventtimestamp' ,
            'orderby' => 'meta_value_num' ,

        );
        //if we passed in a single event to display
        if (!empty($default_args['church_id'])) {
            $event_args['include'] = $default_args['church_id'];
        }

        //output
        $html = '';
        $events = get_posts($event_args);

        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;

        //if we have event
        if ($events) {
            //foreach event
            foreach ($events as $key => $event) {
                                //title
                //collect event data
                $event_id = $event->ID;
                $event_title = get_the_title($event_id);
                $event_permalink = get_permalink($event_id);

                $html .= '<article class="ch-col-12 " style="border: 12px solid '.get_option( 'church_border_color').'">';
                $html .= '<div class="ch-row">';

                $html .= '<h2 class="ch-title">';
                $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-church') . '">';
                $html .= $event_title;
                $html .= '</a>';
                $html .= '</h2>';


                $html .= '<section class="ch-col-6 sermon" >';

                $event_thumbnail = get_the_post_thumbnail($event_id , 'church-thumb');
                $html .= '<div class="ch-col-1 image_content">';

                $event_content = apply_filters('the_content' , $event->post_content);
                $html .= '</div>';

                if (!empty($event_content)) {
                    $event_content = strip_shortcodes(wp_trim_words($event_content , 40 , '...'));
                }


                // http://codex.wordpress.org/Function_Reference/current_time
                $current_time = current_time('mysql');
                list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
                $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('church_before_main_content' , $html);

                //image & content
                if (!empty($event_thumbnail) || !empty($event_content)) {

                    if (!empty($event_thumbnail)) {
                        $html .= '<p class="ch-col-3 image_content">';
                        $html .= $event_thumbnail;

                        $html .= '</p>';
                    }else{
                    $html .= '<p class="ch-col-3 image_content">';
                    $html .= '<img src="' . $this->plugin_url . '/assets/images/no-image-available-icon-6.png">';
                    $html .= '</p>';


                    }
                    if (!empty($event_content)) {
                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/compose.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Publish date:'."\t\n" , 'k7-church') . '</strong>' . get_the_date('M d Y') . '<br>';

                        ?>
                        <body onload='verHora()'><h3 id='relogio'></h3></body><?php


                        // Gets the event start month from the meta field
                        $month = get_post_meta($event_id , '_ch_start_month' , true);
                        // Converts the month number to the month name
                        $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
                        // Gets the event start day
                        $day = get_post_meta($event_id , '_ch_start_day' , true);
                        // Gets the event start year
                        $year = get_post_meta($event_id , '_ch_start_year' , true);

                        $endEvent = get_post_meta($event_id , '_ch_end_eventtimestamp' , true);

                        $current_timestamp = $current_timestamp;


                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/clock.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Event start date:' , 'k7-church') . '</strong>' . "\t\n" . $month . ' ' . $day . ' ' . $year . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/timestampdate.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Start event timestamp:' , 'k7-church') . '</strong>' . "\t\n" . $this->callbacks->formatDate(get_post_meta($event_id , '_ch_start_eventtimestamp' , true)) . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/finish.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('End event timestamp:' , 'k7-church') . '</strong>' . "\t\n" . $this->callbacks->formatDate($endEvent) . '<br>';
                    $html .= '</section>';
                    $html .= '<div class="ch-col-6">';
                    $html .= $event_content;
                    $html .= '</div>';


                    }

                }
                
                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('church_after_main_content' , $html);

                //readmore
                $html .= '<a class="link" href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-church') . '">' . __('View Event' , 'k7-church') . '</a>';
            $html .= '</section>';
            $html .= '</article>';
            $html .= '<div class="cf"></div>';

            }// and foreach
        } // and if

        return $html;
    }

    public function get_participe_church_form(){
       echo  do_shortcode( '[particip-form]', false );

}

    // public function ch_set_church_custom_columns($columns)
    // {
    //     $title = $columns['title'];
    //     $date = $columns['date'];
    //     unset($columns['title'], $columns['date']);

    //     $columns['name'] = __('Event Name', 'k7-church');
    //     $columns['title'] = $title;
    //     $columns['Price'] =  __('Price', 'k7-church');
    //     $columns['Currency'] = __('Currency', 'k7-church');
    //     $columns['date'] = $date;

    //     return $columns;
    // }

    // public function ch_set_church_custom_columns_data($column, $post_id)
    // {
    //     global $total;
    //     $name = isset($total) ? $data['name'] : '';
    //     $email = isset($data['email']) ? $data['email'] : '';
    //     $telephone = isset($data['telephone']) ? $data['telephone'] : '';
    //     $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');
    //     $party = isset($data['party']) && $data['party'] === 1 ? '<strong>'. __( 'YES', 'k7-church').'</strong>' : __(  'NO', 'k7-church');

    //     switch ($column) {
    //         case 'name':
    //             echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
    //             break;

    //         case 'telephone':
    //             echo $telephone;
    //             break;
    //         case 'approved':
    //             echo $approved;
    //             break;

    //         case 'party':
    //             echo $party;
    //             break;
    //     }
    // }

    // public function ch_set_church_custom_columns_sortable($columns)
    // {
    //     $columns['name'] = __( 'name', 'k7-church');
    //     $columns['approved'] = __( 'approved', 'k7-church');
    //     $columns['party'] = __( 'partic', 'k7-church');

    //     return $columns;
    // }


}