<?php

class PushNotifications {

    public function getNumPhotoUpdates($clear = false) {
        $dao = new Dao();
        $sql = "SELECT id FROM image WHERE new_one = 1 AND iphone_status = 1 AND user_id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);

        if ($clear) {
            $sql = "UPDATE image SET new_one = 0 WHERE user_id = {$_SESSION['user_id']}";
            $dao->query($sql);
        }

        return mysql_num_rows($res);
    }

    public function getNumVideoUpdates($clear = false) {
        $dao = new Dao();
        $sql = "SELECT id FROM video WHERE new_one = 1  AND iphone_status = 1 AND user_id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);

        if ($clear) {
            $sql = "UPDATE video SET new_one = 0 WHERE user_id = {$_SESSION['user_id']}";
            $dao->query($sql);
        }

        return mysql_num_rows($res);
    }

    public function getNumTourUpdates($clear = false) {
        $dao = new Dao();
        $sql = "SELECT id FROM tour WHERE new_one = 1  AND iphone_status = 1 AND user_id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);

        if ($clear) {
            $sql = "UPDATE tour SET new_one = 0 WHERE user_id = {$_SESSION['user_id']}";
            $dao->query($sql);
        }

        return mysql_num_rows($res);
    }

    public function getNumMusicUpdates($clear = false) {
        $dao = new Dao();
        $sql = "SELECT id FROM song WHERE new_one = 1  AND iphone_status = 1 AND user_id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);

        if ($clear) {
            $sql = "UPDATE song SET new_one = 0 WHERE user_id = {$_SESSION['user_id']}";
            $dao->query($sql);
        }

        return mysql_num_rows($res);
    }

    public function notifyUpdates() {

        $dao = new Dao();
        $sql = "SELECT app_id FROM user WHERE id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);

        $str = '';

        $str .= "AppId=com.phizuu.hammerhead." . $arr[0]['app_id'];
        $str .= "&photoupdates=" . $this->getNumPhotoUpdates(true);
        $str .= "&musicupdates=" . $this->getNumMusicUpdates(true);
        $str .= "&videoupdates=" . $this->getNumVideoUpdates(true);
        $str .= "&tourupdates=" . $this->getNumTourUpdates(true);

        //echo $str;
        $url = "http://connect.phizuu.com/api/updates/";

        $ch = curl_init(); // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);
    }

    public function sendMessage($message, $location, $range, $module) {
        $dao = new Dao();
        $sql = "SELECT * FROM user WHERE id = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);

//        $messageAppend = "appid=com.phizuu.hammerhead." . urlencode($userArr[0]['app_id']);
//        $messageAppend .= "&message=" . urlencode($message);
//        $messageAppend .= "&messagelocation=" . urlencode($location);
//        $messageAppend .= "&messageradius=" . urlencode($range);
//        $messageAppend .= "&messagemodule=" . urlencode($module);
        //echo $messageAppend;
        //$url = "http://connect.phizuu.com/api/message";
        //$url = "http://connect.phizuu.com/push/sendpush";
        $url = 'http://10.0.1.75/push/sendpush';

        $messageAppend = array(
            'app_id' => $userArr[0]['app_id'],
            'message' => $message,
            'location' => $location,
            'module' => $module,
            'radius' => $range
        );

//        $ch = curl_init(); // initialize curl handle
//        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
//        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
//        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // times out after 4s
//        curl_setopt($ch, CURLOPT_POST, 1); // set POST method
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageAppend));
//        $result = curl_exec($ch); // run the whole process
        $headers = array('Content-Type: application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageAppend));

        $result = curl_exec($ch);

        $details = curl_getinfo($ch);
        curl_close($ch);
        $details['http_code'];
        if ($details['http_code'] == 200) {
            $sql = "INSERT INTO messages (user_id, sent_date) VALUES ('{$_SESSION['user_id']}',CURDATE())";
            $res = $dao->query($sql);
            return array(TRUE, $result);
        } else {
            return array(FALSE, $result);
        }
    }

    public function getRemainingPushMessages($userId) {
        $dao = new Dao();
        $sql = "SELECT * FROM messages WHERE DATEDIFF(CURDATE(),sent_date)<=31 AND user_id='$userId'";
        $res = $dao->query($sql);
        $messagesInThisMonth = mysql_num_rows($res);

        $sql = "SELECT message_limit FROM `user`,`package` WHERE `user`.`id`='$userId' AND `user`.`package_id` = `package`.`id`";
        $res = $dao->query($sql);
        $arr = $dao->getArray($res);
        $messageAllowed = $arr[0]['message_limit'];
        return $messageAllowed - $messagesInThisMonth;
    }

    public static function changeSavedStatus($status) {
        $userId = $_SESSION['user_id'];

        $dao = new Dao();
        $sql = "INSERT INTO published_status (user_id, status) VALUES ('$userId','$status')" .
                " ON DUPLICATE KEY UPDATE status='$status'";

        $dao->query($sql);

        $_SESSION['changes_done'] = $status;
    }

    public static function getSavedStatus() {
        if (isset($_SESSION['changes_done']) && $_SESSION['changes_done'] == '1') {
            return '2';
        }

        $userId = $_SESSION['user_id'];

        $dao = new Dao();
        $sql = "SELECT * FROM published_status WHERE user_id='$userId'";

        $arr = $dao->toArray($sql);

        if (count($arr) > 0) {
            return $arr[0]['status'];
        } else {
            return 0;
        }
    }

}

?>
