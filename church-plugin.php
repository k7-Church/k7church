<?php
/**
 * @package  K7Church
 */
/**
 *  Plugin Name: K7 Church
/*  Description:  This is a plugin for church administration and religious ministries.
/*  Version:      1.0.11
/*  Author:       Márcio Zebedeu
/*  License:      GPL2
/*  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*   Text Domain:  k7
 *  Domain Path: /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

load_plugin_textdomain( 'k7', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    

/**
 * The code that runs during plugin activation
 */
function activate_church_plugin() {
	Inc\Controller\Church_Activate::ch_activate();
}
register_activation_hook( __FILE__, 'activate_church_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_church_plugin() {
	Inc\Controller\Church_Deactivate::ch_deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_church_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Inc\\Church_Init' ) ) {
	Inc\Church_Init::ch_registerServices();
}