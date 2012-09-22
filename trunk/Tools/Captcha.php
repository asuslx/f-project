<?php
/**
 * {Captcha.php}
 *
 * Author: asuslx (asuslx@gmail.com)
 * Date: 9/22/12
 */
 
class F_Tools_Captcha {

    private $_font;
    private $_image;
    private $_length;

    public function __construct($font, $image, $length = 5) {

        if(empty($_SESSION)) {
            throw new F_App_Exception("Session must be started before using F_Tools_Captcha.");
        }

        $this->_font = $font;
        $this->_image = $image;
        $this->_length = $length;

    }

    public static function password($size) {

        $letters = "1234567890qwertyuiopasdfghjklzxcvbnm";
        $password = "";
        for($i = 0; $i < $size; $i++) {
            $password .= $letters[rand(0, strlen($letters) - 1)];
        }
        return $password;
    }

    public function outImage() {

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", 10000) . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Content-Type:image/png");

        $code = self::password($this->_length);
        $linenum = rand(3, 7);

        $im = imagecreatefrompng($this->_image);

        for ($i=0; $i < $linenum; $i++) {

            $color = imagecolorallocate($im, rand(0, 150), rand(0, 100), rand(0, 150));
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }

        $x = rand(0, 35);
        for($i = 0; $i < strlen($code); $i++) {
            $x += 20;
            $letter=substr($code, $i, 1);
            imagettftext ($im, rand(25, 35), rand(2, 4), $x, rand(50, 55), rand(0, 0xffffff), $this->_font, $letter);
        }

        for ($i = 0; $i < $linenum; $i++) {

            $color = imagecolorallocate($im, rand(0, 255), rand(0, 200), rand(0, 255));
            imageline($im, rand(0, 20), rand(1, 50), rand(150, 180), rand(1, 50), $color);
        }

        ImagePNG ($im);
        ImageDestroy ($im);

        $_SESSION['f_tools_captcha'] = $code;
    }

    public static function check($code) {
        return $code == $_SESSION['f_tools_captcha'];
    }

}