<?php
include('../config/config.php');
require_once '../config/database.php';
include('../model/admin_module_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
@session_start();

$module= new ModuleModel();
$iPhionemodule= new ModuleModel();

if($_POST['status']=='edit'){
	$effected = $module->editModule($_POST);
	header("location:../view/admin/module/edit_module.php?id=".$_POST['id']."&status=edited");
	exit;
}
else if($_GET['status']=='delete'){
	$effected = $module->deleteModule($_GET['id']);
	if($effected > 0){
	
	header("location:../view/admin/module/module.php");
	exit;
	}
}
?>