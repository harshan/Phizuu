<?php
session_start();

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../common/facebook.php');

$userArr = UserInfo::getUserInfoDirect();

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$facebook = new Facebook(array(
                'appId'  => '169713979729548',
                'secret' => '88e0c5dd85a4b286ebb948d250a52f08',
                'cookie' => FALSE,
            ));


$accessToken = $facebook->getAccessToken();

$session = $facebook->getSession();

switch ($action) {
    case 'import_fb':
        $loginUrl = "https://graph.facebook.com/oauth/authorize?client_id={$facebook->getAppId()}&redirect_uri=http://localhost.com/phizuu_web/cms/apps/controller/modules/data_import/DataImportController.php?action=auth&scope=user_photos,user_videos,publish_stream,offline_access";

        $me = null;
        if ($session) {
            try {
                $uid = $facebook->getUser();
                $me = $facebook->api('me/'); //To check validity of stored API access token

                $q = $facebook->api('me/photos');
                print_r($q);
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }

        if ($me) {
            $loggedIn = TRUE;
        } else {
            $loggedIn = FALSE;
        }

        //$logoutUrl = $facebook->getLogoutUrl();
        include ('../../../view/user/data_import/main_view.php');
        break;
    case 'auth':
        $code = urlencode($_REQUEST['code']);
        $redirect = urlencode("http://localhost.com/phizuu_web/cms/apps/controller/modules/data_import/DataImportController.php?action=auth");
        $clientId = urlencode($facebook->getAppId());
        $secret = urlencode($facebook->getApiSecret());


        $loginUrl = "https://graph.facebook.com/oauth/access_token?client_id=$clientId&redirect_uri=$redirect&client_secret=$secret&code=$code";
        //echo file_get_contents($loginUrl);
        header("Location: $loginUrl");

        //$logoutUrl = $facebook->getLogoutUrl();
        include ('../../../view/user/data_import/main_view.php');
        break;
    case 'order':
        $orderedArr = $_GET['id'];
        $discography->setOrder($orderedArr);
        break;
    default:
        echo "Error! No valid action";
}

?>