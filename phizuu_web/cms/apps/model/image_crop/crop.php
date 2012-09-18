<?php
include("functions.php");

$file = $_REQUEST["file"];
$x1 = $_REQUEST["x1"];
$y1 = $_REQUEST['y1'];
$width = $_REQUEST['width'];
$height = $_REQUEST['height'];

$r_w = $_REQUEST['r_w'];
$r_h = $_REQUEST['r_h'];

$image = "../../temporary_files/bulk_images/".$file;
$dest_image = "../../temporary_files/images/".$file;
$src = "../temporary_files/images/".$file;

do_crop($image,$dest_image,$x1,$y1,$width,$height,100);


//file_put_contents("test.txt", $r_w);
if($r_w==191) {
    $r_h = 191;
    $r_w = 320;
}

if($r_w==1024) {
   smart_resize_image($dest_image, $r_w, $r_h, true, 'file', false);
   
   $dest_image_iTunesArtwork = "../../temporary_files/images/iTunesArtwork_".$file;
   $imageObj = smart_resize_image($dest_image, 512, 512, true, 'return', false);
   imagepng($imageObj, $dest_image_iTunesArtwork);
   imagedestroy($imageObj);
   
   //create thumb for I-Phone X 57
   $dest_image_icon = "../../temporary_files/images/icon_".$file;
   $imageObj = smart_resize_image($dest_image, 57, 57, true, 'return', false);
   imagepng($imageObj, $dest_image_icon);
   imagedestroy($imageObj);
   //create thumb for I-Phone X 114
   $dest_image_icon2x = "../../temporary_files/images/icon@2x_".$file;
   $imageObj = smart_resize_image($dest_image, 114, 114, true, 'return', false);
   imagepng($imageObj, $dest_image_icon2x);
   imagedestroy($imageObj);
   
   $dest_image_facebook = "../../temporary_files/images/fb_".$file;
   $imageObj = smart_resize_image($dest_image, 90, 90, true, 'return', false);
   imagepng($imageObj, $dest_image_facebook);
   imagedestroy($imageObj);
   
   //Anroid phone resize 
   
   $dest_image_anroid_36 = "../../temporary_files/images/anroid@36_".$file;
   $imageObj = smart_resize_image($dest_image, 36, 36, true, 'return', false);
   imagepng($imageObj, $dest_image_anroid_36);
   imagedestroy($imageObj);
   
   $dest_image_anroid_48 = "../../temporary_files/images/anroid@48_".$file;
   $imageObj = smart_resize_image($dest_image, 48, 48, true, 'return', false);
   imagepng($imageObj, $dest_image_anroid_48);
   imagedestroy($imageObj);
   
   $dest_image_anroid_72 = "../../temporary_files/images/anroid@72_".$file;
   $imageObj = smart_resize_image($dest_image, 72, 72, true, 'return', false);
   imagepng($imageObj, $dest_image_anroid_72);
   imagedestroy($imageObj);
   
   $dest_image_anroid_96 = "../../temporary_files/images/anroid@96_".$file;
   $imageObj = smart_resize_image($dest_image, 96, 96, true, 'return', false);
   imagepng($imageObj, $dest_image_anroid_96);
   imagedestroy($imageObj);
   
   $json->iTunesArtWorkPath = $dest_image_iTunesArtwork;
   $json->iTunesArtWorkPath2x = $dest_image;
   $json->iconPath = $dest_image_icon;
   $json->iconPath2x = $dest_image_icon2x;
   $json->faceBookImagePath = $dest_image_facebook;
   
   $json->anroid36Path = $dest_image_anroid_36;
   $json->anroid48Path = $dest_image_anroid_48;
   $json->anroid72Path = $dest_image_anroid_72;
   $json->anroid96Path = $dest_image_anroid_96;

   $src = json_encode($json);
} else {
    smart_resize_image($dest_image, $r_w, $r_h, true);
}

unlink($image);
echo("&msg=ok&path=".$src);
?>

