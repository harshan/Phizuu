<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/settings_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();


$settings= new SettingsModel();
$iPhionesettings= new SettingsModel();

if($_GET['status']=='remove'){
	$effected = $iPhionesettings->removeSettings_iphone($_GET['id']);
	if($effected > 0){
	echo "record removed from iphone section";
	}
}
else if($_GET['status']=='edit'){

	
	if($_GET['type'] == 'y_user'){
	$type=$_ENV['setting_youtube'];
	}
	else if($_GET['type'] == 'r_user'){
	$type=$_ENV['setting_rssfeed'];
	}
	else if($_GET['type'] == 'f_user'){
	$type=$_ENV['setting_flickr'];;
	}
	else if($_GET['type'] == 't_user'){
	$type=$_ENV['setting_twiter'];
	}
	
		
	$play_val[0] = array('id' => $_GET['id'],'type' => $type);
	$effected = $settings->editSettings($play_val);

}
else if($_GET['status']=='delete'){
	$effected = $settings->deleteSettings($_GET['id']);

}

?>