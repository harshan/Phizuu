<?php
@session_start();
class NewsModel {

    function addNews($news_arr) {

        $sql= "insert into `news` (`title`, `date`, `description`, `user_id`,`iphone_status`) VALUES ('".addslashes($news_arr[0]['title'])."','".addslashes($news_arr[0]['date'])."','".addslashes($news_arr[0]['notes'])."','".addslashes($_SESSION['user_id'])."','1')";

        $result= mysql_query($sql) or die(mysql_error());
        return mysql_insert_id();

    }


    function addNews_iphone($id) {
        $sql= "UPDATE `news` SET iphone_status='1' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function removeNews_iphone($id) {
        $sql= "UPDATE `news` SET iphone_status='' WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function editNews($news_arr) {
        $sql= "UPDATE `news` SET title='".addslashes($news_arr[0]['title'])."',date='".addslashes($news_arr[0]['date'])."', description='".addslashes($news_arr[0]['notes'])."' WHERE id=".addslashes($news_arr[0]['id'])."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function editInlineNews($news_arr) {
        $sql= "UPDATE `news` SET ".addslashes($news_arr[0]['key'])."='".addslashes($news_arr[0]['value'])."' WHERE id=".addslashes($news_arr[0]['id'])."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }

    function deleteNews($id) {
        $sql= "DELETE  from `news` WHERE id=".addslashes($id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return $effected = mysql_affected_rows();

    }


    function listNews($user_id,$starting,$recpage) {

        $sql= "select * from `news` WHERE user_id =".addslashes($user_id)." order by `order` limit $starting, $recpage";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listNewsAll($user_id) {

        $sql= "select * from `news` WHERE user_id =".addslashes($user_id)."";
        $result= mysql_query($sql) or die(mysql_error());
        return  $numrows=mysql_num_rows($result);
    }

    function listAllNews($user_id) {

        $sql= "select * from `news` WHERE user_id =".addslashes($user_id)." order by `order`";
        echo $sql;
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }


    function listAllNewsJson($user_id) {

        $sql= "SELECT `user`.app_id, `user`.id, news.id, news.title, news.`date`, news.description, news.user_id, news.iphone_status FROM `user` Inner Join news ON news.user_id = `user`.id WHERE `user`.app_id='".addslashes($user_id)."' AND  iphone_status ='1' order by `order`, `date` desc";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);
    }


    function getNews($id) {

        $sql= "select * from `news` WHERE id='$id' AND user_id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();

        $this->item = $this->helper->_row($result);
        return $this->item;

    }


    function listBankNews($user_id) {

        $sql= "select * from news WHERE iphone_status !='1' AND user_id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function listIphoneNews($user_id) {

        $sql= "select * from news where iphone_status='1' AND user_id='".addslashes($_SESSION['user_id'])."'";
        $result= mysql_query($sql) or die(mysql_error());

        $this->helper = new Helper();
        return $this->helper->_result($result);

    }

    function updateListNews($list1,$list2) {
        $explode_arr=explode(",",$list1);
        for($x=0; $x<sizeof($explode_arr); $x++) {
            $id=explode("_",$explode_arr[$x]);
            if($id[3] !="") {
                $sql= "UPDATE `news` SET iphone_status='' WHERE id=".addslashes($id[3])."";
                $result= mysql_query($sql) or die(mysql_error());
            }
        }


        $explode_arr2=explode(",",$list2);
        for($y=0; $y<sizeof($explode_arr2); $y++) {
            $id2=explode("_",$explode_arr2[$y]);

            if($id2[3] != "") {
                $sql= "UPDATE `news` SET iphone_status='1' WHERE id=".addslashes($id2[3])."";
                $result= mysql_query($sql) or die(mysql_error());
            }
        }
        return '1';
    }

    function getFeedStatus ($url) {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        $string = curl_exec($ch);
        curl_close($ch);

        if (strpos($string, '<feed') > 0 && strpos($string, '</feed')) {
                return "ATOM";
        } else if (strpos($string, '<channel') > 0 && strpos($string, '</channel')) {
                return "RSS";
        } else {
                return "INV";
        }
    }
    
    
function getNewsTitleById($id) {

        $sql= "select title from news where id=$id";
        $result= mysql_query($sql) or die(mysql_error());
        $row = mysql_fetch_array($result);
        return $row[0];
    }
}

?>