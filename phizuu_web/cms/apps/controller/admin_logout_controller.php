<?php
@session_start();
include("../config/config.php");
$_SESSION['admin_user_id']="";
session_destroy();

header("location:/".$site_main_folder_path."/apps/view/admin/admin_login.php");
exit;
?>