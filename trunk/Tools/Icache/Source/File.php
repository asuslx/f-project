<?php
/**
 * User: asuslx
 * Date: 25.02.13
 * Time: 16:11
 */
 
class F_Tools_Icache_Source_Db extends F_Tools_Icache_Source {

    private $_fsDirectory;

    public function __construct($fsDirectory) {

        $this->_fsDirectory = $fsDirectory;
    }

    public function get($resourceId) {

        return file_get_contents($this->_fsDirectory.'/'.$resourceId);
    }

    public function put($resource, $resourceId) {

        return file_put_contents($this->_fsDirectory.'/'.$resourceId, $resource);
    }

}