<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once ('../model/PushNotifications.php');
require_once ('../model/API.php');
require_once ('../controller/json_controller.php');
require_once '../config/database.php';
require_once ('../config/error_config.php');
require_once ('../controller/db_connect.php');
require_once ('../controller/helper.php');
require_once ('../model/video_model.php');
require_once ('../model/music_model.php');
require_once ('../model/pic_model.php');
require_once ('../model/news_model.php');
require_once ('../model/tours_model.php');
require_once ('../model/settings_model.php');
require_once ('../model/Links.php');
require_once ('../controller/settings_controller.php');
require_once ('../model/Album.php');
require_once ('../model/buy_stuff/BuyStuff.php');
require_once ('../model/discography/Discography.php');
require_once ('../model/soundcloud/SoundCloudMusic.php');
require_once ('../model/UserInfo.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'make_changes_live':
        $pushNotification = new PushNotifications();
        $pushNotification->notifyUpdates();
        $api = new API();
        $api->writeStaticModuleJSON();

	// Start database update for Line Up module
	$nav_modules=$_SESSION['modules'];

	if (isset($nav_modules[0]['line_up'] ) && $nav_modules[0]['line_up'] == '1') {
            require_once ('../model/line_up/LineUp.php');
	    $lineUp = new LineUp();
	    $userArr = UserInfo::getUserInfoDirect();
	    if (!$lineUp->remoteDBUpdate($userArr['app_id'])) {
		echo "Error! Critical error occured while updating Line Up remote database.";
		exit;
	    }
        }
	// End database update for Line Up module

        $redirect = $_GET['redirect'];
        $redirect = '..' . $redirect;
        PushNotifications::changeSavedStatus(0);
        header("Location: $redirect");
        break;
    case 'send_message':
        $pushNotification = new PushNotifications();
			
        if ($_POST['radioAudiance']=='all') {
                $range = '';
                $location = '';
        } else {
            $location = $_POST['latLan'];
            if ($_POST['cmbRange'] != '-1')
                $range = $_POST['cmbRange'] ;
            else
                $range = $_POST['txtRange'] ;
        }
        
        list($resp, $message) = $pushNotification->sendMessage($_POST['txtMessage'],$location, $range,$_POST['module']);
        echo $message;
        if ($resp){
            $resp = 'ok';
        }
        else{
            $resp = "error";
        }
        header("location:../view/user/send_message/send_message.php?success=$resp");
        
        break;
    case 'check_changes':
        $push = new PushNotifications();
        echo $push->getSavedStatus();
        break;
    default:
        echo "Error! No valid action";
}
?>
