<?php
require_once "../../../model/email_list_model.php";
require_once "../../../config/config.php";


class email_list_controller {
    
    
    
    public static function GetAllEmailListByAppId($appId) {
        
        $emailList = new email_list_model();
        return $emailList ->getAllEmailsByAppId($appId);

    }
    
    public static function GetAllEmailAddressByAppId($appId) {
        
        $emailList = new email_list_model();
        return $emailList ->getAllEmailAddressByAppId($appId);

    }
}
 
?>