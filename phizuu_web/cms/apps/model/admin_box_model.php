<?php

@session_start();
class BoxModel{

		function addBox($box_arr,$play_list){
		    $sql= "insert into `box` (`user`, `password`, `status`) VALUES ('".addslashes($box_arr[0]['name'])."','".addslashes($box_arr[0]['password'])."','".addslashes($box_arr[0]['status'])."')";
		
		 $result= mysql_query($sql) or die(mysql_error());
				
		}
		
		function editBox($box_arr){
		$sql= "UPDATE `box` SET user='".addslashes($box_arr[0]['name'])."',password='".addslashes($box_arr[0]['password'])."', status='".addslashes($box_arr[0]['status'])."' WHERE id=".addslashes($box_arr[0]['id'])."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		function deleteBox($id){
		  $sql= "DELETE  from `box` WHERE id=".addslashes($id)."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		
		function listBox($user_id,$starting,$recpage){

		 $sql= "select * from `box` order by id desc limit ".addslashes($starting).", ".addslashes($recpage)."";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}
		
		function listBoxAll(){

		   $sql= "select * from `box` ";
		  $result= mysql_query($sql) or die(mysql_error());
		  return  $numrows=mysql_num_rows($result);
		}
		
		function listBoxAllRecs(){

		   $sql= "select * from `box` ";
		   $result= mysql_query($sql) or die(mysql_error());
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}	
		
		function getBox($id){

		  $sql= "select * from `box` WHERE id='".addslashes($id)."'";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
		
		$this->item = $this->helper->_row($result);
		return $this->item;
				
		}
		
		function checkBox($box_arr){

		$sql= "select * from `box` WHERE user='".addslashes($box_arr[0]['name'])."' ";
		$result= mysql_query($sql) or die(mysql_error());
		$count=mysql_num_rows($result);
		return $count;
				
		}
		
}
?>