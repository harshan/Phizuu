<?php
class Package
{
	public static function addPackage($package_arr,$play_list){
	$package = new PackageModel();
	
	$chk_user=$package -> addPackage($package_arr,$play_list);
	
	}
	
	public static function listPackage($user_id,$starting,$recpage){
	$package = new PackageModel();
	
	return $list_package=$package -> listPackage($user_id,$starting,$recpage);
	
	
	}
	
	public static function listPackageAll(){
	$package = new PackageModel();
	return $list_package=$package -> listPackageAll();

	}
	
	public static function listPackageAllRecs(){
	$package = new PackageModel();
	return $list_package=$package -> listPackageAllRecs();

	}
	
	
	public static function listIphonepackage($user_id){
	$package = new PackageModel();
	
	return $iphone_package=$package -> listIphonepackage($package_arr,$play_list);
	
	}
	
	public static function getPackage($id){
	$package = new PackageModel();
	return $data_package=$package -> getPackage($id);
	
	}

	public static function editPackage($package_arr){
		$package = new PackageModel();
		$effected = $package->editPackage($package_arr);
		
	}

}
?>