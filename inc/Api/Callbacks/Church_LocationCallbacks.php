<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Church_LocationCallbacks extends Church_BaseController

{
    public function ch_locationSettings()
    {
        return require_once("$this->plugin_path/templates/admin/location.php");
    }
}