<?php
/**
 * @package  K7Church
 */
 

 

class Church_LocationCallbacks extends Church_BaseController

{
	public function ch_shortcodePage()
	{
		return require_once("$this->plugin_path/templates/location.php");
	}
}