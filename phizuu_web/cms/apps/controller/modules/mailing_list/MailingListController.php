<?php
session_start();

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/UserInfo.php');

if (!isset($_SESSION['user_id'])) {
    echo 'Unautorized access of the file! Please login <a href="http://phizuu.com/cms">here</a> before downloading the mailing list.';
    exit;
}
$userArr = UserInfo::getUserInfoDirect();

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$wizard = false;
if (isset($_GET['wizard'])) {
    $wizard = true;
}

//$discography = new Discography($userArr['id']);

switch ($action) {
    case 'download_mailing_list':
        $url = "http://connect.phizuu.com/client/{$userArr['app_id']}/mailing_list/";
        $json = file_get_contents($url);
        $data = json_decode($json);

        if (count($data->records) == 0) {
            echo "No records found!";
            exit;
        }

        header("Content-type: text/csv");
        $appName = preg_replace("/[^+A-Za-z0-9]/", "", $userArr['app_name']);
        header("Content-Disposition: attachment; filename=MailingList_$appName.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $headers = array();

        foreach ($data->records[0] as $field=>$val) {
            $headers[] = '"'.ucwords(str_replace('_', ' ', $field)).'"';
        }
        echo implode(',', $headers);

        foreach ($data->records as $record) {
            echo "\n";
            $valArray = array();
            foreach($record as $val) {
                $valArray[] = '"'. $val .'"';
            }
            echo implode(',', $valArray);
        }
        break;
    default:
        echo "Error! No valid action";
}

?>