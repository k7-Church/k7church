<?php 
/**
 * @package  K7Church
 */
namespace Inc\Api\Callbacks;

use Inc\Controller\Church_BaseController;

class Church_AdminCallbacks extends Church_BaseController
{
	public function ch_adminDashboard()
	{
		return require_once( "$this->plugin_path/templates/admin.php" );
	}

	public function ch_adminCpt()
	{
		return require_once( "$this->plugin_path/templates/cpt.php" );
	}

	public function ch_adminTaxonomy()
	{
		return require_once( "$this->plugin_path/templates/taxonomy.php" );
	}

	public function ch_adminWidget()
	{
		return require_once( "$this->plugin_path/templates/widget.php" );
	}

	public function ch_adminGallery()
	{
		echo "<h1>Gallery Manager</h1>";
	}

	public function ch_adminTestimonial()
	{
		echo "<h1>Testimonial Manager</h1>";
	}

	public function ch_adminTemplates()
	{
		echo "<h1>Templates Manager</h1>";
	}

	public function ch_adminAuth()
	{
		echo "<h1>Templates Manager</h1>";
	}

	public function ch_adminMembership()
	{
		echo "<h1>Membership Manager</h1>";
	}

	public function ch_adminChat()
	{
		echo "<h1>Chat Manager</h1>";
	}
	public function painel()
	{
		return require_once( "$this->plugin_path/templates/panel.php" );
	}
}