<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/admin_package_controller.php');
include('../model/admin_package_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();

if(!empty($_GET['package']))
	{
		$package= new Package();
		if($_GET['status'] == "edit"){
		$play_val[0] = array('name' => $_GET['package'],'video_limit' =>$_GET['v_limit'],'music_limit' => $_GET['m_limit'],'music_storage_limit' => ($_GET['m_limit_storage']*1024*1024*1024),'photo_limit' => $_GET['p_limit'],'id' => $_GET['id']);
		$chk = $package->editPackage($play_val);
		
		}
		else{
		$play_val[0] = array('name' => $_GET['package'],'video_limit' =>$_GET['v_limit'],'music_limit' => $_GET['m_limit'],'music_storage_limit' => ($_GET['m_limit_storage']*1024*1024*1024),'photo_limit' => $_GET['p_limit']);
		
		$chk = $package->addPackage($play_val,$_SESSION['user_id']);
		}
}
else{
$_REQUEST['msg_error']="error";
}

//=============================================


include('../view/admin/package/add_package_management.php');

echo $response;
?>