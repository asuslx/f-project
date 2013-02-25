<?php
/**
 * User: asuslx
 * Date: 23.02.13
 * Time: 14:11
 */
 
class F_Tools_Uploader_Storage_Fs extends F_Tools_Uploader_Storage {

    private $_destDir;

    public function __construct($destDir) {

        $this->_destDir = $destDir;
    }


    public function save($fileName, $toName= '') {

        if(!$toName) $fileName = $toName;
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $this->_destDir . '/' . $fileName)) {

            return true;
        } else {
            trigger_error("Ошибка загрузки файла!", E_USER_WARNING);
        }


    }

}