<?php
/**
 * {ContentManager.php}
 *
 * Author: asuslx (asuslx@gmail.com)
 * Date: 9/21/12
 */

/**
 *
 * MySQL tab DDL:
 *
 *
   CREATE TABLE f_app_content(
    name varchar( 255 ) UNIQUE ,
    content longtext
   );

   CREATE TABLE f_app_content_images(
    id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT ,
    name VARCHAR( 255 ) ,
    body MEDIUMBLOB,
    ext VARCHAR( 10 )
   );
*/

class F_Tools_ContentManager {

    public static function get($name, $adminMode = false) {

        if((isset($_REQUEST['f_app_edit_tile']) && $_REQUEST['f_app_edit_tile'] == 'edit')) {

            $content = F_DB::fetch("SELECT content FROM f_app_content where name = '%s'", $name);

            if(!empty($content)) {
                if ($adminMode)
                F_DB::exec("UPDATE f_app_content SET content = '%s' where name='%s'",
                               $_REQUEST['content'],
                               $_REQUEST['name']);

            } else {
                if($adminMode)
                F_DB::exec("INSERT INTO f_app_content (name, content) values ('%s', '%s')",
                               $_REQUEST['name'],
                               $_REQUEST['content']);

            }
        }

        $content = F_DB::fetch("SELECT content FROM f_app_content where name = '%s'", $name);

        if(!empty($content)) {
            $content = $content[0]['content'];
        } else  {
            $content = "";
        }

        if(self::_isEditMode($name)) {
            if($adminMode) {
                $content = self::_editForm($name, $content);
            }
        } else {
            if($adminMode) {
                $content = self::_editLink($name, $content);
            }
        }

        return $content;
    }

    private static function _editForm($name, $content) {
        $out = '<form method="POST" action="?"><input type="hidden" name="name" value="'.$name.'"/><textarea name="content" rows="7" style="width:100%">';
        $out .= $content;
        $out .= '</textarea><input type="submit" name="f_app_edit_tile" value="edit"/></form>';
        return $out;
    }

    private static function _editLink($name, $content) {
        $out = '<div><div style="width:100%; text-align:right;"><a href="?f_app_edit_tile='.$name.'">[edit]</a></div>';
        $out .= $content;
        $out .= '</div>';
        return $out;
    }

    private static function _isEditMode($name) {

        return (isset($_REQUEST['f_app_edit_tile']) && $_REQUEST['f_app_edit_tile'] == $name);
    }

    public static function getImagesCount() {

        $row = F_DB::fetch('select count(*) as cnt from f_app_content_images');
        return $row[0]['cnt'];
    }

    public static function getImages($offset = 0, $limit = 30, $urlPrefix = '/icache/content_images/', $width = -1, $height = -1) {
        $rows = F_DB::fetch("select id, name, ext from f_app_content_images limit %d, %d", $offset, $limit);
        foreach($rows as &$row) {
            $size = '';
            if($width > 0 && $height > 0) {
                $size = '.'.$width.'_'.$height.'_100.';
            }

            $row['url'] = $urlPrefix .$row['id'].$size.'.'.$row['ext'];
        }
        return $rows;
    }

    public static function addImage($name, $body, $ext) {

        return F_DB::exec("insert into f_app_content_images (name, body, ext) values ('%s','%s','%s')", $name, $body, $ext);
    }

    public static function deleteImage($id) {

        return F_DB::exec("delete from f_app_content_images where id=%d", $id);
    }
}