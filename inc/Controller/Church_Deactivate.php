<?php
/**
 * @package  K7Church
 */
namespace Inc\Controller;

class Church_Deactivate
{
	public static function ch_deactivate() {
		flush_rewrite_rules();
	}
}