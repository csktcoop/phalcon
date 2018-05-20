<?php

use Phalcon\Flash\Session as FlashSession;

$di->getUrl()
    ->setBaseUri($config->baseUri);
$di->getSession()
    ->start();

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new FlashSession([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

/**
 * Register a user component
 */
$di->setShared('navigation', function () {
    return new Navigation;
});