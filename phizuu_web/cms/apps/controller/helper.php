<?php

class Helper{

// Converts a mysql_query result into an array of objects with the column name as properties of the object 
	function _result($object){
			$obj = array();
			while ($row = mysql_fetch_object($object)){
			array_push($obj,$row);
			}
			return $obj;
	}
	
	function _array($object,$str){
			$obj = array();
			while ($row = mysql_fetch_assoc($object)){
			array_push($obj,$row[$str]);
			}
			return $obj;
	}
	
	function _row($object){
			 return mysql_fetch_object($object);
	}
	
	function _updatesql($table, $values, $where)
		{
			foreach($values as $key => $val){
				$str[] = $key." = '".$val."'";
			}	
			return "UPDATE "."`$table`"." SET ".implode(', ', $str)." ".$where;
		}
		
	function _wheresql($values)
		{
		foreach($values as $key => $val){
				$str[] = $key." '".$val."'";
			}	
			return  "AND ".implode(' and ', $str)." ";
		}
}
?>