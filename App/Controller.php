<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
abstract class F_App_Controller {

    protected $params = array();
    protected $frame = null;
    private $_template;

    public function __construct () {

        $this->_initialize();
    }

    protected abstract function _initialize();

    protected function onException(Exception $e) {
        throw $e;
    }

    protected function getTemplate($file) {

        return new F_App_Template_Native($file);
    }

    protected function getParams($action) {

        if(isset($this->params[$action]))
            return $this->params[$action];
        else return array();
    }

    public function runAction($action, $request) {

        $method = 'action'.ucfirst($action);

        try {

            $params = array();

            if(method_exists($this, $method)) {

                $templateFile = TMPL_ROOT . str_replace('_', '/', get_class($this)) .'/'. ucfirst($action) . '.html';

                if(!$this->_template = $this->getTemplate($templateFile)) {
                    throw new F_App_Exception_Controller("Unable to load tamplate '".$templateFile."'!");
                }

                $metaParams = $this->getParams($action);

                foreach($metaParams as $metaParam) {
                    $params[$metaParam['name']]= $request->getParam($metaParam);
                }

                $this->$method ($params);
                
            } elseif(method_exists($this, 'action404')) {

                $templateFile = TMPL_ROOT . str_replace('_', '/', get_class($this)) .'/404.html';
                $this->_template = $this->getTemplate($templateFile);
                $this->Action404($params);

            } else {

                throw new F_App_Exception_404("Action $action not found in controller ".get_class($this)."!");
            }

        } catch(Exception $e) {
            $this->onException($e);
        }

        $html =  $this->_template->parse();

        if ($this->frame) {

            if($this->frame instanceof F_App_Frame) {

                $frameHtml = $this->frame->run($params);

                if(strpos($frameHtml, '{controller}') === false) {
                    throw new F_App_Exception_Controller("Frame content must contain \"{controller}\" keyword!");
                } else {

                    $html = str_replace( '{controller}', $html, $frameHtml);
                }

            } else {
                throw new F_App_Exception_Controller("Frame must be instance of F_App_Frame!");
            }

        }

        return $html;
    }

    protected function _assign($name, $value) {

        if($this->_template) {

            if($this->_template instanceof F_App_Template) {

                if($value instanceof F_App_Component) $value = $value->run();
                $this->_template->assign($name, $value);

            } else throw new F_App_Exception_Controller("Template tremplate for controller ".get_class($this)." must be instance of F_App_Template!");

        } else throw new F_App_Exception_Controller("Undefined tremplate for controller ".get_class($this)."!");
    }

}
