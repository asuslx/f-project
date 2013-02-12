<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
class F_App_Template_Native extends F_App_Template {

    private $_fileName;
    private $_data;

    public function __construct($fileName) {

        if(!file_exists($fileName)) throw new F_App_Exception_Template("Template file $fileName not found!");

        $this->_fileName = $fileName;
        $this->_data = array();
    }

    public function assign($name, $value) {

        $this->_data[$name] = $value;
    }

    public function parse() {

        $__tmpl__content = '';
        
        ob_start();
            extract($this->_data);
            include ($this->_fileName);
            $__tmpl__content .= ob_get_contents();
        ob_end_clean();

        if(isset($_GET['_trace']) && $_GET['_trace'] == 'tmpl') {
            $__tmpl__content = '<a href="#" title="'.$this->_fileName.'">[ tmpl ]</a><br/>' . $__tmpl__content ;
        }

        return $__tmpl__content;
    }

}