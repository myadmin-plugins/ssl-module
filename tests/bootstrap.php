<?php

/**
 * PHPUnit bootstrap file for myadmin-ssl-module tests.
 *
 * Defines constants and stubs required by the Plugin class that are
 * normally provided by the MyAdmin framework at runtime.
 */

// Define constants used in Plugin::$settings static initialization
if (!defined('PRORATE_BILLING')) {
    define('PRORATE_BILLING', 1);
}

// Autoloader
require dirname(__DIR__) . '/vendor/autoload.php';
