<?php
@session_start();


class SettingsModel {

    function addSettings($settings_arr) {
        $sql= "insert into `setting` (`type`, `value`, `user_id`) VALUES ('".addslashes($settings_arr[0]['type'])."','".addslashes($settings_arr[0]['name'])."','".addslashes($_SESSION['user_id'])."')";
        
        $result= mysql_query($sql) or die(mysql_error());
        $settings_arr[0]['id']=mysql_insert_id();
        $this->editSettings($settings_arr);

    }


    function addSettings_iphone($id) {
        $sql= "UPDATE `setting` SET iphone_status='1' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function removeSettings_iphone($id) {
        $sql= "UPDATE `setting` SET iphone_status='' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editSettings($settings_arr) {
        $sql= "UPDATE `setting` SET preferred='0' WHERE  user_id= '".addslashes($_SESSION['user_id'])."' AND  type=".addslashes($settings_arr[0]['type'])."";
        $result= mysql_query($sql) or die(mysql_error());

        $sql= "UPDATE `setting` SET preferred='1' WHERE id=".addslashes($settings_arr[0]['id'])." AND  user_id= '".addslashes($_SESSION['user_id'])."' AND  type=".addslashes($settings_arr[0]['type'])."";
        $result= mysql_query($sql) or die(mysql_error());

        return $effected = mysql_affected_rows();

    }

    function addRss($settings_arr) {

        $sql= "INSERT INTO `setting` (`type`, `value`, `user_id`, `preferred`) VALUES ('2','".addslashes($settings_arr['txtRss'])."','".addslashes($_SESSION['user_id'])."', '0' )";
        $result= mysql_query($sql) or die(mysql_error());

        return $result;
    }

    function editRss($settings_arr) {

        $sql= "UPDATE `setting` SET `value`='".addslashes($settings_arr['txtRss'])."' WHERE `user_id`='".addslashes($_SESSION['user_id'])."' AND `id`='".addslashes($settings_arr['txtRssId'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        return $result;
    }


    function deleteSettings($id) {
        $sql= "DELETE  from `setting` WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function listSettings($type) {

        $sql= "select * from `setting` WHERE user_id =".addslashes($_SESSION['user_id'])." AND type =".addslashes($type)."";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);


    }

    function listSettingsAll($user_id) {

        $sql= "select * from `setting` WHERE user_id =".addslashes($user_id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return  $numrows=mysql_num_rows($result);

    }



    function getSettings($id) {

        $sql= "select * from `setting` WHERE id='".addslashes($id)."' AND user_id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }

    function checkSettings($settings_arr) {

        $sql= "select * from `setting` WHERE type='".addslashes($settings_arr[0]['type'])."' AND value='".addslashes($settings_arr[0]['name'])."'";
        $result= mysql_query($sql) or die(mysql_error());
        $count=mysql_num_rows($result);
        return $count;

    }

    function getPrefered($type) {

        $sql= "select * from `setting` WHERE user_id =".addslashes($_SESSION['user_id'])." AND type =".$type." AND preferred= '1'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        $this->item = $this->helper->_row($result);
        return $this->item;


    }

    function getRssFeed($type) {

        $sql= "select * from `setting` WHERE user_id =".addslashes($_SESSION['user_id'])." AND type =".$type." ";

        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->helper = new Helper();
        return $this->helper->_result($result);


    }

    function setAuth($token,$id) {
        $sql= "UPDATE `setting` SET flickr_auth='".addslashes($token)."' WHERE  user_id= '".addslashes($_SESSION['user_id'])."' AND  id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());

        return $effected = mysql_affected_rows();

    }


    function listSettingsApi($app_id,$type) {

        $sql= "SELECT setting.`type`, setting.value, setting.preferred, `user`.app_id FROM setting Inner Join `user` ON setting.user_id = `user`.id WHERE `user`.app_id =".addslashes($app_id)." AND type =".addslashes($type)."";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);


    }

    function getSettingsFromAPI ($userId) {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);
        $userArr = $userArr[0];

        $appId = 'com.phizuu.hammerhead.'. $userArr['app_id'];

        $url = "http://connect.phizuu.com/api/appconfig/$appId";
        
        $response = file_get_contents($url);
        $settings = json_decode($response);
        if (count($settings->Configs)==0) {
            return false;
        } else {
            return $settings->Configs[0]->Config;
        }
    }

    function saveSettingsFromAPI ($settings) {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);
        $userArr = $userArr[0];
        $appId = '';

        $appId = 'com.phizuu.hammerhead.'. $userArr['app_id'];

        $settingsStr = "appid=$appId&";

        if (isset($settings['PushMusic'])) {
            $settingsStr .= "pushmusic=enabled";
        } else {
            $settingsStr .= "pushmusic=disabled";
        }
        
        if (isset($settings['PushVideos'])) {
            $settingsStr .= "&pushvideos=enabled";
        } else {
            $settingsStr .= "&pushvideos=disabled";
        }

        if (isset($settings['PushPhotos'])) {
            $settingsStr .= "&pushphotos=enabled";
        } else {
            $settingsStr .= "&pushphotos=disabled";
        }

        if (isset($settings['PushTours'])) {
            $settingsStr .= "&pushtours=enabled";
        } else {
            $settingsStr .= "&pushtours=disabled";
        }

        if (isset($settings['PushAlerts'])) {
            $settingsStr .= "&pushalerts=enabled";
        } else {
            $settingsStr .= "&pushalerts=disabled";
        }

        if (isset($settings['PushSounds'])) {
            $settingsStr .= "&pushsounds=enabled";
        } else {
            $settingsStr .= "&pushsounds=disabled";
        }
        //$settingsStr = 'appid=com.phizuu.hammerhead.11&appversion=1.0.1&appname=%22Lange%22&pushmusic=disabled&pushvideos=enabled&pushtours=enabled&pushphotos=enabled&pushalerts=enabled&pushsound=enabled&status=active&development=sandbox&devcertificate=devcert&prodcertificate=prodcert';
        //echo $settingsStr;
        //$settingsStr .= "&appversion=1.0.1&appname=%22Lange%22&status=active&development=sandbox&devcertificate=devcert&prodcertificate=prodcert";
        

        $host = "connect.phizuu.com";
        $path = "/api/appconfig/";
        $referer = '';
        $fp = fsockopen($host, 80);

        //echo $path;

        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
        fputs($fp, "Referer: $referer\r\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($settingsStr) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $settingsStr);

        $result = '';
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
        // close the socket connection:
        //echo $result;
        fclose($fp);

        ;
    }

}

?>