<?php
require_once "../cms/apps/database/Dao.php";
require_once ('../cms/apps/config/config.php');

$dao = new Dao();
$sql = "select DISTINCT(module.app_id),user.email from module inner join  user on module.app_id = user.app_id  where module.recurrent_payments=0 order by app_id ASC";
$res = $dao->query($sql);
$userArr = $dao->getArray($res);
$from= 'info@phizuu.com';

foreach ($userArr as $value){
    $appId =  $value['app_id'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,
        "http://10.0.1.14/client/$appId/devicecount"
    );
    $content = curl_exec($ch);
    $arrCount = json_decode($content);
    if($arrCount->{'count'}>=4){
        $dao = new Dao();
        $sql = "update module set recurrent_payments=1 where app_id=$appId ";
        $res = $dao->query($sql);
        
        $to = $value['email'];
        $count = $arrCount->{'count'};
        $last_month_count = $arrCount->{'last_month'};
        $subject = "App update notification email from Phizuu.com";
        $message = "Downloaded total apps = $count and No of last month download = $last_month_count";
        sendEmail($to, $from, $count, $subject, $message);
        //echo $value['app_id'].'=='.$arrCount->{'count'}.'</br>';
    }
   
}

function sendEmail($to,$from,$subject,$message){
    $to = $to;
    $subject = $subject;
    $message =  $message;
    $from = $from;
    $headers = "From:" . $from;
    mail($to,$subject,$message,$headers);
}

?>
