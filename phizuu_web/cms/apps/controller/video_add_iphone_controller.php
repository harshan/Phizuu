<?php
include ('../config/config.php');
require_once '../config/database.php';
include('../model/video_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
include('../controller/limit_files_controller.php');
include('../model/limit_files_model.php');

@session_start();


$video= new VideoModel();
$iPhionevideo= new VideoModel();
if(isset($_GET['status']) && ($_GET['status']=='add')){
	$effected = $video->addVideos_iphone($_GET['id']);

	if($effected > 0){
	header("location:../view/user/videos/videos.php");
	exit;
	}
}
else if(isset($_GET['status']) && ($_GET['status']=='remove')){

        if (isset($_SESSION['modules'])) {
            $effected = $iPhionevideo->removeVideos_iphone($_GET['id']);
            header("location:../view/user/videos/videos.php");
        } else {
            $effected = $video->deleteVideos($_GET['id']);
            header("Location:../controller/modules/app_wizard/AppWizardControllerNew.php");
        }
}
else if(isset($_POST['status']) && ($_POST['status']=='edit')){
if(!empty($_POST['title']))
	{
		$effected = $video->editVideos($_POST);
		
		$get_vid_appid=$video -> getAppid($_POST['id']);
			
		$effectedNoti = $video -> editNotifications($get_vid_appid->app_id);
		

		header("location:../view/user/videos/edit_videos.php?id=".$_POST['id']."&status=edited");
		exit;

	}
	else{
	   header("location:../view/user/videos/edit_videos.php?id=".$_POST['id']."&msg_error=$msg_error&status=error");
	   exit;
	}
}
else if($_GET['status']=='delete'){
	$effected = $video->deleteVideos($_GET['id']);

	if($effected > 0){
        if (isset($_SESSION['modules'])) {
            header("location:../view/user/videos/videos.php");
        } else {
            header("Location:../controller/modules/app_wizard/AppWizardControllerNew.php");
        }

	exit;
	}
}
else if($_GET['status']=='update_list'){

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'video');

$explode_arr2=explode(",",$_GET['list2']);
$response="<br>arrsize - ".sizeof($explode_arr2)."<br>limi - ".$limit_count ->video_limit;
if($limit_count ->video_limit >= sizeof($explode_arr2)){

	$effected = $video->updateListVideos($_GET['list1'],$_GET['list2']);
	 $response.= "updated";
	
	}
	else{
	 $response.="Limit Exceeded - Sorry Couldn't Add Files<br> Allowed only".$limit_count ->video_limit."files ";
	}
}
else if($_GET['status']=='get_youUser'){
	$effected = $video->updateListVideos($_GET['list1'],$_GET['list2']);

	$response= "updated";

}
?>