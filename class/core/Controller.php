<?php
namespace core;

class Controller
{
    private $methods = [];
    private $data;

    public function __construct($data) {
        $this->data    = $data;
        $this->methods = STATIC_MAPPING
            ? self::mapStatic()
            : self::mapDynamic();
    }

    private static function mapStatic() {
        return Config::parseCustomConfig(METHODS);
    }

    private static function mapDynamic() {
        $methods = [];
        foreach (scandir(ROOT . '/class/methods') as $fileName) {
            if (substr($fileName, -4) !== '.php') {
                continue;
            }
            $current = self::parseTokens(token_get_all(file_get_contents(ROOT . '/class/methods/' . $fileName)));
            if ($current !== false) {
                $methods[ key($current) ] = current($current);
            }
        }

        return $methods;
    }

    private static function parseTokens($tokens) {
        $namespace = false;
        $class     = false;
        $method    = false;
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
                        $method = preg_replace("/.*@api-call\s+(\w+).*/", "$1", $tokens[ $i ][1]);
                    }
                    break;
            }
            if ($class && !$method) {
                return false;
            }
            if ($class && $method && !$namespace) {
                return [$method => $class];
            }
            if ($class && $method && $namespace) {
                return [$method => $namespace . '\\' . $class];
            }
        }

        return false;
    }

    public function run() {
        $methodName = isset($this->data[ METHOD ])
            ? $this->data[ METHOD ]
            : 'wrongMethod';
        $class      = isset($this->methods[ $methodName ])
            ? $this->methods[ $methodName ]
            : $this->methods['wrongMethod'];

        $result  = ['r' => [], 'e' => []];
        $request = new $class($this->data);
        try {
            $result['r'] = $request();
        } catch (\Exception $e) {
            $result['e'] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
        }

        return $result;
    }
}