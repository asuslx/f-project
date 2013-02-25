<?php
/**
 * User: asuslx
 * Date: 23.02.13
 * Time: 14:05
 */
 
class F_Tools_Uploader {

    private $_storage;
    private $_fileFieldName;

    public function __construct(F_Tools_Uploader_Storage $storage, $fileFieldName = 'Filedata') {

        $this->_storage = $storage;
        $this->_fileFieldName = $fileFieldName;
    }

    public function run($toName = '') {

        $uploadfile = basename($_FILES[$this->_fileFieldName]['name']);
        return $this->_storage->save($uploadfile, $toName);


    }

}