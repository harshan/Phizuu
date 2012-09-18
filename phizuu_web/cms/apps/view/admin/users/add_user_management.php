<?php
if(isset($_GET['starting']) && !isset($_REQUEST['submit'])){

	$starting=$_GET['starting'];
}else{
	$starting=0;

}

if(isset($_REQUEST['id']) && empty($_REQUEST['id'])){
	include ('../../../config/config.php');
	require_once '../../../config/database.php';
	include('../../../controller/db_connect.php');
	include('../../../controller/helper.php');
	require_once('../../../controller/admin_package_controller.php');
	include('../../../model/admin_package_model.php');
	require_once('../../../controller/admin_box_controller.php');
	include('../../../model/admin_box_model.php');
}

@session_start();
$package= new Package();
$list_package = $package->listPackageAllRecs();

$box= new Box();
$list_box = $box->listBoxAllRecs();

$count=1;

?>
<div id="addMusicBttn2">
<form id="addUser" name="addUser" method="get" onSubmit="showHint(this)">

 <div id="formRow">
		  
		  <div id="formSinFeild2" class="tahoma_12_blue_error_bold">
            <?php if(isset($_REQUEST['msg_error'])){echo $_REQUEST['msg_error'];}?>
		  </div>
		</div>

     <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Artist Name </div>
		  <div id="formSinFeild2">
		    <input  name="name" id="name"  type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>

		  </div>
		</div>
    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Password</div>
		  <div id="formSinFeild2">
		    <input  name="password" id="password" type="password" class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>

    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Email</div>
		  <div id="formSinFeild2">
		    <input  name="email" id="email" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
		  </div>
		</div>
    
    	<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Box Account</div>
		  <div id="formSinFeild2">
		    <select name="boxacc" id="boxacc" class="textfield" style="width:227px">
		       <?php foreach ($list_box as $box){?>
             <option value="<?php echo $box ->id; ?>"><?php echo $box ->user; ?></option>
              <?php }?>
	        </select>
		  </div>
		</div>

	<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">User Type </div>
		  <div id="formSinFeild2">
		    <select name="usertype" id="usertype" class="textfield" style="width:227px"  onchange="showPackage(this.value)">
		      <option value="">Please Select</option>
          <?php foreach ($list_package as $package){?>
             <option value="<?php echo $package ->id; ?>"><?php echo $package ->name; ?></option>
              <?php }?>
	        </select>
		  </div>
		</div>
    
      <div id="div_user">
       </div>

         <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">App ID </div>
		  <div id="formSinFeild2">
		    <input type="text" name="app_id" id="app_id" style="width:227px; height:21px;"/>
		  </div>
		</div>

    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Account Set As </div>
		  <div id="formSinFeild2">
		    <select name="acc_status" id="acc_status" class="textfield" style="width:227px">
		      <option value="1">Active</option>
         	  <option value="0">Inactive</option>
              </select>
		  </div>
		</div>

    <div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2"><input type="image" src="../../../images/save.png" name="button" id="button"width="83" height="25"/>&nbsp;&nbsp;<input type="image" src="../../../images/btn_update.png" name="button2" id="button2"width="83" height="25" onclick="form.reset();"/>
          <input type="hidden" name="status" id="status" value="add" />
    <input type="hidden" name="starting" id="starting" value="<?php echo $starting;?>" /></div>
		</div>

</form>
</div>
