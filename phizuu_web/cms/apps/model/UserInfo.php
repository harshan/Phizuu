<?php
class UserInfo {
    public $userId;
    public $dao;

    public $FREE_PACKAGE_ID = 1;

    public function  __construct($userId=FALSE) {
        if ($userId == FALSE) {
            $this->userId = $_SESSION['user_id'];
        } else {
            $this->userId = $userId;
        }

        $this->dao = new Dao();
    }

    public function getUserInfo() {
        $sql = "SELECT * FROM `user` WHERE `id` = {$this->userId}";
        $userArr = $this->dao->toArray($sql);
        
        if (count($userArr)>0)
            return $userArr[0];
        else
            return FALSE;
    }

    public static function getUserInfoDirect($userId = FALSE) {
        if ($userId == FALSE) {
            $userId = $_SESSION['user_id'];
        }
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `id` = $userId";
        $userArr = $dao->toArray($sql);

        if (count($userArr)>0)
            return $userArr[0];
        else
            return FALSE;
    }
    
    public static function getUserInfoDirectUsername($userName = FALSE) {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `username` = '".mysql_real_escape_string($userName)."'";
        $userArr = $dao->toArray($sql);

        if (count($userArr)>0)
            return $userArr[0];
        else
            return FALSE;
    }
    public static function getManagerInfoDirectUsername($userName = FALSE) {
        $dao = new Dao();
        $sql = "SELECT * FROM `manager` WHERE `username` = '".mysql_real_escape_string($userName)."'";
        $userArr = $dao->toArray($sql);

        if (count($userArr)>0)
            return $userArr[0];
        else
            return FALSE;
    }

    public static function getUserInfoDirectByAppId($appId) {
        $dao = new Dao();
        $sql = "SELECT * FROM `user` WHERE `app_id` = '$appId'";
        $userArr = $dao->toArray($sql);

        if (count($userArr)>0)
            return $userArr[0];
        else
            return FALSE;
    }

    public function isFreeUser() {
        $userArr = $this->getUserInfo();
        if ($userArr['package_id'] == $this->FREE_PACKAGE_ID) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getLimits() {
        $userArr = $this->getUserInfo();
        $package_id = $userArr['package_id'];
        $sql = "SELECT * FROM package WHERE id = $package_id";

        $limitsArr = $this->dao->toArray($sql);
        return $limitsArr[0];
    }
}
?>
