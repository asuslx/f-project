<?php
/**
 * {Driver.php}
 *
 * Author: asuslx (asuslx@gmail.com)
 * Date: 9/20/12
 */
 
abstract class F_DB_Driver {

    public abstract function initialize($host, $login, $password, $dbname);
    public abstract function exec($sql);
    public abstract function fetch($sql);
    public abstract function query($sql);
    public abstract function next($qry);
    public abstract function getLastError();
}