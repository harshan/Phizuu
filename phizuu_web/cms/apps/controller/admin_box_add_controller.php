<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/admin_box_controller.php');
include('../model/admin_box_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();

if(!empty($_GET['box']))
	{
		$box= new Box();
		if($_GET['status'] == "edit"){
		$play_val[0] = array('name' => $_GET['box'],'password' =>$_GET['password'],'status' => $_GET['box_status'],'id' => $_GET['id']);
		$chk = $box->editBox($play_val);
		
		}
		else{
		$play_val[0] = array('name' => $_GET['box'],'password' =>$_GET['password'],'status' => $_GET['box_status']);
		
		$chk_box_username = $box->checkBox($play_val);
		
		if($chk_box_username > 0){
		$_REQUEST['msg_error']="error";
		}else{
		$chk = $box->addBox($play_val,$_SESSION['user_id']);
		}
		
		
		}
}
else{
$_REQUEST['msg_error']="error";
}

//=============================================


include('../view/admin/box/add_box_mgt.php');

echo $response;
?>