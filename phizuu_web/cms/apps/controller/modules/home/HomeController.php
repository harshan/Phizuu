<?php
session_start();
//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');

$userArr = NULL;
if (isset($_SESSION['user_id'])) {
    $dao = new Dao();
    $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
    $res = $dao->query($sql);
    $userArr = $dao->getArray($res);
    $userArr = $userArr[0];
}

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

switch ($action) {
    case 'main_view':
        $popArr = array();

        $sql = "SELECT * FROM information_modules WHERE `app_id`= '{$userArr['app_id']}'";

        $arr = $dao->toArray($sql);
        if (count($arr)>0)
            $popArr['texts'] = $arr[0];
        else
            $popArr['texts'] = array('biography_text'=>'', 'about_text'=>'');
        
        include ('../../../view/user/home/main.php');
        break;
    case 'save':
        $biographyText = $_POST['biographyText'];
        $aboutText = $_POST['aboutText'];
        
        $dao = new Dao();
        $biographyText = mysql_real_escape_string($biographyText);
        $aboutText = mysql_real_escape_string($aboutText);

        $sql = "INSERT INTO information_modules (`app_id`, `biography_text`, `about_text`) " .
               "VALUES ('{$userArr['app_id']}','$biographyText','$aboutText') " .
               "ON DUPLICATE KEY UPDATE `biography_text`='$biographyText', `about_text`='$aboutText'";

        $dao->query($sql);

        header("Location: InfoController.php?action=main_view&saved=ok");
        break;
    default:
            echo "Error! No valid action";
}
?>