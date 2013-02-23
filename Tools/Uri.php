<?php
/**
 * User: asuslx
 * Date: 22.02.13
 * Time: 17:01
 */
 
class F_Tools_Uri {

    private $_protocol;
    private $_host;
    private $_port;
    private $_path = array();
    private $_params;

    public function __construct($uri) {

        $uri = parse_url($uri);

        $this->_path = $uri['path'];
        $this->_port = empty($uri['port']) ? 80 : $uri['port'];
        $this->_host = empty($uri['host']) ? '' : $uri['host'];
        $this->_protocol = empty($uri['scheme']) ? 'http' : $uri['scheme'];

        // TODO: write eelse, check all

    }

    public function getProtocol() {

        return $this->_protocol;
    }

    public function getHost() {

        return $this->_host;
    }

    public function getPort() {

        return $this->_port;
    }

    public function getPath() {

        return $this->_path;
    }

    public function getPathArray() {
        $pathArray = array();
        $tmp = explode('/', $this->_path);
        foreach($tmp as $_) {
            if($_) $pathArray[] = $_;
        }
        return $pathArray;
    }

    public function getParams() {

        return $this->_params;
    }

}