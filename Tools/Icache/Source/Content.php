<?php
/**
 * User: asuslx
 * Date: 05.03.13
 * Time: 9:15
 */
 

class F_Tools_Icache_Source_Content extends F_Tools_Icache_Source {

    public function __construct() {

    }

    public function get($resourceId) {

        list($id, $ext) = explode('.', $resourceId);
         $rows = F_DB::fetch("select body from f_app_content_images where id = %d", $id);
        return $rows[0]['body'];

    }

    public function put($resource, $resourceId) {

        list($id, $ext) = explode('.', $resourceId);

        $rows  = F_DB::fetch("select id from f_app_content_images where id=%d", $id);
        if(!empty($rows)) {
            return F_DB::exec(
               "update f_app_content_images
                set body='".mysql_real_escape_string($resource)."',
                ext='{$ext}'
                where id={$id}"
            );
        } else {
            return F_DB::exec(
               "insert into f_app_content_images (name, body, ext) values ('{$resourceId}','".mysql_real_escape_string($resource)."','{$ext}')"
            );
        }

    }

}