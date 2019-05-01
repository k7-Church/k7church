<?php
/**
 * @version 1.0.13
 *
 * @package K7Church/inc/controller
 */

defined('ABSPATH') || exit;


class Church_Activate
{
    public static function ch_activate()
    {
        flush_rewrite_rules();

        $default = array();

        if (!get_option('church_plugin')) {
            update_option('church_plugin', $default);
        }

        if (!get_option('church_plugin_cpt')) {
            update_option('church_plugin_cpt', $default);
        }

        if (!get_option('church_plugin_tax')) {
            update_option('church_plugin_tax', $default);
        }
    }
}