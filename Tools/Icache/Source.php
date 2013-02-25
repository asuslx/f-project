<?php
/**
 * User: asuslx
 * Date: 19.02.13
 * Time: 16:26
 */

abstract class F_Tools_Icache_Source {

    public abstract function get($resourceId);
    public abstract function put($resorce, $resorceId);

}