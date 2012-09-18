<?php

@session_start();
include("../config/config.php");
$_SESSION['user_id'] = "";

$admin = isset($_SESSION['is_super_admin']) ? $_SESSION['is_super_admin'] : false;


if ($_REQUEST['action']=='main') {
    $_SESSION['user_id'] = null;
    $_SESSION['app_id'] = null;
    $_SESSION['user_name'] = null;
    $_SESSION['modules'] = null;
    header("location:/". $site_main_folder_path ."/apps/view/user/manager_home/manager_home.php");
    break;
}
@session_destroy();
if ($admin == 'yes') {
    header("location:/" . $site_main_folder_path . "/apps/view/admin/admin_login_all.php");
} else {
    header("location:/" . $site_main_folder_path . "/apps/controller/modules/login/?action=logout");
}

exit;
?>