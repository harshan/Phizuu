<?php
@session_start();
class PicModel {

    function addPics($pic_arr,$play_list, $iphone=false) {
        if ($iphone) {
            $iphone = '1';
        } else {
            $iphone = '0';
        }

        if (isset($pic_arr[0]['image_size'])) {
            $imageSize = $pic_arr[0]['image_size'];
        } else {
             $imageSize = 0;
        }
        
        $sql= "insert into `image` (`name`, `uri`, `thumb_uri`, `playlist`, `user_id`,`pic_id`,`iphone_status`,`file_size`) VALUES ('".addslashes($pic_arr[0]['name'])."','".addslashes($pic_arr[0]['uri'])."','".addslashes($pic_arr[0]['thumb_uri'])."','".addslashes($pic_arr[0]['play_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($pic_arr[0]['pid'])."',$iphone, $imageSize)";

        $result= mysql_query($sql) or die(mysql_error());
        return mysql_insert_id();
    }

    function addAllPics($pic_arr,$play_list) {

        $sql= "insert into `image` (`name`, `uri`, `thumb_uri`, `playlist`, `user_id`,`pic_id`) VALUES ('".addslashes($pic_arr[0]['name'])."','".addslashes($pic_arr[0]['uri']) ."','".addslashes($pic_arr[0]['thumb_uri'])."','".addslashes($pic_arr[0]['play_id'])."','".addslashes($_SESSION['user_id'])."','".addslashes($pic_arr[0]['pid'])."')";

        $result= mysql_query($sql) or die(mysql_error());

    }

    function addPics_iphone($id) {
        $sql= "UPDATE `image` SET iphone_status='1' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function removePics_iphone($id) {
        $sql= "UPDATE `image` SET iphone_status='' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editPics($_POST) {
        $sql= "UPDATE `image` SET name='".addslashes($_POST['name'])."',uri='".addslashes($_POST['uri'])."', thumb_uri='".addslashes($_POST['thumb_uri'])."' WHERE id=".addslashes($_POST['id'])."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function editPicsUri($id,$uri,$thumb_url) {
        $sql= "UPDATE `image` SET uri='".addslashes($uri)."', thumb_uri='".addslashes($thumb_url)."' WHERE id=".addslashes($id)."";
        mysql_query($sql) or die(mysql_error());
        

    }
    
    function editNotifications($appid) {


        $sql1= "select * from `notification` WHERE AppId =".$appid."";
        $result1= mysql_query($sql1) or die(mysql_error());
        $count1=mysql_num_rows($result1);

        if($count1 >0) {
            $sql= "UPDATE `notification` SET NumPhotos=NumPhotos+1, LastUpdateDate='".date('Y-m-d')."' WHERE AppId=".addslashes($appid)."";

        }
        else {
            $sql= "INSERT INTO `notification` ( `AppId`, `NumPhotos`, `LastUpdateDate`) VALUES ('".$appid."',NumPhotos+1,'".date('Y-m-d')."')";

        }
        //2010-01-28

        $result= mysql_query($sql) or die(mysql_error());
    }

    function deletePics($id) {
        $sql= "DELETE  from `image` WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function listBankPics($user_id) {

        $sql= "select * from `image` WHERE iphone_status !='1' AND user_id='".addslashes($_SESSION['user_id'])."' ORDER BY `id`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listIphonePics($user_id) {

        $sql= "select * from `image` where iphone_status='1' AND user_id='".addslashes($_SESSION['user_id'])."' ORDER BY `order`";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }


    function listAllPics($user_id) {

        $sql= "select * from `image` WHERE iphone_status ='1' AND user_id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listAllPicsJson($user_id) {

        $sql= "SELECT `user`.app_id, image.id as img_id, image.name, image.uri, image.thumb_uri, image.playlist, image.user_id, image.pic_id, image.iphone_status, `user`.id FROM `user` Inner Join image ON image.user_id = `user`.id WHERE iphone_status ='1' AND `user`.app_id='".addslashes($user_id)."' ORDER BY `order`";
        
        $filename = "myfile.txt";
        $fh = fopen($filename,'w');
        fwrite($fh, $sql);
        fclose($fh);
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }


    function getPic($id) {

        $sql= "select * from `image` WHERE id='".addslashes($id)."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }
    function getPicByUri($pid) {

        $sql= "select * from `image` WHERE pic_id='".addslashes($pid)."' AND user_id = {$_SESSION['user_id']}";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }


    function updateListPics($list1,$list2) {
        $explode_arr=explode(",",$list1);
        for($x=0; $x<sizeof($explode_arr); $x++) {
            $id=explode("_",$explode_arr[$x]);

            if($id[3] !="") {
                $sql= "UPDATE `image` SET iphone_status='', new_one = 0 WHERE id=".addslashes($id[3])."";
                //echo $sql;
                $result= mysql_query($sql) or die(mysql_error());
            }
        }


        $explode_arr2=explode(",",$list2);
        for($y=0; $y<sizeof($explode_arr2); $y++) {
            $id2=explode("_",$explode_arr2[$y]);

            if($id2[3] != "") {
                $sql= "UPDATE `image` SET iphone_status='1', new_one = 1 WHERE iphone_status = '' AND id=".addslashes($id2[3])."";

                $result= mysql_query($sql) or die(mysql_error());
            }
        }
        return '1';
    }

    //get app id
    function getAppid($id) {

        $sql= "SELECT `user`.app_id FROM `user` Inner Join image ON image.user_id = `user`.id WHERE image.id=".$id."";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }
    
    function getPictureImageById($id){
        $sql= "select name,thumb_uri from image where id= $id";
        $result= mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row;
    }
}

?>