<?php
session_start();
//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/app_builder/Navigator.php');
require_once ('../../../model/app_builder/AppWizard.php');
require_once ('../../../model/settings_model.php');
require_once ('../../../model/API.php');
require_once ('../../../controller/json_controller.php');
require_once '../../../config/database.php';
require_once ('../../../config/error_config.php');
require_once ('../../../controller/db_connect.php');
require_once ('../../../controller/helper.php');
require_once ('../../../model/video_model.php');
require_once ('../../../model/music_model.php');
require_once ('../../../model/pic_model.php');
require_once ('../../../model/news_model.php');
require_once ('../../../model/tours_model.php');
require_once('../../../model/settings_model.php');
require_once('../../../model/Links.php');
require_once ('../../../model/Album.php');
require_once ('../../../model/buy_stuff/BuyStuff.php');
require_once ('../../../model/discography/Discography.php');
require_once ('../../../model/soundcloud/SoundCloudMusic.php');
require_once("../../../controller/session_controller.php");

require_once('../../../controller/settings_controller.php');
require_once('../../../controller/news_controller.php');
require_once('../../../controller/limit_files_controller.php');
require_once('../../../model/limit_files_model.php');
require_once('../../../config/error_config.php');
require_once('../../../controller/music_controller.php');
require_once('../../../controller/pic_controller.php');
require_once('../../../controller/flickr_controller.php');
require_once('../../../model/news_model.php');
require_once('../../../controller/news_controller.php');
require_once('../../../controller/settings_controller.php');
require_once('../../../model/settings_model.php');
require_once ('../../../model/Links.php');
require_once('../../../controller/tours_controller.php');
require_once('../../../model/tours_model.php');
require_once('../../../controller/video_controller.php');
require_once('../../../model/video_model.php');
require_once ('../../../model/buy_stuff/BuyStuff.php');
require_once ('../../../model/discography/Discography.php');
require_once ('../../../model/StorageServer.php');
require_once ('../../../model/UserInfo.php');
require_once('../../../controller/settings_controller.php');
require_once('../../../model/settings_model.php');

set_error_handler("errorHandler", E_ALL);

$lastStep = 16;

//echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
//$browser = get_browser();
//print_r($browser);
//browserError();
$userArr = NULL;
if (isset($_SESSION['user_id'])) {
    $dao = new Dao();
    $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
    $res = $dao->query($sql);
    $userArr = $dao->getArray($res);
    $userArr = $userArr[0];
} elseif (isset($_SESSION['admin_user_id'])) {
    //Nothing to do
} else {
    header("Location: ../../../view/user/usr_login_new.php");
    exit;
}

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

$navigator = new Navigator($userArr['id']);

