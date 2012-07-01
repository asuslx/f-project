<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
class F_App_Request {

    private $_path;
    private $_sourceData;

    public function __construct() {

        $this->setSource('get', $_GET);
        $this->setSource('post', $_POST);
        $this->setSource('request', $_REQUEST);
        $this->setSource('cookie', $_COOKIE);

        $this->_path = $_SERVER['REQUEST_URI'];
        $len = strpos($_SERVER['REQUEST_URI'], '?');
        if($len) {
            $this->_path = substr(strtolower($_SERVER['REQUEST_URI']), 0, $len);
        }
    }

    public function setSource($name, $data) {

        $this->_sourceData[self::_weakName($name)] = $data;
    }


    public function getPath() {

        return $this->_path;
    }

    public function getParam($param, $source = 'request', $default = null, $type = false, $strict = false) {

        if(is_array($param)) {

            $source = isset($param['source']) ? $param['source'] : $source;
            $default = isset($param['default']) ? $param['default'] : $default;
            $type = isset($param['type']) ? $param['type'] : $type;
            $strict = isset($param['strict']) ? $param['strict'] : $strict;
            if(isset($param['name'])) {
                $param = $param['name'];
            } else {
                throw new F_App_Exception_Controller("param represented as array don't consist \"name\"");
            }
        }

        $result = null;
        if(!isset($this->_sourceData[self::_weakName($source)])) {
            throw new F_App_Exception_Request("Source named \"$source\" is not set!");
        }
        if(isset($this->_sourceData[self::_weakName($source)][$param])) {
            $result = $this->_sourceData[self::_weakName($source)][$param];
        } elseif($strict) {
            throw new F_App_Exception_Request("Strict param named \"$param\" is not found!");
        } else {
            $result = $default;
        }

        return self::_validate($result, $type, $strict);
    }

    private static function _weakName($name) {

        return strtoupper(trim($name));
    }

    private static function _validate($value, $type, $strict) {

        $result = $value;
        if(!$type) return $result;

        $result = self::_convert($value, $type);

        if($strict && ((string) $result != (string)$value)) throw new F_App_Exception_Request("Type conversion failed for strict parameter!");

        return $result;
    }

    private static function _convert($value, $type) {

        switch(self::_weakName($type)) {

            case 'STR':
            case 'STRING':
                $value = (string) $value;
                break;

            case 'INT':
            case 'INTEGER':
                $value = (int) $value;
                break;

            case 'FLOAT':
                $value = (float) $value;
                break;

            default: throw new F_App_Exception_Request("Invalid type requested \"$type\"!");
        }

        return $value;
    }


}