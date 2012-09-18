<?php
session_start();
require_once 'cms/apps/config/config.php';
require_once 'cms/apps/database/Dao.php';

$errorTxt = "";

if (isset ($_GET['id']) && isset ($_GET['code'])){
    $id = $_GET['id'];
    $code = $_GET['code'];

    $sql = "SELECT * FROM user WHERE id = '$id' AND email_code = '$code'";
    $dao = new Dao();
    $res = $dao->query($sql);
    if (mysql_num_rows($res)>0) {
        $arr = $dao->getArray($res);
        $paid = $arr[0]['paid'];

        if($paid ==0) {
            $append = ",package_id=1";
        }else{
            $append = '';
        }
        
        $sql = "UPDATE user SET is_suspended=0 $append WHERE id = '$id'";
        $res = $dao->query($sql);
        session_destroy();
        header("Location: cms/apps/");
    } else {
        $errorTxt = "Invalid confirmation code";
    }
} else {
    $errorTxt = "Invalid URL";
}
?>
