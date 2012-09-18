<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ThumbNail
 *
 * @author Administrator
 */
class ThumbNail {
//$image = name of the original image
//$new_image_name = new name of image
//$width = width of image as you required
//$height = height of image as you required
//$fs_path = path where the file will be saved or located the original file
function create_abs_image($image, $new_image_name, $width, $height, $fs_path, $aspectratio=1, $resize=1) {
    $src = $fs_path . $image;

    if ($imagedata = @getimagesize($src)) {
        define('JPEGQUALITY', 95); //define the quality of JPG thumbnails
        error_reporting(0);

        $types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
        $not_supported_formats = array("PSD"); //write ext in capital letter
        umask(0);

        if (!$imagedata[2] || $imagedata[2] == 4 || $imagedata[2] == 5) {
            die('The specified file is not a picture!');
        }

        $imgtype = "!(ImageTypes() & IMG_" . strtoupper($types[$imagedata[2]]) . ")";

        if ((eval($imgtype)) || (in_array(strtoupper(array_pop(explode('.', basename($src)))), $not_supported_formats))) {
            $src = substr($src, (strrpos($fs_path . '/', '/')) + 1);
            return $src;
        }

        if (!isset($width))
            $width = floor($height * $imagedata[0] / $imagedata[1]);
        if (!isset($height))
            $height = floor($width * $imagedata[1] / $imagedata[0]);

        if ($aspectratio && isset($width) && isset($height)) {
            if ((($imagedata[1] / $height) > ($imagedata[0] / $width))) {
                $width = ceil(($imagedata[0] / $imagedata[1]) * $height);
            } else {
                $height = ceil($width / ($imagedata[0] / $imagedata[1]));
            }
        }

        $thumbfile = basename($new_image_name);

        $calc_x = ($imagedata[0] < $width ? $imagedata[0] : $width);
        $calc_y = ($imagedata[1] < $height ? $imagedata[1] : $height);


        if (($imagedata[0] > $width || $imagedata[1] > $height) || (($imagedata[0] < $width || $imagedata[1] < $height) && $resize)) {
            $makethumb = true;
        } else {
            $makethumb = false;
        }

//        $width = 80;
//        $height = 91.75;

        if ($makethumb) {
            $image = call_user_func("imagecreatefrom" . $types[$imagedata[2]], $src);

            if (function_exists("imagecreatetruecolor") && ($thumb = imagecreatetruecolor($width, $height))) {
                imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $imagedata[0], $imagedata[1]);
            } else {
                $thumb = imagecreate($width, $height);
                imagecopyresized($thumb, $image, 0, 0, 0, 0, $width, $height, $imagedata[0], $imagedata[1]);
            }

            if ($types[$imagedata[2]] == "jpeg") {
                call_user_func("image" . $types[$imagedata[2]], $thumb, $fs_path . $thumbfile, JPEGQUALITY);
            } else {
                call_user_func("image" . $types[$imagedata[2]], $thumb, $fs_path . $thumbfile);
            }

            imagedestroy($image);
            imagedestroy($thumb);
        }
    }
}

}

?>
