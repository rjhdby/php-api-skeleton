<?php
@include_once __DIR__ . '/../vendor/autoload.php';
if (is_file('vendor/autoload.php')) {
    @include_once 'vendor/autoload.php';
}

define('ROOT', str_replace('\\', '/', __DIR__) . '/..');  //Root api directory
define('PROPERTIES', ROOT . '/config/properties.php');    //Properties file path
define('METHODS', ROOT . '/config/methods.php');          //Configuration file for static mapping
define('DEBUG', true);                                    //Whether use debug mode
define('EXCEPTIONS', true);                               //Whether use exceptions instead of E_USER_NOTICE
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
define('STATIC_MAPPING', false);                          //Whether use static class mapping
define('CASE_SENSITIVE', true);                           //Whether api calls methods names is case sensitive

ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');

spl_autoload_register(
    function ($class) {
        foreach ([__DIR__ . '/../class', __DIR__] as $root) {
            $class = str_replace('\\', '/', $class);
            $file  = $root . '/' . $class . '.php';
            if (is_file($file)) {
                /** @noinspection PhpIncludeInspection */
                include_once $file;

                return;
            }
        }
    }
);