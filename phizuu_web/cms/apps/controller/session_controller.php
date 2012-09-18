<?php
@session_start();
if(empty($_SESSION['user_id']) && !isset($_SESSION['admin_user_id'])) {
    @session_destroy();

    header("Location: /".$site_main_folder_path."/apps/controller/modules/login/?action=main_view");
    exit;
}

if (isset($_SESSION['modules'][0]['payments']) && $_SESSION['modules'][0]['payments']=='1' && $menu_item!='payments') {
    echo "Unautorized Access! This access will be reported to admin.";
    exit;
}
?>