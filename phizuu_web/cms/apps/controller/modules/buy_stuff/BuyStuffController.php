<?php
session_start();
//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/buy_stuff/BuyStuff.php');

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

$buyStuff = new BuyStuff();

switch ($action) {
    case 'main_view':
        $popArr = array();
        $popArr['links'] = $buyStuff->listStuff($userArr['id']);
        include ('../../../view/user/buy_stuff/buy_stuff_home.php');
        break;
    case 'order':
        $orderedArr = $_GET['id'];
        $buyStuff->setOrder($orderedArr);
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
            $sql = "UPDATE buy_stuff SET $field = '$value' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;
    case 'add_new':
        $id = $buyStuff->addStuff($userArr['id'], $_POST['title'], $_POST['link']);
        $dontEcho = true;
        $linksArr = array(array('id'=>$id, 'title'=>$_POST['title'], 'uri'=>$_POST['link']));
        
        include '../../../view/user/app_wizard/supporting/new_line_buy_stuff.php';
        $json = array('line'=>$row,'status'=>'ok');
        echo json_encode($json);
        break;
   case 'delete_link':
        $id = $_POST['id'];

        $dao = new Dao();
        $sql = "DELETE FROM buy_stuff WHERE id=$id";
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