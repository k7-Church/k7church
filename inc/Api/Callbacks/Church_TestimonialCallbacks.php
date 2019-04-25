<?php
/**
 * @package  K7Church
 */
 

 

class Church_TestimonialCallbacks extends Church_BaseController
{
    public function ch_shortcodePage()
    {
        return require_once( "$this->plugin_path/templates/testimonial.php" );
    }
}
