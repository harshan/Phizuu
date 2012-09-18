<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
//require_once 'youTube_controller.php';
require_once '../model/you_tube/YouTube.php';
require_once('../controller/video_controller.php');
require_once('../model/video_model.php');
require_once '../config/database.php';
require_once('../controller/db_connect.php');
require_once('../model/PushNotifications.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'order':
        $listArr1 = $_GET['list1'];
        $listArr2 = $_GET['list2'];
        print_r($listArr1);
        print_r($listArr2);
        $dao = new Dao();

        foreach ($listArr1 as $order=>$id) {
            $sql = "UPDATE video SET `order`='$order', iphone_status='', new_one=0 WHERE id='$id'";
            $dao->query($sql);
        }

        foreach ($listArr2 as $order=>$id) {
            $sql = "UPDATE video SET iphone_status=1, new_one=1 WHERE id='$id' AND iphone_status=''"; //Set new ones
            $dao->query($sql);
            $sql = "UPDATE video SET `order`='$order' WHERE id='$id'"; // Set order
            $dao->query($sql);
        }
        PushNotifications::changeSavedStatus(1);
        
        break;
    case 'edit':
        $parts = explode('_', $_POST['id']);
        $field = $parts[0];
        $id = $parts[1];
        $value = $_POST['value'];
        switch ($parts[0]) {
            case '1':
                $field = '`title`';
                break;
        }

        if ($field != '') {
            $sql = "UPDATE video SET $field = '".mysql_real_escape_string($value)."' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        PushNotifications::changeSavedStatus(1);
        break;

    case 'ajax_get_data':
        $id = $_POST['id'];
        $dao = new Dao();
        $sql = "SELECT * FROM video WHERE id=$id";
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        $arr = $arr[0];
        echo json_encode($arr);
        break;

    case 'edit_video_ajax':
        $id = addslashes($_POST['id']);
        $title = addslashes($_POST['title']);
        $duration = addslashes($_POST['duration']);
        $year = addslashes($_POST['year']);
        $note = addslashes($_POST['note']);

        $sql = "UPDATE `video` SET `title` = '$title', `duration` = '$duration', `year` = '$year', `note` = '$note' WHERE `id` = $id";

        $dao = new Dao();
        $res = $dao->query($sql);
        PushNotifications::changeSavedStatus(1);
        echo "ok";
        break;
    case 'list_youtube_videos':
        $username = $_POST['username'];
        $youtube = new YouTube($username);
        $playLists = $youtube->getPlayLists();
        //print_r($playList);
        $resp = array();
        if($playLists===FALSE) {
            $resp['error'] = TRUE;
        } else {
            $resp['data'] = $playLists;
            $resp['error'] = FALSE;
        }
        echo json_encode($resp);
        break;

    case 'get_photos':
        $username = $_POST['username'];
        $youtube = new YouTube();
        if  ($_POST['id']!="") {
            $arr = explode("_", $_POST['id']);
            $videos = $youtube->playlistVideos($arr[0],$arr[1]);
        } else {
            $videos = $youtube->videos($username);
        }

        ob_start();
        $bank_video = $videos;
        include("../view/user/app_wizard/supporting/video_list.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;
        $json->videos = $videos;

        echo json_encode($json);
        break;

    case 'get_videos':
        $username = $_POST['username'];
        $youtube = new YouTube($username);
        if  ($_POST['id']!="") {
            if ($_POST['id']=="Favorites") {
                $videos = $youtube->getFavoritesVideos();
            } else {
                $videos = $youtube->getVideosOfPlayList($_POST['id']);
            }
        } else {
            $videos = $youtube->getUploadedVideos();
        }
        
        if ($videos === FALSE) {
            $json->error = true;
        } else {
            $json->error = false;
            $videoObj = new VideoModel();
            $videos = $videoObj->filterVideosByURL($videos);
        }

        ob_start();
        $bank_video = $videos;
        include("../view/user/app_wizard/supporting/video_list.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;
        $json->videos = $videos;

        echo json_encode($json);
        break;

    case 'add_video':
        $play_val[0] = array('year' => $_POST['year'],'title' => $_POST['title'],'note' => urldecode($_POST['note']),'uri' =>$_POST['uri'],'duration' => $_POST['duration'],'play_id' => '','vid' => $_POST['vid'],'thumb' => $_POST['thumb'],'vid_gp3' => $_POST['vid_gp3']);
        $video = new VideoModel();
        $id = $video->addVideos($play_val, '',true);
        $iphone = true;
        $bank_video = array();
        $_POST['id'] = $id;
        $bank_video[] = $_POST;

        include("../view/user/app_wizard/supporting/video_list.php");
        break;

    case 'add_video_cms':
        $play_val[0] = array('year' => $_POST['year'],'title' => $_POST['title'],'note' => urldecode($_POST['note']),'uri' =>$_POST['uri'],'duration' => $_POST['duration'],'play_id' => '','vid' => $_POST['vid'],'thumb' => $_POST['thumb'],'vid_gp3' => $_POST['vid_gp3']);
        $video = new VideoModel();
        $id = $video->addVideos($play_val, '',false);
        $iphone = true;
        $bank_video = array();
        $_POST['id'] = $id;
        $bank_video[] = $_POST;
        $cms = true;
        PushNotifications::changeSavedStatus(1);
        include("../view/user/app_wizard/supporting/video_list.php");
        break;
    

    default:
        echo "Error! No valid action";
}
?>