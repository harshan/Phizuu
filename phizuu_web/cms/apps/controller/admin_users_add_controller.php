<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/admin_users_controller.php');
include('../model/admin_users_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();

if(!empty($_GET['user']))
	{

		$user= new user();
		if($_GET['status'] == "edit"){
		$play_val[0] = array('username' => $_GET['user'],'password' =>md5($_GET['pwd']),'app_id' => $_GET['app'],'email' => $_GET['email'],'box_id' => $_GET['boxacc'],'package_id' => $_GET['package'],'status' => $_GET['acc_status'],'id' => $_GET['id']);
		$chk = $user->editUser($play_val);
		
		}
		else{
		
		if(!empty($_GET['user']) && !empty($_GET['pwd']) && !empty($_GET['app']) && !empty($_GET['email']) && !empty($_GET['package']) && !empty($_GET['boxacc']) && !empty($_GET['acc_status'])){
		$play_val[0] = array('username' => $_GET['user'],'password' =>md5($_GET['pwd']),'app_id' => $_GET['app'],'email' => $_GET['email'],'package_id' => $_GET['package'],'box_id' => $_GET['boxacc'],'status' => $_GET['acc_status']);
		
		
		$chk_admin_username = $user->checkAdmin($play_val);
		
		if($chk_admin_username > 0){
		//app_id or username already exists
		$_REQUEST['msg_error']="error";
		}else{
		$chk = $user->addUser($play_val,$_SESSION['user_id']);
		}
		
		}else{
		
		$_REQUEST['msg_error']=$msg_error_required_all;
		
		}
		
				
		}
}
else{
$_REQUEST['msg_error']=$msg_error_required_all;
}

//=============================================
if(isset($_GET['user']))
	{

		require_once('../controller/admin_package_controller.php');
		include('../model/admin_package_model.php');
		require_once('../controller/admin_box_controller.php');
		include('../model/admin_box_model.php');
	}

include('../view/admin/users/add_user_management.php');

echo $response;
?>