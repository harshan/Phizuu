<?php
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_box_controller.php');
require_once('../../../controller/pagination_controller_users.php');
include('../../../model/admin_box_model.php');
include('../../../config/error_config.php');

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$recpage = 10;//number of records per page
$pagename='list_box_mgt';
$addpage='add_box_mgt';


$box= new Box();
$box_list = $box->listBox($_SESSION['user_id'],$starting,$recpage);
$count=1;

$numrows = $box->listBoxAll();
$obj = new pagination_class($numrows,$starting,$recpage,$pagename,$addpage);

 if($numrows >0){


  $response='<div id="page_contents">
  <div id="lightBlueHeader">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle">User Accounts</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
	  	<div id="titleBox">
		  <div class="tahoma_14_white" id="titleLft">Username</div>
		  <div class="tahoma_14_white" id="durationLft"></div>
		 </div>';

  	  
      if(sizeof($box_list) >0){
	  foreach($box_list as $lst_box){
 $response .= '
 <div id="textBar">
			<div class="tahoma_12_blue" id="titleLftNum"><strong>'.$lst_box -> id.'</strong></div>
			<div class="tahoma_12_blue" id="titleLftAdmin">'.$lst_box -> user.'</div>
			<!--<div class="tahoma_14_white" id="durationLft2"><span class="tahoma_12_blue">3:10</span></div>-->
			<div class="tahoma_12_blue" id="noteLft">
			<div id="icon"><a href="#"  onclick="showEdit(\''.addslashes('edit_box_mgt').'\',\''.$lst_box->id.'\',\''.$starting.'\')"><img src="../../../images/file.png" alt="Edit" border="0" /></a></div>
				<div id="icon"><a href="#"  onclick="showDelete(\''.addslashes('../../../controller/admin_box_add_iphone_controller').'\',\''.$lst_box->id.'\',\'delete\',\''.$starting.'\')" ><img src="../../../images/cross.png" alt="Delete" border="0" /></a></div> 
				</div>
		</div>';
   
	  $count++;
	  }
	  }
 $response .= ' <div id="umsArea_pagination">
 <table  border="0" width="100%"><tr>
    <td>'.$obj->anchors.'</td>
  </tr>
  <tr>
    <td>'.$obj->total.'</td>
  </tr></table>
</div></div>';
}
else{

$response='<div id="page_contents"><table width="200" border="0">
  <!--<tr>
    <td>Sorry No records found</td>
  </tr>-->
  
  </table>
</div>';
}

echo $response;
?>