<?php
include("../../../config/config.php");
include("../../../controller/flickr_controller.php");
require_once '../../../config/database.php';
include('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../config/error_config.php');
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');

@session_start();

$flickr= new Flickr();
$user = $flickr->Flickr($_SESSION['flickr_User']);
if($_GET['id'] == ""){
 $playlists = $flickr->getPhotos2();
}
else{
    $playlists = $flickr->getPhotos($_GET['id'],'');
}
$id=$_REQUEST['id'];


include('list_photos_by_cat_a_tbl_inc.php');


echo $response;
?>