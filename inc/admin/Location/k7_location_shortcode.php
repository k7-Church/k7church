<?php


if( ! defined('ABSPATH') ) exit;


if(class_exists('K7_location_shortcode'));

//defines the functionality for the location shortcode
class K7_location_shortcode
{

    //on initialize
    public function __construct()
    {
        add_action('init', array($this, 'register_location_shortcodes')); //shortcodes
    }

    //location shortcode
    public function register_location_shortcodes()
    {
        add_shortcode('k7_locations', array($this, 'location_shortcode_output'));
    }


    //shortcode display
    public function location_shortcode_output($atts, $content = '', $tag)
    {

        //get the global k7_simple_locations class
        global $k7_simple_locations;

        //build default arguments
        $arguments = shortcode_atts(array(
                'location_id' => '',
                'number_of_locations' => -1)
            , $atts, $tag);


        $k7_simple_locations = new K7_Location();

        //uses the main output function of the location class
        $html = $k7_simple_locations->get_locations_output($arguments);

        return $html;
    }
}

new K7_location_shortcode();

?>