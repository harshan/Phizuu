<?php
@session_start();
require_once ('../config/config.php');
require_once "../database/Dao.php";

$facebookLink = $_POST['value'];
if($_REQUEST['action']=='updateFBLink'){
    $userId=$_SESSION['user_id'];
    $dao = new Dao();
    $sql = "select * from setting where user_id=$userId and type=7";
    $res = $dao->query($sql);
    $res = $dao->getArray($res);
    if(isset($res[0]['id'])){
         $sql = "update setting set value = '".addslashes($facebookLink)."' where user_id = $userId and type=7";
         $res = $dao->query($sql);
         echo 'ok';
    }else{
         $sql = "insert into setting(type, preferred, value,user_id) values(7,1,'".addslashes($facebookLink)."',$userId)";
         $res = $dao->query($sql);
         echo 'ok';
    }
    
}
?>
