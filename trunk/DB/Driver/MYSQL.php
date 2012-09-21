<?php
/**
 * {MYSQL.php}
 *
 * Author: asuslx (asuslx@gmail.com)
 * Date: 9/20/12
 */
 
class F_DB_Driver_MYSQL extends  F_DB_Driver {

    public function initialize($host, $login, $password, $dbname) {

        if(!mysql_connect($host, $login, $password)) {
            throw new F_DB_Exception("Unable to connect!");
        }

        if(!mysql_select_db($dbname)) {
            throw new F_DB_Exception("Unable to select db!");
        }

        if(!mysql_query("SET NAMES 'utf8'")) {
            throw new F_DB_Exception("Unable to set character encoding!");
        }

        return true;
    }

    // for small queries
    public function fetch($sql) {


        if(!$qry = mysql_query($sql)) return false;
        $result = array();
        while($rec = mysql_fetch_assoc($qry)) {$result[] = $rec;}

        return $result;
    }

    // returns cursor
    public function query($sql) {

        return mysql_query($sql);
    }

    // iterates cursor
    public function next($cursor) {

        return mysql_fetch_assoc($cursor);
    }

    // for exec statements
    public function exec($sql) {

        return mysql_query($sql);
    }

    // last error
    public function getLastError() {

        return mysql_error();
    }

}