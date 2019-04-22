<?php
/**
 * @package  K7Church
 */
namespace Inc\Api\Callbacks;

use Inc\Controller\Church_BaseController;

class Church_TestimonialCallbacks extends Church_BaseController
{
    public function ch_shortcodePage()
    {
        return require_once( "$this->plugin_path/templates/testimonial.php" );
    }
}
