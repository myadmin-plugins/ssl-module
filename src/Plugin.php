<?php

namespace Detain\MyAdminSsl;

use Symfony\Component\EventDispatcher\GenericEvent;

class Plugin {

	public static $name = 'SSL Certificates Module';
	public static $description = 'Allows selling of SSL Certificates Module';
	public static $help = '';
	public static $module = 'ssl';
	public static $type = 'module';


	public function __construct() {
	}

	public static function getHooks() {
		return [
			self::$module.'.load_processing' => [__CLASS__, 'loadProcessing'],
			self::$module.'.settings' => [__CLASS__, 'getSettings'],
		];
	}

	public static function loadProcessing(GenericEvent $event) {

	}

	public static function getSettings(GenericEvent $event) {
		$settings = $event->getSubject();
		$settings->add_dropdown_setting(self::$module, 'General', 'outofstock_ssl', 'Out Of Stock Ssl', 'Enable/Disable Sales Of This Type', $settings->get_setting('OUTOFSTOCK_SSL'), array('0', '1'), array('No', 'Yes', ));
	}
}
