<?php

require "../../common/ThumbNail.php";

require_once "../../../model/pic_model.php";
require_once "../../../config/app_key_values.php";
require_once ('../../../config/config.php');
require_once '../../../database/Dao.php';
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
@session_start();

$folderName = $_SESSION['app_id'];
$domain = $_SERVER["SERVER_NAME"];
if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
    $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/gallery_images/";
    $callbackThumbURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/gallery_thumb_images/";
} elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
    $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/gallery_images/";
    $callbackThumbURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/gallery_thumb_images/";
} else {
    $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/gallery_images/";
    $callbackThumbURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/gallery_thumb_images/";
}

function GetExtension($extFile) {
    $extention = substr($extFile, strrpos($extFile, '.'));
    return $extention;
}
function GetName($extFile) {
    //$extention = substr($extFile, strripos($extFile, '.'));
    $extention = explode(".", $extFile);
    
    
    return $extention[0];
}
$image_url = "../../../../../static_files/$folderName/images/gallery_images/";
$image_thumb_url = "../../../../../static_files/$folderName/images/gallery_thumb_images/";
if (!is_dir($image_url)) {
    mkdir($image_url, 0777, true);
}
if (!is_dir($image_thumb_url)) {
    mkdir($image_thumb_url, 0777, true);
}
$message[] = '';
if (isset($_FILES['images']['tmp_name'])) {

    foreach ($_FILES["images"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            list($width, $height, $type, $attr) = @getimagesize($_FILES['images']['tmp_name']);
            if ($_FILES['images']['size'][0] > 1048576) {
                $message[] = ("Your picture size is too large (Max 1MB) ");
            } else {
                $name = $_FILES["images"]["name"][$key];
                $fileName = GetName($name);

                //insert data in to database 
                $pic_model = new PicModel();
                $play_val[0] = array('name' => $fileName, 'uri' => $callbackURL, 'thumb_uri' => $callbackThumbURL, 'play_id' => -1, 'pid' => -1, 'image_size' => $_FILES['images']['size'][0]);

                $lastInsertId = $pic_model->addPics($play_val, $_SESSION['user_id']);
                $newImageName = $lastInsertId . GetExtension($name);
                $uri = $callbackURL . $newImageName;
                $thumb_uri = $callbackThumbURL . $newImageName;
                $pic_model->editPicsUri($lastInsertId, $uri, $thumb_uri);
                $imageId = $lastInsertId . GetExtension($name);

                //save file i server
                if (isset($lastInsertId)) {
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $image_url . $imageId)) {

                        copy($image_url . $newImageName, $image_thumb_url . $newImageName);
                        $thumbNail = new ThumbNail();
                        $thumbNail->create_abs_image($newImageName, $newImageName, 75, 75, $image_thumb_url);
                        $message[] = "<div style='font-size:11px;font-family: arial'>" . $name . " Successfully Uploaded</div>";

                        $pics = array();
                        $pics[0] = new stdClass();
                        $pics[0]->id = $lastInsertId;
                        $pics[0]->name = $fileName;
                        $pics[0]->thumb_uri = $image_thumb_url.$newImageName;
                        $pics[0]->uri = $image_url.$newImageName;
                        ob_start();
                        include '../../../view/user/pictures/album/pic_list_1.php';
                        $html.= ob_get_contents();
                        ob_end_clean();
                    }
                }
            }
        } else {
            $message[] = ('Error while file uploading');
        }
    }
} else {
    $message[] = "Invalied file format";
}

$msg = '';
foreach ($message as $value) {


    $msg.=$value;
}

$data = new stdClass();
$data->msg = $msg;
$data->html = $html;

echo json_encode($data);


//echo $msg;

