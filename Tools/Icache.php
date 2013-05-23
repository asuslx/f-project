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

    private function _imageResize($ext, $image, $width, $height, $quality = 100) {
        // Get original size of image
        $image = imagecreatefromstring($image);

        $srcw = imagesx($image);
        $srch = imagesy($image);

        if($width == ICACHE_WIDTH_FULL) $width = $srcw;
        if($height == ICACHE_HEIGHT_FULL) $height = $srch;

        // Create new image using thumbnail sizes
        $thumb = imagecreatetruecolor($width,$height);

        if(!($width == $srcw && $height == $srch)) {
            if(($srcw < $srch)) {
                $srch = $srcw *  $height / $width;
            } else {
                $srcw = $srch * $height / $width;
            }
        }
        
        // Copy original image to thumbnail
        imagecopyresampled($thumb,$image,0,0,0,0,$width,$height,$srcw,$srch);
        ob_start();
            switch($ext){
                case 'bmp': imagewbmp($thumb, null); break;
                case 'gif':
                    imagegif($thumb, null);
                break;
                case 'jpg': imagejpeg($thumb, null, $quality); break;

                case 'png':
                    $quality = round($quality / 10) - 1;
                    $quality = $quality < 0 ? 0 :$quality;
                    imagepng($thumb, null, $quality);
                    break;
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
            if(($width != ICACHE_WIDTH_FULL) || ($height != ICACHE_HEIGHT_FULL) || ($quality != 100)) {

                $resized = $this->_imageResize(end($parts), $resource, $width, $height, $quality);
              
            }
            $result = file_put_contents($this->config->getCacheDir() . '/' . $resourceId, $resized);
        }
        if($result) {
           
            header('Location: '. str_replace(WWW_ROOT,"",$this->config->getCacheDir()). $resourceId);
        }
    }

    private static function clearCache($path, $match) {

        static $deld = 0, $dsize = 0;
        $dirs = glob($path."*");
        $files = glob($path.$match);
        foreach($files as $file) {
            if(is_file($file)){
                $dsize += filesize($file);
                unlink($file);
                $deld++;
            }
        }
    }

    public function putImage($resourceId, $resource) {

        list($id, $ext) = explode('.', $resourceId);
        self::clearCache($this->config->getCacheDir(), $id."*".$ext);

        return $this->source->put($resource, $resourceId);
    }
}