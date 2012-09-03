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

            $content .= "<table style=\"border: 2px solid green; margin: 2px;\"><tr><tr><td style=\"border: 1px solid blue; color: red;\">".$this->_fileName."</td></tr><td>";

        }
        
        ob_start();
        extract($this->_data);
        include ($this->_fileName);
        $content .= ob_get_contents();
        ob_end_clean();

        if(isset($_GET['_trace']) && $_GET['_trace'] == 'tmpl') {
            $content .= "</td></tr></table>";
        }

        return $content;
    }

}