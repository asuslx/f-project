<?php
/**
 * User: asuslx
 * Date: 19.02.13
 * Time: 16:26
 */

const ICACHE_WIDTH_FULL = -1;
const ICACHE_HEIGHT_FULL = -1;
const ICACHE_QUALITU_FULL = 100;
 
abstract class F_Tools_Icache_Source {

    public abstract function get($resourceId, $width = ICACHE_WIDTH_FULL, $height = ICACHE_WIDTH_FULL, $quality = 100);
    public abstract function put($resorce, $resorceId);

}