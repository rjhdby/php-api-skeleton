[![Code Climate](https://codeclimate.com/github/rjhdby/php-api-skeleton/badges/gpa.svg)](https://codeclimate.com/github/rjhdby/php-api-skeleton)
# Simple skeleton for php backend api

## Setup

### PHP 5.5 and higher compatibility

### config/environment.php
File with global project properties. You **must** view and edit it before using this skeleton.
```php
define('ROOT', '/srv/www/htdocs');                        //Root api directory
define('SETTINGS', ROOT . '/config/properties.php');      //Properties file path
define('DEBUG', true);                                    //Whether use debug mode
define('EXCEPTIONS', true);                               //Whether use exceptions instead of E_USER_NOTICE
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
define('STATIC_MAPPING', false);                          //Whether use static class mapping
```

## Using
See [class/methods/Example.php](https://github.com/rjhdby/api-skeleton/blob/master/class/methods/Example.php).

Each method must implements `core\Method` interface.
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
use core\Method;

class WrongMethod implements Method{
    public function __construct($data) {}
    public function __invoke() {}
}
```

In this case you can delete file `methods.php`

## Static class mapping 
Disabled by default.
Set constant `STATIC_MAPPING` in `environment.php` to `TRUE` to use static mapping instead of dynamic.

### config/methods.php
Contains one associative array `$methods` with names of methods to classes mapping.
```php
$methods = [
    'example'     => methods\Example::class,
    'wrongMethod' => methods\WrongMethod::class
];
```