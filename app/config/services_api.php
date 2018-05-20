<?php

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;


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
$di->setShared('db', function () use ($config)
{
    $db     = $config->database;
    $class  = 'Phalcon\Db\Adapter\Pdo\\' . $db->adapter;
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

$di->setShared('lang', function () use ($config)
{
	$request = new Phalcon\Http\Request;
	
    $lang = $request->get('ln');
	$lang = $lang
	    	? $lang
	    	// Ask browser what is the best language
	    	: $request->getBestLanguage();
	$lang = APP_PATH . "/language/$lang.php";

	// Check if we have a translation file for that lang
	if (file_exists($lang)) {
	        include $lang;
	    } else {
	        // Fallback to English
	        include APP_PATH . "/language/en-US.php";
	    }

	return new Phalcon\Translate\Adapter\NativeArray([
		"content" => $msg
	]);
});

// The URL component is used to generate all kind of urls in the application
$di->getUrl()->setBaseUri($config->baseUri);

// Starts the session the first time some component requests the session service
$di->getSession()->start();