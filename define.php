<?php
require_once __DIR__ . '/environment.php';

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
