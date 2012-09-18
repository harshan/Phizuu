<?php
session_start();
require_once "../../../model/home_image_model.php";
require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');

$userArr = NULL;
if (isset($_SESSION['user_id'])) {
    $dao = new Dao();
    $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
    $res = $dao->query($sql);
    $userArr = $dao->getArray($res);
    $userArr = $userArr[0];
}

function GetExtension($extFile) 
{
$extention = substr($extFile, strrpos($extFile, '.'));
return $extention;
}
$action = "";
if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
}

if($action == 'cropImage'){
 
}
//unlink($imagePath);
if($action == 'delete_image'){
    $id = $_POST['id'];
    $imagePath =  $_REQUEST['imagePath'];
    $imagePathThumb = $_REQUEST['imagePathThumb'];
    $homeImageModel = new home_image_model();
    echo $result = $homeImageModel->deleteHomeImage($id);

    unlink($imagePath);
    unlink($imagePathThumb);
    if($result==1) {
        echo "ok";
    } else {
        echo "Cannot find the element id: $id";
    }   
}

if($action == 'order'){
    $orderedArr = $_GET['id'];
    $homeImageModel = new home_image_model();
    $homeImageModel->setOrder($orderedArr);
}

if($action == 'set_default'){
    $id = $_POST['id'];
    $folderName = $_SESSION['app_id'];
    $imagePath =  $_REQUEST['imagePath'];
    $exe = GetFileName($imagePath);
    $defaultImagePath = "../../../application_dirs/$folderName/";
    
    if($exe == '.jpg'){
        $imageObject = imagecreatefromjpeg($imagePath);
        imagejpeg($imageObject, $defaultImagePath.'HomeImage1@2x.jpg');
        echo 'ok';
    }elseif($exe == '.png'){
        $imageObject = imagecreatefromjpeg($imagePath);
        imagepng($imageObject, $defaultImagePath.'HomeImage1@2x.jpg');
        echo 'ok';
    }elseif($exe == '.gif'){
        $imageObject = imagecreatefromjpeg($imagePath);
        imagegif($imageObject, $defaultImagePath.'HomeImage1@2x.jpg');
        echo 'ok';
    }
    $homeImageModel = new home_image_model();
    $homeImageModel->ReserDefauldImage($_SESSION['app_id']);
    $homeImageModel->SetDefauldImage($id);
    
    
    
}

function GetFileName($path){
    $path_parts = pathinfo($path);

    $exe =  $path_parts['extension'];
    //$file =  $path_parts['filename']; 
    return '.'.$exe;
}

if($action == 'get_default_image'){
    $homeImageModel = new home_image_model();
    $result = $homeImageModel->GetDetaultImage($_SESSION['app_id']);
    echo $result[0]->id;


}
if($action == 'noOfRecoeds'){
    $homeImageModel = new home_image_model();
    $result = $homeImageModel->GetNoRecoeds($_SESSION['app_id']);
    echo $result;

}
if($action =='checkDetaultImage'){
    $homeImageModel = new home_image_model();
    $result = $homeImageModel->CheckDefaultImage($_SESSION['app_id']);
    echo $result;
}
if($action =='getHotSpot'){
    $homeImageModel = new home_image_model();
    $result = $homeImageModel->GetDetaultImageById($_REQUEST['id']);
    echo json_encode($result);
}
?>
