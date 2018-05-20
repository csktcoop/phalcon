<?php

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include APP_PATH . "/config/config.php";
});

/**
 * Get config service for use in inline setup below
 */
$config = $di->getConfig();

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () use ($config) {
    $db  = $config->database;

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $db->adapter;
    $params = [
        'host'     => $db->host,
        'username' => $db->username,
        'password' => $db->password,
        'dbname'   => $db->name,
        'charset'  => $db->charset
    ];

    if ($db->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    return new $class($params);
});