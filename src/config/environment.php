<?php
/**
 * Global project properties
 */

define('ROOT', str_replace('\\', '/', __DIR__) . '/..');  //Root api directory
define('PROPERTIES', ROOT . '/config/properties.php');    //Properties file path
define('METHODS', '/tmp/methods.json');                   //Temp file for methods cache
define('DEBUG', true);                                    //Whether use debug mode
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
define('STATIC_MAPPING', false);                          //Whether use static class mapping
define('CASE_SENSITIVE', true);                           //Whether api calls methods names is case sensitive

ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');
