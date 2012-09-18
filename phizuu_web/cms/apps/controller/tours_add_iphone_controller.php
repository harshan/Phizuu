<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/tours_model.php');
include('../config/error_config.php');

include('../controller/db_connect.php');
include('../controller/helper.php');

@session_start();


$tours= new ToursModel();
$iPhionetours= new ToursModel();

if($_GET['status']=='remove'){
	$effected = $iPhionetours->removeTours_iphone($_GET['id']);
	
	header("location:../view/user/tours/list_tours_all.php");
	exit;
}
else if($_POST['status']=='edit'){
	$effected = $tours->editTours($_POST);
	header("location:../view/user/tours/edit_tours.php?id=".$_POST['id']."&status=edited");
	exit;
}
else if($_GET['status']=='delete'){
	$effected = $tours->deleteTours($_GET['id']);
	if($effected > 0){
	
	header("location:../view/user/tours/list_tours_all.php");
	exit;
	}
}
else if($_GET['status']=='update_list'){

$explode_arr2=explode(",",$_GET['list2']);


	$effected = $tours->updateListTours($_GET['list1'],$_GET['list2']);
	 $response.= "updated";

}

?>