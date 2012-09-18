<?php

@session_start();

class ToursModel {

    function addTours($tours_arr) {
        $dao = new Dao();
        $sql = "SELECT MAX(`order`) AS maxOrder FROM tour WHERE user_id={$_SESSION['user_id']}";
        $result = mysql_query($sql) or die(mysql_error());
        $maxOrder = mysql_fetch_array($result);
        $maxOrder = $maxOrder['maxOrder'] + 1;

        $sql = "INSERT INTO `tour` (`name`, `date`, `location`, `description`, `user_id`,`ticket_url`, `iphone_status`, `new_one`, `order`) VALUES ('" . addslashes($tours_arr[0]['name']) . "','" . addslashes($tours_arr[0]['date']) . "','" . addslashes($tours_arr[0]['location']) . "','" . addslashes($tours_arr[0]['notes']) . "','" . addslashes($_SESSION['user_id']) . "','{$tours_arr[0]['ticketURL']}', '1',1,$maxOrder)";

        $result = mysql_query($sql) or die(mysql_error());
        $tourId = $insert_id = mysql_insert_id();

        $this->updateTourImage($tourId, $tours_arr[0]['flyerFileName']);


        return $insert_id = mysql_insert_id();
    }

    function updateTourImage($tourId, $imageName) {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
        $res = $dao->query($sql);
        $userArr = $dao->getArray($res);
        $userArr = $userArr[0];

        $appId = $userArr['app_id'];
        require_once "../config/app_key_values.php";
        $domain = $_SERVER["SERVER_NAME"];
        if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL;
            
        } elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
            $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL;
           
        } else {
            $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL ;
            
        }
        
        $dirName = "../../../images/tour_images/$appId/";

        if (!is_dir($dirName))
            mkdir($dirName);

        $baseURL = "$callbackURL/images/tour_images/$appId/";

        $thumbWidth = 104;
        $thumbHeight = 121;

        $imageHeight = 480;
        $imageWidth = 220;

        $flyerFileName = "flyer_$tourId.jpg";
        $thumbFileName = "thumb_$tourId.jpg";

        if ($imageName != '') {
            $image = new SimpleImage();
            $image->load($imageName);

            $oWidth = $image->getWidth();
            $oHeight = $image->getHeight();

            if ($oWidth < $imageWidth && $oHeight < $imageHeight) {
                $image->save($dirName . $flyerFileName, IMAGETYPE_JPEG, 80);
            } else {

                $tH = $imageHeight;
                $tW = ($oWidth / $oHeight) * $tH;

                if ($tW > $imageWidth) {
                    $tW = $imageWidth;
                    $tH = ($oHeight / $oWidth) * $tW;
                }
                $image->resize($tW, $tH);
                $image->save($dirName . $flyerFileName, IMAGETYPE_JPEG, 80);
            }

            $oWidth = $image->getWidth();
            $oHeight = $image->getHeight();

            $tH = $thumbHeight;
            $tW = ceil(($oWidth / $oHeight) * $tH);
            $y = 0;
            $x = ceil(($tW - $thumbWidth) / 2);
            if ($tW < $thumbWidth) {
                $tW = $thumbWidth;
                $tH = ($oHeight / $oWidth) * $tW;
                $y = ceil(($tH - $thumbHeight) / 2);
                $x = 0;
            }
            $image->resize($tW, $tH);

            $newImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
            //echo $tW.",".$tH;
            //imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h)
            //imagecopyresampled($newImage, $image->image,0,0,$x,$y,$thumbWidth, $thumbHeight,$thumbWidth, $thumbHeight);
            imagecopy($newImage, $image->image, 0, 0, $x, $y, $thumbWidth, $thumbHeight);

            $image->image = $newImage;
            $image->save($dirName . $thumbFileName, IMAGETYPE_JPEG, 90);

            $thumbURL = $baseURL . $thumbFileName;
            $flyerURL = $baseURL . $flyerFileName;
        } else {
            $default = $this->getDefaultImage($_SESSION['user_id']);
            $thumbURL = $default[1];
            $flyerURL = $default[0];
        }


        $sql = "UPDATE tour SET `thumb_url`='$thumbURL', `flyer_url`='$flyerURL' WHERE id='$tourId'";

        $result = mysql_query($sql) or die(mysql_error());
    }

    function addTours_iphone($id) {
        $sql = "UPDATE `tour` SET iphone_status='1' WHERE id=" . addslashes($id) . "";
        $result = mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();
    }

    function removeTours_iphone($id) {
        $sql = "UPDATE `tour` SET iphone_status='' WHERE id=" . addslashes($id) . "";
        $result = mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();
    }

    function editTours($tours_arr) {
        $sql = "UPDATE `tour` SET name='" . addslashes($tours_arr[0]['name']) . "',date='" . addslashes($tours_arr[0]['date']) . "', description='" . addslashes($tours_arr[0]['notes']) . "' WHERE id=" . addslashes($tours_arr[0]['id']) . "";
        $result = mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();
    }

    function editInlineTours($tours_arr) {
        $sql = "UPDATE `tour` SET " . $tours_arr[0]['key'] . "='" . addslashes($tours_arr[0]['value']) . "' WHERE id=" . addslashes($tours_arr[0]['id']) . "";
        $result = mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();
    }

    function deleteTours($id) {
        $sql = "DELETE  from `tour` WHERE id=" . addslashes($id) . "";
        $result = mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();
    }

    function listTours($user_id, $starting, $recpage, $hideOld = FALSE) {
        $where = '';
        if ($hideOld == TRUE) {
            $where = 'AND DATEDIFF(NOW(),`date`)<=31';
        }

        $sql = "select * from `tour` WHERE user_id =" . addslashes($user_id) . " $where order by `order`,`date` limit " . addslashes($starting) . ", " . addslashes($recpage) . "";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function listToursAll($user_id) {

        $sql = "select * from `tour` WHERE user_id =" . addslashes($user_id) . " ORDER BY `order`";
        $result = mysql_query($sql) or die(mysql_error());
        return $numrows = mysql_num_rows($result);
    }

    function listAllTours($user_id) {

        $sql = "select * from `tour` WHERE user_id =" . addslashes($user_id) . " order by date desc";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function listAllToursJson($user_id) {

        $sql = "SELECT `user`.app_id, `user`.id, tour.id, tour.name, tour.`date`, location, tour.description, tour.ticket_url, tour.thumb_url, tour.flyer_url, tour.user_id, tour.registerations, tour.iphone_status FROM `user` Inner Join tour ON tour.user_id = `user`.id WHERE  `user`.app_id='" . addslashes($user_id) . "' AND  iphone_status ='1' order by `order`,`date`";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function getTours($id) {

        $sql = "select * from `tour` WHERE id='" . addslashes($id) . "' AND user_id='" . addslashes($_SESSION['user_id']) . "'";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;
    }

    function listBankTours($user_id) {

        $sql = "select * from tour WHERE iphone_status !='1' AND user_id='" . addslashes($_SESSION['user_id']) . "'";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function listIphoneTours($user_id) {

        $sql = "select * from tour where iphone_status='1' AND user_id='" . addslashes($_SESSION['user_id']) . "'";
        $result = mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }

    function updateListTours($list1, $list2) {
        $explode_arr = explode(",", $list1);
        for ($x = 0; $x < sizeof($explode_arr); $x++) {
            $id = explode("_", $explode_arr[$x]);
            if ($id[3] != "") {
                $sql = "UPDATE `tour` SET iphone_status='',new_one=0 WHERE id=" . addslashes($id[3]) . "";
                $result = mysql_query($sql) or die(mysql_error());
            }
        }


        $explode_arr2 = explode(",", $list2);
        for ($y = 0; $y < sizeof($explode_arr2); $y++) {
            $id2 = explode("_", $explode_arr2[$y]);

            if ($id2[3] != "") {
                $sql = "UPDATE `tour` SET iphone_status='1', new_one=1 WHERE iphone_status='' AND id=" . addslashes($id2[3]) . "";
                $result = mysql_query($sql) or die(mysql_error());
            }
        }
        return '1';
    }

    function deleteAllTours() {
        $sql = "DELETE FROM `tour` WHERE user_id = {$_SESSION['user_id']}";
        $dao = new Dao();
        $result = $dao->query($sql);
    }

    function getDefaultImage($userId) {
        $sql = "SELECT url, thumb FROM `tour_default_image` WHERE user_id = $userId";
        $result = mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row;
    }

    function getTourNameById($id) {
        $sql = "SELECT name FROM `tour` WHERE id = $id";
        $result = mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row[0];
    }

}

?>