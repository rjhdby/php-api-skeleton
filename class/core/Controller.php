<?php
namespace core;
/**
 * Class Controller
 * @package core
 *
 * Main class that orchestrating api calls
 */
class Controller
{
    private $methods = [];
    private $data;

    /**
     * $_POST or $_GET array will be passed as $data argument
     * depends of GET and DEBUG constants set in environment.php
     *
     * @param array $data
     */
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
        $namespace = '';
        $method    = '';
        for ($i = 0, $max = count($tokens); $i < $max; $i++) {
            switch ($tokens[ $i ][0]) {
                case T_CLASS:
                    return $method ? [$method => $namespace . '\\' . $tokens[ $i + 2 ][1]] : false;
                case T_NAMESPACE:
                    $namespace = $tokens[ $i + 2 ][1];
                    break;
                case T_DOC_COMMENT:
                    if (preg_match('/@api-call/m', $tokens[ $i ][1]) !== 0) {
                        $method = preg_replace("/.*@api-call\s+(\w+).*/s", "$1", $tokens[ $i ][1]);
                    }
            }
        }

        return false;
    }

    /**
     * Process api call and return resulting array or throw an Exception
     *
     * @return array
     */
    public function run() {
        $methodName = isset($this->data[ METHOD ])
            ? $this->data[ METHOD ]
            : 'wrongMethod';
        $class      = isset($this->methods[ $methodName ])
            ? $this->methods[ $methodName ]
            : $this->methods['wrongMethod'];

        $result = ['r' => [], 'e' => []];
        try {
            $request     = new $class($this->data);
            $result['r'] = $request();
        } catch (\Exception $e) {
            $result['e'] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
        }

        return $result;
    }
}