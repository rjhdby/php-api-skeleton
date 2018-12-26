<?php

namespace core;

use errors\ParameterException;
use errors\WrongMethodException;
use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class Controller
{
    /**
     * @param $get
     * @param $post
     * @param $files
     * @param $body
     * @return Method
     * @throws ParameterException
     * @throws WrongMethodException
     */
    public static function getMethod($get, $post, $files, $body) {
        $methodName = mb_strtolower(self::getMethodName($get, $post, $body));
        $method     = self::findMethod($methodName);
        /** @noinspection PhpIncludeInspection */
        require_once $method['path'];
        $class = $method['ns'] . $method['class'];

        return new $class($get, $post, $files, $body);
    }

    /**
     * @param $get
     * @param $post
     * @param $body
     * @return mixed
     * @throws ParameterException
     */
    private static function getMethodName($get, $post, $body) {
        if (isset($get[ METHOD ])) {
            return $get[ METHOD ];
        }
        if (isset($post[ METHOD ])) {
            return $post[ METHOD ];
        }
        try {
            $parsedBody = json_decode($body, true);
            if (isset($parsedBody[ METHOD ])) {
                return $parsedBody[ METHOD ];
            }
        } catch (Exception $e) {
        }

        throw new ParameterException('Method does not set');
    }

    /**
     * @param string $methodName
     * @return array
     * @throws WrongMethodException
     */
    private static function findMethod($methodName) {
        $methodName = mb_strtolower($methodName);
        $methods    = [];
        if (is_file(METHODS)) {
            $file    = file_get_contents(METHODS);
            $methods = json_decode($file, true);
            if (isset($methods[ $methodName ])) {
                $method = $methods[ $methodName ];
                $stat   = stat($method['path']);
                if ($stat['size'] !== $method['size'] || $stat['mtime'] !== $method['mtime']) {
                    $methods = [];
                }
            }
        }
        if (empty($methods) || !isset($methods[ $methodName ])) {
            $methods = self::reReadMethods();
        }
        if (empty($methods) || !isset($methods[ $methodName ])) {
            throw new WrongMethodException($methodName);
        }

        return $methods[ $methodName ];
    }

    private static function reReadMethods() {
        $methods  = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOT . '/class/methods'));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file) {
            $filePath = str_replace('\\', '/', $file[0]);
            $stat     = stat($filePath);
            $current  = self::parseTokens($filePath);
            if (empty($current)) {
                continue;
            }
            $methods[ mb_strtolower($current['class']) ] = [
                'path'  => $filePath,
                'size'  => $stat['size'],
                'mtime' => $stat['mtime'],
                'class' => $current['class'],
                'ns'    => $current['ns']
            ];
        }
        self::saveMapping($methods);

        return $methods;
    }

    private static function parseTokens($file) {
        $tokens     = token_get_all(file_get_contents($file));
        $nsStart    = false;
        $classStart = false;
        $namespace  = '';
        foreach ($tokens as $token) {
            switch ($token[0]) {
                case ';':
                    if ($nsStart) {
                        $nsStart = false;
                    }
                    break;
                case T_CLASS:
                    $classStart = true;
                    break;
                case T_NAMESPACE:
                    $nsStart = true;
                    break;
                case T_STRING:
                    if ($classStart) {
                        return ['class' => $token[1], 'ns' => $namespace];
                    }
                    if ($nsStart) {
                        $namespace .= $token[1] . '\\';
                    }
                    break;
            }
        }

        return [];
    }

    private static function saveMapping(array $methods) {
        file_put_contents(METHODS, json_encode($methods));
    }
}