<?php
require_once('../config/config.php');
require_once '../config/database.php';
require_once('../model/login_model.php');
require_once('../config/error_config.php');
require_once('../controller/db_connect.php');



require_once('../controller/helper.php');
@session_start();

$user = $_POST['username'];
$pwd=md5($_POST['password']);
$login = new LoginModel();

		if(!empty($user) && !empty($pwd))
		{
		
		$chk_user=false;
		$chk_user=$login -> checkUser($user,$pwd);

				if($chk_user == 1)
				{
                                    $chk_nav=$login -> checkNavModules();

                                    //Once chage request approved

                                    $navi_val[0] = array('music' => $chk_nav ->music,'videos' =>$chk_nav ->videos,'photos' => $chk_nav ->photos,'flyers' => $chk_nav ->flyers,'news' => $chk_nav ->news,'tours' => $chk_nav ->tours,'links' => $chk_nav ->links,'settings' => $chk_nav ->settings,'send_message' => $chk_nav ->send_message,'analytics' => $chk_nav ->analytics, 'payments'=>$chk_nav->recurrent_payments, 'buy_stuff'=>$chk_nav->buy_stuff, 'fan_contents'=>$chk_nav->fan_contents);
                                    /*
                                    $navi_val[0] = array('music' => 1,'videos' =>1,'photos' => 1,'flyers' => 1,'news' => 1,'tours' => 1,'links' => 1,'settings' => 1);
                                    */
                                    
                                    if($navi_val[0]['payments']=='1') { //No payments - No access
                                        $navi_val[0] = array('music' => '0','videos' =>'0','photos' => '0','flyers' => '0','news' => '0','tours' => '0','links' => '0','settings' => '0','send_message' => '0','analytics' => '0', 'payments'=>$chk_nav->recurrent_payments);
                                    }
                                    
                                    $_SESSION['modules']=$navi_val;

                                    if($navi_val[0]['payments']=='1') { //No payments - redirect to payments
                                        header("Location: ../controller/modules/payments/PaymentController.php?action=view");
                                    } else {
                                        header("Location: ../view/user/music/index.php");
                                    }
                                    exit;
				} elseif ($chk_user == 2) {
                                    header("location:../controller/modules/app_wizard/AppWizardControllerNew.php");
                                } elseif ($chk_user == 3) {
                                    header("location:../view/user/usr_login_new.php?msg_error_login=CMS is freezed until your application is reviewed by Apple");
                                } else{
                                    header("location:../view/user/usr_login_new.php?msg_error_login=$msg_error_login");
                                    exit;
				}
		}
		else{
		
			if(empty($user) ){
			$msg_error_user=1;
			}
			
			if(empty($pwd) ){
			$msg_error_pwd=1;
			}
			header("location:../view/user/usr_login_new.php?msg_error_user=$msg_error_user&msg_error_pwd=$msg_error_pwd");
			exit;
		}


?>
