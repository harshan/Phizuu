<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/login_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
require_once ('../model/login/Login.php');

require_once ('../database/Dao.php');

require_once ('../model/UserInfo.php');
@session_start();

$user = $_POST['username'];
$pwd=$_POST['password'];
$login = new LoginModel();

		if(!empty($user) && !empty($pwd))
		{
		
		$chk_user=false;
		$pwd=md5($pwd);
		$chk_user=$login -> checkAdminUser($user,$pwd);

				if($chk_user == true)
				{
                                    if(isset ($_POST['loginas'])) {
                                        $loginNew = new Login();
                                        $status = $loginNew->loginUser($_POST['loginas'], null, true);
                                        if ($status === FALSE) {
                                            $error = "Invalid login";
                                        } elseif ($status == 1) { // CMS User
                                            if($_SESSION['modules'][0]['payments']=='1') { //No payments - redirect to payments
                                                header("Location: ../controller/modules/payments/PaymentController.php?action=view");
                                            } else {
                                                header("Location: ../view/user/music/music.php");
                                            }
                                            exit;
                                        } elseif ($status == 0) { // App Wizard User
                                            header("Location: ../controller/modules/app_wizard/AppWizardControllerNew.php");
                                            exit;
                                        } elseif ($status == 3) { // Freezed user
                                            $error = "CMS is freezed until your application is reviewed by Apple";
                                        } else {
                                            $error = "Invalid user status";
                                        }
                                        $error = urlencode($error);
                                        header("location: ../view/admin/admin_login_all.php?msg_error_other_user=$error");
                                        exit;
                                    }
				
                                    header("location:../controller/modules/admin/admin_controller.php?action=show_user_module");
                                    exit;
				}
				else{
				
				header("location:../view/admin/admin_login_all.php?msg_error_login=$msg_error_login");
				exit;			
				}
		}
		else{
		
			if(empty($user) ){
			$msg_error_user='Required field';
			}
			
			if(empty($pwd) ){
			$msg_error_pwd='Required field';
			}
			header("location:../view/admin/admin_login_all.php?msg_error_user=$msg_error_user&msg_error_pwd=$msg_error_pwd");
			exit;
		}

?>