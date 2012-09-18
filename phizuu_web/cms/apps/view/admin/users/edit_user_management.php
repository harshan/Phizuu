<?php
include ('../../../config/config.php');
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/admin_package_controller.php');
include('../../../model/admin_package_model.php');
require_once('../../../controller/admin_users_controller.php');
include('../../../model/admin_users_model.php');
include('../../../config/error_config.php');
require_once('../../../controller/admin_box_controller.php');
include('../../../model/admin_box_model.php');

@session_start();
$user= new User();
$user_det = $user->getUser($_GET['id']);
$package= new Package();
$list_package = $package->listPackageAllRecs();

$box= new Box();
$list_box = $box->listBoxAllRecs();

						  
$response ='<div id="addMusicBttn2">
<form id="addUser" name="addUser" method="get" onSubmit="showHint(this)">

     <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Artist Name </div>
		  <div id="formSinFeild2">
		    <input  name="name" id="name"  type="text" class="textFeildBoarder" style="width:227px; height:21px;" value="'.$user_det->username.'"/>';
	  if(isset($_REQUEST['msg_error'])){echo $msg_error;}
	 
	$response .='
		  </div>
		</div>
    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Password</div>
		  <div id="formSinFeild2">
		    <input  name="password" id="password" type="password" class="textFeildBoarder" style="width:227px; height:21px;" value=""/>
		  </div>
		</div>

    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Email</div>
		  <div id="formSinFeild2">
		    <input  name="email" id="email" type="text" class="textFeildBoarder" style="width:227px; height:21px;"  value="'.$user_det->email.'"/>
		  </div>
		</div>
    
    	<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Box Account</div>
		  <div id="formSinFeild2">
		    <select name="boxacc" id="boxacc" class="textfield" style="width:227px">';
		  foreach ($list_box as $box){
			$response .=' <option value="'. $box ->id.'"';
		  if($box ->id == $user_det->box_id){
		  $response .='selected';
		  } 
			$response .='>'.$box ->user.'</option>';
			  }
	$response .='</select>
		  </div>
		</div>

	<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">User Type </div>
		  <div id="formSinFeild2">
		    <select name="usertype" id="usertype" class="textfield" style="width:227px"  onchange="showPackage(this.value)">
		  <option value="">Please Select</option>';
		  foreach ($list_package as $package){
			$response .=' <option value="'. $package ->id.'"';
		  if($package ->id == $user_det->package_id){
		  $response .='selected';
		  } 
			$response .='>'.$package ->name.'</option>';
			  }
	$response .='       </select>
		  </div>
		</div>
    
      <div id="div_user">';
      include("package_det_management.php");
          $response .=' 
              </div>

         <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">App ID </div>
		  <div id="formSinFeild2">
		    <input type="text" name="app_id" id="app_id" style="width:227px; height:21px;" value="'.$user_det->app_id.'" />';
	  if(isset($_REQUEST['msg_error'])){echo $msg_error;}
	  
	    $response .='
		  </div>
		</div>

    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Account Set As </div>
		  <div id="formSinFeild2">
		    <select name="acc_status" id="acc_status" class="textfield" style="width:227px">
		     <option value="1"';
		 if($user_det->status == 1){
					  $response .='selected';
					  }
					   $response .=' >Active</option>
         <option value="0"';
		 if($user_det->status == 0){
					  $response .='selected';
					  }
					  $response .='>Inactive</option>
        </select>
		  </div>
		</div>

    <div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2"><input type="image" src="../../../images/save.png" name="button" id="button"width="83" height="25" onclick="form.submit();"/>&nbsp;&nbsp;<input type="image" src="../../../images/btn_update.png" name="button2" id="button2"width="83" height="25" onclick="form.reset();"/>
      <input type="hidden" name="id" id="id" value="'. $_GET['id'].'" />
      <input type="hidden" name="status" id="status" value="edit" />
	  <input type="hidden" name="starting" id="starting" value="'. $_GET['starting'].'" /></div>
		</div>

</form>
</div>';						  

echo $response;
?>
