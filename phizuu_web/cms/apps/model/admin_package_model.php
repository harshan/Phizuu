<?php
@session_start();
class PackageModel{

		function addPackage($package_arr,$play_list){
		    $sql= "insert into `package` (`name`, `video_limit`, `music_limit`,`music_storage_limit`, `photo_limit`) VALUES ('".addslashes($package_arr[0]['name'])."','".addslashes($package_arr[0]['video_limit'])."','".addslashes($package_arr[0]['music_limit'])."','".addslashes($package_arr[0]['music_storage_limit'])."','".addslashes($package_arr[0]['photo_limit'])."')";
		
		 $result= mysql_query($sql) or die(mysql_error());
				
		}
		
			function editPackage($package_arr){
		$sql= "UPDATE `package` SET name='".addslashes($package_arr[0]['name'])."',video_limit='".addslashes($package_arr[0]['video_limit'])."', music_limit='".addslashes($package_arr[0]['music_limit'])."', music_storage_limit='".addslashes($package_arr[0]['music_storage_limit'])."' , photo_limit='".addslashes($package_arr[0]['photo_limit'])."' WHERE id=".addslashes($package_arr[0]['id'])."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		function deletePackage($id){
		  $sql= "DELETE  from `package` WHERE id=".addslashes($id)."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		
		function listPackage($user_id,$starting,$recpage){

		 $sql= "select * from `package` order by id desc limit ".addslashes($starting).", ".addslashes($recpage)."";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}
		
		function listPackageAll(){

		   $sql= "select * from `package` ";
		  $result= mysql_query($sql) or die(mysql_error());
		  return  $numrows=mysql_num_rows($result);
		}
		
		function listPackageAllRecs(){

		   $sql= "select * from `package` ";
		  $result= mysql_query($sql) or die(mysql_error());
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}	
		
		function getPackage($id){

		  $sql= "select * from `package` WHERE id='".addslashes($id)."'";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
		
		$this->item = $this->helper->_row($result);
		return $this->item;
				
		}
		
}
?>