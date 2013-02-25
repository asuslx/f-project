<?php
/**
 * User: asuslx
 * Date: 23.02.13
 * Time: 14:08
 */
 
abstract class F_Tools_Uploader_Storage {

    public abstract function save($fileName, $toName);
}