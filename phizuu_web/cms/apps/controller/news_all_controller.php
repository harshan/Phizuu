<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once('../model/news_model.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'order':
        $orderedArr = $_GET['id'];
        $dao = new Dao();
        
        foreach ($orderedArr as $order=>$id) {
            $sql = "UPDATE news SET `order`='$order' WHERE id='$id'";
            $dao->query($sql);
        }
        break;
     case 'delete_news':
        $id = $_POST['id'];
        
        $dao = new Dao();
        $sql = "DELETE FROM news WHERE id=$id";
        $dao->query($sql);

        if(mysql_affected_rows()==1) {
            echo "ok";
        } else {
            echo "Cannot find the element id: $id";
        }

        break;
     case 'validate_rss':
        $newsModel = new NewsModel();
        echo $newsModel->getFeedStatus($_POST['url']);
        break;

    default:
        echo "Error! No valid action";
}
?>