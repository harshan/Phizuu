<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ab_module
 *
 * @author Harshan
 */
class ab_module {
    private $dao;

    public function ab_module() {
        $this->dao = new Dao();
    }
    
    public function  getAllModulesByUser($userId) {
        $sql = "SELECT * FROM ab_modules WHERE user_id='$userId'";
        $res = $this->dao->query($sql);
        return $this->dao->getArray($res);
    }
}

?>
