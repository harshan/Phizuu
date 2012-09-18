<?php
require_once('../config/config.php');
require_once '../config/database.php';
require_once('../controller/admin_module_controller.php');
require_once('../model/admin_module_model.php');
require_once('../config/error_config.php');
require_once('../controller/db_connect.php');
require_once('../controller/helper.php');
@session_start();

if(!empty($_GET['app_id']))
	{
		$module= new Module();
		if($_GET['status'] == "edit"){
		
		$play_val[0] = array('app_id' => $_GET['app_id'],'music' =>getChecked($_GET['music']),'videos' => getChecked($_GET['videos']),'photos' => getChecked($_GET['photos']),'flyers' => getChecked($_GET['flyers']),'news' => getChecked($_GET['news']),'tours' => getChecked($_GET['tours']),'links' => getChecked($_GET['links']),'settings' =>getChecked( $_GET['settings']),'id' => $_GET['id']);
		$chk = $module->editModule($play_val);
		
		}
		else{
		$play_val[0] = array('app_id' => $_GET['app_id'],'music' =>getChecked($_GET['music']),'videos' => getChecked($_GET['videos']),'photos' => getChecked($_GET['photos']),'flyers' => getChecked($_GET['flyers']),'news' => getChecked($_GET['news']),'tours' => getChecked($_GET['tours']),'links' => getChecked($_GET['links']),'settings' =>getChecked( $_GET['settings']),'id' => $_GET['id']);
		
		$chk = $module->addModule($play_val,$_SESSION['user_id']);
		}
}
else{
$_REQUEST['msg_error']="error";
}

//=============================================
exit;

require_once('../view/admin/module/add_module.php');

echo $response;

function getChecked($val){
if($val == "true"){
$new_val='1';
}else{
$new_val='0';
}
return $new_val;
}
?>