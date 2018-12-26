<?php
use core\Controller;

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/environment.php';

require_once 'config/autoload.php';

set_exception_handler(
    function ($e) {
        /** @var  Exception $e */
        /** @noinspection PhpStatementHasEmptyBodyInspection */
        if (DEBUG) {
            /** @noinspection ForgottenDebugOutputInspection */
            printError($e->getCode(), $e->getMessage());
        }
    }
);

register_shutdown_function(function () {
    $error = error_get_last();
    /** @noinspection PhpStatementHasEmptyBodyInspection */
    if ($error !== null && DEBUG) {
        /** @noinspection ForgottenDebugOutputInspection */
        printError($error['type'], 'File: ' . $error['file'] . '\nLine: ' . $error['line'] . '\nMessage: ' . $error['message']);
    }
});

function printError($code, $text) {
    echo json_encode(['r' => (object)[], 'e' => ['code' => $code, 'text' => $text]], JSON_UNESCAPED_UNICODE);
}

function printResult($result) {
    echo json_encode(['r' => $result, 'e' => (object)[]], JSON_UNESCAPED_UNICODE);
}

try {
    $body   = file_get_contents('php://input');
    $method = Controller::getMethod($_GET, $_POST, $_FILES, $body);

    printResult($method());
} catch (\Throwable $e) {
    printError($e->getCode(), $e->getMessage());
}
