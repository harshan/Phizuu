<?php
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_package_controller.php');
require_once('../../../controller/pagination_controller_users.php');
include('../../../model/admin_package_model.php');
include('../../../config/error_config.php');

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){

	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$recpage = 10;//number of records per page
$pagename='list_package_management';
$addpage='add_package_management';


$package= new Package();
$package_list = $package->listPackage($_SESSION['user_id'],$starting,$recpage);
$count=1;

$numrows = $package->listPackageAll();
$obj = new pagination_class($numrows,$starting,$recpage,$pagename,$addpage);

 if($numrows >0){
 $response='<div id="page_contents">
 	  <div id="titleBoxNewsBox">
		  <div class="tahoma_14_white" id="umsId">ID</div>
		  <div class="tahoma_14_white" id="cpPackageType">Package</div>
		  <div class="tahoma_14_white" id="cpLimit">Video Limit </div>
		  <div class="tahoma_14_white" id="cpLimit">Music Limit </div>
		  <div class="tahoma_14_white" id="cpLimit">Photo Limit</div>
		  <div class="tahoma_14_white" id="umsAppId"></div>
	  </div>';
  	  
      if(sizeof($package_list) >0){
	  foreach($package_list as $lst_package){
 $response .= '
 <div id="umsArea">
			<div class="tahoma_12_blue" id="umsIdTxt">'.$lst_package -> id.' </div>
			<div class="tahoma_12_blue" id="cpPackageTxt">'.$lst_package -> name.'</div>
			<div class="tahoma_12_blue" id="cpLimitTxt">'.$lst_package -> video_limit.'</div>
			<div class="tahoma_12_blue" id="cpLimitTxt">'.$lst_package -> music_limit.'</div>
			<div class="tahoma_12_blue" id="cpLimitTxt">'.$lst_package -> photo_limit.'</div>
			<div class="tahoma_12_blue" id="umsAppIdTxt">
				<div id="icon"><a href="#"  onclick="showEdit(\''.addslashes('edit_package_management').'\',\''.$lst_package->id.'\',\''.$starting.'\')"><img src="../../../images/file.png" alt="Edit" border="0" /></a></div>
				<div id="icon"><a href="#"  onclick="showDelete(\''.addslashes('../../../controller/admin_package_add_iphone_controller').'\',\''.$lst_package->id.'\',\'delete\',\''.$starting.'\')" ><img src="../../../images/cross.png" alt="Delete" border="0" /></a></div>
			</div>
	  </div>
';
   
	  $count++;
	  }
	  }
 $response .= '
  <div id="umsArea_pagination">
 <table  border="0"  width="100%"><tr>
    <td>'.$obj->anchors.'</td>
  </tr>
  <tr>
    <td>'.$obj->total.'</td>
  </tr></table>
  </div> </div>
';
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