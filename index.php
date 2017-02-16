<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/define.php';

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
