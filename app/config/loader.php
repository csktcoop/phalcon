<?php

$loader = new Phalcon\Loader();

$loader->registerFiles([
    APP_PATH . $config->dir->helper . 'common.php'
]);

$loader->registerDirs([
    APP_PATH . $config->dir->library
]);

/**
 * Register Namespaces
 */
$loader->registerNamespaces([
    'App\Model'       => APP_PATH . $config->dir->model,
    'App\Plugin'      => APP_PATH . $config->dir->plugin,
    'App\Form'        => APP_PATH . $config->dir->form,
])->register();