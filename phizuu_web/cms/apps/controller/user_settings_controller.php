<?php
class UserSettings
{
public static function getUserSettings($user_type){
	$login = new LoginModel();

			return $get_user=$login -> getUserSettings($user_type);
			
	}

public static function getPreferedUser_settings($user_type){
@session_start();

	$login = new LoginModel();
	$count=0;
	if($user_type == $_ENV['setting_youtube']){
	$session_user='YouTube_User';
	}
	else if($user_type == $_ENV['setting_flickr']){
	$session_user='flickr_User';
	}


			$get_user=$login -> getUserSettings($user_type);
			
			$preferred ='';
			foreach ($get_user as $user){
				if($user ->preferred == '1'){
				$preferred='1';
				
				$_SESSION[$session_user]=$user ->value;
				}
				if($count == 0){
				$list_user=$user ->value;
				}
				$count++;
			}
			if($preferred != '1'){
			$_SESSION[$session_user]="";
			$_SESSION[$session_user]=$list_user;
			}

	}

}
?>