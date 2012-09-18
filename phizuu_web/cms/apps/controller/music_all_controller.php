<?php

require_once "../config/app_key_values.php";
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once('../model/soundcloud/soundcloud.php');
require_once('../model/soundcloud/SoundCloudMusic.php');
require_once('../model/PushNotifications.php');
require_once('../common/oauth.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'find_iTunes':
        $keyWord = $_REQUEST['keyWord'];
        $ch = curl_init("http://itunes.apple.com/search?term=".$keyWord."&entity=musicTrack");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $appiTunes = curl_exec($ch);
        $appiTunes = json_decode($appiTunes);
        $string = '';
        if(isset($appiTunes->{"results"}[0]->{"artistName"})){
         $string =   "<div>
                    <div style='width: 200px;float: left;background:#2a2b2b;color:#fff'>Artist Name</div>
                    <div style='width: 300px;float: left;background:#2a2b2b;color:#fff'>Song Title</div>
                    <div style='width: 50px;float: left;background:#2a2b2b;color:#fff'>Select</div>
                </div><div style='clear: both'>";
        
        foreach($appiTunes->{"results"} as $val){
            $string.="<div style='width: 200px;float: left;overflow: hidden'>".$val->{'artistName'}."</div>
                    <div style='width: 300px;float: left;overflow: hidden'>".$val->{'trackName'}."</div>
                    <div style='width: 50px;float: left;overflow: hidden'><img src='../../../images/album_add_icon_en.png' style='cursor: pointer;' onclick='selectItune(\"".$val->{'trackViewUrl'}."\")' /></div>";
            
        }
        
        $string.='</div>';
        }else{
            $string = "<div style='color:red'>Sorry no records found!</div>";
        }
        echo $string;
        break;
    case 'order':
        $listArr1 = $_GET['list1'];
        $listArr2 = $_GET['list2'];
        $dao = new Dao();

        foreach ($listArr1 as $order => $id) {
            $sql = "UPDATE song SET `order`='$order', iphone_status='' WHERE id='$id'";
            $dao->query($sql);
        }

        foreach ($listArr2 as $order => $id) {
            $sql = "UPDATE song SET `order`='$order',iphone_status=1 WHERE id='$id'";
            $dao->query($sql);
        }

        PushNotifications::changeSavedStatus(1);

        break;

    case 'order_wizard':
        $listArr1 = $_GET['id'];
        $dao = new Dao();

        foreach ($listArr1 as $order => $id) {
            $sql = "UPDATE song SET `order`='$order' WHERE id='$id'";
            $dao->query($sql);
        }

        break;

    case 'update_cover':
        PushNotifications::changeSavedStatus(1);
        $url = getSmallImageIfAvailable($_POST['url']);
        $dao = new Dao();
        $sql = "INSERT INTO album_cover (user_id, cover_url) VALUES({$_SESSION['user_id']},'$url')
                ON DUPLICATE KEY UPDATE cover_url='$url'";
        $dao->query($sql);
        echo 'ok';
        break;

    case 'ajax_get_data':
        $id = $_POST['id'];
        $dao = new Dao();
        $sql = "SELECT * FROM song WHERE id=$id";
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        $arr = $arr[0];
        echo json_encode($arr);
        break;

    case 'edit_music':
        $id = addslashes($_POST['id']);
        $title = addslashes($_POST['title']);
        $duration = addslashes($_POST['duration']);
        $iTunesURI = urldecode($_POST['iTunesURI']);
        $androidURL = urldecode($_POST['androidURI']);
        $album = addslashes($_POST['album']);
        $year = addslashes($_POST['year']);
        $note = addslashes($_POST['note']);
        $imageURI = addslashes(getSmallImageIfAvailable(urldecode($_POST['imageURI'])));
        $catId = addslashes(getSmallImageIfAvailable(urldecode($_POST['categoryEdit'])));

        $affiliateURL = '';

        if ($iTunesURI != '' && isiTunesURLChanged($iTunesURI, $id)) {
            $correctedURL = getCorrectedURL($iTunesURI);
            if ($correctedURL === FALSE) {
                echo "iTunesURLError";
                exit;
            } else {
                $iTunesURI = $correctedURL;
            }

            $affiliateURL = generateAffiliateURL($iTunesURI);
            if ($affiliateURL === FALSE) {
                echo "iTunesURLError";
                exit;
            }
        }

        if ($androidURL != '') {
            $correctedURL = getCorrectedURL($androidURL);
            if ($correctedURL === FALSE) {
                echo "androidURLError";
                exit;
            } else {
                $androidURL = $correctedURL;
            }
        }

        $sql = "UPDATE `song` SET `title` = '$title', `album` = '$album', `duration` = '$duration',  `itunes_uri` = '$iTunesURI', `android_url` = '$androidURL', `year` = '$year', `note` = '$note',  `image_uri` = '$imageURI', `itunes_affiliate_url`='$affiliateURL', category_id='$catId' WHERE `id` = $id";

        $dao = new Dao();
        $res = $dao->query($sql);
        echo "ok";

        PushNotifications::changeSavedStatus(1);
        break;

    case 'get_sound_cloud_request_token':
        $soundCloud = new SoundCloudMusic();

        if ($_GET['logout'] == 'true') {
            $soundCloud->removeInfoFromTheDatabase($_SESSION['user_id']);
        }

//        if ($_SERVER['SERVER_NAME'] == 'localhost') {
//            $callbackURL = 'http://localhost/phizuu_web/cms/apps/view/user/music/sound_cloud_callback.php';
//        } else {
//            $callbackURL = 'http://phizuu.com/cms/apps/view/user/music/sound_cloud_callback.php';
//        }
        //new code added by harshan
        if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
            $callbackURL = "http://" . app_key_values::$LIVE_SERVER_DOMAIN . app_key_values::$LIVE_SERVER_URL . "/cms/apps/view/user/music/sound_cloud_callback.php";
        } elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
            $callbackURL = "http://" . app_key_values::$TEST_SERVER_DOMAIN . app_key_values::$TEST_SERVER_URL . "/cms/apps/view/user/music/sound_cloud_callback.php";
        } else {
            $callbackURL = "http://" . app_key_values::$LOCALHOST_SERVER_URL . app_key_values::$LOCALHOST_SERVER_URL . "/cms/apps/view/user/music/sound_cloud_callback.php";
        }


        $oauthInfo = $soundCloud->getInfoFromTheDatabase($_SESSION['user_id']);

        if ($oauthInfo != NULL) {
            $userData = $soundCloud->getUserData($oauthInfo);
        } else {
            $userData = FALSE;
        }

        if ($userData == FALSE) {
            $rtn->auth = 'no';
            $soundCloud->removeInfoFromTheDatabase($_SESSION['user_id']);
            $rtn->url = $soundCloud->getAuthRequestURL($callbackURL) . "&display=popup";
        } else {
            $rtn->auth = 'ok';
            $soundCloudInfo = $userData;
            ob_start();
            include '../view/user/music/sound_cloud_user_info.ajax.php';
            $rtn->userData = ob_get_contents();
            ob_end_clean();
        }

        echo json_encode($rtn);

        break;

    case 'get_access_token':
        $soundCloud = new SoundCloudMusic();

        $oauthToken = $_POST['oauth_verifier'];

        $token = $soundCloud->getAccessToken($oauthToken);
        $soundCloud->saveInfoToDatabase($_SESSION['user_id'], $token['oauth_token'], $token['oauth_token_secret']);

        $soundcloud = new Soundcloud(
                        SOUNDCOULD_CONSUMER_KEY,
                        SOUNDCOULD_CONSUMER_SECRET,
                        $token['oauth_token'],
                        $token['oauth_token_secret']
        );


        // Get basic info about the authicated visitor.
        $me = $soundcloud->request('me');
        $me = new SimpleXMLElement($me);
        $soundCloudInfo = get_object_vars($me);

        include '../view/user/music/sound_cloud_user_info.ajax.php';

        break;

    case 'get_soundcloud_tracks':
        $soundCloud = new SoundCloudMusic();
        $tracks = $soundCloud->getTracks($_SESSION['user_id']);
        include '../view/user/music/list_tracks.php';
        break;

    case 'soundcloud_add_track':
        $soundCloud = new SoundCloudMusic();
        $track = $soundCloud->getTrack($_POST['track_id'], $_SESSION['user_id']);
        $track['duration'] = $track['duration'] / 1000;
        $track['soundcloud-url'] = $track['stream-url'];
        $track['stream-url'] = $track['stream-url'] . "?consumer_key=" . SOUNDCOULD_CONSUMER_KEY;

        if (isset($_GET['wizard'])) {
            $wizard = TRUE;
        } else {
            $wizard = FALSE;
        }

        $musicId = $soundCloud->addMusic($track, $_SESSION['user_id'], $wizard);
        $bmusic->id = $musicId;
        $bmusic->duration = ceil($track['duration']);
        $bmusic->title = $track['title'];
        $bmusic->note = $track['description'];
        $bmusic->stream_uri = $track['soundcloud-url'];
        $bmusic->permalink = $track['permalink-url'];
        include '../view/user/music/bank_list_new.php';
        PushNotifications::changeSavedStatus(1);
        break;

    case 'delete_track':
        $sql = "DELETE FROM song WHERE id={$_POST['track_id']}";
        $dao = new Dao();
        $dao->query($sql);
        if (mysql_affected_rows() > 0) {
            echo "ok";
        } else {
            echo "Error occured while deleting!";
        }
        PushNotifications::changeSavedStatus(1);
        break;

    case 'add_category':
        $soundCloud = new SoundCloudMusic();
        list($rtn, $msg) = $soundCloud->addCategory($_POST['name'], $_SESSION['user_id']);

        $rtnObj = new stdClass();
        if ($rtn === FALSE) {
            $rtnObj->error = TRUE;
            $rtnObj->msg = $msg;
        } else {
            $rtnObj->error = FALSE;
            $rtnObj->id = $msg;
        }
        echo json_encode($rtnObj);
        PushNotifications::changeSavedStatus(1);
        break;

    case 'delete_category':
        $soundCloud = new SoundCloudMusic();
        $rtn = $soundCloud->deleteCategory($_POST['id']);

        $rtnObj = new stdClass();
        if ($rtn === FALSE) {
            $rtnObj->error = TRUE;
        } else {
            $rtnObj->error = FALSE;
        }
        echo json_encode($rtnObj);
        PushNotifications::changeSavedStatus(1);
        break;
    case 'edit_category':
        $id = $_POST['cat_id'];
        $value = substr($_POST['value'], 0, 15);

        $soundCloud = new SoundCloudMusic();
        $soundCloud->editCategory($id, $value, $_SESSION['user_id']);
        echo $value;
        PushNotifications::changeSavedStatus(1);
        break;
    case 'get_comment_summery':
        $module = $_REQUEST['module'];
        $itemId = $_REQUEST['itemId'];
        $ch = curl_init(app_key_values::$API_URL . "client/" . $_SESSION['app_id'] . "/$module/$itemId/comment");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $comments = curl_exec($ch);
        $comments = json_decode($comments);
        //print_r($comments->{'comments'}[0]->{"image"}->{'uri'});
        $i = 1;
        include '../view/common/view_comment_summery.php';
        break;


    default:
        echo "Error! No valid action";
}

