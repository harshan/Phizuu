<?php
session_start();
sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/Links.php');
require_once ('../../../model/paypal/PayPal.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../model/discography/Discography.php');
require_once ('../../../model/StorageServer.php');
require_once("../../../controller/session_controller.php");
require_once ('../../../model/app_builder/Navigator.php');
require_once ('../../../model/app_builder/AppWizard.php');
require_once ('../../../model/app_content_update/AppContentUpdate.php');
require_once ('../../../common/xpert_mailer/MAIL.php');

//Payments
require_once '../../../common/phpcreditcard.php';
require_once '../../../../../constants_new.php';
require_once '../../../common/CallerService.php';
require_once '../../../common/Country.php';

$userArr = UserInfo::getUserInfoDirect();

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$discography = new Discography($userArr['id']);


switch ($action) {
    case 'main_view':
        $navigator = new Navigator($userArr['id']);
        $appWizard = new AppWizard($userArr['id']);

        $path = $appWizard->createFolderForApplication($userArr['app_id']);

        $sql = "DELETE FROM ab_modules WHERE user_id={$userArr['id']}";
        $dao = new Dao();
        $dao->query($sql);

        if ($handle = opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    unlink("$path/$file");
                }
            }
            closedir($handle);
        }

        $navigator->setCurrentStep(2);

        $_SESSION['update_contents'] = 'yes';
        header("Location: ../../../controller/modules/app_wizard/AppWizardControllerNew.php");
        break;
    case 'wizard_canceled':
        unset($_SESSION['update_contents']);
        include '../../../view/user/update_app/update_cancelled.php';
        break;

    case 'wizard_completed':
        unset($_SESSION['update_contents']);

        $appContentUpdate = new AppContectUpdate($userArr['id'], $userArr['app_id']);

        $updatedContents= $appContentUpdate->getUploadedContents();

        include '../../../view/user/update_app/update_wizard_completed.php';
        break;

    case 'get_payment':
        $countries = Country::getCountryArray();
        include '../../../view/user/update_app/get_payment.php';
        break;

    case 'send_request':
        $amount = 49;
        $payPal = new PayPal();
        try {
            $payPal->takePayment($amount, $_POST, "Payment for content update request for {$userArr['username']}",$userArr['email']);
            
            $appWizard = new AppWizard($userArr['id']);
            $path = $appWizard->createFolderForApplication($userArr['app_id']);
            
            $appContentUpdate = new AppContectUpdate($userArr['id'], $userArr['app_id']);
            $image = $appContentUpdate->createZipFile($path, $userArr['app_id']);

            $appContentUpdate->sendEmails($userArr, $amount);

            $msgSuccess = "Content update request sent successfully!";
        } catch (Exception $e) {
            $msg = $e->getMessage();
        }
        $countries = Country::getCountryArray();
        include '../../../view/user/update_app/get_payment.php';
        break;
    
    default:
            echo "Error! No valid action";
}

?>