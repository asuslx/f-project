<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */

define("F_LOCATION", dirname(dirname(__FILE__)) . '/');
define("APP_ROOT",   dirname(dirname($_SERVER['SCRIPT_FILENAME'])). '/');

define("WWW_ROOT",  APP_ROOT . "www/");
define("CODE_ROOT", APP_ROOT . "code/");
define("TMPL_ROOT", APP_ROOT . "tmpl/");

define("ROOT_CTRL", "Ctrl");

include_once(F_LOCATION . "F/Autoload.php");

F_Autoload::instance()->registerPath(F_LOCATION);
F_Autoload::instance()->registerPath(CODE_ROOT);
F_Autoload::instance()->registerPath(TMPL_ROOT);

 
class F_App {

    private static $_instance;

    public static function instance() {

        if(!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private $_request;

    protected function __construct() {

        $this->_request = new F_App_Request();
    }

    private function _getCallInfo() {

        $path = ROOT_CTRL.'/'. trim($this->_request->getPath(),'/');
        $path = trim($path, '/');
        $path = explode('/',$path);
        

        $controllerClass = '';
        $action = 'Index';
        $checkForClass = '';
        $existedClass = '';

        for($i = 0; $i < count($path); $i++) {
            $controllerClass .= ($i?'_':'') . ucfirst($path[$i]);
            $checkForClass[]= $controllerClass;
        }
        $checkForClass = array_reverse($checkForClass);
        $prev = false;
        foreach($checkForClass as $controllerClass) {

            if(class_exists($controllerClass)) {
                $existedClass = $controllerClass;
                if($prev) {
                    $_ = explode('_', $prev);
                    $action = ucfirst(end($_));
                }
                break;
                
            } else {
                $prev = $controllerClass;
            }
        }

        return new F_App_CallInfo($existedClass, $action, $this->_request);
    }

    public function run() {

        $callInfo = $this->_getCallInfo();

        $class = $callInfo->getClass();
        $action = $callInfo->getAction();
        $request = $callInfo->getRequest();
        $ctrl = new $class();
        echo $ctrl->runAction($action, $request);

    }

}
