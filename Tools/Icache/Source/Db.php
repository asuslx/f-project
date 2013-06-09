<?php
/**
 * User: asuslx
 * Date: 22.02.13
 * Time: 16:37
 */
 
class F_Tools_Icache_Source_Db extends F_Tools_Icache_Source {

    protected $tableName;
    protected $idField;
    protected $extField;
    protected $imageField;

    public function __construct($tableName, $idField, $extField, $imageField) {

        $this->tableName = $tableName;
        $this->idField = $idField;
        $this->extField = $extField;
        $this->imageField = $imageField;
    }

    public function get($resourceId) {

        list($id, $ext) = explode('.', $resourceId);
        $rows = F_DB::fetch("select {$this->imageField} from {$this->tableName} where {$this->idField} = %d ", $id);
        if(!empty($rows)) {
            return $rows[0][$this->imageField];
        } else {
            return false;
        }

    }

    public function put($resource, $resourceId) {
       
        list($id, $ext) = explode('.', $resourceId);

        return F_DB::exec(
           "update {$this->tableName}
            set {$this->imageField}='".mysql_real_escape_string($resource)."',
                {$this->extField}='{$ext}'
            where {$this->idField}={$id}"
        );


    }

}