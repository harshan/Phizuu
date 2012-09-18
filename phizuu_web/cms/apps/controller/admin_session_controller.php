<?php
@session_start();
if(empty($_SESSION['admin_user_id']) || ($_SESSION['is_admin'] != "yes"))
{
session_destroy();

header("location:/".$site_main_folder_path."/apps/view/admin/admin_login.php");
exit;
}
?>