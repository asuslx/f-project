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

        $content = '';
        if(isset($_GET['_trace']) && $_GET['_trace'] == 'tmpl') {

            ob_start();
            $_b = "<pre style=\"position:relative; border: 1px solid #000; height: 9em; overflow: auto; margin: 0.5em; background-color:white; color:black;\">";
            $_e = "</pre>";
                var_dump($this->_fileName);
                var_dump($this->_data);
            $content .= ob_get_contents();
            $content = $_b. htmlspecialchars($content,  ENT_SUBSTITUTE, 'cp1251'). $_e;
            ob_end_clean();

        }
        
        ob_start();
        extract($this->_data);
        include ($this->_fileName);
        $content .= ob_get_contents();
        ob_end_clean();

        return $content;
    }

}