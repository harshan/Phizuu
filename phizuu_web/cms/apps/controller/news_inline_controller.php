<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/news_controller.php');
include('../model/news_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

		$id_arr=explode("_",$_POST['id']);
		
		if($id_arr[0] == "div1"){
		 $key='title';
		}

		else if($id_arr[0] == "div3"){
		 $key='description';
		
		}
		else if($id_arr[0] == "div4"){
		 $key='date';
		
		}
$news= new News();
if(isset($_POST['value'])) {
		$play_val[0] = array('key' => $key,'value' => $_POST['value'],'id' => $id_arr[1]);
		$chk = $news->editInlineNews($play_val);

}
else {

}

function write_json(){


$json_class= new jsonClass();
$api_structure= new ApiStructure();
$json_news_stream = $json_class->streamNews($_SESSION['user_id']);
$api_structure->write_file($json_news_stream,$_SESSION['app_id'],'news');


/* for rss feed
//require_once('rss_controller.php');
$rss_class= new rssClass();
$api_structure= new ApiStructure();
$rss_news_feed = $rss_class->feedNews($_SESSION['user_id']);
$api_structure->write_file($rss_news_feed,$_SESSION['app_id'],'news');
*/


}

echo $value=$_POST['value'];
?>