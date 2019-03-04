<?php

use Phalcon\Loader;

$loader = new Loader();

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'Store2\Models' => APP_PATH . '/common/models/',
    'Store2'        => APP_PATH . '/common/library/',
]);

/**
 * Register module classes
 */
$loader->registerClasses([
    'Store2\Modules\Frontend\Module' => APP_PATH . '/modules/frontend/Module.php',
    'Store2\Modules\Cli\Module'      => APP_PATH . '/modules/cli/Module.php'
]);

$loader->register();
