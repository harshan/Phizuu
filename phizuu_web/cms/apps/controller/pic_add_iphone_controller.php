<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/pic_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/limit_files_controller.php');
include('../model/limit_files_model.php');
include('../controller/helper.php');


//@session_start();

$pic= new PicModel();
$iPhionepic= new PicModel();
if(isset($_GET['status']) && ($_GET['status']=='add')){
	$effected = $pic->addPics_iphone($_GET['id']);

	if($effected > 0){
	echo "record added to the iphone section";
	}
}
else if(isset($_GET['status']) && ($_GET['status']=='remove')){
	$effected = $iPhionepic->removePics_iphone($_GET['id']);

	if($effected > 0){
            //echo "record removed from iphone section";
	}
	
	header("location:../view/user/pictures/photos.php");
	exit;
}
else if(isset($_POST['status']) && ($_POST['status']=='edit')){
if(!empty($_POST['name']))
	{
		$effected = $pic->editPics($_POST);
		
		$get_pic_appid=$pic -> getAppid($_POST['id']);
			
		$effectedNoti = $pic -> editNotifications($get_pic_appid->app_id);
		
		
		header("location:../view/user/pictures/edit_pics.php?id=".$_POST['id']."&status=edited");
		exit;
	}
	else{
	   header("location:../view/user/pictures/edit_pics.php?id=".$_POST['id']."&msg_error=$msg_error&status=error");
	   exit;
	}
}
else if($_GET['status']=='delete'){
	$effected = $pic->deletePics($_GET['id']);

	if($effected > 0){
	
	header("location:../view/user/pictures/photos.php");
	exit;
	}
}
else if($_GET['status']=='update_list'){

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'picture');

$explode_arr2=explode(",",$_GET['list2']);
$response="<br>arrsize - ".sizeof($explode_arr2)."<br>limi - ".$limit_count ->photo_limit;
if($limit_count ->photo_limit >= sizeof($explode_arr2)){
	$effected = $pic->updateListPics($_GET['list1'],$_GET['list2']);

	$response.= "updated";

	}
	else{
	 $response.="Limit Exceeded - Sorry Couldn't Add Files<br> Allowed only".$limit_count ->photo_limit."files ";
	}

}
else if($_GET['status']=='get_flickUser'){
	$effected = $pic->updateListPics($_GET['list1'],$_GET['list2']);

	$response= "updated";

}

?>