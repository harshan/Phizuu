<?php
@session_start();
class LimitFilesModel {

    function getLimit($id,$type) {

        if($type == 'video') {
            $limit_type='package.video_limit';
        }
        else if($type == 'music') {
            $limit_type='package.music_limit,package.music_storage_limit';
        }
        else {
            $limit_type='package.photo_limit';
        }
        $sql= "SELECT user.id, $limit_type FROM
					user Inner Join package ON package.id = user.package_id WHERE user.id='".addslashes($id)."'";
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

}
?>