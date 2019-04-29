<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Church_EventCallbacks extends Church_BaseController

{
       public function ch_event_settings()
    {
    
        return require_once("$this->plugin_path/templates/admin/event.php");
     
    }

    public function ch_event_sanitize($input){

    	var_dump($_POST); die;
    	return $input;
    }

    public function ch_event_section()
    {

    }

    public function ch_event_textFields_border()
    {
    	$value = esc_attr( get_option( 'event_border_color' ) );
    	echo '<input type="text" class="regular-text" name="event_border_color" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }

        public function ch_event_textFields_status_started()
    {
    	$value = esc_attr( get_option( 'event_status_started' ) );
    	echo '<input type="text" class="regular-text" name="event_status_started" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }



    public function ch_event_textFields_status_soon_finished(){
    	$value = esc_attr( get_option( 'event_status_finished' ) );
    	echo '<input type="text" class="regular-text" name="event_status_finished" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }
	public function ch_event_textFields_status_soon(){
		$value = esc_attr( get_option( 'event_status_soon' ) );
    	echo '<input type="text" class="regular-text" name="event_status_soon" value="' . $value .'" placeholder="eg.#FFFFFF">';
	}
	public function ch_event_button_class(){
		$value = esc_attr( get_option( 'event_status_button' ) );
    	echo '<input type="text" class="regular-text" name="event_status_button" value="' . $value .'" placeholder="eg. class="button primary">';
	}




}