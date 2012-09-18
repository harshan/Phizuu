<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/news_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
include('../controller/settings_controller.php');
include('../model/settings_model.php');


@session_start();

$news= new NewsModel();
$iPhionenews= new NewsModel();
$settings= new SettingsModel();

if(isset($_GET['status']) && ($_GET['status']=='add')){
	$effected = $news->addNews_iphone($_GET['id']);
	if($effected > 0){
	echo "record added to the iphone section";
	}
}
else if(isset($_GET['status']) && ($_GET['status']=='remove')){
	$effected = $iPhionenews->removeNews_iphone($_GET['id']);

	header("location:../view/user/news/list_news_all.php");
	exit;
}
else if(isset($_POST['status']) && ($_POST['status']=='edit')){
	$effected = $news->editNews($_POST);

	header("location:../view/user/news/edit_news.php?id=".$_POST['id']."&status=edited");
	exit;
}
else if(isset($_GET['status']) && ($_GET['status']=='delete')){
	$effected = $news->deleteNews($_GET['id']);
	if($effected > 0){

	header("location:../view/user/news/list_news_all.php");
	exit;
	}
}
else if(isset($_GET['status']) && $_GET['status']=='update_list'){


$explode_arr2=explode(",",$_GET['list2']);


	$effected = $news->updateListNews($_GET['list1'],$_GET['list2']);
	 //$response.= "updated";

}
else if($_POST['status']=='RssStatus'){

	if($_POST['stat'] == "0"){
	$effected = $settings->addRss($_POST);
	}
	else{
	$effected = $settings->editRss($_POST);
	}
        //print_r($_POST);
        if (!isset($_GET['wizard'])) {
            header("Location:../view/user/news/news_new.php?&status=edited");
        } else {
            header("Location:../controller/modules/app_wizard/AppWizardControllerNew.php");
        }
	exit;
}

function write_json(){
$json_class= new jsonClass();
$api_structure= new ApiStructure();
$json_news_stream = $json_class->streamNews($_SESSION['user_id']);
$api_structure->write_file($json_news_stream,$_SESSION['app_id'],'news');

/* for rss feed
require_once('rss_controller.php');

$rss_class= new rssClass();
$api_structure= new ApiStructure();
$rss_news_feed = $rss_class->feedNews($_SESSION['user_id']);
$api_structure->write_file($rss_news_feed,$_SESSION['app_id'],'news');

*/
}
?>