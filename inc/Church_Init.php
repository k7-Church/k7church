<?php
/**
 * @package  K7Church
 */
namespace Inc;

final class Church_Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function ch_getServices()
	{
		return [
			Pages\Church_Dashboard::class,
			Controller\Church_Enqueue::class,
			Controller\Church_SettingsLinks::class,
			Controller\Church_CustomPostTypeController::class,
			Controller\Church_CustomTaxonomyController::class,
			Controller\Church_WidgetController::class,
			Controller\Church_GalleryController::class,
			Controller\Church_TestimonialController::class,
			Controller\Church_TemplateController::class,
			Controller\Church_AuthController::class,
			Controller\Church_MembershipController::class,
			Controller\Church_ChatController::class,
			Controller\Church_LocationController::class,
			Controller\Church_SermonController::class,
			Api\Widgets\Church_LocationWidget::class,
		];
	}

	/**
	 * Loop through the classes, initialize them,
	 * and call the register() method if it exists
	 * @return
	 */
	public static function ch_registerServices()
	{
		foreach (self::ch_getServices() as $class) {
			$service = self::ch_instantiate($class);
			if (method_exists($service, 'ch_register')) {
				$service->ch_register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function ch_instantiate($class)
	{
		$service = new $class();

		return $service;
	}
}
