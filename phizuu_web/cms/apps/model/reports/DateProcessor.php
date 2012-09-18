<?php
class DateProcessor {
	function getFromDateByText($text) {
		switch ($text) {
			case 'today':
				return $this->_getDateByTimeStamp (strtotime("now"));
				break;
			case 'yesterday':
				return $this->_getDateByTimeStamp (strtotime("-1 days"));
				break;
			case 'last_7_days':
				return $this->_getDateByTimeStamp (strtotime("-7 days"));
				break;
			case 'this_month':
				$month = date('m');
				$year = date('Y');
				return "$year-$month-01";
				break;
			case 'last_month':
				$month = date('m',strtotime("-1 month"));
				$year = date('Y',strtotime("-1 month"));
				return "$year-$month-01";
				break;	
			default:
				throw new Exception("Error converting text to from date");
		}
	}
	
	function getToDateByText($text) {
		switch ($text) {
			case 'last_month':
				return date('Y-m-d',strtotime('-1 second', strtotime(date('m').'/01/'.date('Y').' 00:00:00')));
				break;
		}
	}
	
	function _getDateByTimeStamp($timeStamp) {
		return date("Y-m-d",$timeStamp);
	}
	
	function getThisMonthName() {
		return date ("F");
	}
	
	function getLastMonthName() {
		return date ("F",strtotime("-1 month"));
	}
	
	function getDatesInBetween($to, $from) {
		return (strtotime($to) - strtotime(date($from))) / (60 * 60 * 24);
	}
	
	function getDatesArrayInBetween($from, $to) {
		$toStamp = strtotime($to);
		if ($to==NULL) 
			$toStamp = strtotime('now');
			
		$fromStamp = strtotime($from);
		
		$cnt = 0;
		$currentStamp = $fromStamp;
		$datesArray = array();
		
		while($currentStamp<=$toStamp) {
			$datesArray[$cnt]['name'] = date('M j',$currentStamp);
			$datesArray[$cnt]['date'] = date('Y-m-d',$currentStamp);
			$currentStamp = strtotime('+1 day', $currentStamp);
			$cnt++;
		}
		return $datesArray;
	}
}
?>