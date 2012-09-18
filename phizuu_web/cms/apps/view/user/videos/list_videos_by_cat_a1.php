<?php
include("../../../controller/youTube_controller.php");
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/video_controller.php');
include('../../../model/video_model.php');
include('../../../config/error_config.php');
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');

@session_start();
$response1='';
$youtube= new YouTube();
if ($_GET['id']=='') 
    $playlists = $youtube->videos($_SESSION['YouTube_User']);
else
    $playlists = $youtube->playlistVideos($_GET['id'],'');

$id=$_REQUEST['id'];
include('list_videos_by_cat_a1_inc.php');

echo $response;
?>