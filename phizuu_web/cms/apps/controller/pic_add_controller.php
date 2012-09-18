<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/pic_controller.php');
include('../controller/limit_files_controller.php');
include("../controller/flickr_controller.php");
include('../model/pic_model.php');
include('../model/limit_files_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');


@session_start();


$play_val[0] = array('name' => $_GET['name'],'uri' =>urldecode($_GET['uri']),'thumb_uri' => urldecode($_GET['thumb_uri']),'play_id' => $_GET['play_id'],'pid' => $_GET['pid']);
//print_r($play_val[0]);
$id=  $_GET['play_id'];

$pic= new Picture();
$limitFiles= new LimitFiles();

$bankpic_count=sizeof($pic->listBankPics($_SESSION['user_id']));
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'picture');

if(empty($_GET['pid']) && empty($_GET['name'])) {


        $flickr_all= new Flickr($_SESSION['flickr_User']);
        if($_GET['play_id']!=''){
        $playlists_all = $flickr_all->getPhotos($_GET['play_id'],'');
        } else {
          $playlists_all = $flickr_all->getPhotos2();
        }

        foreach($playlists_all as $play_val_all) {

            $status=$pic->getPicByUri($play_val_all['pid']);


            if(empty($status->id)) {

                $play_val_all_new[0] = array('name' => $play_val_all['title'],'uri' =>$play_val_all['image'],'thumb_uri' => $play_val_all['thumb'],'play_id' => $id,'pid' => $play_val_all['pid']);

                $chk = $pic->addAllPics($play_val_all_new,$_SESSION['user_id']);

            }
        }


}
else {

    if((($limit_count ->photo_limit) > $bankpic_count) && (($limit_count ->photo_limit) >= ($bankpic_count + 1))) {
        $chk = $pic->addPics($play_val,$_SESSION['user_id']);
    }
    else {

        $response1="Limit Exceeded - Sorry Couldn't Add Files";
    }
}

//=============================================


//$_SESSION['flickr_User']="shalini81i";


$flickr= new Flickr();
$user = $flickr->Flickr($_SESSION['flickr_User']);
if($_GET['id'] == "") {
    $playlists = $flickr->getPhotos2();
}
else {
    $playlists = $flickr->getPhotos($_GET['id'],'');
}

include('../view/user/pictures/list_photos_by_cat_a_tbl_inc.php');


echo $response;
?>