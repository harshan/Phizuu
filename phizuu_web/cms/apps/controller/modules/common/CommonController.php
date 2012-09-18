<?php
session_start();
require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/StorageServer.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../model/image_crop/functions.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$userId = $_SESSION['user_id'];

$dao = new Dao();

$baseFilePath = '../../../../../static_files';

switch ($action) {
    case 'get_select_pic_list':
        $sql = "SELECT * FROM image WHERE image.user_id=$userId ORDER BY `order`, `id`";
        $pics = $dao->toObject($sql);

        include("../../../view/common/image_chooser/list_select_photos.php");
        break;
    case 'upload_temp_picture':
        $obj = handleUpload();
        if ($obj->error=='') {
            $storage = new StorageServer($baseFilePath);
            $destPath = $storage->getPathForCatogory('images', 'temp_images') . "/temp.jpg";
            move_uploaded_file($obj->path, $destPath);

            smart_resize_image($destPath, $_GET['crop_stage_width'], $_GET['crop_stage_height'], true, 'file', false);

            $obj->url = $storage->getURLForPath('images', 'temp_images', 'temp.jpg');
        } 

        echo json_encode($obj);
        
        break;
    case 'crop_uploaded_image':
        $baseName = $_POST['image_base_name'];
        $imageCat = $_POST['image_catagory_name'];
        $thumbCat = $_POST['thumb_catagory_name'];

        $imageType = $_POST['output_image_type'];

        $storage = new StorageServer($baseFilePath);
        $srcPath = $storage->getPathForCatogory('images', 'temp_images') . "temp.jpg";
        $destPath = $storage->getPathForCatogory('images', $imageCat) . "$baseName.$imageType";
        $destThumbPath = $storage->getPathForCatogory('images', $thumbCat) . "$baseName.$imageType";

        do_crop($srcPath,$srcPath, $_POST['x'],$_POST['y'],$_POST['w'],$_POST['h'],90);
        $image = smart_resize_image($srcPath, $_POST['image_width'], $_POST['image_height'], true, 'return');
               
        if ($imageType == 'jpg') {
            imagejpeg($image, $destPath, 90);
        } elseif ($imageType == 'png') {
            imagepng($image, $destPath, 9);
        }

        $url = $storage->getURLForPath('images', $imageCat, "$baseName.$imageType");
        $thumbUrl = $storage->getURLForPath('images', $thumbCat, "$baseName.$imageType");

        if ($_POST['create_thumb']==true) {
            createThumb($destPath, $destThumbPath, $_POST['thumb_width'], $_POST['thumb_height']);
            $thumbUrl = '';
        }

        $obj->url = $url;
        $obj->url_path = preg_replace('/^\.\.\/\.\.\/\.\.\//', '', $destPath);
        $obj->thumb_url = $thumbUrl;
        echo json_encode($obj);
        break;    
    default:
        echo "Error! No valid action";
}

function handleUpload() {
    $error = "";
    $msg = "";

    $fileElementName = 'image';
    if(!empty($_FILES[$fileElementName]['error'])) {
        switch($_FILES[$fileElementName]['error']) {

            case '1':
                $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                break;
            case '2':
                $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                break;
            case '3':
                $error = 'The uploaded file was only partially uploaded';
                break;
            case '4':
                $error = 'No file was uploaded.';
                break;

            case '6':
                $error = 'Missing a temporary folder';
                break;
            case '7':
                $error = 'Failed to write file to disk';
                break;
            case '8':
                $error = 'File upload stopped by extension';
                break;
            case '999':
            default:
                $error = 'No error code avaiable';
        }
    } elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none') {
        $error = 'No file was uploaded..';
    } else {
        $msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
        $msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
        $msg .= print_r($_GET,true);
        //for security reason, we force to remove all uploaded file
    }

    $returnObj = new stdClass();
    $returnObj->error = $error;
    $returnObj->msg = $msg;
    $returnObj->path = $_FILES[$fileElementName]['tmp_name'];

    return $returnObj;
}

function createThumb($srcFileName, $dstFileName, $imageWidth, $imageHeight, $unlink=false) {
    $image_info = getimagesize($srcFileName);
    $imageType = $image_info[2];
    if( $imageType == IMAGETYPE_JPEG ) {
        $image = imagecreatefromjpeg($srcFileName);
    } elseif( $imageType == IMAGETYPE_GIF ) {
        $image = imagecreatefromgif($srcFileName);
    } elseif( $imageType == IMAGETYPE_PNG ) {
        $image = imagecreatefrompng($srcFileName);
    }

    $oWidth = imagesx($image);
    $oHeight = imagesy($image);
    
    $rW = $imageWidth;
    $rH = ($oHeight/$oWidth)*$rW;

    $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
    //echo $rH;
    if($rH>=$imageHeight) {
        $extraHeight = $rH - $imageHeight;

        $top = ($extraHeight/2)*($oWidth/$imageWidth);
        $src_h = $imageHeight * ($oWidth/$imageWidth);

        imagecopyresampled($newImage, $image, 0, 0, 0, $top, $imageWidth, $imageHeight, $oWidth, $src_h);
    } else {
        $rH = $imageHeight;
        $rW = ($oWidth/$oHeight) * $rH;

        $extraWidth = $rW - $imageWidth;

        $left = ($extraWidth/2)*($oHeight/$imageHeight);
        $src_w = $imageWidth * ($oHeight/$imageHeight);

        imagecopyresampled($newImage, $image, 0, 0, $left, 0, $imageWidth, $imageHeight, $src_w, $oHeight);
    }
    imagedestroy($image);
    imagejpeg($newImage,$dstFileName,90);
    imagedestroy($newImage);

    if($unlink)
        unlink($srcFileName);
}
?>
