<?php
require_once "../../../controller/email_list_controller.php";
session_start();
    header("Content-type: text/csv");  
    header("Cache-Control: no-store, no-cache");  
    header('Content-Disposition: attachment; filename="filename.csv"');

    $emailListController = new email_list_controller();
    $mailList = $emailListController->GetAllEmailListByAppId($_SESSION['app_id']);
    
    $emailAddressList = array();
    foreach ($mailList as $fields) {
        $email = array();
        array_push($email, $fields['email']);
        array_push($emailAddressList, $email);
    }


    $fp = fopen("php://output",'w'); 
    foreach ($emailAddressList as $fields1) {
        fputcsv($fp, $fields1);
    }
    
    fclose($fp);
?>
