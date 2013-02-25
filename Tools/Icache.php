<?php
/**
 * User: asuslx
 * Date: 20.02.13
 * Time: 11:31
 */

const ICACHE_WIDTH_FULL = -1;
const ICACHE_HEIGHT_FULL = -1;
const ICACHE_QUALITU_FULL = 100;
 
class F_Tools_Icache {

    protected $config;
    protected $source;

    public function __construct(F_Tools_Icache_Config $config, F_Tools_Icache_Source $source) {

        $this->config = $config;
        $this->source = $source;
    }

    public function outImage($resourceId) {

        $parts = explode('.', $resourceId);
        $width = ICACHE_WIDTH_FULL;
        $height = ICACHE_HEIGHT_FULL;
        $quality = ICACHE_QUALITU_FULL;

        if(count($parts) == 3) {
            $params = explode('_', $parts[1]);
            $width = $params[0];
            $height = $params[1];
            $quality = $params[2];
        }

        $sourceResourceId = reset($parts) . '.' .end($parts);

        $resource = $this->source->get($sourceResourceId);
        if($resource) $result = file_put_contents($this->config->getCacheDir() . '/' . $resourceId, $resource);
        if($result) {
            header('Location: /icache/'. $resourceId);
        }
    }

    public function putImage($resourceId, $resource) {

        return $this->source->put($resource, $resourceId);
    }
}