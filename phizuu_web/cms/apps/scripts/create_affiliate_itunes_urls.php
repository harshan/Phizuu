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
require_once('../controller/settings_controller.php');

$dao = new Dao();

$sql = "SELECT * FROM song WHERE itunes_uri!=''";
$res = $dao->query($sql);

$songs = $dao->getArray($res);


echo "Song ID - User ID - iTunes URL - Corrected URL - Affiliate URL" . "<br><br>";


$usersArr = array();

foreach ($songs as $song) {
    $url = $song['itunes_uri'];
    $correctedURL=getCorrectedURL($url);
    $usersArr[$song['user_id']] = true;
    if ($correctedURL!==FALSE) {
        $affiliateURL = generateAffiliateURL($correctedURL);
        if ($action == 'check') {
            echo "{$song['id']} - {$song['user_id']} - $url - $correctedURL - $affiliateURL " . "<br>";
        } elseif ($action == 'change'){
            echo "Changed: {$song['id']} - {$song['user_id']} - $url - $correctedURL - $affiliateURL " . "<br>";
            $sql = "UPDATE song SET itunes_affiliate_url='$affiliateURL' WHERE id = {$song['id']}";
            $res = $dao->query($sql);
        }
    }
}

echo "<br><br>User ID - App Name" . "<br><br>";
$api = new API();
foreach($usersArr as $userId => $unused) {
    $_SESSION['user_id'] = $userId;
    if ($action == 'check') {
        echo "Should be Published: $userId - {$api->getAppName()}" . "<br>";
    } elseif ($action == 'change') {
        echo "Published: $userId - {$api->getAppName()}" . "<br>";
        $api->writeStaticModuleJSON();
    }
}

function getCorrectedURL ($url) {
    $prefix = substr($url, 0, 7);

    if ($prefix == 'itms://') {
        $postFix = substr($url, 7);
        return 'http://' . $postFix;
    } elseif ($prefix == 'http://') {
        return $url;
    } else {
        return FALSE;
    }
}

function generateAffiliateURL($url) {
    $token = "001e88f18ace7b791dacf5b6e6a8d162f81c396c150aec77199b0e385340465a";
    $mId = "13508";

    $url = "http://feed.linksynergy.com/createcustomlink.shtml?token=$token&mid=$mId&murl=$url";

    $ch= curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    if (substr($response, 0, 7)!="http://") {
        return FALSE;
    } else {
        return $response;
    }
}
?>
