<?php
if(isset($_GET['starting']))
{
	require_once ('../../../config/config.php');
	require_once '../../../config/database.php';
	require_once('../../../controller/db_connect.php');
	require_once('../../../controller/helper.php');
}

require_once('../../../controller/admin_users_controller.php');
require_once('../../../model/admin_users_model.php');
require_once('../../../controller/pagination_controller_users.php');

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){

	$starting=$_GET['starting'];
	

}else{
	$starting=0;
}
$recpage = 10;//number of records per page
$pagename='list_user_management';
$addpage='add_user_management';

$user= new user();
$user_list = $user->listUser($_SESSION['user_id'],$starting,$recpage);
$count=1;

$numrows = $user->listUserAll($_SESSION['user_id']);
$obj = new pagination_class($numrows,$starting,$recpage,$pagename,$addpage);

 if($numrows >0){
 $response='<div id="page_contents">
 <div id="titleBoxNewsBox">
		  <div class="tahoma_14_white" id="umsId">ID</div>
		  <div class="tahoma_14_white" id="umsName">Artist Name </div>
		  <div class="tahoma_14_white" id="umsEmail">Email</div>
		  <div class="tahoma_14_white" id="umsAppId">App ID</div>
		  <div class="tahoma_14_white" id="umsLimit">Video Limit</div>
		  <div class="tahoma_14_white" id="umsLimit">Music Limit</div>
		  <div class="tahoma_14_white" id="umsLimit">Photo Limit</div>
		  <div class="tahoma_14_white" id="umsAppId">Account Status</div>
		  <div class="tahoma_14_white" id="umsAction"></div>
		</div>
';
  	  
      if(sizeof($user_list) >0){
	  foreach($user_list as $lst_user){
 $response .= '
 	<div id="umsArea">
			<div class="tahoma_12_blue" id="umsIdTxt">'.$lst_user -> id.'</div>
			<div class="tahoma_12_blue" id="umsNameTxt">'.$lst_user -> username.'</div>
			<div class="tahoma_12_blue" id="umsEmailTxt">'.$lst_user -> email.'</div>
			<div class="tahoma_12_blue" id="umsAppIdTxt">'.$lst_user -> app_id.'</div>
			<div class="tahoma_12_blue" id="umsLimitTxt">'.$lst_user -> video_limit.'</div>
			<div class="tahoma_12_blue" id="umsLimitTxt">'.$lst_user -> music_limit.'</div>
			<div class="tahoma_12_blue" id="umsLimitTxt">'.$lst_user -> photo_limit.'</div>
			<div class="tahoma_12_blue" id="umsAppIdTxt">'.$lst_user -> status.'</div>
			<div class="tahoma_12_blue" id="umsActionTxt">
				<div id="icon"><a href="#"  onclick="showEdit(\''.addslashes('edit_user_management').'\',\''.$lst_user->id.'\',\''.$starting.'\')"><img src="../../../images/file.png" alt="Edit" border="0" /></a></div>
				<div id="icon"><a href="#"  onclick="showDelete(\''.addslashes('../../../controller/admin_users_add_iphone_controller').'\',\''.$lst_user->id.'\',\'delete\',\''.$starting.'\')" ><img src="../../../images/cross.png" alt="Delete" border="0" /></a></div>
			</div>
	  </div> ';
   
	  $count++;
	  }
	  }
 $response .= '
 <div id="umsArea_pagination">
 <table  border="0" width="100%"><tr>
    <td class="tahoma_12_blue">'.$obj->anchors.'</td>
  </tr>
  <tr>
    <td class="tahoma_12_blue">'.$obj->total.'</td>
  </tr></table>
</div>';
}
else{
$response='<div id="page_contents"><table width="200" border="0">
  <!--<tr>
    <td>Sorry No records found</td>
  </tr>-->
  
  </table>
</div></div>';
}
echo $response;
?>