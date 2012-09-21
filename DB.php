<?php
/**
 * {DB.php}
 *
 * Author: asuslx (asuslx@gmail.com)
 * Date: 9/20/12
 */
 
class F_DB {

    private static $_driver;

    public static function initialize(F_DB_Driver $d, $host, $login, $password, $dbname) {

        self::$_driver = $d;
        return self::$_driver->initialize($host, $login, $password, $dbname);
    }

    public static function exec($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
            echo $sql.'<br/>';
        }

        return self::$_driver -> exec($sql);
    }

    public static  function fetch($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
            echo $sql.'<br/>';
        }

        return self::$_driver -> fetch($sql);
    }

    public static  function query($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
            echo $sql.'<br/>';
        }

        return self::$_driver -> query($sql);
    }

    public static  function next($qry) {

        return self::$_driver -> next($qry);
    }

    public static  function getLastError() {
        
        return self::$_driver -> getLastError();
    }


    private static function _formatQuery($args) {

        $params = array();
        $sql = $args[0];
        for($i = 1; $i < count($args); $i++) {
            if(is_string($args[$i])) $args[$i] = mysql_real_escape_string($args[$i]);
            $params[]=$args[$i];
        }
        eval('$sql = sprintf($sql, "'.implode('","', $params).'");');

        return $sql;
    }

}