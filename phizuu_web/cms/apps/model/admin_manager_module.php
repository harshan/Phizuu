<?php

require_once "../../../controller/helper.php";
class adminManagerModel {

    function getAllManagers() {
        $sql = "select * from manager ";
        $result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
				return $this->helper->_result($result);
    }

}

?>
