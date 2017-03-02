# Simple skeleton for php backend api
[![Code Climate](https://codeclimate.com/github/rjhdby/php-api-skeleton/badges/gpa.svg)](https://codeclimate.com/github/rjhdby/php-api-skeleton)
[![Build Status](https://travis-ci.org/rjhdby/php-api-skeleton.svg?branch=master)](https://travis-ci.org/rjhdby/php-api-skeleton)
[![Coverage Status](https://coveralls.io/repos/github/rjhdby/php-api-skeleton/badge.svg?branch=master)](https://coveralls.io/github/rjhdby/php-api-skeleton?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Project structure

    .
    ├── class
    │   ├── core                     # Classes that providing core functionality
    │   │    ├── Config.php          # Providing methods for parse INI-files and retrieve his values
    │   │    ├── Controller.php      # Main class that orchestrating api calls
    │   │    └── MethodInterface.php # Interface for 'methods' classes
    │   ├── methods                  # Classes that providing processing api calls 
    │   │    ├── Example.php         # Example of simple api call handler
    │   │    ├── WrongMethod.php     # Mandatory class that used for processing unknown api calls
    │   │    └── ...
    │   └── db                
    │        └── MyPdoConnection.php # Template singleton for PDO connection. Just for my own purposes. :)  
    ├── config
    │   ├── environment.php          # Global project properties
    │   ├── methods.php              # Static mapping for api calls. May be deleted if dynamic mapping is used 
    │   └── properties.php           # User defined properties that can be retrieved by Config::get() method
    ├── test                         # PHPUnit test classes
    │   ├── bootstrap.php            # Bootstrap for PHPUnit
    │   └── ...
    ├── index.php                    # Entry point  
    ├── LICENSE.txt                  # License. And what you expected?
    ├── README.md                    # This text
    └── ...                          # All other files is used for tests and code quality checks

## Setup

### PHP 5.5 and higher compatibility (tested on PHP 5.6+)

### config/environment.php
File with global project properties. You **must** view and edit it before using this skeleton.
```php
define('ROOT', str_replace('\\', '/', __DIR__) . '/..');  //Root api directory
define('PROPERTIES', ROOT . '/config/properties.php');    //Properties file path
define('METHODS', ROOT . '/config/methods.php');          //Configuration file for static mapping
define('DEBUG', true);                                    //Whether use debug mode
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
define('STATIC_MAPPING', false);                          //Whether use static class mapping
```

## Using
See [class/methods/Example.php](https://github.com/rjhdby/api-skeleton/blob/master/class/methods/Example.php).

Each method must implements `core\MethodInterface` interface.
  * Method `__construct` must receive an associative array ($_GET or $_POST will be forwarded to constructor)
  * Method `__invoke` must return an array or throw an Exception

The request to `index.php` must contains name of desired method, or an `Wrong method` error will be returned.

The response will be a JSON string.

### Normal response
```json
{
   "r": ["an array returned by __invoke()"],
   "e": {}
}
```

### Error response
```json
{
   "r": {},
   "e": {
       "code": "$exception.getCode()",
       "text": "$exception.getMessage()"
   }
}
```

### config/properties.php
Standard INI-file. All settings may be used inside the project with `Config::get()` method.

**properties.php**
```ini
db_type=mysql
db_host=localhost
db_user=user
db_pass=pass
db_dbname=db
```

**Your code**
```php
$dbUser = core\Config::get('db_user');
```

## Dynamic class mapping
Default behavior. Set constant `STATIC_MAPPING` in `environment.php` to `TRUE` to disable.

1. All api-call classes should be placed in `class/methods/` directory. 
2. Each api-call class should contain PHP-doc comment with `@api-call` annotation before namespace declaration. Value of this annotation will be used as api-call method name.

```php
<?php
/** @api-call wrongMethod */
namespace methods;
use core\MethodInterface;

class WrongMethod implements MethodInterface{
    public function __construct($data) {}
    public function __invoke() {}
}
```

In this case you can delete file `methods.php`

## Static class mapping 
Disabled by default.
Set constant `STATIC_MAPPING` in `environment.php` to `TRUE` to use static mapping instead of dynamic.

### config/methods.php
INI-style file containing static mapping for api calls. May be deleted if dynamic mapping is used
```ini
example = methods\Example
wrongMethod = methods\WrongMethod
```