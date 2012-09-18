<?php
@session_start();
class UserModel{

		function addUser($user_arr,$play_list){
		    $sql= "insert into `user` (`username`, `password`, `app_id`, `email`, `package_id`, `status`, `box_id`) VALUES ('".addslashes($user_arr[0]['username'])."','".addslashes($user_arr[0]['password'])."','".addslashes($user_arr[0]['app_id'])."','".addslashes($user_arr[0]['email'])."','".addslashes($user_arr[0]['package_id'])."','".addslashes($user_arr[0]['status'])."','".addslashes($user_arr[0]['box_id'])."')";
		
		 $result= mysql_query($sql) or die(mysql_error());
		 return $id=mysql_insert_id();
				
		}
		
			function editUser($user_arr){
		$sql= "UPDATE `user` SET username='".addslashes($user_arr[0]['username'])."',password='".addslashes($user_arr[0]['password'])."', app_id='".addslashes($user_arr[0]['app_id'])."' , email='".addslashes($user_arr[0]['email'])."',package_id='".addslashes($user_arr[0]['package_id'])."',status='".addslashes($user_arr[0]['status'])."',box_id='".addslashes($user_arr[0]['box_id'])."' WHERE id=".addslashes($user_arr[0]['id'])."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		function deleteUser($id){
		echo  $sql= "DELETE  from `user` WHERE id=".addslashes($id)."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		
		function listUser($user_id,$starting,$recpage){

		 $sql= "select `user`.id,`user`.username,`user`.package_id,`user`.box_id,`user`.email,`user`.app_id,`user`.status,`package`.video_limit,`package`.music_limit,`package`.photo_limit from `user`,`package` WHERE `user`.package_id= `package`.id  order by id desc limit ".addslashes($starting).", ".addslashes($recpage)."";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}
		
		function listUserAll(){

		  $sql= "select * from `user` ";
		  $result= mysql_query($sql) or die(mysql_error());
		  return  $numrows=mysql_num_rows($result);
		}
		
		function listUserAllRecs(){

		  $sql= "select * from `user` ";
		  $result= mysql_query($sql) or die(mysql_error());
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}	
		
		function getUser($id){

		  $sql= "select * from `user` WHERE id='".addslashes($id)."'";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
		
		$this->item = $this->helper->_row($result);
		return $this->item;
				
		}
			
		function checkAdmin($user_arr){

		$sql= "select * from `user` WHERE username='".addslashes($user_arr[0]['username'])."' OR app_id='".addslashes($app_id)."' ";
		$result= mysql_query($sql) or die(mysql_error());
		$count=mysql_num_rows($result);
		return $count;
				
		}
		
		function checkAppId($app_id){

		$sql= "select * from `user` WHERE app_id='".addslashes($app_id)."' ";
		$result= mysql_query($sql) or die(mysql_error());
		$count=mysql_num_rows($result);
		return $count;
				
		}
		
}
?>