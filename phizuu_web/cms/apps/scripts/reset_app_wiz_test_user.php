<?php
require_once ('../config/config.php');
require_once ('../database/Dao.php');



if (!isset($_GET['username'])) {
    echo "Error! You must provide username to use this script!";
    exit;
}
$dao = new Dao();

$username = $_GET['username'];
$sql = "SELECT `id` FROM `user` WHERE username='$username'";
$res = $dao->query($sql);
$arr = $dao->getArray($res);

if (count($arr)==0) {
    echo "Error! Invalid username!";
    exit;
}

$userID = $arr[0]['id'];

$sql = "SELECT `step_count` FROM `ab_step` WHERE user_id='$userID'";
$res = $dao->query($sql);
$arr = $dao->getArray($res);

if (!isset($_GET['step'])) {
    echo "Current step is {$arr[0]['step_count']}<br/><br/>";
    echo "Warning! You should provide the step to reset it";
    exit;
}

$step = $_GET['step'];

$sql = "UPDATE `ab_step` SET `step_count` = '$step' WHERE `user_id` =$userID";
$dao->query($sql);

$sql = "DELETE FROM `ab_modules` WHERE `user_id` = $userID";
$dao->query($sql);

$sql = "UPDATE `user` SET `status` = '0' WHERE `id` = $userID;";
$dao->query($sql);

echo "'$username' was successfuly reset!";
?>
