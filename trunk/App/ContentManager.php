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
*/

class F_App_ContentManager {

    public static function get($name) {

        if((isset($_REQUEST['f_app_edit_tile']) && $_REQUEST['f_app_edit_tile'] == 'edit')) {

            $content = F_DB::fetch("SELECT content FROM f_app_content where name = '%s'", $name);
            if($content === false) throw new F_App_Exception(F_DB::getLastError());
            if(!empty($content)) {

                if(!F_DB::exec("UPDATE f_app_content SET content = '%s' where name='%s'",
                               mysql_real_escape_string($_REQUEST['content']),
                               mysql_real_escape_string($_REQUEST['name']))) {
                    throw new F_App_Exception(F_DB::getLastError());
                }

            } else {
                if(!F_DB::exec("INSERT INTO f_app_content (name, content) values ('%s', '%s')",
                               mysql_real_escape_string($_REQUEST['name']),
                               mysql_real_escape_string($_REQUEST['content'])))
                    throw new F_App_Exception(F_DB::getLastError());
            }
        }

        $content = F_DB::fetch("SELECT content FROM f_app_content where name = '%s'", $name);
        if($content === false) throw new F_App_Exception(F_DB::getLastError());
        if(!empty($content)) {
            $content = $content[0]['content'];
        } else  {
            $content = "";
        }

        if(self::_isEditMode($name)) {
            if(F_App::instance()->isAdminMode()) {
                $content = self::_editForm($name, $content);
            }
        } else {
            if(F_App::instance()->isAdminMode()) {
                $content = self::_editLink($name, $content);
            }
        }

        return $content;
    }

    public static function edit($name) {

        if(F_App::instance()->isAdminMode()) {

        } else throw new F_App_Exception("Access deined!");

    }

    private static function _editForm($name, $content) {
        $out = '<form method="POST" action="?"><input type="hidden" name="name" value="'.$name.'"/><textarea name="content" rows="10" style="width:100%">';
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

}