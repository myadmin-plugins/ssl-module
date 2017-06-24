<?php

namespace Detain\MyAdminSsl;

use Symfony\Component\EventDispatcher\GenericEvent;

class Plugin {

	public static $name = 'SSL Certificates Module';
	public static $description = 'Allows selling of SSL Certificates Module';
	public static $help = '';
	public static $module = 'ssl';
	public static $type = 'module';
	public static $settings = [
		'SERVICE_ID_OFFSET' => 3000,
		'USE_REPEAT_INVOICE' => FALSE,
		'USE_PACKAGES' => TRUE,
		'BILLING_DAYS_OFFSET' => 0,
		'IMGNAME' => 'vcard_48.png',
		'REPEAT_BILLING_METHOD' => PRORATE_BILLING,
		'DELETE_PENDING_DAYS' => 45,
		'SUSPEND_DAYS' => 14,
		'SUSPEND_WARNING_DAYS' => 7,
		'TITLE' => 'SSL Certificates',
		'MENUNAME' => 'SSL',
		'EMAIL_FROM' => 'support@inssl.net',
		'TBLNAME' => 'SSL',
		'TABLE' => 'ssl_certs',
		'TITLE_FIELD' => 'ssl_hostname',
		'PREFIX' => 'ssl'];


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
