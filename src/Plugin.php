<?php

namespace Detain\MyAdminSsl;

use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class Plugin
 *
 * @package Detain\MyAdminSsl
 */
class Plugin {

	public static $name = 'SSL Certificates';
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

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return array
	 */
	public static function getHooks() {
		return [
			self::$module.'.load_processing' => [__CLASS__, 'loadProcessing'],
			self::$module.'.settings' => [__CLASS__, 'getSettings']
		];
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function loadProcessing(GenericEvent $event) {
		$service = $event->getSubject();
		$service->setModule(self::$module)
			->setActivationStatuses(['pending', 'pendapproval', 'active'])
			->set_enable(function($service) {
				$serviceTypes = run_event('get_service_types', FALSE, self::$module);
				$serviceInfo = $service->getServiceInfo();
				$settings = get_module_settings(self::$module);
				$db = get_module_db(self::$module);
				$db->query("update {$settings['TABLE']} set {$settings['PREFIX']}_status='active' where {$settings['PREFIX']}_id='{$serviceInfo[$settings['PREFIX'].'_id']}'", __LINE__, __FILE__);
				$GLOBALS['tf']->history->add(self::$module, 'change_status', 'active', $serviceInfo[$settings['PREFIX'].'_id'], $serviceInfo[$settings['PREFIX'].'_custid']);
				$smarty = new \TFSmarty;
				$smarty->assign('ssl_hostname', $serviceInfo[$settings['PREFIX'].'_hostname']);
				$smarty->assign('ssl_name', $serviceTypes[$serviceInfo[$settings['PREFIX'].'_type']]['services_name']);
				$email = $smarty->fetch('email/admin_email_ssl_created.tpl');
				$subject = 'New SSL Certificate Created '.$db->Record[$settings['TITLE_FIELD']];
				$headers = '';
				$headers .= 'MIME-Version: 1.0'.EMAIL_NEWLINE;
				$headers .= 'Content-type: text/html; charset=UTF-8'.EMAIL_NEWLINE;
				$headers .= 'From: '.TITLE.' <'.EMAIL_FROM.'>'.EMAIL_NEWLINE;
				admin_mail($subject, $email, $headers, FALSE, 'admin_email_ssl_created.tpl');
			})->set_reactivate(function($service) {
				$serviceTypes = run_event('get_service_types', FALSE, self::$module);
				$serviceInfo = $service->getServiceInfo();
				$settings = get_module_settings(self::$module);
				$db = get_module_db(self::$module);
				$db->query("update {$settings['TABLE']} set {$settings['PREFIX']}_status='active' where {$settings['PREFIX']}_id='{$serviceInfo[$settings['PREFIX'].'_id']}'", __LINE__, __FILE__);
				$GLOBALS['tf']->history->add(self::$module, 'change_status', 'active', $serviceInfo[$settings['PREFIX'].'_id'], $serviceInfo[$settings['PREFIX'].'_custid']);
				$smarty = new TFSmarty;
				$smarty->assign('ssl_hostname', $serviceInfo[$settings['PREFIX'].'_hostname']);
				$smarty->assign('ssl_name', $serviceTypes[$serviceInfo[$settings['PREFIX'].'_type']]['services_name']);
				$email = $smarty->fetch('email/admin_email_ssl_reactivated.tpl');
				$subject = $db->Record[$settings['TITLE_FIELD']].' '.$service_name.' '.$settings['TBLNAME'].' Re-Activated';
				$headers = '';
				$headers .= 'MIME-Version: 1.0'.EMAIL_NEWLINE;
				$headers .= 'Content-type: text/html; charset=UTF-8'.EMAIL_NEWLINE;
				$headers .= 'From: '.TITLE.' <'.EMAIL_FROM.'>'.EMAIL_NEWLINE;
				admin_mail($subject, $email, $headers, FALSE, 'admin_email_ssl_reactivated.tpl');
			})->set_disable(function() {
			})->register();
	}

	/**
	 * @param \Symfony\Component\EventDispatcher\GenericEvent $event
	 */
	public static function getSettings(GenericEvent $event) {
		$settings = $event->getSubject();
		$settings->add_dropdown_setting(self::$module, 'General', 'outofstock_ssl', 'Out Of Stock Ssl', 'Enable/Disable Sales Of This Type', $settings->get_setting('OUTOFSTOCK_SSL'), ['0', '1'], ['No', 'Yes']);
	}
}
