<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once '../config/database.php';
require_once('../controller/db_connect.php');
require_once '../model/tour/MySpaceTourParser.php';
require_once '../model/tour/SongKick.php';
require_once '../model/tour/simple_html_dom.php';
require_once '../model/tours_model.php';
require_once '../model/settings_model.php';
require_once ('../common/SampleImage.php');
require_once ('../model/cms_config/CMSConfig.php');

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

switch ($action) {
    case 'order':
        $orderedArr = $_GET['id'];
        $dao = new Dao();
        
        foreach ($orderedArr as $order=>$id) {
            $sql = "UPDATE tour SET `order`='$order' WHERE id='$id'";
            $dao->query($sql);
        }
        break;
    case 'change_images':
        $tourId = $_GET['id'];


        if($_FILES['flyerImage']['size']>0)
            $flyerActualFileName = $_FILES['flyerImage']['tmp_name'];
        else
            $flyerActualFileName = '';

        $tour = new ToursModel();
        $tour->updateTourImage($tourId, $flyerActualFileName);

       if (!isset($_GET['wizard'])) {
           header("Location: ../view/user/tours/tours_new.php");
       }else{
           header("Location: ../controller/modules/app_wizard/AppWizardControllerNew.php");
       }

        break;
    case 'edit':
        $parts = explode('_', $_POST['id']);
        $field = $parts[0];
        $id = $parts[1];
        $value = $_POST['value'];
        switch ($parts[0]) {
            case '1':
                $field = '`name`';
                break;
            case '2':
                $field = 'date';
                break;
            case '3':
                $field = 'location';
                break;
            case '4':
                $field = 'description';
                break;
            case '5':
                $field = 'ticket_url';
                break;
            default:
                $field = '';
                break;
        }
        $escapedValue = mysql_real_escape_string(stripslashes($value));

        if ($field != '') {
            $sql = "UPDATE tour SET $field = '$escapedValue' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;
    case 'fetch_myspace_tours':
        //$mySpaceParser = new MySpaceTourParser($_POST['mySpaceURL']);
        $songKick = new SongKick();

        $error = FALSE;
        try {
            $events = $songKick->getEventsArr($_POST['mySpaceURL']);
        } catch (Exception $e) {
            //echo $e->getMessage() . ":" . $e->getLine();
            $error = TRUE;
        }

       $settingModel = new SettingsModel();
       $settings[0] = array('type'=>$_ENV['myspace_url'], 'name'=>$_POST['mySpaceURL']);
       $settingModel->addSettings($settings);

       if ($error || count($events)==0) {

            if (isset($_SESSION['modules'])) {
                header("Location: ../view/user/tours/tours_new.php?error=url");
            } else {
                header("Location:../controller/modules/app_wizard/AppWizardControllerNew.php?error=url&action=tours_module");
            }
           exit;
       }
       
       $toursModel = new ToursModel();
       $toursModel->deleteAllTours();

       foreach ($events as $event)
       {
            $ticketURL = '';
            if(isset($event->ticketURL))
                 $ticketURL = $event->ticketURL;

            $toursArr[0] = array('name' => $event->title,'date' =>$event->date,'location' => $event->location,'notes' => '', 'ticketURL'=>$ticketURL, 'thumbFileName'=>'', 'flyerFileName'=>'');
            $toursModel->addTours($toursArr);
       }
       
       if (!isset($_GET['wizard'])) {
           header("Location: ../view/user/tours/tours_new.php");
       }else{
           header("Location: ../controller/modules/app_wizard/AppWizardControllerNew.php");
       }
       break;
   case 'delete_tour':
        $id = $_POST['id'];

        $dao = new Dao();
        $sql = "DELETE FROM tour WHERE id=$id";
        $dao->query($sql);

        if(mysql_affected_rows()==1) {
            echo "ok";
        } else {
            echo "Cannot find the element id: $id";
        }

        break;

    case 'hide_old_tours':
        CMSConfig::saveConfig($_SESSION['user_id'], 'old_tour_hidden', 'hidden');
        header("Location: ../view/user/tours/tours_new.php");
        break;

    case 'show_old_tours':
        CMSConfig::saveConfig($_SESSION['user_id'], 'old_tour_hidden', 'visible');
        header("Location: ../view/user/tours/tours_new.php");
        break;

    case 'update_default_image':
        $url = $_POST['url'];
        $thumb = $_POST['thumb'];
        $dao = new Dao();
        $sql = "INSERT INTO tour_default_image (user_id, url, thumb) VALUES({$_SESSION['user_id']},'$url', '$thumb')
                ON DUPLICATE KEY UPDATE url='$url', thumb='$thumb'";
        $dao->query($sql);
        echo 'ok';
        break;

    default:
        //echo "Error! No valid action";
}
?>