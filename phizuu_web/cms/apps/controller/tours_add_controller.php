<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/tours_controller.php');
include('../model/tours_model.php');
include('../config/error_config.php');

include('../controller/db_connect.php');
include('../controller/helper.php');

@session_start();


if(!empty($_GET['name'])) {
    $tours= new Tours();
    if($_GET['status'] == "edit") {
        $play_val[0] = array('name' => $_GET['name'],'date' =>$_GET['date'],'location' => $_GET['location1'],'notes' => $_GET['notes'],'id' => $_GET['id']);
        $chk = $tours->editTours($play_val);

    }
    else {
        $play_val[0] = array('name' => $_GET['name'],'date' =>$_GET['date'],'location' => $_GET['location1'],'notes' => $_GET['notes']);
        $chk = $tours->addTours($play_val);
    }

}
else {
    $_REQUEST['msg_error']="error";
}
//=============================================


include('../view/user/tours/add_tours.php');

echo $response;
?>