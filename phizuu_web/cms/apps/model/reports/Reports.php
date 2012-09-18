<?php

require_once ('../../../database/Dao.php');

class Reports {
	private $appId;
	
	public function Reports($appId = NULL) {
		$this->appId = $appId;
	}

	public function findNumTotalUUIDs($from = NULL, $to = NULL, $date = NULL) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
		
		$appendixForAppId = "";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND";
		}
		
		$appendixForDuration = "`connect_date` BETWEEN '$from' AND $toString";
		if ($date != NULL) {
			$appendixForDuration = "`connect_date` = '$date'";
		}
		
		$sql = "SELECT * FROM `an_connections` WHERE $appendixForAppId $appendixForDuration AND module_id IS NULL";
		//echo $sql;
		$res = $dao->query($sql);
		return mysql_num_rows($res);
	}	
	
	public function findNumUniqUUIDs($from = NULL, $to = NULL, $date = NULL) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
		
		$appendixForAppId = "";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND";
		}
		
		$appendixForDuration = "`connect_date` BETWEEN '$from' AND $toString";
		if ($date != NULL) {
			$appendixForDuration = "`connect_date` = '$date'";
		}
		
		$sql = "SELECT * FROM `an_connections` WHERE $appendixForAppId $appendixForDuration GROUP BY `uuid_id`";
		//echo $sql;
		$res = $dao->query($sql);
		return mysql_num_rows($res);
	}
	
	public function findNumNewUUIDs($from = NULL, $to = NULL, $date = NULL) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
		
		$appendixForAppId = "`new_systemwise` = 1";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND `new_appwise` = 1";
		}	
		
		$appendixForDuration = "`connect_date` BETWEEN '$from' AND $toString";
		if ($date != NULL) {
			$appendixForDuration = "`connect_date` = '$date'";
		}
	
		$sql = "SELECT `uuid_id` FROM `an_connections` WHERE $appendixForAppId AND $appendixForDuration";
		//echo $sql;
		$res = $dao->query($sql);
		return mysql_num_rows($res);
	}
	
	public function findOSUsagePercentage($from, $to) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
		
		$appendixForAppId = "";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND";
		}	
	
		$sql = "SELECT COUNT(*), `os_name` FROM `an_connections`, `an_oses` WHERE (`an_connections`.`os_id` = `an_oses`.`os_id`) AND $appendixForAppId `connect_date` BETWEEN '$from' AND $toString AND `module_id` IS NULL GROUP BY `an_connections`.`os_id`";
		$res = $dao->query($sql);
		$osArray = $dao->getArray($res);
		
		$allUsage = 0;
		foreach ($osArray as $item) {
			$allUsage+=$item[0];
		}
		
		$percentages = array();
		foreach ($osArray as $item) {
			$percentages[] = array($item[1], round(($item[0]/$allUsage)*100,2));
		}
		
		return $percentages;	
	}
	
	public function findNumUniqURIAccess($from, $to = NULL) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
		
		$appendixForAppId = "";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND";
		}		
				
		$sql = "SELECT `module_id`, COUNT(*) AS count FROM `an_connections` WHERE $appendixForAppId `connect_date` BETWEEN '$from' AND $toString GROUP BY `module_id`";
		//echo $sql;
		$res = $dao->query($sql);
		$urlArray = $dao->getArray($res);
		
		$sql = "SELECT `module_id`, `module_name` FROM `an_url_module`";
		//echo $sql;
		$res = $dao->query($sql);
		$modules = $dao->getArray($res);
		
		$moduleArray = array();
		foreach ($modules as $module) {
			$moduleArray[$module['module_id']] = str_replace('_',' ',ucfirst($module['module_name']));
		}
		
		$rtn = array();
		
		$cnt = 0;
		foreach ($urlArray as $url) {
			if ($url[0] == '') {
				$rtn[$cnt][0] = "Other";
				$rtn[$cnt][1] = $url[1];
			} else {
				$rtn[$cnt][0] = $moduleArray[$url['module_id']];
				$rtn[$cnt][1] = $url[1];				
			}
			$cnt++;
		}	
		
		return $rtn;
	}
	
	public function findVisitsByLocations($from, $to = NULL, $limit = false) {
		$dao = new Dao();
		
		$toString = "'$to'";
		if ($to == NULL) {
			$toString = "CURDATE()";
		}
                
                
                if ($limit) {
                    $limitText = "LIMIT 0,$limit";
                } else {
                    $limitText = '';
                }
		
		$appendixForAppId = "";
		if ($this->appId != NULL) {
			$appendixForAppId = "`app_id`={$this->appId} AND";
		}	
		
		$sql = "SELECT `an_countries`.`country_name`, COUNT(*) AS count FROM `an_connections`, `an_countries` WHERE $appendixForAppId `connect_date` BETWEEN '$from' AND $toString AND (`an_connections`.`country_code` = `an_countries`.`country_code`) AND `an_connections`.`module_id` IS NULL GROUP BY `an_connections`.`country_code` ORDER BY `count` DESC $limitText";
		$res = $dao->query($sql);
		$locationArray = $dao->getArray($res);
		return $locationArray;
	}	
}
?>