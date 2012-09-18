<?php
class Module
{
	public static function addModule($module_arr,$play_list){
	$module = new ModuleModel();
	
	$chk_user=$module -> addModule($module_arr,$play_list);
	
	}
	
	public static function listModule($user_id,$starting,$recpage){
	$module = new ModuleModel();
	
	return $list_module=$module -> listModule($user_id,$starting,$recpage);
	
	
	}
	
	public static function listModuleAll(){
	$module = new ModuleModel();
	return $list_module=$module -> listModuleAll();

	}
	
	public static function listModuleAllRecs(){
	$module = new ModuleModel();
	return $list_module=$module -> listModuleAllRecs();

	}
	
	
	public static function listIphonemodule($user_id){
	$module = new ModuleModel();
	
	return $iphone_module=$module -> listIphonemodule($module_arr,$play_list);
	
	}
	
	public static function getModule($id){
	$module = new ModuleModel();
	return $data_module=$module -> getModule($id);
	
	}

	public static function editModule($module_arr){
		$module = new ModuleModel();
		$effected = $module->editModule($module_arr);
		
		}

	public static function listModuleUser(){
	$module = new ModuleModel();
	
	return $list_module=$module -> listModuleUser();
	
	
	}

}
?>