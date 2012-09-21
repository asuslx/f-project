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

    private $_callInfo;

    private static $_instance;

    public static function instance() {

        if(!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function __construct() {

        $request = new F_App_Request();

        $path = ROOT_CTRL.'/'. trim($request->getPath(),'/');
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

        $this->_callInfo = new F_App_CallInfo($existedClass, $action, $request);

    }

    public function getCallInfo() {

        return $this->_callInfo;
    }

    public function isAdminMode() {

        $domain = $this->_callInfo->getRequest()->getHost();
        $parts = explode('.', $domain);
        $check = 0;
        if(strtolower($parts[0]) == 'www') $check++;

        return (strtolower($parts[$check]) == 'admin');
    }

    public function run() {

        $class = $this->_callInfo->getClass();
        $action = $this->_callInfo->getAction();
        $request = $this->_callInfo->getRequest();

        $ctrl = new $class();

        echo $ctrl->runAction($action, $request);
    }

}
