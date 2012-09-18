<?php
include('../config/config.php');
include("session_controller.php");
require_once '../config/database.php';
include('db_connect.php');
include('helper.php');
include('settings_controller.php');
include('../model/settings_model.php');
require_once('flickr_controller.php');
@session_start();

//checking for default flicker account

$settings= new Settings();
$get_prefered = $settings->getPrefered($_ENV['setting_flickr']);
$redirect=$_SESSION['redirect_page_name'];

if(!empty($get_prefered ->id)){

//check whether auth token created
if(!empty($get_prefered ->flickr_auth)){

		$_SESSION['token']=$get_prefered ->flickr_auth;
		
		$request=$_SESSION['request_page_name'];
		header("location:../view/user/".$redirect."");
		exit;
}
else{


		$flickr= new Flickr();
		//echo $_GET['frob'];
		if(isset($_GET['frob'])){
		$_SESSION['frob']=$_GET['frob'];
		
		$token = $flickr->getFrob_Token($_SESSION['frob']);
		
		 $_SESSION['token']=$token;
		
		//save auth token in db
		
		$set_auth = $settings->setAuth($token,$get_prefered ->id);
		
		
		if(!empty($token)){
		
		$redirect=$_SESSION['redirect_page_name'];
		header("location:../view/user/".$redirect."");
		exit;
		}
	}
	else{
	
			$url = $flickr->create_Login();
			header("location:".$url."");
			exit;
	}
}//else flickr_auth
}
else{

		$request=$_SESSION['request_page_name'];
		header("location:../view/user/".$request."?msg=default");
		exit;
}
?>
