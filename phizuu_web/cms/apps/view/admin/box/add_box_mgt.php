<?php
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}?>
<form id="addBox" name="addBox" method="get" onSubmit="showHint(this)"> 
	  <div id="lightBlueHeader">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle">Box Accounts</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
     <div id="addMusicBttn2">
	    <div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Box Name </div>
		  <div id="formSinFeild2">
		    <input  type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="box" id="box" /><?php if(isset($_REQUEST['msg_error'])){echo $msg_error;}?>
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Password</div>
		  <div id="formSinFeild2">
		    <input  type="password" class="textFeildBoarder" style="width:227px; height:21px;"  name="password" id="password"/>
		  </div>
		</div>
		<div id="formRow">
		  <div class="tahoma_12_blue" id="formName2">Status </div>
		  <div id="formSinFeild2">
		    <select  name="box_status" class="textfield" style="width:227px">
          <option value="1">Active</option>
          <option value="0">Inactive</option>
	        </select>
           
		  </div>
		</div>
		<div id="formRowButtons">
		  <div class="tahoma_12_blue" id="formName2"></div>
		  <div id="formSinFeild2"><input type="image" src="../../../images/btn_submit.png" name="button" id="button"width="84" height="25"/>&nbsp;&nbsp;<a href="box_mgt.php"><input type="image" src="../../../images/btn_reset.png" name="button" id="button" width="88" height="25" border="0"/></a>
                     <input type="hidden" name="status" id="status" value="add" />
   		  <input type="hidden" name="starting" id="starting" value="<?php echo $starting;?>" />
          </div>
		</div>
 </div>       
</form>

