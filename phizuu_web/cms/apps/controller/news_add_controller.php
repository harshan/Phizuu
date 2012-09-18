<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/news_controller.php');
include('../model/news_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

@session_start();


if(!empty($_GET['title']))
	{
		$news= new News();
		if($_GET['status'] == "edit"){
		$play_val[0] = array('title' => $_GET['title'],'date' =>$_GET['date'],'notes' => $_GET['notes'],'id' => $_GET['id']);
		$chk = $news->editNews($play_val);
		
		}
		else{
		$play_val[0] = array('title' => $_GET['title'],'date' =>$_GET['date'],'notes' => $_GET['notes']);
		$chk = $news->addNews($play_val,$_SESSION['user_id']);
		}
}
else{
$_REQUEST['msg_error']="error";
}

//=============================================

include('../view/user/news/add_news.php');

echo $response;
?>