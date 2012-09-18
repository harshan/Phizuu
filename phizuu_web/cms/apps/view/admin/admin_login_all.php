<?php include('../../config/error_config.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../common/auto_suggesions/js/bsn.AutoSuggest_2.1.3.js" charset="utf-8"></script>
<link rel="stylesheet" href="../../common/auto_suggesions/css/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />
</head>
	

<body>
<div id="loginWideDivSign">
  <div id="middleDiv2">
  	<div>
	  <div id="logoContainer"><a href="index2.html"></a></div>
    </div>
<!--  	<div id="navigator"></div>-->
  	<div id="buttonContainerSign">
            <div style="text-align: center;width: 350px;margin-bottom: 30px"><img src="../../images/logo.png"/></div>
  	  <div id="adminLoginArea">
			<div id="adminLoginSide"><img src="../../images/ashLft.png" width="15" height="330" /></div>
			<div id="adminLoginCenter">
				
                                <div id="adminLoginBody" style="margin-top: 8px">
                <form action="../../controller/admin_login_controller.php" method="post">
                                         <div class="tahoma_18_white" id="userLoginMidBar">User Login </div>
                        <div class="line"></div>
					<div id="feildRowLoggin">
					<div class="tahoma_12_blue" id="nameLoggin">Username</div>
					<div id="logginTextFeild" style="color: #d4d4d4;font-family: arial;font-size: 11px"><input name="username" type="text" class="textFeildBoarder" style="width:230px;height:30px;background: url('../../images/textbox.png') no-repeat  top left;border: 0px;color:#A5A1A0;"/><?php if(isset($_REQUEST['msg_error_user'])){echo $_REQUEST['msg_error_user'];}?></div>
					</div>
					<div id="feildRowLoggin">
					<div class="tahoma_12_blue" id="nameLoggin">Password</div>
					<div id="logginTextFeild" style="color: #d4d4d4;font-family: arial;font-size: 11px"><input name="password" type="password" class="textFeildBoarder" style="width:230px;height:30px;background: url('../../images/textbox.png') no-repeat  top left;border: 0px;color:#A5A1A0;"/><?php if(isset($_REQUEST['msg_error_pwd'])){echo $_REQUEST['msg_error_pwd'];}?></div>
					</div>
                    <div id="feildRowLoggin" style="margin-bottom: 20px">
					<div class="tahoma_12_blue" id="nameLoggin">Login As:</div>
                                        <div id="logginTextFeild" style="color: #d4d4d4;font-family: arial;font-size: 11px"><input name="loginas" type="text" class="textFeildBoarder" style="width:230px;height:30px;background: url('../../images/textbox.png') no-repeat  top left;border: 0px;color:#A5A1A0;" title="Type a part of the username (Auto suggestions)" id="testinput"/><input type="hidden" id="testid" value="" style="font-size: 10px; width: 20px;" disabled="disabled" /><br/><?php if(isset($_REQUEST['msg_error_other_user'])){echo $_REQUEST['msg_error_other_user'];}?></div>
                                        
					</div>

                    <div><input type="image" src="../../images/login.png" name="button" id="button" width="235" height="40"/></div>
				</form>
				</div>
			</div>
			<div id="adminLoginSide"><img src="../../images/ashRght.png" width="15" height="330" /></div>
	  </div>
	
	
	
	
	</div>
  </div>
  
</div>
 <div id="footerMain">
    <div class="lineBottom"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
    </div>
    
    <script type="text/javascript">
	var options = {
		script:"username_suggesions.php?json=true&limit=6&",
		varname:"input",
		json:true,
		shownoresults:false,
		maxresults:6,
		callback: function (obj) { document.getElementById('testid').value = obj.id; }
	};
	var as_json = new bsn.AutoSuggest('testinput', options);


	var options_xml = {
		script: function (input) { return "test.php?input="+input+"&testid="+document.getElementById('testid').value; },
		varname:"input"
	};
	var as_xml = new bsn.AutoSuggest('testinput_xml', options_xml);
</script>



</body>
</html>
