<?php
class LogFileEntry {

    private $connectionId;
    private $ip;
    private $date;
    private $time;
    private $url;
    private $os;
    private $uuid;
    private $appId;
    private $locationArray;
    private $ip2c;
    
    public function getIP() {
        return $this->ip;
    }

    public function setLocationArray($array) {
        $this->locationArray = $array;
    }

    public function setConnectionId($id) {
        $this->connectionId = $id;
    }

    // Constructs object with all the information
    public function LogFileEntry($ip = NULL, $date = NULL, $time = NULL, $url = NULL, $os = NULL, $uuid = NULL, $appId = NULL) {
        $this->ip = $ip;
        $this->date = $date;
        $this->time = $time;
        $this->url = $url;
        $this->os = $os;
        $this->uuid = $uuid;
        $this->appId = $appId;

        $this->ip2c = new ip2country();
    }

    public function addToDatabase() {
        $dao = new Dao();

        $osId = $this->_retrieveOSId();
        $uuidIdArr = $this->_retrieveUUIDId();
        $uuidId = $uuidIdArr[0];
        $newSystemWise = $uuidIdArr[1];

        $sql = "SELECT `os_id` FROM `an_oses` WHERE `os_name` = '{$this->os}'";
        //echo $sql;
        $res = $dao->query($sql);

        $osId = NULL;
        if (mysql_num_rows($res) > 0) { // The OS is already in the system
            $row = mysql_fetch_array($res);
            $osId = $row['os_id'];
        } else { // The OS is not in the system, we need to add new one
            $sql = "INSERT INTO `an_oses` (`os_name`) VALUES ('{$this->os}')";
            $res = $dao->query($sql);
            $osId = mysql_insert_id();
        }


        // Get the OS id from the system

        $sql = "SELECT `os_id` FROM `an_oses` WHERE `os_name` = '{$this->os}'";
        //echo $sql;
        $res = $dao->query($sql);

        $osId = NULL;
        if (mysql_num_rows($res) > 0) { // The OS is already in the system
            $row = mysql_fetch_array($res);
            $osId = $row['os_id'];
        } else { // The OS is not in the system, we need to add new one
            $sql = "INSERT INTO `an_oses` (`os_name`) VALUES ('{$this->os}')";
            $res = $dao->query($sql);
            $osId = mysql_insert_id();
        }


        // Get module text from URL
        $moduleId = 'NULL';
        $parts = explode('/', $this->url);
        if ($parts[3] != '') {
            $moduleId = $this->_retrieveModuleNameId($parts[3]);
            $moduleId = "'$moduleId'";
        }

        // Get IP location
        $countryCode = $this->ip2c->get_country_code($this->ip);

        $sql = "INSERT INTO `an_connections` (
					`connection_id`,
					`app_id`,
					`uuid_id`, 
					`ip_address`, 
					`connect_date`, 
					`connect_time`, 
					`url`, 
					`os_id`, 
					`new_appwise`,
					`new_systemwise`,
					`country_code`,
					`module_id`
				) VALUES (
					NULL, 
					'{$this->appId}',
					'{$uuidId}', 
					'{$this->ip}', 
					'{$this->date}', 
					'{$this->time}', 
					'{$this->url}', 
					'{$osId}',
					{$this->_isNewUUID(true, $uuidId)},
					{$newSystemWise},
					'$countryCode',
					{$moduleId} 
				)";

        $res = $dao->query($sql);

        if ($res) { // If insertion successfull return the connection_id
            return mysql_insert_id();
        } else {
            return false;
        }
    }

    // Get the OS id from the system
    public function _retrieveOSId() {
        $dao = new Dao();


        $sql = "SELECT `os_id` FROM `an_oses` WHERE `os_name` = '{$this->os}'";
        $res = $dao->query($sql);

        if (mysql_num_rows($res) > 0) { // The OS is already in the system
            $row = mysql_fetch_array($res);
            return $row['os_id'];
        } else { // The OS is not in the system, we need to add new one
            $sql = "INSERT INTO `an_oses` (`os_name`) VALUES ('{$this->os}')";
            $res = $dao->query($sql);
            return mysql_insert_id();
        }
    }

    // Get the UUID id from the system
    public function _retrieveUUIDId() {
        $dao = new Dao();


        $sql = "SELECT `uuid_id` FROM `an_uuids` WHERE `uuid` = '{$this->uuid}'";
        $res = $dao->query($sql);

        if (mysql_num_rows($res) > 0) { // The UUID is already in the system
            $row = mysql_fetch_array($res);
            return array($row['uuid_id'],0);
        } else { // The UUID is not in the system, we need to add new one
            $sql = "INSERT INTO `an_uuids` (`uuid`) VALUES ('{$this->uuid}')";
            $res = $dao->query($sql);
            return array(mysql_insert_id(),1);
        }
    }

    // Get the module id from the system
    public function _retrieveModuleNameId($moduleName) {
        $dao = new Dao();

        $sql = "SELECT `module_id` FROM `an_url_module` WHERE `module_name` = '{$moduleName}'";
        $res = $dao->query($sql);

        if (mysql_num_rows($res) > 0) { // The UUID is already in the system
            $row = mysql_fetch_array($res);
            return $row['module_id'];
        } else { // The UUID is not in the system, we need to add new one
            $sql = "INSERT INTO `an_url_module` (`module_name`) VALUES ('{$moduleName}')";
            $res = $dao->query($sql);
            return mysql_insert_id();
        }
    }

    //Adds IP location to a record. Connection ID and loctionArray should be set before call
    public function addIPLocation() {
        $dao = new Dao();

        $sql = "UPDATE `an_connections` SET
					`country_code` = '{$this->locationArray}'
				WHERE `connection_id` = {$this->connectionId}";
        //echo ($sql);
        $res = $dao->query($sql);
    }

    private function _isNewUUID($appWise, $uuidId) {
        $dao = new Dao();
        $appIdText = "";

        if ($appWise) {
            $appIdText = "`app_id` = '{$this->appId}' AND";
        }

        $sql = "SELECT `uuid_id` FROM `an_connections` WHERE $appIdText `uuid_id` = '$uuidId'";
        $res = $dao->query($sql);
        if (mysql_num_rows($res) > 0)
            return 0;
        else
            return 1;
    }

    private function _getCountryCode($ip) {
        $apiURL = "http://api.ipinfodb.com/v3/ip-city/?key=08fd75e3b802f687ebe9da4453ec21db3ae63801074d163603edb80f82d1a1f6&ip=$ip&format=json";
        $json = json_decode(file_get_contents($apiURL));

        if (isset ($json->countryCode) && $json->countryCode!="") {
            return $json->countryCode;
        } else {
            return "UN";
        }
    }

}
