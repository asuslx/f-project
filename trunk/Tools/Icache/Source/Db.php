<?php
/**
 * User: asuslx
 * Date: 22.02.13
 * Time: 16:37
 */
 
class F_Tools_Icache_Source_Db extends F_Tools_Icache_Source {

    public function get($resourceId, $width = ICACHE_WIDTH_FULL, $height = ICACHE_HEIGHT_FULL, $quality = ICACHE_QUALITU_FULL) {
        return $resourceId . '  - (' . $width . '_' .
        $height . '_' .
        $quality . ')';
    }

    public function put($resource, $resourceId) {

    }

}