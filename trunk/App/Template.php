<?php
/**
 * Author: asuslx (asuslx@gmail.com)
 * Date: 4/26/12
 */
 
abstract class F_App_Template {

    abstract public function __construct($fileName);
    abstract public function assign($name, $value);
    abstract public function parse();
}