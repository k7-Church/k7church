<?php


class Church_SermonCallbacks extends Church_BaseController
{
	public function ch_sermonSettings(){
		
        return require_once("$this->plugin_path/templates/admin/sermon.php");
	}

}