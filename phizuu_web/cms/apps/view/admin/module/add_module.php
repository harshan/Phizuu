<?php
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}

$module= new Module();
$list_mod_users = $module->listModuleUser();

?>
<form id="addModule" name="addModule" method="get" onSubmit="showHint(this)">
  <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">App_Id </div>
		  <div id="formSinFeild2">
		    <select  name="app_id" id="app_id" class="textfield" style="width:227px">
		      <?php foreach ($list_mod_users as $mod_users){?>
             <option value="<?php echo $mod_users ->app_id; ?>"><?php echo $mod_users ->app_id." - ".$mod_users ->username; ?></option>
              <?php }?>
	        </select>
		  </div>
		</div>
        <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Music</div>
		  <div id="formSinFeild2"><input type="checkbox" name="music" id="music" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Video</div>
		  <div id="formSinFeild2"><input type="checkbox" name="video" id="video" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Photos</div>
		  <div id="formSinFeild2"><input type="checkbox" name="photo" id="photo" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Flyers </div>
		  <div id="formSinFeild2"><input type="checkbox" name="flyer" id="flyer" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">News </div>
		  <div id="formSinFeild2"><input type="checkbox" name="news" id="news" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Tours </div>
		  <div id="formSinFeild2"><input type="checkbox" name="tour" id="tour" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Links </div>
		  <div id="formSinFeild2"><input type="checkbox" name="link" id="link" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Settings </div>
		  <div id="formSinFeild2"><input type="checkbox" name="setting" id="setting" value="1" /></div>
		</div>
 		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Messages </div>
		  <div id="formSinFeild2"><input type="checkbox" name="messages" id="messages" value="1" /></div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Analytics </div>
		  <div id="formSinFeild2"><input type="checkbox" name="analytics" id="analytics" value="1" /></div>
		</div>
		<div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2">

          <input type="image" src="../../../images/btn_submit.png" onclick="addModule.submit()"  width="84" height="25" />
          &nbsp;&nbsp;
          <a href="module.php"><img src="../../../images/btn_reset.png" width="83" height="25" border="0" /></a>
          </div>
          <input type="hidden" name="status" id="status" value="add" />
    	  <input type="hidden" name="starting" id="starting" value="<?php echo $starting;?>" />
		</div>
</form>

