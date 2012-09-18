<?php
session_start();

//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/login/Login.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../common/xpert_mailer/MAIL.php');

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

switch ($action) {
    case 'main_view':
        include ('../../../view/user/login/login.php');
        break;
    case 'login':
        $login = new Login();
        $error = '';

        if (trim($_POST['username']) == '' ) {
            $error = "Username is empty";
        } else if ($_POST['password'] == '' ) {
            $error = "Password is empty";
        } else {
            $status = $login->loginUser($_POST['username'], $_POST['password']);
            
            if ($status === FALSE) {
                $status = $login->loginManager($_POST['username'], $_POST['password']);
                if($status=='manager'){
                    header("Location: ../../../view/user/manager_home/manager_home.php");
                    break;
                }
            }
            if ($status === FALSE) {
                $error = "Invalid login";
            } elseif ($status == 1) { // CMS User
                if($_SESSION['modules'][0]['payments']=='1') { //No payments - redirect to payments
                    header("Location: ../../../controller/modules/payments/PaymentController.php?action=view");
                } else {
                    header("Location: ../../../view/user/music/music.php");
                }
                exit;
            } elseif ($status == 0) { // App Wizard User
                header("Location: ../../../controller/modules/app_wizard/AppWizardControllerNew.php");
                exit;
            } elseif ($status == 3 || $status == 4) { // Freezed user
                $error = "CMS is freezed until your application is reviewed by Apple";
            } elseif ($status == 5) { // Not confirmed user
                $error = "Before login, please confirm your email address by clicking the link in the email alredy sent to you subjected 'Welcome to phizuu'.";
            } else {
                $error = "Invalid user status";
            }
        }

        include ('../../../view/user/login/login.php');
        break;
    case 'logout':
        $login = new Login();
        $login->logout();
        include ('../../../view/user/login/login.php');
        break;
    case 'forgot_password':
        $login = new Login();
        $login->logout();
        include ('../../../view/user/login/forgot_password.php');
        break;
    case 'recover_password':
        $login = new Login();
        $username = $_POST['username'];
        $userInfo = UserInfo::getUserInfoDirectUsername($username);
        
        if($userInfo===FALSE){
            $userInfo = UserInfo::getManagerInfoDirectUsername($username);
            if($userInfo!=FALSE){
                $userInfo['app_name'] = '';
            }
            
            
        }
        $error = '';
        if ($username == '') {
            $error = "Username is empty!";
            include ('../../../view/user/login/forgot_password.php');
        }else if ($userInfo === FALSE) {
            $error = "Username cannot be found in the system!";
            include ('../../../view/user/login/forgot_password.php');
        } else {
            $res = $login->sendPasswordForgetMail($userInfo['email'], $userInfo['password'], $userInfo['username'], $userInfo['app_name']);
        
            if ($res) {
                $message = "We have sent an email to '" . $userInfo['email'] . "' with instructions. Please check your emails and follow the instructions. If this is not your email, please contact info@phizuu.com, since this is the email in our record for the given username.";
            } else {
                $error = "<b>Error! Failed to send an email to '" . $userInfo['email'] . "'</b>";
            }
            include ('../../../view/user/login/forgot_password.php');
        }
        break;
    case 'reset_password':
        $login = new Login();
        $usernameURL = $_GET['username'];
        $passwordURL = $_GET['id'];

        if ($login->checkResetPasswordURL($usernameURL, $passwordURL)) {
            include ('../../../view/user/login/reset_password.php');
        } else {
            echo "Invalid email URL";
        }
        
    case 'change_password':
        $login = new Login();
        $usernameURL = $_POST['username_url'];
        $passwordURL = $_POST['password_url'];

        $password = $_POST['password'];
        $re_password = $_POST['re_password'];

        if ($password != $re_password) {
            $error = "Passwords are not matching";
        } else if ($login->checkResetPasswordURL($usernameURL, $passwordURL)) {
            if ($login->resetPassword($usernameURL, $password)) {
                $message = "Password reset successfully! Please wait while redirecting to login page...";
                $message .= "<br/><br/>If automatically not redirected, please click <a href='../../../'>here<a>.";
            } else {
                $error = "Error occured while changing password!";
            }
            
        } else if($login->checkManagerResetPasswordURL($usernameURL, $passwordURL)){
            if ($login->resetManagerPassword($usernameURL, $password)) {
                $message = "Password reset successfully! Please wait while redirecting to login page...";
                $message .= "<br/><br/>If automatically not redirected, please click <a href='../../../'>here<a>.";
            } else {
                $error = "Error occured while changing password!";
            }
            
        }else{
            $error = "Invalid request";
        }
        include ('../../../view/user/login/reset_password.php');
        break;
    case 'change_password_settings':
        $login = new Login();
        $password = $_POST['password'];
        $userInfo = UserInfo::getUserInfoDirect();

        $login->resetPassword($userInfo['username'], $password);

        header("Location: ../../../view/user/settings/settings_new.php");
        break;    
    
    default:
        echo "No valid action";
} ?>