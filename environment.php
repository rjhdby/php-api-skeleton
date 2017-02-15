<?php
define('ROOT', '/srv/www/htdocs');                        //Root api directory
define('SETTINGS', ROOT . '/properties/properties.php');  //Properties file path
define('DEBUG', true);                                    //Whether use debug mode
define('EXCEPTIONS', true);                               //Whether use exceptions instead of E_USER_NOTICE
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST

ini_set('display_errors', DEBUG ? 'On' : 'Off');
ini_set('display_startup_errors', DEBUG ? 'On' : 'Off');
