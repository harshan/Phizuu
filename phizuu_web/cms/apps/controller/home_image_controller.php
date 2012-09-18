<?php
//session_start();
require_once "../../../config/config.php";
require_once "../../../model/home_image_model.php";


class home_image_controller {
    
    
    
    public static function addHomeImage($home_image_arr) {
        
        $home_image = new home_image_model();
        $home_image ->addHomeImage($home_image_arr);
        
        //header("location:../view/user/home_images/home_images.php");
    }
    
    public static function getAllHomeImagesByAppId($user_id){
        $home_image = new home_image_model();
        return $home_image->getAllHomeImagesByAppID($user_id);
        
    }
    public static function deleteHomeImagesByAppId($id){
        $home_image = new home_image_model();
        return $home_image->deleteHomeImage($id);
        
    }
    
    public static function getNoOfRerodes(){
        $home_image = new home_image_model();
        return $home_image->GetNoRecoeds($_SESSION['user_id']);
    }
    
    public static function getHomeImageById($id){
        $home_image = new home_image_model();
        return $home_image->GetDetaultImageById($id);
    }
   
    
    
}

?>
