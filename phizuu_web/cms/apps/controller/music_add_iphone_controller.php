<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/music_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
include('../controller/limit_files_controller.php');
include('../model/limit_files_model.php');

@session_start();


$music= new MusicModel();
$iPhionemusic= new MusicModel();


if(isset($_GET['status']) && ($_GET['status']=='add')){
	$effected = $music->addMusic_iphone($_GET['id']);

			
	if($effected > 0){
	header("location:../view/user/music/index.php");
	exit;
	}
}
else if(isset($_GET['status']) && ($_GET['status']=='remove')){
	$effected = $iPhionemusic->removeMusic_iphone($_GET['id']);
        if (isset($_SESSION['modules'])) {
            header("location:../view/user/music/index.php");
        } else {
            header("Location:../controller/modules/app_wizard/AppWizardControllerNew.php");
        }
	exit;
	
}
else if(isset($_POST['status']) && ($_POST['status']=='edit')){

	$effected = $music->editMusic($_POST);
	
	$get_music_appid=$music -> getAppid($_POST['id']);
		
	$effectedNoti = $music -> editNotifications($get_music_appid->app_id);

	header("location:../view/user/music/edit_music.php?id=".$_POST['id']."&status=edited");
	exit;

}
else if($_GET['status']=='delete'){
	$effected = $music->deleteMusic($_GET['id']);

	header("location:../view/user/music/index.php");
	exit;

}
else if($_GET['status']=='update_list'){
$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');

$explode_arr2=explode(",",$_GET['list2']);
$response="<br>arrsize - ".sizeof($explode_arr2)."<br>limi - ".$limit_count ->music_limit;
if($limit_count ->music_limit >= sizeof($explode_arr2)){


	$effected = $music->updateListMusic($_GET['list1'],$_GET['list2']);
	$response= "updated";
	
	}
	else{
	$response.="Limit Exceeded - Sorry Couldn't Add Files<br> Allowed only".$limit_count ->music_limit."files ";
	}

}
else if($_GET['status']=='get_flickUser'){
	$effected = $music->updateListMusic($_GET['list1'],$_GET['list2']);
	$response= "updated";

}

?>