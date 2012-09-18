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
require_once('../../../controller/settings_controller.php');
require_once('../../../controller/news_controller.php');

$userArr = NULL;
if (isset($_SESSION['user_id'])) {
    $dao = new Dao();
    $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
    $res = $dao->query($sql);
    $userArr = $dao->getArray($res);
    $userArr = $userArr[0];
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
            header("Location: AppWizardController.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();

        include '../../../view/user/app_builder/application_settings.php';
        break;
    case 'application_settings_save':
        if($navigator->getCurrentStep()!=1) {
            header("Location: AppWizardController.php");
            exit;
        }

        if(isset($_POST['pushNotifications'])) {
            $push = '1';
        } else {
            $push = '0';
        }

        $appWizard = new AppWizard($userArr['id']);
        $appId = $appWizard->generateAppId($push);

        $sql = "UPDATE user SET app_name = '{$_POST['appName']}', push=$push, app_id=$appId WHERE `id` = {$_SESSION['user_id']}";
        $dao = new Dao();
        $dao->query($sql);
        
        $navigator->gotoNextStep();
        
        header("Location: AppWizardController.php");
        break;
        
    case 'choose_modules':
        if($navigator->getCurrentStep()!=2) {
            header("Location: AppWizardController.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);

        $popArr['modules'] = $appWizard->getListOfModules();
        $popArr['selectedModules'] = $appWizard->getSelectedModules(true);

        include '../../../view/user/app_builder/choose_modules.php';
        break;
        
    case 'save_modules':
        if($navigator->getCurrentStep()!=2) {
            header("Location: AppWizardController.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $appWizard->saveModuleList($_POST['modules']);
        $navigator->gotoNextStep();
        header("Location: AppWizardController.php");
        break;
        
    case 'fill_modules':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardController.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        $popArr['modules'] = $appWizard->getSelectedModules();
        $popArr['isFlickerSet'] = $appWizard->isFlickerSet();
        $popArr['isYouTubeSet'] = $appWizard->isYouTubeSet();

        include '../../../view/user/app_builder/fill_modules.php';
        break;

    case 'fill_modules_action':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardController.php");
            exit;
        }

        if ($_POST['action'] =='backward') {
            $navigator->gotoPrevStep();
            header("Location: AppWizardController.php");
        } elseif ($_POST['action'] =='forward') {
            $appWizard = new AppWizard($userArr['id']);
            $listOfModules = $appWizard->getSelectedModules();

            $inComplete = FALSE;
            foreach ($listOfModules as $module) {
                if ($module['completed'] != 1) {
                    $inComplete = TRUE;
                }
            }

            if ($inComplete == TRUE) {
                echo "There is an error in your request! <a href='AppWizardController.php'>Click here</a> to retry";
                exit;
            }

            $navigator->gotoNextStep();
            header("Location: AppWizardController.php");
        }

        include '../../../view/user/app_builder/fill_modules.php';
        break;
        
    case 'check_module_filled':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardController.php");
            exit;
        }

        $appWizard = new AppWizard($userArr['id']);
        

        $moduleName = ucfirst($_GET['module']);
        if ($moduleName =="Tours") {
            $moduleName = "Events";
        }
        $status = $appWizard->getContentStatus($moduleName);
        $appWizard->setContentStatus($moduleName, $status);
        header("Location: AppWizardController.php");
        break;

    case 'add_acount':
        if($navigator->getCurrentStep()!=3) {
            header("Location: AppWizardController.php");
            exit;
        }

        $settings = new SettingsModel();

        $module = $_GET['module'];
        $value = $_GET['value'];
        $url = $_GET['url'];

        $settingsArr[0]['user_id'] = $userArr['id'];
        $settingsArr[0]['name'] = $value;

        if ($module==1) {
            $settingsArr[0]['type'] = $_ENV['setting_flickr'];
            
        } else if ($module==2) {
            $settingsArr[0]['type'] = $_ENV['setting_youtube'];
        }

        $settings->addSettings($settingsArr);
        header("Location: $url");

        break;
        
    case 'package_upgrade':
        if($navigator->getCurrentStep()!=1 && $navigator->getCurrentStep()!=2 && $navigator->getCurrentStep()!=3) {
            header("Location: AppWizardController.php");
            exit;
        }
        $_SESSION['upgrade']='yes';

        header('Location: ../../../../../free/pricing.html');
        
        break;
        
    case 'app_builder':
        if($navigator->getCurrentStep()!=4) {
            header("Location: AppWizardController.php");
            exit;
        }
        $appWizard = new AppWizard($userArr['id']);
        $popArray = array();
        $popArray['packageInfo'] = $appWizard->getPackageInfo();
        $popArray['listOfModules'] = $appWizard->getSelectedModules(true);
        include ('../../../view/user/app_builder/app_builder_home.php');
        break;
        
    case 'write_xml':
        if($navigator->getCurrentStep()!=4) {
            header("Location: AppWizardController.php");
            exit;
        }
        
        $appWizard = new AppWizard($userArr['id']);
        $path = $appWizard->createFolderForApplication($userArr['app_id']);

        if ($userArr['package_id']==1) {
            $adds = true;
        } else {
            $adds = false;
        }

        if ($userArr['push']==1) {
            $push = true;
        } else {
            $push = false;
        }

        $facebookImageURL = "http://phizuu.com/images/facebook_post_images/{$userArr['app_id']}/{$userArr['app_id']}.png";
        $coverImageURL = "http://phizuu.com/images/music_cover_images/{$userArr['app_id']}/{$userArr['app_id']}.png";

        $homeImagesArr = array();
        for ($i=1; $i<=$_POST['homeImageCount']; $i++) {
            if ($_POST['homeImage-'.$i] != '')
                $homeImagesArr[] = $_POST['homeImage-'.$i];
        }

        $moduleArr = $appWizard->getSelectedModules(true);

        $result = $appWizard->writeXML($userArr['app_id'], $userArr['app_name'], count($homeImagesArr), $moduleArr, 'mobclixId', $push, $adds, $facebookImageURL, $path);
        if (!$result) {
            echo "Error ocurred while creating XML";
        }

        $result = $appWizard->moveImages($_POST['iconImage'],$_POST['iTunesArtWork'],$_POST['faceBookPostImage'], $_POST['musicImage'], $_POST['loadImage'], $homeImagesArr, $userArr['app_id'], $path);
        if (!$result) {
            echo "Error ocurred while moving images";
        }

        $result = $appWizard->createTextFiles($_POST['aboutText'],$_POST['bioText'],$path);
        if (!$result) {
            echo "Error while creating the text files";
        }

        $result = $appWizard->createZip($path, $userArr['app_id']);
        if (!$result) {
            echo "Error while creating the zip achive";
        }

        $appWizard->sendEmail($userArr['app_name'], $userArr['app_id'], $userArr['username'], $userArr['email'], $userArr['package_id']);

        $sql = "UPDATE user SET status = '3' WHERE id = {$userArr['id']}";
        $dao = new Dao();
        $dao->query($sql);

        $sql = "INSERT INTO album_cover (user_id, cover_url) VALUES({$_SESSION['user_id']},'$coverImageURL')
                ON DUPLICATE KEY UPDATE cover_url='$coverImageURL'";
        $dao->query($sql);

        $api = new API();
        $api->writeStaticModuleJSON();

        $navigator->gotoNextStep();
        header("Location: AppWizardController.php");
        break;
        
    case 'last_step':
        if($navigator->getCurrentStep()!=5) {
            header("Location: AppWizardController.php");
            exit;
        }
        include ('../../../view/user/app_builder/last_step.php');
        break;
 

    default:
        if ($navigator->isFirstTime()) {
            header("Location: AppWizardController.php?action=first_time");
        } else {
            //echo $navigator->getCurrentStep();
            switch ($navigator->getCurrentStep()) {
                case 1:
                    header("Location: AppWizardController.php?action=application_settings");
                    break;
                case 2:
                    header("Location: AppWizardController.php?action=choose_modules");
                    break;
                case 3:
                    header("Location: AppWizardController.php?action=fill_modules");
                    break;
                case 4:
                    header("Location: AppWizardController.php?action=app_builder");
                    break;
                case 5:
                    header("Location: AppWizardController.php?action=last_step");
                    break;
                default:
                    echo "Error step!!!";
                    break;
            }
        }
        break;
}
?>
