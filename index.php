<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/environment.php';

spl_autoload_register(
    function ($class) {
        foreach ([ROOT, __DIR__] as $root) {
            $class = str_replace('\\', '/', $class);
            $file  = $root . '/class/' . $class . '.php';
            if (is_file($file)) {
                /** @noinspection PhpIncludeInspection */
                include_once $file;

                return;
            }
        }
    }
);

set_exception_handler(
    function ($e) {
        /** @var  Exception $e */
        if (DEBUG) {
            /** @noinspection ForgottenDebugOutputInspection */
            var_dump($e->getMessage());
        }
    }
);

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && DEBUG) {
        /** @noinspection ForgottenDebugOutputInspection */
        var_dump($error);
    }
});

if (STATIC_MAPPING) {
    require_once __DIR__ . '/config/methods.php';
} else {
    $methods = [];
    foreach (scandir(ROOT . '/class/methods') as $fileName) {
        if (substr($fileName, -4) !== '.php') {
            continue;
        }
        $namespace = false;
        $class     = false;
        $method    = false;
        $tokens    = token_get_all(file_get_contents(ROOT . '/class/methods/' . $fileName));
        for ($i = 0, $max = count($tokens); $i < $max; $i++) {
            switch ($tokens[ $i ][0]) {
                case T_CLASS:
                    $class = $tokens[ $i + 2 ][1];
                    break;
                case T_NAMESPACE:
                    $namespace = $tokens[ $i + 2 ][1];
                    break;
                case T_DOC_COMMENT:
                    if (preg_match('/@api-call/', $tokens[ $i ][1]) !== 0) {
                        $method = mb_strtolower(preg_replace("/.*@api-call\s+(\w+).*/", "$1", $tokens[ $i ][1]));
                    }
                    break;
            }
            if ($class && !$method) {
                break;
            }
            if ($class && $method && !$namespace) {
                $methods[ $method ] = $class;
                break;
            }
            if ($class && $method && $namespace) {
                $methods[ $method ] = $namespace . '\\' . $class;
                break;
            }
        }
    }
    unset($tokens, $namespace, $class, $method);
//    var_dump($methods);
}


$payload = (DEBUG && isset($_GET[ METHOD ])) || GET ? $_GET : $_POST;

$methodName = mb_strtolower(isset($payload[ METHOD ]) ? $payload[ METHOD ] : 'wrongMethod');
$class      = isset($methods[ $methodName ]) ? $methods[ $methodName ] : $methods['wrongMethod'];

$result  = ['r' => [], 'e' => []];
$request = new $class($payload);
try {
    $result['r'] = $request();
} catch (Exception $e) {
    $result['e'] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
}
/** @noinspection ForgottenDebugOutputInspection */
print_r(json_encode($result, JSON_UNESCAPED_UNICODE));
