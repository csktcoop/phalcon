<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('APP_PATH') || die;

define("DATETIME", "Y-m-d H:i:s");

return new \Phalcon\Config([
    'version' => '1.0',
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'name'     => 'test',
        'charset'  => 'utf8',
        'prefix'   => '',
    ],
    'dir' => [
        'controller' => '/controller/',
        'model'      => '/model/',
        'view'       => '/view/',
        'plugin'     => '/plugin/',
        'form'       => '/form/',
        'library'    => '/library/',
        'helper'     => '/helper/',
        'migration'  => '/migration/',
        'cache'      => '/cache/',
    ],

    // This allows the baseUri to be understand project paths that are not in the root directory
    // of the webpspace.  This will break if the public/index.php entry point is moved or
    // possibly if the web server rewrite rules are changed. This can also be set to a static path.
    'baseUri'        => preg_replace('/public([\/\\\\])(index|api).php$/', '', $_SERVER["PHP_SELF"]),

    /**
     * if true, then we print a new line at the end of each CLI execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    'printNewLine' => true,
    'timezone'     => 'Asia/Ho_Chi_Minh'
]);
