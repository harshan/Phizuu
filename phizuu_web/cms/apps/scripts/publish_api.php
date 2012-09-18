<?php
session_start();
if(!isset ($_GET['action'])){
    echo "Add action -- action=check|change";
    exit;
}

$action = $_GET['action'];

require_once '../config/config.php';
require_once '../database/Dao.php';
require_once '../model/API.php';
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
require_once('../model/settings_model.php');
require_once('../model/Links.php');
require_once ('../model/Album.php');
require_once ('../model/buy_stuff/BuyStuff.php');
require_once ('../model/discography/Discography.php');
require_once('../model/soundcloud/SoundCloudMusic.php');

require_once('../controller/settings_controller.php');

$dao = new Dao();

$append = '';
if (isset($_GET['user_id'])) {
    $append = 'AND id='.$_GET['user_id'];
}

if (isset($_GET['app_id'])) {
    $append = 'AND app_id='.$_GET['app_id'];
}


echo "<table><tr><th>Status</th><th>User ID</th><th>App ID</th><th>App Name</th></tr>";


$usersArr = array();

$sql = "SELECT * FROM user WHERE app_id!=0 $append";
$res = $dao->query($sql);
$usersArr = $dao->getArray($res);

$api = new API();
foreach($usersArr as $user) {
    $_SESSION['user_id'] = $user['id'];
    if ($action == 'check') {
        $actionText = "Not Published";
    } elseif ($action == 'change') {
        $actionText = "Published";
        $api->writeStaticModuleJSON();
    }
    echo "<tr><td>$actionText</td><td>{$user['id']}</td><td>{$user['app_id']}</td><td>{$user['app_name']}</td></tr>";
}

echo "</table>";

?>