function getSmallImageIfAvailable($url) {
    $mediumURL = getMediumImageURL($url);
    if (getResponseCode($mediumURL) == '200') {
        return $mediumURL;
    } else {
        return $url;
    }
}

function getMediumImageURL($fullImageURL) {
    $suffix = substr($fullImageURL, strlen($fullImageURL) - 4);
    $prefix = substr($fullImageURL, 0, strlen($fullImageURL) - 4);
    return $prefix . "_m" . $suffix;
}

function getResponseCode($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_exec($ch);

    $details = curl_getinfo($ch);

    curl_close($ch);
    return $details['http_code'];
}

function isiTunesURLChanged($newUri, $songId) {
    $sql = "SELECT `itunes_uri` FROM `song` WHERE `itunes_uri`='$newUri' AND id = '$songId'";
    $dao = new Dao();
    $res = $dao->query($sql);

    if (mysql_num_rows($res) == 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function generateAffiliateURL($url) {
    /* $token = "001e88f18ace7b791dacf5b6e6a8d162f81c396c150aec77199b0e385340465a";
      $mId = "13508";

      $url = "http://feed.linksynergy.com/createcustomlink.shtml?token=$token&mid=$mId&murl=$url";

      $ch= curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $response = curl_exec($ch);

      echo $url . "\n";
      echo $response;

      if (substr($response, 0, 7)!="http://") {
      return FALSE;
      } else {
      return $response;
      } */
    return $url;
}

function getCorrectedURL($url) {
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

?>