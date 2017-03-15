<?php
namespace core;

use methods\core\WrongMethod;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

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
        if (!CASE_SENSITIVE) {
            $this->methods = array_change_key_case($this->methods, CASE_LOWER);
        }
    }

    private static function mapStatic() {
        return Config::parseCustomConfig(METHODS);
    }

    private static function mapDynamic() {
        $methods  = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(ROOT . '/class/methods'));
        $regex    = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        foreach ($regex as $file => $value) {
            $current = self::parseTokens(token_get_all(file_get_contents(str_replace('\\', '/', $file))));
            if ($current !== false) {
                $methods[ key($current) ] = current($current);
            }
        }

        return $methods;
    }

    private static function parseTokens(array $tokens) {
        $nsStart    = false;
        $classStart = false;
        $namespace  = '';
        $method     = '';
        foreach ($tokens as $token) {
            if ($token[0] === T_CLASS) {
                if ($classStart && $method === '') {
                    return false;
                }
                $classStart = true;
            }
            if ($classStart && $token[0] === T_STRING) {
                return [$method => $namespace . $token[1]];
            }
            if ($token[0] === T_DOC_COMMENT && preg_match('/@api-call/m', $token[1]) !== 0) {
                $method = preg_replace("/.*@api-call\s+(\w+).*/s", "$1", $token[1]);
            }
            if ($token[0] === T_NAMESPACE) {
                $nsStart = true;
            }
            if ($nsStart && $token[0] === ';') {
                $nsStart = false;
            }
            if ($nsStart && $token[0] === T_STRING) {
                $namespace .= $token[1] . '\\';
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
        $result = ['r' => (object)[], 'e' => (object)[]];
        if (!isset($this->data[ METHOD ])) {
            $result['e'] = ['code' => 0, 'text' => 'Unknown method'];

            return $result;
        }
        $methodName = $this->data[ METHOD ];
        if (CASE_SENSITIVE === false) {
            $methodName = mb_strtolower($methodName);
        }
        $class = isset($this->methods[ $methodName ])
            ? $this->methods[ $methodName ]
            : WrongMethod::class;

        try {
            $request     = new $class($this->data);
            $result['r'] = $request();
        } catch (\Exception $e) {
            $result['e'] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
        }

        return $result;
    }
}