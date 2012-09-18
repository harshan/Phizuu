<?php
class Settings
{
	public static function addSettings($settings_arr){
	$settings = new SettingsModel();
	
	$chk_user=$settings -> addSettings($settings_arr);
	
	}
	
	public static function addAllsettings($settings_arr,$play_list){
	$settings = new SettingsModel();
	$chk_user=$settings -> addAllsettings($settings_arr,$play_list);
	
	}

	
	public static function checkSettings($settings_arr, $userId = NULL){
	$settings = new SettingsModel();
	
	return $check_settings=$settings -> checkSettings($settings_arr,$userId);
		
	}
	
	public static function listSettings($type){
	$settings = new SettingsModel();
	
	return $list_settings=$settings -> listSettings($type);
		
	}
	
	
	public static function getPrefered($type){
	$settings = new SettingsModel();
	
	return $get_prefered=$settings -> getPrefered($type);
		
	}
	public static function listSettingsAll($user_id){
	$settings = new SettingsModel();
	
	return $list_settings=$settings -> listSettingsAll($user_id);
		
	}
	
	public static function listIphonesettings($user_id){
	$settings = new SettingsModel();
	
	return $iphone_settings=$settings -> listIphonesettings($settings_arr,$play_list);
	
	}
	
	public static function getSettings($id){
	$settings = new SettingsModel();
	return $data_settings=$settings -> getSettings($id);
	
	}

	public static function editSettings($settings_arr){
		$settings = new SettingsModel();
		$effected = $settings->editSettings($settings_arr);
	
	}
	
	public static function setAuth($token,$id){
		$settings = new SettingsModel();
		$effected = $settings->setAuth($token,$id);
	}
	
	public static function listSettingsApi($app_id,$type){
		$settings = new SettingsModel();
		
		return $list_settings=$settings -> listSettingsApi($app_id,$type);
			
		}
        public static function updatefacebookLink($userId){
            $settings = new SettingsModel();
            $settings->updateFacebookLink($userId);
        }
         
        public static function getFacebookLink($userId){
            $settings = new SettingsModel();
            return $settings->getFacebookLink($userId);
        }
                
}
?>