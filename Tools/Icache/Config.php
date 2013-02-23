<?php
/**
 * User: asuslx
 * Date: 19.02.13
 * Time: 16:25
 */
 
class F_Tools_Icache_Config {

    private $_cacheDir;
    private $_tmpDir;

    public function __construct($cacheDir, $tmpDir) {

        $this->_cacheDir  = $cacheDir;
        $this->_tmpDir = $tmpDir;
    }

    public function getTmpDir() {
        return $this->_tmpDir;
    }

    public function getCacheDir() {
        return $this->_cacheDir;
    }

}