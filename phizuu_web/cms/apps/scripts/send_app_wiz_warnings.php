<?php
require_once ('../config/config.php');
require_once ('../database/Dao.php');
require_once ('../common/xpert_mailer/MAIL.php');

$sql = "SELECT id,email,username FROM user LEFT JOIN app_wizard_warnings ON user.`id`=app_wizard_warnings.`user_id` ".
       "WHERE status=0 AND warning_count=0 AND DATEDIFF(NOW(),created_date)>3 AND DATEDIFF(NOW(),created_date)<7";

$dao = new Dao();
$firstWaring = $dao->toArray($sql);
foreach($firstWaring as $user) {
    sendFirstWarning($user['email'], $user['username'], $user['id']);
    logError("First warning sent to {$user['email']},{$user['username']}");
}


$sql = "SELECT id,email,username FROM user LEFT JOIN app_wizard_warnings ON user.`id`=app_wizard_warnings.`user_id` ".
       "WHERE status=0 AND warning_count=1 AND DATEDIFF(NOW(),created_date)>7 AND DATEDIFF(NOW(),created_date)<14";

$firstWaring = $dao->toArray($sql);
foreach($firstWaring as $user) {
    sendSecondWarning($user['email'], $user['username'], $user['id']);
    logError("Second warning sent to {$user['email']},{$user['username']}");
}


$sql = "SELECT id,email,username FROM user LEFT JOIN app_wizard_warnings ON user.`id`=app_wizard_warnings.`user_id` ".
       "WHERE status=0 AND warning_count=2 AND DATEDIFF(NOW(),created_date)>14";
$firstWaring = $dao->toArray($sql);
foreach($firstWaring as $user) {
    $sql = "DELETE FROM user WHERE id='{$user['id']}'";
    $rtn = $dao->query($sql);
    if($rtn)
        logError("Deleted the user {$user['email']},{$user['username']}");
    else
        logError("Could not delete the user {$user['email']},{$user['username']}");
}

function sendFirstWarning($email, $username, $id) {
    $m = new MAIL;
    // set from address
    $m->From('info@phizuu.com');
    // add to address
    $m->AddTo($email);
    // set subject
    $m->Subject('phizuu Application Wizard Reminder');
    // set text message
    $m->Text('Please use html browser to view this email');

    $html = '<html>';
    $html .= '<body>';
    $html .= "Hey $username, <br/><br/>";
    $html .= "It has been more than 3 days from when you have created your phizuu account with the username '$username'.";
    $html .= " Still you haven't completed your iPhone application. ";
    $html .= "Please click <a href='http://phizuu.com/cms/apps/'>here</a> to login and complete your Application<br/><br/>";
    $html .= "-Team phizuu";
    $html .= '</body>';
    $html .= '</html>';
    $html .= '</html>';

    $m->Html($html);

    if($m->Send()) {
        $dao = new Dao();
        $sql = "UPDATE app_wizard_warnings SET warning_count=1 WHERE user_id='$id'";
        $dao->query($sql);
    } else {
        logError("Failed sending first warning mail to $username, $email");
    }
}

function sendSecondWarning($email, $username, $id) {
    $m = new MAIL;
    // set from address
    $m->From('info@phizuu.com');
    // add to address
    $m->AddTo($email);
    // set subject
    $m->Subject('phizuu Application Wizard Reminder');
    // set text message
    $m->Text('Please use html browser to view this email');

    $html = '<html>';
    $html .= '<body>';
    $html .= "Hey $username, <br/><br/>";
    $html .= "It has been more than one week from when you have created your phizuu account with the username '$username'. ";
    $html .= "Unfortunately, still you haven't completed your iPhone application. We regret to inform you that we will be deleting your phizuu account unless you complete the application within next 7 days. ";
    $html .= "Please click <a href='http://phizuu.com/cms/apps/'>here</a> to login and complete your Application<br/><br/>";
    $html .= "-Team phizuu";
    $html .= '</body>';
    $html .= '</html>';
    $html .= '</html>';

    $m->Html($html);

    if($m->Send()) {
        $dao = new Dao();
        $sql = "UPDATE app_wizard_warnings SET warning_count=2 WHERE user_id='$id'";
        $dao->query($sql);
    } else {
        logError("Failed sending second warning mail to $username, $email");
    }
}

function logError($error) {
    if ($handle = fopen('../logs/app_wizard_warning.log', 'a')) {
        $errorText =  date("D M j G:i:s T Y") . " --- " . $error . "\r\n";
        fwrite($handle, $errorText);
        fclose($handle);
    }
}
?>
