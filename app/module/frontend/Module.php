<?php

namespace App\Module;

use Phalcon\Loader;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Frontend implements ModuleDefinitionInterface
{

    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new \Phalcon\Loader;

        $loader->registerDirs([
            __DIR__ . $di->getConfig()->dir->controller
        ])->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Setting up the view component
         */
        $di->set('view', function ()
        {
            $cfg = $this->getConfig();
            
            // Create an events manager
            $manager = new \Phalcon\Events\Manager;
            
            $manager->attach(
                "view:beforeRender",
                function (\Phalcon\Events\Event $event, $view) {
                    
                }
            );
            $manager->attach(
                "view:afterRender",
                function (\Phalcon\Events\Event $event, $view) {
                    
                }
            );
            
            $view = new \Phalcon\Mvc\View;
            $view->setDI($this);
            $view->setViewsDir(APP_PATH . $cfg->dir->view);
            $view->setLayoutsDir('layout/');
            $view->setEventsManager($manager);

            $view->registerEngines([
                '.volt'  => function ($view) {
                    $config = $this->getConfig();

                    $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $this);
                    
                    $volt->setOptions([
                        // 'autoescape' => false,
                        // 'prefix'     => null,
                        'compiledPath' => BASE_PATH . $config->dir->cache . 'volt/',
                        'compiledSeparator' => '_'
                    ]);

                    return $volt;
                }
            ]);

            return $view;
        });
    }
}