switch ($action) {
    case 'first_time':
        include '../../../view/user/app_builder/first_time.php';
        break;
    case 'application_settings':
        if($navigator->getCurrentStep()!=1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();

        include '../../../view/user/app_wizard/application_settings.php';
        break;
    case 'application_settings_save':
        if($navigator->getCurrentStep()!=1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        if(isset($_POST['pushNotifications'])) {
            $push = '1';
        } else {
            $push = '0';
        }

        if ($_POST['appName'] == '') {
            trigger_error("No application name!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appId = $appWizard->generateAppId($push);

        $sql = "UPDATE user SET app_name = '".mysql_real_escape_string($_POST['appName'])."', push=$push, app_id=$appId WHERE `id` = {$_SESSION['user_id']}";
        $dao = new Dao();
        $dao->query($sql);
        
        $navigator->gotoNextStep();
        
        header("Location: AppWizardControllerNew.php");
        break;
        
    case 'icon_image':
        if($navigator->getCurrentStep()!=2) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        include '../../../view/user/app_wizard/choose_icon.php';
        break;
        
    case 'icon_image_save':
        if($navigator->getCurrentStep()!=2) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        if ($_POST['iconImage'] == '' || $_POST['iconImage2x'] == '' || $_POST['iTunesArtWork']=='' ||  $_POST['iTunesArtWork2x']=='' || $_POST['faceBookPostImage']=='' || $_POST['anroid36'] == '' || $_POST['anroid48'] == '' || $_POST['anroid72'] == '' || $_POST['anroid96'] == '') {
            trigger_error("Invalid data in icon image save!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveIconImages($_POST['iconImage'],$_POST['iconImage2x'],$_POST['iTunesArtWork'],$_POST['iTunesArtWork2x'],$_POST['faceBookPostImage'],$_POST['anroid36'],$_POST['anroid48'],$_POST['anroid72'],$_POST['anroid96'],$userArr['app_id']);
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'icon_image_skip':
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
        
    case 'loading_image':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        if ($userArr['package_id']==1) {
            header("Location: AppWizardControllerNew.php?action=loading_image_save");
        }
        
        include '../../../view/user/app_wizard/choose_loading_image.php';
        break;

    case 'loading_image_save':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        if ($userArr['package_id']!=1 && $_POST['loadImage'] == '') {
            trigger_error("Error in loading image!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        if ($userArr['package_id']==1) {
            $appWizard->saveLoadImage(TRUE,"",$userArr['app_id']);
        } else {
            $appWizard->saveLoadImage(FALSE, $_POST['loadImage'] ,$userArr['app_id']);
        }

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'loading_image_skip':
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
        
    case 'home_images':
        if($navigator->getCurrentStep()!=4) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        $popArray['app_name'] = $userArr['app_name'];
        //include '../../../view/user/app_wizard/choose_home_images.php';
        require_once "../../../controller/home_image_controller.php";
        include '../../../view/user/app_wizard/home_images.php';
        break;

    case 'home_images_save':
//        if($navigator->getCurrentStep()!=4) {
//            header("Location: AppWizardControllerNew.php");
//            exit;
//        }

//        $appWizard = new AppWizard($userArr['id']);
//        $packageInfo = $appWizard->getPackageInfo();
        
//        $homeImagesArr = array();
//        for ($i=1; $i<=$packageInfo['home_screen_images']; $i++) {
//            if ($_POST['homeImage-'.$i] != '')
//                $homeImagesArr[] = $_POST['homeImage-'.$i];
//        }

//        if (count ($homeImagesArr)== 0) {
//            trigger_error("No home images!");
//            exit;
//        }
//
//        $appWizard->saveHomeImages($homeImagesArr, $userArr['app_id']);
//        $appWizard->saveModule('Home');
//        if(isset($_SESSION['update_contents'])) {
//            $navigator->setCurrentStep($lastStep-1);
//        } else {
//            //$navigator->gotoNextStep();
//        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'home_images_skip':
        $navigator->setCurrentStep($lastStep-1);
        header("Location: AppWizardControllerNew.php");
        break;

    case 'music_module':
        if($navigator->getCurrentStep()!=5) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        include '../../../view/user/app_wizard/music_module.php';

        break;
        
    case 'music_module_save':
        if($navigator->getCurrentStep()!=5) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $limitFiles= new LimitFiles();
        $imusic= new Music();
        $iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
        $limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');

        if (count ($iphone_music)== 0) {
            trigger_error("No music!");
            exit;
        } elseif ($limit_count->music_limit < count ($iphone_music)) {
            trigger_error("Exeeds the music limit!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Music');
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
        
    case 'music_module_skip':
        if($navigator->getCurrentStep()!=5) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $navigator->gotoNextStep(); //Skip Music Cover Step
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        
        break;
        

    case 'music_module_cover':
        if($navigator->getCurrentStep()!=6) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        include '../../../view/user/app_wizard/choose_music_cover.php';
        break;
        
    case 'music_module_cover_save':
        if($navigator->getCurrentStep()!=6) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

         if ($_POST['musicImage'] == '') {
            trigger_error("Error in music image!");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveMusicCoverImage($_POST['musicImage'],$userArr['app_id']);

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
 
    case 'photo_module':
        if($navigator->getCurrentStep()!=7) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        include '../../../view/user/app_wizard/photo_module.php';

        break;

    case 'photo_module_save':
        if($navigator->getCurrentStep()!=7) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $limitFiles= new LimitFiles();
        $ipic= new Picture();
        $limit_count=$limitFiles->getLimit($_SESSION['user_id'],'picture');
        $iphone_pic = $ipic->listIphonePics($_SESSION['user_id']);

        if (count ($iphone_pic)== 0) {
            trigger_error("No photos!");
            exit;
        } elseif ($limit_count->photo_limit < count ($iphone_pic)) {
            trigger_error("Exeeds the photo limit!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Album');
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'photo_module_skip':
        if($navigator->getCurrentStep()!=7) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
    case 'video_module':
        if($navigator->getCurrentStep()!=8) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        include '../../../view/user/app_wizard/video_module.php';

        break;

    case 'video_module_save':
        if($navigator->getCurrentStep()!=8) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $limitFiles= new LimitFiles();
        $ivideo= new Video();
        $limit_count=$limitFiles->getLimit($_SESSION['user_id'],'video');
        $iphone_video = $ivideo->listIphoneVideos($_SESSION['user_id']);

        if (count ($iphone_video)== 0) {
            trigger_error("No videos!");
            exit;
        } elseif ($limit_count->video_limit < count ($iphone_video)) {
            trigger_error("Exeeds the video limit!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Videos');
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'video_module_skip':
        if($navigator->getCurrentStep()!=8) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
     case 'news_module':
        if($navigator->getCurrentStep()!=9) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        include '../../../view/user/app_wizard/news_module.php';

        break;

    case 'news_module_save':
        if($navigator->getCurrentStep()!=9) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('News');
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'news_module_skip':
        if($navigator->getCurrentStep()!=9) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

     case 'links_module':
        if($navigator->getCurrentStep()!=10) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $popArr = array();
        $links = new Links();
        $popArr['links'] = $links->listLinks($userArr['id']);
        include '../../../view/user/app_wizard/links_module.php';

        break;

    case 'links_module_save':
        if($navigator->getCurrentStep()!=10) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $links = new Links();
        $links = $links->listLinks($userArr['id']);
        if (count($links) == 0) {
            trigger_error("No Links!");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Links');
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'links_module_skip':
        if($navigator->getCurrentStep()!=10) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
    case 'tours_module':
        if($navigator->getCurrentStep()!=11) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        include '../../../view/user/app_wizard/tours_module.php';
        break;

    case 'tours_module_save':
        if($navigator->getCurrentStep()!=11) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $tours= new Tours();
        $numrows = $tours->listToursAll($_SESSION['user_id']);
        $tours_list = $tours->listTours($_SESSION['user_id'],0,$numrows);
        if (count($tours_list) == 0) {
            trigger_error("No Links!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Events');
        $navigator->gotoNextStep();

        header("Location: AppWizardControllerNew.php");
        break;

    case 'tours_module_skip':
        if($navigator->getCurrentStep()!=11) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'buystuff_module':
        if($navigator->getCurrentStep()!=12) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $buyStuff = new BuyStuff();
        $popArr = array();
        $popArr['links'] = $buyStuff->listStuff($userArr['id']);
        include ('../../../view/user/app_wizard/buy_stuff_module.php');
        break;

    case 'buystuff_module_save':
        if($navigator->getCurrentStep()!=12) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $buyStuff = new BuyStuff();
        $links = $buyStuff->listStuff($userArr['id']);
        if (count($links) == 0) {
            trigger_error("No Buy Stuff Links!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('BuyStuffs');
        $navigator->gotoNextStep();

        header("Location: AppWizardControllerNew.php");
        break;

    case 'buystuff_module_skip':
        if($navigator->getCurrentStep()!=12) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'discography_module':
        if($navigator->getCurrentStep()!=13) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $discography = new Discography($userArr['id']);
        $popArr = array();
        $popArr['discographies'] = $discography->listDiscographies();
        include ('../../../view/user/app_wizard/discography_module.php');
        break;

    case 'discography_module_save':
        if($navigator->getCurrentStep()!=13) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $discography = new Discography($userArr['id']);
        $links = $discography->listDiscographies();
        if (count($links) == 0) {
            trigger_error("No Discography items!");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModule('Discography');
        $navigator->gotoNextStep();

        header("Location: AppWizardControllerNew.php");
        break;

    case 'discography_module_skip':
        if($navigator->getCurrentStep()!=13) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
    
    case 'home_video':
        if($navigator->getCurrentStep()!=14) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        include '../../../view/user/app_wizard/choose_home_video.php';
        break;
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
    
    case 'home_video_save':
        if($navigator->getCurrentStep()!=14) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $app_id = $_SESSION['app_id'];
        $fileName = $app_id.'.mp4';
        $old_path = "../../../temporary_files/video/";
        $path = "../../../application_dirs/$app_id/";
        $old_fileName_path = $old_path.$fileName;
        $new_fileName_path = $path.'loading_video.mp4';
        if (file_exists($old_fileName_path)) {
            if (!copy($old_fileName_path, $new_fileName_path)) {
                echo "failed to copy";
            }
        }
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
     case 'home_video_skip':
        if($navigator->getCurrentStep()!=14) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $app_id = $_SESSION['app_id'];
        $fileName = $app_id.'.mp4';
        $old_path = "../../../temporary_files/video/";
        $old_fileName_path = $old_path.$fileName;
        if (file_exists($old_fileName_path)) {
            unlink($old_fileName_path);
        }
        
        $path = "../../../application_dirs/$app_id/";
        $new_fileName_path = $path.'loading_video.mp4';
        if (file_exists($new_fileName_path)) {
            unlink($new_fileName_path);
        }
        
        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;
        
    case 'information_module':
        if($navigator->getCurrentStep()!=$lastStep-1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        $userInfo = UserInfo::getUserInfoDirect();
        $popArray['userInfo'] = $userInfo;

        include '../../../view/user/app_wizard/information_module.php';
        break;
        
    case 'validate_twitter':
        $newsModel = new NewsModel();
        $url = 'http://twitter.com/statuses/user_timeline/'.$_POST['username'].'.rss';
        echo $newsModel->getFeedStatus($url);
        break;    

    case 'information_module_save':
        if($navigator->getCurrentStep()!=$lastStep-1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $result = $appWizard->createInformationFiles($_POST['aboutText'],$_POST['bioText'],$_POST['keywordText'],$userArr['app_id']);
        if (!$result) {
            echo "Error while creating the text files";
        }

        unset($_SESSION['update_contents']);
        header('Location: ../../../controller/modules/update_app/UpdateAppController.php?action=wizard_completed');
        break;

    case 'information_module_skip':
        if($navigator->getCurrentStep()!=$lastStep-1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        
        unset($_SESSION['update_contents']);
        header('Location: ../../../controller/modules/update_app/UpdateAppController.php?action=wizard_completed');
        break;
        
    case 'write_xml':
        if($navigator->getCurrentStep()!=$lastStep-1) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }

        if ($_POST['aboutText'] == '' || $_POST['keywordText']=='' || strlen($_POST['keywordText'])>100) {
            trigger_error("Information error!");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $path = $appWizard->createFolderForApplication($userArr['app_id']);

        $facebookImageURL = "http://phizuu.com/images/facebook_post_images/{$userArr['app_id']}/{$userArr['app_id']}.png";
        $fanWall = FALSE;
        if(isset($_POST['fanWall']) && $_POST['fanWall']=='Yes') {
            $appWizard->addWallPost($userArr['app_id'], $userArr['app_name'], $_POST['fanWallPost'], $facebookImageURL);
            $fanWall = TRUE;
        }

        $twitter = FALSE;
        if(isset($_POST['twitter']) && $_POST['twitter']=='Yes') {
            $settings= new Settings();
            $play_val[0] = array('name' => $_POST['twitterUsername'],'type' =>$_ENV['setting_twiter']);
            $chk = $settings->addSettings($play_val);
            $twitter = TRUE;
        }

        $appWizard->addAdditionalModules($userArr['package_id'], $fanWall, $twitter);
        
        $result = $appWizard->createInformationFiles($_POST['aboutText'],$_POST['bioText'],$_POST['keywordText'],$userArr['app_id']);
        if (!$result) {
            echo "Error while creating the text files";
        }

        $appWizard->saveAdditionalInfo($userArr['id']);
        $appWizard->sendEmail($userArr['app_name'], $userArr['app_id'], $userArr['username'], $userArr['email'], $userArr['package_id']);

        $sql = "UPDATE user SET status = '3' WHERE id = {$userArr['id']}";
        $dao = new Dao();
        $dao->query($sql);

        $navigator->gotoNextStep();
        header("Location: AppWizardControllerNew.php");
        break;

    case 'download_bundle':
        $_SESSION['user_id'] = $_GET['user_id'];
        $userArr = UserInfo::getUserInfoDirect($_GET['user_id']);
        $appId = $userArr['app_id'];
        
        $appWizard = new AppWizard($userArr['id']);
        
        $path = "../../../application_dirs/$appId";
        if (!file_exists($path)) {
            echo "No data directory found!";
            exit;
        }

        if ($userArr['package_id']==1) {
            $adds = true;
        } else {
            $adds = false;
        }

        if ($userArr['push']!=3) {
            $push = false;
        } else {
            $push = true;
        }

        $facebookImageURL = "http://phizuu.com/images/facebook_post_images/{$userArr['app_id']}/{$userArr['app_id']}.png";
        $coverImageURL = "http://phizuu.com/images/music_cover_images/{$userArr['app_id']}/{$userArr['app_id']}.png";

        $dao = new Dao();
        $sql = "INSERT INTO album_cover (user_id, cover_url) VALUES({$userArr['id']},'$coverImageURL')
                ON DUPLICATE KEY UPDATE cover_url='$coverImageURL'";
        $dao->query($sql);

        $appWizard->copyThemePListFile($userArr['app_id']);
        $moduleArr = $appWizard->getSelectedModules(true);

        //$homeImagesCount  = @file_get_contents($path."/home_image_count.txt");
        //unlink($path."/home_image_count.txt");
        $result = $appWizard->writeXML($userArr['app_id'], $userArr['app_name'], $moduleArr, 'mobclixId', $push, $adds, $facebookImageURL, $path);
        if (!$result) {
            echo "Error ocurred while creating XML";
        }

        $appWizard->writePermisionsForModules($moduleArr, $userArr['app_id'], $userArr['package_id']);

        $path = $appWizard->createZip($path, $userArr['app_id'], $userArr['package_id'], $userArr['app_name']);
        if (!$result) {
            echo "Error while creating the zip achive";
        }

        $api = new API();
        $api->writeStaticModuleJSON();

        $out = file_get_contents($path);

        header('Content-Type: application/zip');
        header("Content-Length: ".strlen($out));
        header("Content-Description: File Transfer");
        header("Content-Transfer-Encoding: Binary");
        header('Content-Disposition: attachment; filename="'.$appId.'.zip"');

        echo $out;
        unlink($path);
        break;
    
    case 'last_step':
        if($navigator->getCurrentStep()!=$lastStep) {
            header("Location: AppWizardControllerNew.php");
            exit;
        }
        include '../../../view/user/app_wizard/last_step.php';
        break;
    case 'package_upgrade':
        $_SESSION['upgrade']='yes';

        header('Location: ../../../../../paid/pricing.html');

        break;
    default:
        if ($navigator->isFirstTime()) {
            header("Location: AppWizardControllerNew.php?action=application_settings");
        } else {
            //echo $navigator->getCurrentStep();
            switch ($navigator->getCurrentStep()) {
                case 1:
                    header("Location: AppWizardControllerNew.php?action=application_settings");
                    break;
                case 2:
                    header("Location: AppWizardControllerNew.php?action=icon_image");
                    break;
                case 3:
                    header("Location: AppWizardControllerNew.php?action=loading_image");
                    break;
                case 4:
                    header("Location: AppWizardControllerNew.php?action=home_images");
                    break;
                case 5:
                    header("Location: AppWizardControllerNew.php?action=music_module");
                    break;
                case 6:
                    header("Location: AppWizardControllerNew.php?action=music_module_cover");
                    break;
                case 7:
                    header("Location: AppWizardControllerNew.php?action=photo_module");
                    break;
                case 8:
                    header("Location: AppWizardControllerNew.php?action=video_module");
                    break;
                case 9:
                    header("Location: AppWizardControllerNew.php?action=news_module");
                    break;
                case 10:
                    header("Location: AppWizardControllerNew.php?action=links_module");
                    break;
                case 11:
                    header("Location: AppWizardControllerNew.php?action=tours_module");
                    break;
                case 12:
                    header("Location: AppWizardControllerNew.php?action=buystuff_module");
                    break;
                case 13:
                    header("Location: AppWizardControllerNew.php?action=discography_module");
                    break;
                case 14:
                    header("Location: AppWizardControllerNew.php?action=home_video");
                    break;
                case $lastStep-1:
                    header("Location: AppWizardControllerNew.php?action=information_module");
                    break;
                case $lastStep:
                    header("Location: AppWizardControllerNew.php?action=last_step");
                    break;
                default:
                    echo "Error step!!!";
                    break;
            }
        }
        break;
}


function errorHandler($errno, $errstr, $errfile, $errline) {
    include '../../../view/common/error.php';
}

function browserError() {
    include '../../../view/common/browser_error.php';
    exit;
}

function logError($error) {
    $userId = $_SESSION['user_id'];
    if ($handle = fopen('../../../logs/app_wizard.log', 'a')) {
        $errorText =  date("D M j G:i:s T Y") . " - " . " $userId " . " --- " . $error . "\r\n";
        fwrite($handle, $errorText);
        fclose($handle);
    }
}
?>
