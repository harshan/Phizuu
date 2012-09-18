<?php
require_once "../../../database/Dao.php";
class email_list_model {
    
    private $dao;

    public function email_list_model() {
        $this->dao = new Dao();
    }
    public function getAllEmailsByAppId($appId) {
         $sql= "select * from `mailing_list` where `app_id`= $appId " ;
         $res = $this->dao->query($sql);
         return $this->dao->getArray($res);
        
    }


}

?>
