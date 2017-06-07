<?php
/* TODO:
 - service type, category, and services  adding
 - dealing with the SERVICE_TYPES_ssl define
 - add way to call/hook into install/uninstall
*/
return [
	'name' => 'MyAdmin SSL Certificates Module for MyAdmin',
	'description' => 'Allows selling of SSL Certificates Module',
	'help' => '',
	'module' => 'ssl',
	'author' => 'detain@interserver.net',
	'home' => 'https://github.com/detain/myadmin-ssl-module',
	'repo' => 'https://github.com/detain/myadmin-ssl-module',
	'version' => '1.0.0',
	'type' => 'module',
	'hooks' => [
		'ssl.load_processing' => ['Detain\MyAdminSsl\Plugin', 'Load'],
		'ssl.settings' => ['Detain\MyAdminSsl\Plugin', 'Settings'],
		/* 'function.requirements' => ['Detain\MyAdminSsl\Plugin', 'Requirements'],
		'ssl.activate' => ['Detain\MyAdminSsl\Plugin', 'Activate'],
		'ssl.change_ip' => ['Detain\MyAdminSsl\Plugin', 'ChangeIp'],
		'ui.menu' => ['Detain\MyAdminSsl\Plugin', 'Menu'] */
	],
];
