<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/settings_controller.php');
include('../model/settings_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();


$settings= new Settings();




if($_GET['type'] == 'y_user'){
$type=$_ENV['setting_youtube'];

}
else if($_GET['type'] == 'r_user'){
$type=$_ENV['setting_rssfeed'];
}
else if($_GET['type'] == 'f_user'){
$type=$_ENV['setting_flickr'];
}
else if($_GET['type'] == 't_user'){
$type=$_ENV['setting_twiter'];
}
$play_val[0] = array('name' => $_GET['name'],'type' =>$type);

if($_GET['name'] != ""){
$chk_username = $settings->checkSettings($play_val, $_SESSION['user_id']);

	if($chk_username > 0){
	 $response="username already exist";
         echo $response;
	}else{
	$chk = $settings->addSettings($play_val);
	}

}



?>