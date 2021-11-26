<?php

namespace Over_Code\Libraries\Captcha;

/**
 * Used to generate image of the form number.
 */
final class Image
{
    public function create(string $text): string
    {
        // Image creation
        $img = imagecreatetruecolor(118, 30);

        // Colors creation
        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagefilledrectangle($img, 0, 0, 129, 29, $white);

        // Define font to use
        $font = LIB_PATH . DS . 'captcha' . DS . 'fonts' . DS . 'destruct.ttf';

        // Add text
        imagettftext($img, 24, 0, 3, 29, $black, $font, $text);

        // Output image in a variable before destroy it
        ob_start();
        imagejpeg($img);
        $content = ob_get_contents();
        ob_end_clean();

        imagedestroy($img);

        return base64_encode($content);
    }
}
