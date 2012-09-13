<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
abstract class F_App_Component {

    private $_template;
    private $_templateVars = array();

    public function __construct() {

        $file = APP_ROOT . 'tmpl/' . str_replace('_', '/', get_class($this)) . '.html';
        $this->_template = $this->getTemplate($file);
    }

    protected function _assign($name, $value) {

        $this->_templateVars[$name] = $value;
    }

    protected function getTemplate($file) {

        return new F_App_Template_Native($file);
    }

    protected abstract function _run();

    public function run() {

        $this->_run();

        foreach($this->_templateVars as $_n => $_v) {
            if($_v instanceof F_App_Component) $_v = $_v->run();
            $this->_template->assign($_n, $_v);
        }

        return $this->_template->parse();
    }

}