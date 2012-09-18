<?php
@session_start();
class ModuleModel{

		function addModule($module_arr,$play_list){
		    $sql= "insert into `module` (`app_id`, `music`, `videos`, `photos`, `flyers`, `news`, `tours`, `links`, `settings`) VALUES ('".addslashes($module_arr[0]['app_id'])."','".addslashes($module_arr[0]['music'])."','".addslashes($module_arr[0]['videos'])."','".addslashes($module_arr[0]['photos'])."','".addslashes($module_arr[0]['flyers'])."','".addslashes($module_arr[0]['news'])."','".addslashes($module_arr[0]['tours'])."','".addslashes($module_arr[0]['links'])."','".addslashes($module_arr[0]['settings'])."')";
		
		 $result= mysql_query($sql) or die(mysql_error());
				
		}
		
			function editModule($module_arr){
		$sql= "UPDATE `module` SET app_id='".addslashes($module_arr[0]['app_id'])."',music='".addslashes($module_arr[0]['music'])."', videos='".addslashes($module_arr[0]['videos'])."', photos='".addslashes($module_arr[0]['photos'])."' , flyers='".addslashes($module_arr[0]['flyers'])."', `news`='".addslashes($module_arr[0]['news'])."', `tours`='".addslashes($module_arr[0]['tours'])."', `links`='".addslashes($module_arr[0]['links'])."', `settings`='".addslashes($module_arr[0]['settings'])."' WHERE id=".addslashes($module_arr[0]['id'])."";
		$result= mysql_query($sql) or die(mysql_error());
		return $effected = mysql_affected_rows();
				
		}
		
		function deleteModule($id){
		  $sql= "DELETE  from `module` WHERE id=".addslashes($id)."";
		$result= mysql_query($sql);
		return $effected = mysql_affected_rows();
				
		}
		
		
		function listModule($user_id,$starting,$recpage){

		 $sql= "select * from `module` order by id desc limit ".addslashes($starting).", ".addslashes($recpage)."";
		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}
		
		function listModuleAll(){

		   $sql= "select * from `module` ";
		  $result= mysql_query($sql) or die(mysql_error());
		  return  $numrows=mysql_num_rows($result);
		}
		
		function listModuleAllRecs(){

		   $sql= "select * from `module` ";
		  $result= mysql_query($sql) or die(mysql_error());
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}	
		
		function getModule($id){

		  $sql= "SELECT module.music, module.videos, module.photos, module.app_id, module.news, module.flyers, module.id, module.tours, module.links, module.settings, `user`.username FROM module Inner Join `user` ON module.app_id = `user`.app_id  WHERE module.id='".addslashes($id)."'";

		$result= mysql_query($sql) or die(mysql_error());
		
		$this->helper = new Helper();
		
		$this->item = $this->helper->_row($result);
		return $this->item;
				
		}
		
		function listModuleUser(){

		  $sql= "SELECT `user`.username, `user`.app_id FROM `user` WHERE NOT EXISTS (SELECT app_id FROM module WHERE `user`.app_id = module.app_id)";
		  $result= mysql_query($sql) or die(mysql_error());
		$this->helper = new Helper();
				return $this->helper->_result($result);
				
		}	
			
		
}
?>