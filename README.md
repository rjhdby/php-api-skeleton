# Simple skeleton for php backend api

## Setup

### environment.php
File with global project properties. You **must** view and edit it before using this skeleton.
```php
define('ROOT', '/srv/www/htdocs');                        //Root api directory
define('SETTINGS', ROOT . '/properties/properties.php');  //Properties file path
define('DEBUG', true);                                    //Whether use debug mode
define('EXCEPTIONS', true);                               //Whether use exceptions instead of E_USER_NOTICE
define('METHOD', 'm');                                    //Name of parameter in POST/GET data that contains method name
define('GET', false);                                     //Whether use $_GET instead of $_POST
```
### methods.php
Contains one associative array `$methods` with names of methods to classes mapping.
```php
use methods\Example;
use methods\WrongMethod;

$methods = [
    'example'     => Example::class,
    'wrongMethod' => WrongMethod::class
];
```

### properties/properties.php
Standard INI-file. All settings may be used inside project with `Config::get()` method.

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
$dbUser = Config::get('db_user');
```

## Using
See [class/methods/Example.php](https://github.com/rjhdby/api-skeleton/blob/master/class/methods/Example.php).

1) Each method must implements `method\Method` interface.
  * Method `__construct` must receive an associative array ($_GET or $_POST will be forwarded to constructor)
  * Method `__invoke` must return an array or throw an Exception
2) Each method must have mapping record in `methods.php` inside `$methods` array.

The request to `index.php` must contains name of desired method, or an `Wrong method` error will be returned.

The response will be a JSON string.
   
### Normal response

```json
{
   "r": returned by __invoke() array,
   "e": {}
}
```

### Error response

```json
{
   "r": {},
   "e": {
       "code": $exception.getCode(),
       "text": $exception.getMessage()
   }
}
```