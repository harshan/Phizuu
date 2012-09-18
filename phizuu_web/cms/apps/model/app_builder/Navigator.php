<?php
class Navigator {
    private $dao;
    private $userId;

    public function Navigator($userId) {
        $this->dao = new Dao();
        $this->userId = $userId;
    }

    public function getCurrentStep() {
        $sql = "SELECT * FROM ab_step WHERE user_id='{$this->userId}'";
        $res = $this->dao->query($sql);
        $arr = $this->dao->getArray($res);
        return $arr[0]['step_count'];
    }

    public function setCurrentStep($step) {
        $sql = "UPDATE `ab_step` SET `step_count` = '$step' WHERE `user_id` = {$this->userId};";
        $res = $this->dao->query($sql);
    }

    public function  isFirstTime() {
        $sql = "SELECT * FROM ab_step WHERE user_id='{$this->userId}'";
        $res = $this->dao->query($sql);
        if (mysql_num_rows($res)==0) {
            $sql = "INSERT INTO `ab_step` (
                    `user_id` ,
                    `step_count`
                   )
                    VALUES (
                    '{$this->userId}', '1'
                    );";
            $this->dao->query($sql);
            return true;
        } else {
            return false;
        }
    }

    public function gotoNextStep() {
        $sql = "UPDATE `ab_step` SET `step_count` = (`step_count`+1) WHERE `user_id` = {$this->userId};";
        $res = $this->dao->query($sql);
    }

    public function gotoPrevStep() {
        $sql = "UPDATE `ab_step` SET `step_count` = (`step_count`-1) WHERE `user_id` = {$this->userId};";
        $res = $this->dao->query($sql);
    }
}
?>
