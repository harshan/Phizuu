<?php
session_start();
//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/Links.php');
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/music_controller.php');
include('../../../model/music_model.php');
include('../../../config/error_config.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');
require_once('../../../model/soundcloud/soundcloud.php');

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

$links = new Links();

switch ($action) {
    case 'main_view':
        $popArr = array();
        $popArr['links'] = $links->listLinks($userArr['id']);
        include ('../../../view/user/links/links_home.php');
        break;
    case 'order':
        $orderedArr = $_GET['id'];
        $links->setOrder($orderedArr);
        break;
    case 'edit':
        $parts = explode('_', $_POST['id']);
        $field = $parts[0];
        $id = $parts[1];
        $value = $_POST['value'];
        switch ($parts[0]) {
            case '1':
                $field = '`title`';
                break;
            case '2':
                $field = 'uri';
                break;
            default:
                $field = '';
                break;
        }

        if ($field != '') {
            $sql = "UPDATE link SET $field = '$value' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;
    case 'add_new':
        $id = $links->addLink($userArr['id'], $_POST['title'], $_POST['link']);
        $dontEcho = true;
        $linksArr = array(array('id'=>$id, 'title'=>$_POST['title'], 'uri'=>$_POST['link']));
        include ('../../../view/user/links/new_line_sub_view.php');
        $json = array('line'=>$row,'status'=>'ok');
        echo json_encode($json);
        break;
   case 'delete_link':
        $id = $_POST['id'];

        $dao = new Dao();
        $sql = "DELETE FROM link WHERE id=$id";
        $dao->query($sql);

        if(mysql_affected_rows()==1) {
            echo "ok";
        } else {
            echo "Cannot find the element id: $id";
        }

        break;
    default:
            echo "Error! No valid action";
}
?>