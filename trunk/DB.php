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
        $timeStart = microtime(true);
        $result = self::$_driver->initialize($host, $login, $password, $dbname);
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
           trigger_error("Connect to DB time: {$time} sec.", E_USER_NOTICE);
        }

         if($result === false) {
            trigger_error("DB Error: ".self::$_driver->getLastError());
        }
        return $result;
    }

    public static function exec($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
        $timeStart = microtime(true);
        $result = self::$_driver -> exec($sql);
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
           trigger_error("SQL: {$sql} | Time: {$time} sec.", E_USER_NOTICE);
        }

        if($result === false) {
            trigger_error("DB Error: ".self::$_driver->getLastError(). " SQL: ". $sql. " Time: ".$time, E_USER_ERROR);
        }
        return $result;
    }

    public static  function fetch($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
       
        $timeStart = microtime(true);
        $result = self::$_driver -> fetch($sql);
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;
        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
           trigger_error("SQL: {$sql} | Time: {$time} sec.", E_USER_NOTICE);
        }

        if($result === false) {
            trigger_error("DB Error: ".self::$_driver->getLastError(). " SQL: ". $sql. " Time: ".$time, E_USER_ERROR);
        }
        return $result;

    }

    public static  function query($sql) {

        if(func_num_args() > 1) {
            $sql = self::_formatQuery(func_get_args());
        }
        
        $timeStart = microtime(true);
        $result = self::$_driver -> query($sql);
        $timeEnd = microtime(true);
        $time = $timeEnd - $timeStart;

        if(!empty($_GET['_trace']) && $_GET['_trace']=='db') {
           trigger_error("SQL: {$sql} | Time: {$time} sec.", E_USER_NOTICE);
        }

        if($result === false) {
            trigger_error("DB Error: ".self::$_driver->getLastError(). " SQL: ". $sql. " Time: ".$time, E_USER_ERROR);
        }
        return $result;

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