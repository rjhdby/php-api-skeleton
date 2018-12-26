# Simple skeleton for php backend api
[![Code Climate](https://codeclimate.com/github/rjhdby/php-api-skeleton/badges/gpa.svg)](https://codeclimate.com/github/rjhdby/php-api-skeleton)
[![Build Status](https://travis-ci.org/rjhdby/php-api-skeleton.svg?branch=master)](https://travis-ci.org/rjhdby/php-api-skeleton)
[![Coverage Status](https://coveralls.io/repos/github/rjhdby/php-api-skeleton/badge.svg?branch=master)](https://coveralls.io/github/rjhdby/php-api-skeleton?branch=master)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Project structure

    .
    ├── src
    │   ├── class
    │   │   ├── core                      # Classes that providing core functionality
    │   │   │    ├── Config.php           # Providing methods for parse INI-files and retrieve his values
    │   │   │    ├── Controller.php       # Main class that orchestrating api calls
    │   │   │    └── Method.php           # Abstract class for 'methods' classes inheritance
    │   │   ├── methods                   # Classes that providing processing api calls
    │   │   │    ├── example              
    │   │   │    │    └── Example.php     # Example of simple api call handler
    │   │   │    └── ...
    │   │   └── errors
    │   │   │    └── ...                  # Custom exceptions
    │   │   └── db                
    │   │        └── MyPdoConnection.php  # Template singleton for PDO connection. Just for my own purposes. :)  
    │   ├── config
    │   │   ├── environment.php           # Global project properties
    │   │   ├── autoload.php              # Autoload realization 
    │   │   └── properties.php            # User defined properties that can be retrieved by Config::get() method
    │   └── index.php                     # Entry point
    ├── test                              # PHPUnit test classes
    │   ├── bootstrap.php                 # Bootstrap for PHPUnit
    │   └── ...
    ├── LICENSE.txt                       # License. And what you expected?
    ├── README.md                         # This text
    └── ...                               # All other files is used for tests and code quality checks

## Setup

### PHP 5.5 and higher compatibility (tested on PHP 5.6+)

### config/environment.php
File with global project properties. You **must** view and edit it before using this skeleton.
```php
define('ROOT', str_replace('\\', '/', __DIR__) . '/..');  //Root api directory
define('PROPERTIES', ROOT . '/config/properties.php');    //Properties file path
define('METHODS', '/tmp/methods.json');                   //Temp file for methods cache
define('DEBUG', true);                                    //Whether use debug mode
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
define('STATIC_MAPPING', false);                          //Whether use static class mapping
define('CASE_SENSITIVE', true);                           //Whether api calls methods names is case sensitive
```

## Using
See [class/methods/Example.php](https://github.com/rjhdby/api-skeleton/blob/master/class/methods/Example.php).

  * Each method must extends abstract `core\Method` class.
  * All api-call classes should be placed under `class/methods/` directory.
  * Method `__construct($get, $post, $files, $body)` will receive 4 associative arrays ($_GET, $_POST, $_FILES adn JSON-decoded request body)
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

## Class `Method`

You can access to input data through those fields.

```php
$_GET['key']   === $this->get['key'] === $this['key'];
$_POST['key']  === $this->post['key'];
$_FILES['key'] === $this->files['key'];
json_decode(file_get_contents('php://input'), true)['key'] === $this->body['key'];
```

## methods
```
protected function has(...$keys)            // returns TRUE, if all keys present inside `$this->get`   
protected function missing($keys)           // returns TRUE, if at least one key not present inside `$this->get`
protected function checkParams(...$keys)    // returns nothing. Throws ParameterException if at least one key not present inside `$this->get`
```
## config/properties.php
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
 

```php
<?php
namespace methods\core;
use core\Method;

class MyMethod extends Method {
    public function __invoke() {}
}
```

