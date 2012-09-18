<?php
include('../config/config.php');
require_once '../config/database.php';
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../model/login_model.php');


$user = $_POST['username'];

$login = new LoginModel();

		if(!empty($user))
		{
		
		 $users=$login -> checkUserName($user);
		

				if($users)
				{
				foreach ($users as $user){
				
				$content= $user->name."<br>".$user->password."<br>";

				$Name = "Dear User"; //senders name
				$email = $forgot_pwd_email; //senders e-mail adress
				$recipient = $user->email; //recipient
				$mail_body = "Your Password is :".$content; //mail body
				$subject = "Forgot Password"; //subject
				$header = "From:  PHIZUU  <" . $email . ">\r\n"; //optional headerfields
				
				mail($recipient, $subject, $mail_body, $header); //mail command :)

				}

				header("location:music/index.php");
				exit;
				}
				else{
				$msg='Invalid UserName';
				$msg_error_login=1;
				header("location:../view/user/forgot_pwd.php?msg_error_login=$msg_error_login");
				exit;
				}
		}
		else{

			if(empty($user) ){
			$msg_error_user=1;
			}
			
			header("location:../view/user/forgot_pwd.php?msg_error_user=$msg_error_user");
			exit;
		}

		
?>
