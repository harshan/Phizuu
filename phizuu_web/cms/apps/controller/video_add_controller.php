<?php
include ('../config/config.php');
require_once '../config/database.php';
include('../controller/video_controller.php');
include('../controller/limit_files_controller.php');
include("../controller/youTube_controller.php");
include('../model/video_model.php');
include('../model/limit_files_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

@session_start();


$play_val[0] = array('year' => $_GET['year'],'title' => $_GET['title'],'note' => $_GET['note'],'uri' =>urldecode($_GET['stream_uri']),'duration' => $_GET['duration'],'play_id' => $_GET['play_id'],'vid' => $_GET['vid'],'thumb' => urldecode($_GET['thumb']),'vid_gp3' => urldecode($_GET['vid_gp3']));

$id=  $_GET['play_id'];

$video= new Video();
$limitFiles= new LimitFiles();

$bankvideo_count=sizeof($video->listBankVideos($_SESSION['user_id']));
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'video');

if(empty($_GET['vid']) && empty($_GET['title'])){

if((($limit_count ->video_limit) > $bankvideo_count) && (($limit_count ->video_limit) >= ($bankvideo_count + 1))){
	
	$youtube_all= new YouTube();
	$playlists_all = $youtube_all->playlistVideos($_GET['play_id'],'');

$response1 = '';
 foreach($playlists_all as $play_val_all){
	
	$status=$video->getVideoByUri($play_val_all['vid']);
		if(empty($status->id)){
		$play_val_all_new[0] =  array('year' => $play_val_all['year'],'title' => $play_val_all['title'],'note' => $play_val_all['note'],'uri' =>$play_val_all['uri'],'duration' => $play_val_all['duration'],'play_id' => $id,'vid' => $play_val_all['vid'],'thumb' => $play_val_all['thumb']);
		
		$chk = $video->addAllVideos($play_val_all_new,$_SESSION['user_id']);
		}
	}
	}//if count
	else{
	 $response1="Limit Exceeded - Sorry Couldn't Add Files";
	}
}
else{
	if((($limit_count ->video_limit) > $bankvideo_count) && (($limit_count ->video_limit) >= ($bankvideo_count + 1))){
	
	$chk = $video->addVideos($play_val,$_SESSION['user_id']);
	}
	else{

	 $response1="Limit Exceeded - Sorry Couldn't Add Files";
	}
}



//=============================================

//$_SESSION['YouTube_User']="shalini81i";
$youtube= new YouTube();
if ($_GET['play_id']=='')
    $playlists = $youtube->videos($_SESSION['YouTube_User']);
else
    $playlists = $youtube->playlistVideos($_GET['play_id'],'');

$id=$_REQUEST['play_id'];


include('../view/user/videos/list_videos_by_cat_a1_inc.php');

echo $response;
?>