<?php

@session_start();
class MusicModel {

    function addMusic($music_arr) {

        $sql= "insert into `song` (`title`,`playlist`, `user_id`,`music_id`) VALUES ('".addslashes($music_arr[0]['name'])."','".addslashes($music_arr[0]['folder_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($music_arr[0]['file_id'])."')";


        $result= mysql_query($sql) or die(mysql_error());

    }

    function addAllMusic($music_arr) {

        $sql= "insert into `song` (`title`,`playlist`, `user_id`,`music_id`) VALUES ('".addslashes($music_arr[0]['name'])."','".addslashes($music_arr[0]['folder_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($music_arr[0]['file_id'])."')";

        $result= mysql_query($sql) or die(mysql_error());

    }

    function uploadMusic($music_arr) {
        if ($music_arr[0]['app_wizard'])
            $iphoneStatus = '1';
        else
            $iphoneStatus = '';

        $sql= "insert into `song` (`title`, `album`, `duration`, `stream_uri`, `year`, `user_id`, `music_id`,`file_capacity`,`genre`,`iphone_status`) VALUES ('".addslashes($music_arr[0]['name'])."','".addslashes($music_arr[0]['album'])."','".addslashes($music_arr[0]['duration'])."','".addslashes($music_arr[0]['stream_uri'])."','".addslashes($music_arr[0]['year'])."','".addslashes($music_arr[0]['user_id'])."','".addslashes($music_arr[0]['file_id'])."','".addslashes($music_arr[0]['size'])."','".addslashes($music_arr[0]['genre'])."','$iphoneStatus')";

        $result= mysql_query($sql) or die(mysql_error());

        return mysql_insert_id();

    }

    function addMusic_iphone($id) {
        $sql= "UPDATE `song` SET iphone_status='1' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function removeMusic_iphone($id) {
        $sql= "UPDATE `song` SET iphone_status='' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editMusic($_POST) {

        $sql= "UPDATE `song` SET title='".addslashes($_POST['title'])."',album='".addslashes($_POST['album'])."', duration='".addslashes($_POST['duration'])."', stream_uri='".addslashes($_POST['stream_uri'])."', itunes_uri='".addslashes($_POST['itune_uri'])."', year='".addslashes($_POST['year'])."', note='".addslashes($_POST['note'])."', image_id='".addslashes($_POST['pic_selected'])."',image_uri='".addslashes($_POST['pic_uri'])."' WHERE id=".addslashes($_POST['id'])."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function editNotifications($appid) {


        $sql1= "select * from `notification` WHERE AppId =".$appid."";
        $result1= mysql_query($sql1) or die(mysql_error());
        $count1=mysql_num_rows($result1);

        if($count1 >0) {
            $sql= "UPDATE `notification` SET NumMusic=NumMusic+1, LastUpdateDate='".date('Y-m-d')."' WHERE AppId=".addslashes($appid)."";

        }
        else {
            $sql= "INSERT INTO `notification` ( `AppId`, `NumPhotos`, `LastUpdateDate`) VALUES ('".$appid."',NumPhotos+1,'".date('Y-m-d')."')";

        }
        //2010-01-28
        $result= mysql_query($sql) or die(mysql_error());
    }


    function deleteMusic($id) {
        $sql= "DELETE  from `song` WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function listBankMusic($user_id) {

        $sql= "select * from `song` WHERE iphone_status !='1' AND user_id='".addslashes($_SESSION['user_id'])."' ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listIphoneMusic($music_arr) {

        $sql= "select * from `song` where iphone_status='1'  AND user_id='".addslashes($_SESSION['user_id'])."'  ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }



    function listAllMusic($user_id) {

        $sql= "select * from `song` WHERE  user_id='".addslashes($user_id)."' AND  iphone_status ='1'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }


    function listAllMusicJson($user_id) {
        $sql= "SELECT `user`.app_id, `user`.id, song.id, song.title, song.album, song.duration, song.stream_uri, song.itunes_uri, song.android_url, song.`year`, song.note, song.image_id, song.playlist, song.user_id, song.music_id, song.iphone_status, song.file_capacity, song.image_uri, song.itunes_affiliate_url, song.soundcloud_uri, song.category_id FROM `user` Inner Join song ON song.user_id = `user`.id WHERE  `user`.app_id='".addslashes($user_id)."' AND  iphone_status ='1' ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function getSoundCloudSettings() {
        
    }


    function getMusic($id) {

        $sql= "select * from `song` WHERE id='".addslashes($id)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }

    function getMusicStorage($user_id) {

        $sql= "select SUM(file_capacity) from `song` WHERE user_id='".addslashes($user_id)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }
    function getMusicByUri($pid) {

        $sql= "select * from `song` WHERE music_id='".addslashes($pid)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }


    function updateListMusic($list1,$list2) {
        $explode_arr=explode(",",$list1);
        for($x=0; $x<sizeof($explode_arr); $x++) {
            $id=explode("_",$explode_arr[$x]);

            if($id[3] !="") {
                $sql= "UPDATE `song` SET iphone_status='', new_one=0 WHERE id=".addslashes($id[3])."";
                $result= mysql_query($sql) or die(mysql_error());

            }
        }


        $explode_arr2=explode(",",$list2);
        for($y=0; $y<sizeof($explode_arr2); $y++) {
            $id2=explode("_",$explode_arr2[$y]);

            if($id2[3] != "") {

                $sql= "UPDATE `song` SET iphone_status='1', new_one=1 WHERE iphone_status='' AND id=".addslashes($id2[3])."";
                $result= mysql_query($sql) or die(mysql_error());

            }
        }
        return '1';
    }


    function getBoxAccount() {
        $sql= "SELECT `user`.id, `user`.box_id, box.`user`, box.`password` FROM `user` Inner Join box ON `user`.box_id = box.id WHERE `user`.id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }

    //get app id
    function getAppid($id) {

        $sql= "SELECT `user`.app_id FROM `user` Inner Join song ON song.user_id = `user`.id WHERE song.id=".$id."";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }

    function getCoverImage($userId) {
        $sql = "SELECT cover_url FROM `album_cover` WHERE user_id = $userId";
        $result= mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row[0];
    }
    
    function getMusicName($id){
        $sql="select title from song where id=$id";
        $result= mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row[0];
    }

}

?>