<?php
require_once "../cms/apps/database/Dao.php";
require_once ('../cms/apps/config/config.php');


$dao = new Dao();
$sql = "SELECT id,app_id,email FROM `user`";
$res = $dao->query($sql);
$userArr = $dao->getArray($res);

$from= 'info@phizuu.com';

foreach ($userArr as $value){
    $appId =  $value['app_id'];
    $id =  $value['id'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,
        "http://204.51.99.15/client/$appId/devicecount"
    );
    $content = curl_exec($ch);
    $arrCount = json_decode($content);
    
    if($arrCount->{'count'}>450 && $arrCount->{'count'}<500){
        $to = $value['email'];
        $count = $arrCount->{'count'};
        $last_month_count = $arrCount->{'last_month'};
        $subject = "App update notification email from Phizuu.com";
        $message = "Downloaded total apps = $count and No of last month download = $last_month_count";
        sendEmail($to, $from, $count, $subject, $message);
        
    }else if($arrCount->{'count'}>=500){
        $to = $value['email'];
        $count = $arrCount->{'count'};
        $last_month_count = $arrCount->{'last_month'};
        $subject = "App update notification email from Phizuu.com";
        $message = "Downloaded total apps = $count and No of last month download = $last_month_count";
        sendEmail($to, $from, $count, $subject, $message);
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

//    function SendEmail($to, $from, $count, $last_month_count) {
//
//        $to = $to;
//        //$to = "dilrukj@insharptechnologies.com";
//        $subject = "Notification from phizuu.com ";
//        
//        echo $message = "Downloaded total apps = $count and No of last month download = $last_month_count";
//        
//        //$from = "harshan1981@yahoo.co.uk";
//        //$headers = "From:" . $from;
//        //mail($to,$subject,$message,$headers);
//        //echo "Mail Sent.";
//        //old email funtion 
//        $headers = 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//        $headers .= 'From: notification@phizuu.com' . "\r\n";
//        $headers .="Content-Transfer-Encoding: 8bit";
//
//
//        $from = 'mail.phizuu.com';
//        //$subject = 'This is the subject';
//        $body = '';
//        $SMTPMail = new SMTPClient('mail.phizuu.com', '26', 'notification@phizuu.com', 'abc123', 'notification@phizuu.com', $to, $subject, $message);
//        $SMTPChat = $SMTPMail->SendMail();
//    }

?>
