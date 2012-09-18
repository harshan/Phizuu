<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/admin_box_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();


$box= new BoxModel();
$iPhionebox= new BoxModel();

if($_POST['status']=='edit'){
	$effected = $box->editBox($_POST);

	header("location:../view/admin/box/edit_box_mgt.php?id=".$_POST['id']."&status=edited");
	exit;
}
else if($_GET['status']=='delete'){
	$effected = $box->deleteBox($_GET['id']);
	if($effected > 0){
	
	header("location:../view/admin/box/box_mgt.php");
	exit;
	}
}

?>