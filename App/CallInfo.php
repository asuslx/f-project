<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/27/12
 */
 
class F_App_CallInfo {

    private $_class;
    private $_action;
    private $_request;

    public function __construct($class, $action, $request) {

        $this->_class = $class;
        $this->_action = $action;
        $this->_request = $request;
    }

    public function getClass() {

        return $this->_class;
    }

    public function getAction() {

        return $this->_action;
    }

    public function getRequest() {

        return $this->_request;
    }

}