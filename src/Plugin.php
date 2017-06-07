<?php

namespace Detain\MyAdminSsl;

use Symfony\Component\EventDispatcher\GenericEvent;

class Plugin {

	public function __construct() {
	}

	public static function Load(GenericEvent $event) {

	}

	public static function Settings(GenericEvent $event) {
		$settings = $event->getSubject();
		$settings->add_dropdown_setting('ssl', 'General', 'outofstock_ssl', 'Out Of Stock Ssl', 'Enable/Disable Sales Of This Type', $settings->get_setting('OUTOFSTOCK_SSL'), array('0', '1'), array('No', 'Yes', ));
	}
}
