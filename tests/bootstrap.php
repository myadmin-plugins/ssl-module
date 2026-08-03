<?php

/**
 * PHPUnit bootstrap file for myadmin-ssl-module tests.
 *
 * Defines constants and stubs required by the Plugin class that are
 * normally provided by the MyAdmin framework at runtime.
 *
 * Note: PHPUnit has already loaded vendor/autoload.php by the time this file
 * runs, so detain/myadmin-plugin-installer has already supplied the real
 * get_module_settings(), get_module_db() and function_requirements(). Rather
 * than shadow them, this bootstrap feeds them the globals they read
 * ($GLOBALS['modules'], $GLOBALS['<module>_dbh']) so the plugin talks to the
 * genuine framework helpers and gets test doubles back.
 */

// Define constants used in Plugin::$settings static initialization
if (!defined('PRORATE_BILLING')) {
    define('PRORATE_BILLING', 1);
}

// Autoloader (already loaded by the PHPUnit entry script; required here too so
// this bootstrap stands on its own).
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Test doubles for \MyAdmin\App, \MyAdmin\Mail, \TFSmarty and \ServiceHandler.
// These let the tests invoke the plugin's lifecycle closures and assert what
// they actually did, rather than grepping src/Plugin.php for call spellings.
require_once __DIR__ . '/support/doubles.php';

/**
 * The service type table the plugin looks certificate names up in, keyed the
 * way run_event('get_service_types') keys it in production: by the value
 * stored in the service row's <prefix>_type column.
 */
const SSL_TEST_SERVICE_TYPES = [
    31 => [
        'services_id' => 31,
        'services_name' => 'RapidSSL',
        'services_type' => 31,
        'services_category' => 3,
    ],
];

if (!function_exists('run_event')) {
    /**
     * @param string $event
     * @param mixed $default
     * @param string $module
     * @return mixed
     */
    function run_event(string $event, $default = false, string $module = '')
    {
        if ($event === 'get_service_types') {
            return SSL_TEST_SERVICE_TYPES;
        }
        return $default;
    }
}

if (!function_exists('myadmin_log')) {
    /**
     * @param string $module
     * @param string $level
     * @param string $message
     * @param int|string $line
     * @param string $file
     * @param string $section
     * @param int|string $id
     * @return void
     */
    function myadmin_log(string $module, string $level, string $message, $line = '', $file = '', string $section = '', $id = ''): void
    {
    }
}

// Register the module with the plugin installer's registry so the real
// get_module_settings('ssl') returns the plugin's own settings, and point the
// real get_module_db('ssl') at the query-recording spy.
register_module(\Detain\MyAdminSsl\Plugin::$module, \Detain\MyAdminSsl\Plugin::$settings);
$GLOBALS[\Detain\MyAdminSsl\Plugin::$module . '_dbh'] = new \Detain\MyAdminSsl\Tests\Support\DbSpy();
