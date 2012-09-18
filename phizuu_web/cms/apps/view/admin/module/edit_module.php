<?php
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_module_controller.php');
include('../../../model/admin_module_model.php');
include('../../../config/error_config.php');

@session_start();
$module= new Module();
$module_det = $module->getModule($_GET['id']);

$count=1;

$response ='<form id="addModule" name="addModule"  method="get" onSubmit="showHint(this)">
 <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">App_Id </div>
		  <div id="formSinFeild2"><input type="text" name="module" id="module" value="'.$module_det->username.'" readonly />';
	  if(isset($_REQUEST['msg_error'])){echo $msg_error;}
	 
	$response .='
		  </div>
		</div>
        <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music</div>
		  <div id="formSinFeild2"><input type="checkbox" name="music" id="music" value="1"';
	  if($module_det->music == '1'){
	  $response .=' checked="checked"';
	  }
    $response .='/></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Video</div>
		  <div id="formSinFeild2"><input type="checkbox" name="video" id="video" value="1"';
	  if($module_det->videos == '1'){
	  $response .=' checked="checked"';
	  }
    $response .='/></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Photos</div>
		  <div id="formSinFeild2"><input type="checkbox" name="photo" id="photo" value="1"';
	  if($module_det->photos == '1'){
	  $response .=' checked="checked"';
	  }
    $response .=' /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Flyers </div>
		  <div id="formSinFeild2"><input type="checkbox" name="flyer" id="flyer" value="1" ';
	  if($module_det->flyers == '1'){
	  $response .=' checked="checked"';
	  }
    $response .='/></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">News </div>
		  <div id="formSinFeild2"><input type="checkbox" name="news" id="news" value="1"';
	  if($module_det->news == '1'){
	  $response .=' checked="checked"';
	  }
    $response .='/></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Tours </div>
		  <div id="formSinFeild2"><input type="checkbox" name="tour" id="tour" value="1" ';
	  if($module_det->tours == '1'){
	  $response .=' checked="checked"';
	  }
    $response .='/></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Links </div>
		  <div id="formSinFeild2"><input type="checkbox" name="link" id="link" value="1"';
	  if($module_det->links == '1'){
	  $response .=' checked="checked"';
	  }
    $response .=' /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Settings </div>
		  <div id="formSinFeild2"><input type="checkbox" name="setting" id="setting" value="1"';
	  if($module_det->settings == '1'){
	  $response .=' checked="checked"';
	  }
    $response .=' /></div>
		</div>
		<div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2">
          <input type="image" src="../../../images/btn_submit.png" onclick="addModule.submit()"  width="84" height="25" />
          <!--<img src="../../../images/btn_submit.png" width="84" height="25" />-->&nbsp;&nbsp;
          <a href="module.php"><img src="../../../images/btn_reset.png" width="83" height="25" border="0" /></a>
          <!--<img src="../../../images/btn_reset.png" width="83" height="25" />--></div>
          <input type="hidden" name="id" id="id" value="'. $_GET['id'].'" />
	  <input type="hidden" name="app_id" id="app_id" value="'. $module_det->app_id.'" />
      <input type="hidden" name="status" id="status" value="edit" />
	  <input type="hidden" name="starting" id="starting" value="'. $_GET['starting'].'" />
		</div>


</form>
';
echo $response;
?>
