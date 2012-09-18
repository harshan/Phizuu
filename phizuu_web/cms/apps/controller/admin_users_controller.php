<?php

require_once "../../../model/admin_manager_module.php";
class User
{
	public static function addUser($user_arr,$play_list){
	$user = new UserModel();
	
	$chk_user=$user -> addUser($user_arr,$play_list);
	
	}
	
	public static function listUser($user_id,$starting,$recpage){
	$user = new UserModel();
	
	return $list_user=$user -> listUser($user_id,$starting,$recpage);
	
	
	}
	
	public static function listUserAll($user_id){
	$user = new UserModel();
	
	return $list_user=$user -> listUserAll($user_id);
	
	
	}
	
	public static function listIphoneuser($user_id){
	$user = new UserModel();
	
	return $iphone_user=$user -> listIphoneuser($user_arr,$play_list);
	
	}
	
	public static function getUser($id){
	$user = new UserModel();
	return $data_user=$user -> getUser($id);
	
	}

	public static function editUser($user_arr){
		$user = new UserModel();
		$effected = $user->editUser($user_arr);
		
	}

	public static function checkAdmin($user_arr){
	$user = new UserModel();
	
	return $check_admin=$user -> checkAdmin($user_arr);
		
	}
        public static function GetAllManagers(){
	$adminManagerModel = new adminManagerModel();
	return $adminManagerModel->getAllManagers();
        }


}
?>