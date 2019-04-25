<?php 
/**
 * @package  K7Church
 */

/**
* 
*/
class Church_WidgetController extends Church_BaseController
{
	public function ch_register()
	{
		if ( ! $this->ch_activated( 'media_widget' ) ) return;

		$media_widget = new Church_MediaWidget();
		$media_widget->ch_register();
	}
}