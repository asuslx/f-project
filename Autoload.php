<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/13/12
 */
 
class F_Autoload {

    private static $_instance;
    private static $delimiter = '_';
    private $paths = array();

    protected function __construct() {

        $this -> paths = array();
        spl_autoload_register('self::autoload', true);
    }

    public static function instance() {

        if(!self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function registerPath($path) {
        $this->paths[] = $path;
    }

    public function fileToClassName ($fileName) {

        foreach($this->paths as $path) {
            $fileName = str_replace($path, '', $fileName);
        }
        $className = str_replace('/', self::$delimiter, $fileName);
        $className = substr($className, 0, strpos($className, '.'));
        $className = trim($className, '_');
        return $className;
    }

    private static function _fileExists($file) {

        $ps = explode(":", ini_get('include_path'));
        foreach($ps as $path) {
            if(file_exists($path.'/'.$file)) return true;
        }
        if(file_exists($file)) return true;
        return false;

    }

    private static function autoload($class) {

        $file = str_replace(self::$delimiter, '/', $class) . '.php';

        if(self::_fileExists($file)) {
            require_once ($file);

        } else {

            foreach(self::instance()->paths as $path) {
                if(file_exists($path . $file)) {
                    require_once ($path . $file);
                    break;
                }
            }
        }
    }

}

F_Autoload::instance();
