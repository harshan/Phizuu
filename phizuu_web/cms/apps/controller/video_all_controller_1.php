<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'order':
        $listArr1 = $_GET['list1'];
        $listArr2 = $_GET['list2'];
        print_r($listArr1);
        print_r($listArr2);
        $dao = new Dao();

        foreach ($listArr1 as $order=>$id) {
            $sql = "UPDATE video SET `order`='$order', iphone_status='' WHERE id='$id'";
            $dao->query($sql);
        }

        foreach ($listArr2 as $order=>$id) {
            $sql = "UPDATE video SET `order`='$order',iphone_status=1 WHERE id='$id'";
            $dao->query($sql);
        }
        
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
        }

        if ($field != '') {
            $sql = "UPDATE video SET $field = '$value' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;
    default:
        echo "Error! No valid action";
}
?>