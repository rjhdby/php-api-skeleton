<?php
spl_autoload_register(
    function ($class) {
        foreach (array(ROOT, __DIR__) as $root) {
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