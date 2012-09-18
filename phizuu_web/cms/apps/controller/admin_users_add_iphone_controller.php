<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/admin_users_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();

$user= new UserModel();

if($_POST['status']=='edit'){
	$effected = $user->editUser($_POST);
	header("location:../view/admin/user/edit_user_management.php?id=".$_POST['id']."&status=edited");
	exit;
}
else if($_GET['status']=='delete'){
	$effected = $user->deleteUser($_GET['id']);
	if($effected > 0){
	
	header("location:../view/admin/user/user_management.php");
	exit;
	}
}

?>