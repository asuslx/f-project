<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
class F_App_Frame {

    private $_template;

    public function __construct() {

        $file = APP_ROOT . 'tmpl/' . str_replace('_', '/', get_class($this)) . '.html';
        $this->_template = $this->getTemplate($file);
    }

    protected function _assign($name, $value) {

        $this->_template->assign($name, $value);
    }

    protected function getTemplate($file) {

        return new F_App_Template_Native($file);
    }

    public function run() {

        return $this->_template->parse();
    }

}