<?php
if(isset($_GET['p_id']))
{
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_package_controller.php');
include('../../../model/admin_package_model.php');
include('../../../config/error_config.php');
}
@session_start();
$package= new Package();
if(!empty($user_det->package_id)){
$_GET['p_id']=$user_det->package_id;
}

$package_det = $package->getPackage($_GET['p_id']);

if(empty($user_det->package_id)){
?>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Video Limit</div>
		  <div id="formSinFeild2">
		    <input class="textFeildBoarder" style="width:227px; height:21px;" type="text" name="v_limit" id="v_limit" value="<?php echo $package_det-> video_limit;?>" readonly />
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="m_limit" id="m_limit" value="<?php echo $package_det-> music_limit;?>" readonly class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>
        <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Storage Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="m_limit_storage" id="m_limit_storage" value="<?php echo ($package_det-> music_storage_limit/(1024*1024*1024));?>" readonly />(MB)
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Photo Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="p_limit" id="p_limit" value="<?php echo $package_det-> photo_limit;?>" readonly  class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>
<? } else{
$response .='
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Video Limit</div>
		  <div id="formSinFeild2">
		    <input class="textFeildBoarder" style="width:227px; height:21px;" type="text" name="v_limit" id="v_limit"  value="'.$package_det-> video_limit.'" readonly />
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="m_limit" id="m_limit" value="'.$package_det-> music_limit.'" readonly class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music Storage Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="m_limit_storage" id="m_limit_storage" value="'.($package_det-> music_storage_limit/(1024*1024*1024)).'" readonly class="textFeildBoarder" style="width:227px; height:21px;"/>(MB)
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Photo Limit</div>
		  <div id="formSinFeild2">
		    <input type="text" name="p_limit" id="p_limit" value="'.$package_det-> photo_limit.'" readonly  class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>';
}
?>