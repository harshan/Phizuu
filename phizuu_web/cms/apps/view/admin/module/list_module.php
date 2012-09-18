<?php
if(isset($_GET['starting']))
{
	require_once ('../../../config/config.php');
	require_once '../../../config/database.php';
	require_once('../../../controller/db_connect.php');
	require_once('../../../controller/helper.php');
	require_once('../../../controller/admin_module_controller.php');
	require_once('../../../model/admin_module_model.php');
}

require_once('../../../controller/pagination_controller.php');
require_once('../../../config/error_config.php');

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$recpage = 10;//number of records per page
$pagename='list_module';


$module= new module();
$module_list = $module->listModule($_SESSION['user_id'],$starting,$recpage);
$count=1;

$numrows = $module->listModuleAll();
$obj = new pagination_class($numrows,$starting,$recpage,$pagename);

 if($numrows >0){
 $response='<div id="page_contents">
   <div id="titleBoxAdmin">
			<div class="tahoma_14_white" id="titleLftNum">AppId</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Music</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Video</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Photos</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Flyers</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">News</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Tours</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Links</div>
		  <div class="tahoma_14_white" id="titleLftAdminArea">Settings</div>
                  <div class="tahoma_14_white" id="titleLftAdminArea">Messages</div>
                  <div class="tahoma_14_white" id="titleLftAdminArea">Analytics</div>
		</div>';
  	  
      if(sizeof($module_list) >0){
	  foreach($module_list as $lst_module){
 $response .= '<div id="textBarAdmin">
    <div class="tahoma_12_blue" id="titleLftNum"><strong>'.$lst_module -> app_id.'</strong></div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> music.'</div>
    <div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> videos.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> photos.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> flyers.'</div>
    <div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> news.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> tours.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> links.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> settings.'</div>
            <div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> settings.'</div>
                <div class="tahoma_12_blue" id="titleLftAdminArea">'.$lst_module -> settings.'</div>
	<div class="tahoma_12_blue" id="titleLftAdminArea">
		<div id="icon"><a href="#" onclick="showEdit(\''.addslashes('edit_module').'\',\''.$lst_module->id.'\',\''.$starting.'\')"><img src="../../../images/file.png" alt="Edit" border="0" /></a></div> 
	</div>
  </div>';
   
	  $count++;
	  }
	  }
 $response .= ' <div id="umsArea_pagination">
 <table  border="0"  width="100%">
 <tr>
    <td>'.$obj->anchors.'</td>
  </tr>
  <tr>
    <td>'.$obj->total.'</td>
  </tr></table>
</div>';
}
else{
$response='<div id="page_contents"><table width="200" border="1">
  <!--<tr>
    <td>Sorry No records found</td>
  </tr>-->
  
  </table>
</div>';
}
echo $response;
?>