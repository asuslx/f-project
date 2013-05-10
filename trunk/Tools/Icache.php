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

    private function _imageResize($ext, $image, $width, $height){
        // Get original size of image
        $image = imagecreatefromstring($image);

        // Create new image using thumbnail sizes
        $thumb = imagecreatetruecolor($width,$height);


        $srcw = imagesx($image);
        $srch = imagesy($image);



        if($width > $height) {
            $correction = $height / $width;
            $srch = $srch * $correction;
        } else {
            $correction =  $width / $height;
            $srcw = $srcw * $correction;
        }

        // Copy original image to thumbnail
        imagecopyresized($thumb,$image,0,0,0,0,$width,$height,$srcw,$srch);
        ob_start();
            switch($ext){
                case 'bmp': imagewbmp($thumb); break;
                case 'gif': imagegif($thumb); break;
                case 'jpg': imagejpeg($thumb); break;
                case 'png': imagepng($thumb); break;
            }
            $thumb = ob_get_contents(); // read from buffer
        ob_end_clean(); // delete buffer

        return $thumb;

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

        $result = false;
        $resized = $resource;
        if($resource) {
            if(($width != ICACHE_WIDTH_FULL) && ($height != ICACHE_HEIGHT_FULL)) {

                $resized = $this->_imageResize(end($parts), $resource, $width, $height);
              
            }
            $result = file_put_contents($this->config->getCacheDir() . '/' . $resourceId, $resized);
        }
        if($result) {
           
            header('Location: '. str_replace(WWW_ROOT,"",$this->config->getCacheDir()). $resourceId);
        }
    }

    public function putImage($resourceId, $resource) {

        return $this->source->put($resource, $resourceId);
    }
}