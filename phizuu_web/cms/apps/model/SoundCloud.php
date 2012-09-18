<?php
class SoundCloud {
    private $userName;
    private $passWord;
    private $userId;

    private $dao;

    function SoundCloud($userId) {
        $this->dao = new Dao();
        $this->userId = $userId;
    }

/*
 * Fills details from the database. Returns false is there is no data
 */

    function fillData() {
        $sql = "SELECT * FROM sound_cloud_details WHERE user_id = {$this->userId}";
        $res = $this->dao->query($sql);
        $arr = $this->dao->getArray($res);

        if(count($arr)>0) {
            
        } else {
            return FALSE;
        }
    }
}

?>
