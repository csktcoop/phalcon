<?php

error_reporting(E_ALL);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('DS', DIRECTORY_SEPARATOR);

/* MAP
Dependency Injector AS di
services.php
    di.config
    di.db
loader.php
    loader.registerFiles: helper/common.php
    loader.registerDirs: library
    loader.registerNamespaces: App\Model, App\Plugin, App\Form
services_web.php
    di.flash
    di.navigation
Application AS app
app.registerModules: frontend, api
    frontend
        module.php
            loader.registerDirs: controller
    api
routes.php
    router.setDefaultModule: frontend
    router.add: module/params
    router.add: module/controller/params
    router.add: module/controller/action/params
di.dispatcher
print app.getContent
*/

try {
    /**
     * The FactoryDefault Dependency Injector automatically registers the services that
     * provide a full stack framework. These default services can be overidden with custom ones.
     */
    $di = new Phalcon\Di\FactoryDefault;

    /**
     * Include general services
     */
    require APP_PATH . '/config/services.php';

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/loader.php';

    /**
     * Include web environment specific services
     */
    require APP_PATH . '/config/services_web.php';

    /**
     * Handle the request
     */
    $application = new Phalcon\Mvc\Application($di);

    /**
     * Register application modules
     */
    $application->registerModules([
        'frontend' => [
            'className' => 'App\Module\Frontend',
            'path'      => APP_PATH . '/module/frontend/module.php'
        ],
        'api'      => [
            'className' => 'App\Module\Api',
            'path'      => APP_PATH . '/module/api/module.php'
        ]
    ]);

    /**
     * Include routes
     */
    require APP_PATH . '/config/routes.php';

    // DISPATCH
    /**
    * Set the default namespace for dispatcher
    */
    //*
    $di->setShared('dispatcher', function()
    {
        $manager = new Phalcon\Events\Manager;
        
        // Check if the user is allowed to access certain action using the Security plugin
        $manager->attach('dispatch:beforeExecuteRoute', new \App\Plugin\Security);
        
        // Handle exceptions and not-found exceptions using NotFound plugin
        $manager->attach('dispatch:beforeException', new \App\Plugin\NotFound);
        
        $dispatcher = new Phalcon\Mvc\Dispatcher;
        
        $dispatcher->setEventsManager($manager);
        $dispatcher->setDefaultController('index');
        
        return $dispatcher;
    });
    //*/// DISPATCH
    
    echo str_replace(["\n","\r","\t"], '', $application->handle()->getContent());

} catch (\Exception $e) {
    echo $e->getMessage() . '<br>';
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
}