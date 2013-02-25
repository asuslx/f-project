<?php
/**
 * User: asuslx
 * Date: 23.02.13
 * Time: 14:10
 */
 
class F_Tools_Uploader_Storage_Db extends F_Tools_Uploader_Storage {

    public function __construct($tableName, $idField, $extField, $imageField) {

    }

    public function get($resourceId) {
        return $resourceId . '  - (' . $width . '_' .
        $height . '_' .
        $quality . ')';
    }

    public function put($resource, $resourceId) {

    }

}