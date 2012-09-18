<?php
session_start();

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/Links.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../model/discography/Discography.php');
require_once ('../../../model/StorageServer.php');
require_once("../../../controller/session_controller.php");

$userArr = UserInfo::getUserInfoDirect();

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$wizard = false;
if (isset($_GET['wizard'])) {
    $wizard = true;
}

$discography = new Discography($userArr['id']);

switch ($action) {
    case 'main_view':
        $popArr = array();
        $popArr['discographies'] = $discography->listDiscographies();
        include ('../../../view/user/discography/discography_home.php');
        break;
    case 'order':
        $orderedArr = $_GET['id'];
        $discography->setOrder($orderedArr);
        break;
    case 'edit':
        $parts = explode('_', $_POST['id']);
        $field = $parts[0];
        $id = $parts[1];
        $value = $_POST['value'];

        $valueEscaped = mysql_real_escape_string($value);

        if ($field != '') {
            $sql = "UPDATE discography SET $field = '$valueEscaped' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);

            if ($field == 'details') {
                echo str_replace("\n", "<br/>", $value);
            } else {
                echo $value;
            }
        }
        break;
    case 'add_new_item':
        $discography->addNew($_POST, $_FILES);

        if ($wizard)
            header('Location: ../../../controller/modules/app_wizard/AppWizardControllerNew.php?action=discography_module');
        else
            header('Location: DiscographyController.php?action=main_view');
        
        break;
   case 'delete_item':
        $id = $_POST['id'];

        $dao = new Dao();
        $sql = "DELETE FROM discography WHERE id=$id";
        $dao->query($sql);

        if(mysql_affected_rows()==1) {
            $discography->deletePictures($id);
            echo "ok";
        } else {
            echo "Cannot find the element id: $id";
        }

        break;

    case 'get_buy_links_ajax':
        $id = $_POST['id'];

        $buyLinks = $discography->getBuyLinks($id);
        include ('../../../view/user/discography/new_line_buy_sub_view.php');
        break;

    case 'edit_buy_link':
        $parts = explode('_', $_POST['id']);
        $field = $parts[0];
        $id = $parts[1];

        $value = $_POST['value'];
        $valueEscaped = mysql_real_escape_string($value);

        if ($field != '') {
            $sql = "UPDATE discography_buy_links SET $field = '$valueEscaped' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;
    case 'delete_buy_link':
        $id = $_POST['id'];

        $dao = new Dao();
        $sql = "DELETE FROM discography_buy_links WHERE id=$id";
        $dao->query($sql);

        if(mysql_affected_rows()==1) {
            echo "ok";
        } else {
            echo "Cannot find the element id: $id";
        }

        break;
    case 'add_new_buy_link_ajax':
        $id = $discography->addNewBuyURL($_POST['id'], $_POST['title'], $_POST['link']);

        $buyLinks = $discography->getBuyLinks($_POST['id'], $id);
        include ('../../../view/user/discography/new_line_buy_sub_view.php');

        break;
    case 'update_image':
        $discography->updateImage($_GET['id'], $_FILES['image']['tmp_name']);

        if ($wizard)
            header('Location: ../../../controller/modules/app_wizard/AppWizardControllerNew.php?action=discography_module');
        else
            header('Location: DiscographyController.php?action=main_view');

        break;
    default:
        echo "Error! No valid action";
}

?>