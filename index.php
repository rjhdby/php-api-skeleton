<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/define.php';
require_once __DIR__ . '/methods.php';

if (DEBUG && isset($_GET[ METHOD ])) {
    $_POST = $_GET;
}

$methodName = isset($_POST[ METHOD ]) ? $_POST[ METHOD ] : 'wrongMethod';
$class      = isset($methods[ $methodName ]) ? $methods[ $methodName ] : $methods['wrongMethod'];

$result  = ['r' => [], 'e' => []];
$request = new $class($_POST);
try {
    $result['r'] = $request();
} catch (Exception $e) {
    $result['e'] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
}
/** @noinspection ForgottenDebugOutputInspection */
print_r(json_encode($result, JSON_UNESCAPED_UNICODE));