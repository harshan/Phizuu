<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/admin_package_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();


$package= new PackageModel();
$iPhionepackage= new PackageModel();

if($_POST['status']=='edit'){
	$effected = $package->editPackage($_POST);

	header("location:../view/admin/package/edit_package_management.php?id=".$_POST['id']."&status=edited");
	exit;

}
else if($_GET['status']=='delete'){
	$effected = $package->deletePackage($_GET['id']);
	if($effected > 0){
	
	header("location:../view/admin/package/package_management.php");
	exit;
	}
}

?>