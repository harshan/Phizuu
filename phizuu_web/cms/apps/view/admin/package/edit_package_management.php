<?php
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_package_controller.php');
include('../../../model/admin_package_model.php');
include('../../../config/error_config.php');

@session_start();
$package= new Package();
$package_det = $package->getPackage($_GET['id']);
$count=1;

$response ='<form id="addPackage" name="addPackage"  method="get" onSubmit="showHint(this)">
<div id="lightBlueHeader2">	
        <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Create Package</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
	  <div id="addMusicBttn2">
	    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Package Type </div>
		  <div id="formSinFeild2">
		    <input  type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="package" id="package" value="'.$package_det->name.'" />';
			 if(isset($_REQUEST['msg_error']))
			 {
			 $response .= $msg_error;
			 }
		$response .='  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Video Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="v_limit" id="v_limit" value="'.$package_det->video_limit.'"/>
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" class="textFeildBoarder" style="width:227px; height:21px;"  name="m_limit" id="m_limit"  value="'.$package_det->music_limit.'"/>
		  </div>
		</div>
        <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Storage Limit (MB)</div>
		  <div id="formSinFeild2">
		    <input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="m_limit_storage" id="m_limit_storage" value="'.($package_det->music_storage_limit/(1024*1024*1024)).'"/>
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Photo Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="p_limit" id="p_limit" value="'.$package_det->photo_limit.'"/>
		  </div>
		</div>
		<div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2"><input type="image" src="../../../images/save.png" name="button" id="button"width="83" height="25"/> <a href="package_management.php"><input type="image" src="../../../images/cancel.png" name="button" id="button" width="88" height="25" border="0"/></a>
          <input type="hidden" name="id" id="id" value="'. $_GET['id'].'" />
      	  <input type="hidden" name="status" id="status" value="edit" />
	  	  <input type="hidden" name="starting" id="starting" value="'. $_GET['starting'].'" />
          </div>
		</div>
   </div>';

echo $response;
?>
