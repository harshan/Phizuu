<?php
class Links {
    private $dao;

    public function Links() {
        $this->dao = new Dao();
    }

    public function  listLinks($userId) {
        $sql = "SELECT * FROM link WHERE user_id='$userId' ORDER BY `order`";
        $res = $this->dao->query($sql);
        return $this->dao->getArray($res);
    }

    public function setOrder($orderedArr) { 
        foreach ($orderedArr as $order=>$id) {
            echo $sql = "UPDATE link SET `order`='$order' WHERE id='$id'";
            $this->dao->query($sql);
        }
    }

    public function addLink($userId,$title, $link) {
        $sql = "INSERT INTO link (`title`,`uri`,`user_id`) VALUES ('$title', '$link', '$userId')";
        $this->dao->query($sql);
        return mysql_insert_id();
    }
    public function getLinkTitleByName($id) {
        $sql = "select uri from link where id=$id";
        $result = $this->dao->query($sql);
        $row = mysql_fetch_array($result);
        return $row[0];
    }
    
}
?>
