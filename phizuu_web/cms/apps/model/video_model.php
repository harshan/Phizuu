<?php
@session_start();
class VideoModel {

    function addVideos($video_arr,$play_list,$iphone=false) {
        if ($iphone) {
            $iphone = '1';
        } else {
            $iphone = '';
        }


        $sql= "insert into `video` (`title`, `duration`, `stream_uri`, `year`, `note`, `playlist`, `user_id`,`video_id`,`thum_uri`,`stream_uri_3gp`,`iphone_status`) VALUES ('".addslashes($video_arr[0]['title'])."','".addslashes($video_arr[0]['duration'])."','".addslashes($video_arr[0]['uri'])."','".addslashes($video_arr[0]['year'])."','".addslashes($video_arr[0]['note'])."','".addslashes($video_arr[0]['play_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($video_arr[0]['vid'])."','".addslashes($video_arr[0]['thumb'])."','".addslashes($video_arr[0]['vid_gp3'])."','$iphone')";

        $result= mysql_query($sql) or die(mysql_error());

        return mysql_insert_id();

    }

    function addAllVideos($video_arr,$play_list) {

        $sql= "insert into `video` (`title`, `duration`, `stream_uri`, `year`, `note`, `playlist`, `user_id`,`video_id`,`thum_uri`,`stream_uri_3gp`) VALUES ('".addslashes($video_arr[0]['title'])."','".addslashes($video_arr[0]['duration'])."','".addslashes($video_arr[0]['uri'])."','".addslashes($video_arr[0]['year'])."','".addslashes($video_arr[0]['note'])."','".addslashes($video_arr[0]['play_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($video_arr[0]['vid'])."','".addslashes($video_arr[0]['thumb'])."','".addslashes($video_arr[0]['vid_gp3'])."')";

        $result= mysql_query($sql) or die(mysql_error());

    }

    function addVideos_iphone($id) {
        $sql= "UPDATE `video` SET iphone_status='5' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function removeVideos_iphone($id) {
        $sql= "UPDATE `video` SET iphone_status='' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editVideos($_POST) {
        $sql= "UPDATE `video` SET title='".addslashes($_POST['title'])."',duration='".addslashes($_POST['duration'])."', year='".addslashes($_POST['year'])."',note='".addslashes($_POST['note'])."' WHERE id=".addslashes($_POST['id'])."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editNotifications($appid) {


        $sql1= "select * from `notification` WHERE AppId =".$appid."";
        $result1= mysql_query($sql1) or die(mysql_error());
        $count1=mysql_num_rows($result1);

        if($count1 >0) {
            $sql= "UPDATE `notification` SET NumVideos=NumVideos+1, LastUpdateDate='".date('Y-m-d')."' WHERE AppId=".addslashes($appid)."";

        }
        else {
            $sql= "INSERT INTO `notification` ( `AppId`, `NumVideos`, `LastUpdateDate`) VALUES ('".$appid."',NumVideos+1,'".date('Y-m-d')."')";

        }
        //2010-01-28

        $result= mysql_query($sql) or die(mysql_error());
    }


    function deleteVideos($id) {
        $sql= "DELETE  from`video` WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function listBankVideos($user_id) {

        $sql= "select * from video WHERE iphone_status !='1' AND user_id='".addslashes($_SESSION['user_id'])."' ORDER BY `id`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listIphoneVideos($user_id) {

        $sql= "select * from video where iphone_status='1' AND user_id='".addslashes($_SESSION['user_id'])."'  ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listAllVideos($user_id) {

        $sql= "select * from video WHERE  user_id='".addslashes($user_id)."'  AND  iphone_status ='1'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listAllVideosJson($user_id) {

        $sql= "SELECT video.app_id, video.id, video.title, video.duration, video.stream_uri, video.`year`, video.note, video.playlist, video.user_id, video.iphone_status, video.video_id, video.thum_uri, video.stream_uri_3gp FROM video Inner Join `user` ON video.user_id = `user`.id  WHERE  `user`.app_id='".addslashes($user_id)."' AND  iphone_status ='1' ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function getVideo($id) {

        $sql= "select * from video WHERE id='".addslashes($id)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }
    function getVideoByUri($vid) {

        $sql= "select * from video WHERE video_id='".addslashes($vid)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }


    function updateListVideos($list1,$list2) {
        $explode_arr=explode(",",$list1);
        for($x=0; $x<sizeof($explode_arr); $x++) {
            $id=explode("_",$explode_arr[$x]);
            if($id[3] !="") {
                $sql= "UPDATE `video` SET iphone_status='',new_one=0 WHERE id=".addslashes($id[3])."";
                $result= mysql_query($sql) or die(mysql_error());
            }
        }


        $explode_arr2=explode(",",$list2);
        for($y=0; $y<sizeof($explode_arr2); $y++) {
            $id2=explode("_",$explode_arr2[$y]);

            if($id2[3] != "") {
                $sql= "UPDATE `video` SET iphone_status='1',  new_one=1 WHERE iphone_status='' AND id=".addslashes($id2[3])."";
                $result= mysql_query($sql) or die(mysql_error());
            }
        }
        return '1';
    }

    //get app id
    function getAppid($id) {

        $sql= "SELECT `user`.app_id FROM `user` Inner Join video ON video.user_id = `user`.id WHERE video.id=".$id."";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }

    function filterVideosByURL($videos) {
        $rtnVideos = array();
        foreach($videos as $video) {
            $sql = "SELECT id FROM video WHERE user_id={$_SESSION['user_id']} AND stream_uri = '{$video['uri']}'";
            $dao = new Dao();
            $res = $dao->query($sql);
            if (mysql_num_rows($res)==0) {
                $rtnVideos[] = $video;
            }
        }
        return $rtnVideos;
    }
    
    function getVideoNameById($id) {
        $sql= "select title,thum_uri from video where id=$id";
        $result= mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row;

    }

}

?>