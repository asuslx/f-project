<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 1/26/13
 */

class F_Debugger {

    private static $_instance;

    public static function instance() {

        if(!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private $_streams = array();

    public function __construct() {

    }

    public function addStream($errorType, F_Debugger_Stream $stream) {

        $this ->_streams[] = array('type' => $errorType, 'stream' => $stream);
    }

    public static function __handle($errno, $errmsg, $filename, $linenum, $vars) {

        self::instance()->_handle($errno, $errmsg, $filename, $linenum, $vars);
    }

    public static function __shutdown() {
        require_once(dirname(__FILE__).'/Debugger/Message.php');
             
        if(!is_null($error = error_get_last())) {

            self::instance()->_handle($error['type'], $error['message'], $error['file'], $error['line'], array());
        }
    }

    private function _handle($errno, $errmsg, $filename, $linenum, $vars) {

        $handled = false;
        
        foreach($this->_streams as $_) {
            
            if(($errno & $_['type'])) {
                
                $_['stream'] ->put(new F_Debugger_Message($errno, $errmsg, $filename, $linenum, $vars));
                $handled = true;
            }
        }

        if(!$handled) {
            trigger_error("Unhandled by F_Debugger, errno: $errno msg: ". $errmsg, $errno);
        }
   }

    public function initialize() {

        error_reporting(0);
        set_error_handler("F_Debugger::__handle");
        register_shutdown_function("F_Debugger::__shutdown");
    }
}