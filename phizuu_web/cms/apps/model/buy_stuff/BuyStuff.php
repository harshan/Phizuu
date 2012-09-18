<?php
class BuyStuff {
    private $dao;

    public function BuyStuff() {
        $this->dao = new Dao();
    }

    public function  listStuff($userId) {
        $sql = "SELECT * FROM buy_stuff WHERE user_id='$userId' ORDER BY `order`";
        $res = $this->dao->query($sql);
        return $this->dao->getArray($res);
    }

    public function setOrder($orderedArr) { 
        foreach ($orderedArr as $order=>$id) {
            $sql = "UPDATE buy_stuff SET `order`='$order' WHERE id='$id'";
            $this->dao->query($sql);
        }
    }

    public function addStuff($userId, $title, $link) {
        $sql = "SELECT MAX(`order`) FROM buy_stuff WHERE user_id=$userId;";
        $arr = $this->dao->toArray($sql);
        $sql = "INSERT INTO buy_stuff (`title`,`uri`,`user_id`,`order`) VALUES ('$title', '$link', '$userId',{$arr[0][0]}+1);";
        $this->dao->query($sql);
        return mysql_insert_id();
    }
}
?>
