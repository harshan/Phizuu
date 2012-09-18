<?php
//require_once "../../../controller/helper.php";
include_once  "../../../database/Dao.php";
class home_image_model {
    
    private $dao;

    public function home_image_model() {
        $this->dao = new Dao();
    }
    public function addHomeImage($home_image_arr) {
        
        echo $sql= "insert into `home_image` (`user_id`,`image_url`,`image_url_thumb`,`hot_spot_type`,`module_name`,`link_url`,`top`,`left`,`width`,`height`,`order_no`) VALUES ('".addslashes($home_image_arr['app_id'])."','".addslashes($home_image_arr['image_url'])."','".addslashes($home_image_arr['image_url_thumb'])."','".$home_image_arr['hot_spot_type']."','".$home_image_arr['module_name']."','".$home_image_arr['link_url']."','".$home_image_arr['top']."','".$home_image_arr['left']."','".$home_image_arr['width']."','".$home_image_arr['height']."','".$home_image_arr['order_no']."')";
        $this->dao->query($sql);

    }
    
    public function getAllHomeImagesByAppID($user_id){
        $sql= "select * from `home_image` where `user_id`= $user_id order by `order_no`" ;
//        $result= $this->dao->query($sql);
//        $this->helper = new Helper();
//        return $this->helper->_result($result);
         $res = $this->dao->query($sql);
         return $this->dao->getArray($res);
    }
    public function deleteHomeImage($id) {
        
        $sql= "delete from `home_image` where `id`= $id";
        $this->dao->query($sql);
        return mysql_affected_rows();

    }
    
   public function setOrder($orderedArr) { 
        foreach ($orderedArr as $order=>$id) {
            $sql = "UPDATE `home_image` SET `order_no`='$order' WHERE id='$id'";
            $this->dao->query($sql);
        }
    }
   
    public function GetNoRecoeds($user_id) { 
         $sql = "select id as count from `home_image`  WHERE `user_id`= $user_id";
         $res = $this->dao->query($sql);
         return mysql_num_rows($res);
    }
    
    public function GetDetaultImage($user_id){
        $sql = "select id from `home_image`  WHERE `user_id`= $user_id and default_image=1";
        $res = $this->dao->toObject($sql);
         return $res;

    }
    public function GetDetaultImageById($id){
        $sql = "select * from `home_image`  WHERE `id`= $id";
        $res = $this->dao->query($sql);
        return $this->dao->getArray($res);

    }
    public function SetDefauldImage($id){
         $sql = "update `home_image` set default_image=1 WHERE `id`= $id";
         $this->dao->query($sql);
    }
    public function ReserDefauldImage($user_id){
         $sql = "update `home_image` set default_image=0 WHERE `user_id`= $user_id";
         $this->dao->query($sql);
    }
    public function CheckDefaultImage($user_id){
        $sql = "select * from `home_image`   WHERE `user_id`= $user_id and default_image=1";
        $res = $this->dao->query($sql);
        return mysql_num_rows($res);
    }
}

?>
