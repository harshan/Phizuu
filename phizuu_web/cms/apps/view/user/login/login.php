<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--


function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
</head>
	

<body  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<div id="mainWideDivSign">
  <div id="middleDiv">
<!--  	<div id="header"><img src="../../../images/logo.png" width="334" height="62" /></div>-->
	<div id="navigator"></div>
	<div id="buttonContainerSign">
            <div style="text-align: center;width: 370px;margin-bottom: 30px"><img src="../../../images/logo.png"/></div>
		<div id="SignInBox">
<!--		  <div id="userLoginTitleRow">
		  	<div id="loginTtitleCorner"><img src="../../../images/usLoLft.png" width="17" height="48" /></div>
			<div class="tahoma_18_white" id="userLoginMidBar">User Login </div>
			<div id="loginTtitleCorner"><img src="../../../images/usLoRght.png" /></div>
		  </div>-->
		  <div id="ashCorner"><img src="../../../images/ashLft.png" /></div>
		  <div id="userLogginMidBox">
                   <form action="../../../controller/modules/login/?action=login" method="post">
             
                        <div class="tahoma_18_white" id="userLoginMidBar">User Login </div>
                        <div class="line"></div>
                        <div id="feildRowLoggin" style="height: 60px">
			   <div class="tahoma_12_blue" id="nameLoggin">Username</div>
				  <div id="logginTextFeild">
                                      <input name="username" type="text" class="textFeildBoarder" id="loginTextBox" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"/><?php if(isset($_REQUEST['msg_error_user'])){echo '<div class="tahoma_12_blue_error" style="float:right">'.$msg_error_user.'</div>';}?>      
			  </div>
                        </div>
				<div id="feildRowLoggin" style="height: 60px"> 
				  <div class="tahoma_12_blue" id="nameLoggin">Password</div>
				  <div id="logginTextFeild" >
                                           <input name="password" type="password" class="textFeildBoarder" id="loginTextBox" style="width:230px; height:30px;" value="<?php if(isset($_POST['password'])) echo $_POST['password']; ?>"/><?php if(isset($_REQUEST['msg_error_pwd'])){echo '<div class="tahoma_12_blue_error" style="float:right">'.$msg_error_pwd.'</div>';}?>
				  </div>
				</div>
                <div id="feildRowLoggin">
<!--				  <div class="tahoma_12_blue" id="nameLoggin"></div>-->
<div id="logginTextFeild" align="left" style="margin-top: 17px">
            <input type="image" src="../../../images/login.png" name="Login" id="Login"width="235" height="40"/>
            <div class="line"style="margin-top: 20px"></div>
            <div style="vertical-align:bottom; float:left; padding-top:10px">
                <a href="../../../controller/modules/login/?action=forgot_password" class="tahoma_11_blue"> Forgot Password ?</a>
            </div>
            </div>
				</div>
                         <div id="feildRowLoggin" style="padding-bottom: 5px; height: auto; min-height: 20px">
          <?php if(isset($error) && $error != ''){?>
			
            <div class="tahoma_12_blue_error" style="width: 350px; height: auto;" id="nameLoggin"><?php echo $error;?></div>
            

          <?php  }?>
            </div>
			<!--<div id="logginBttn">
            <input type="image" src="../../images/login.png" name="Login" id="Login"width="83" height="25"/>
            </div>-->
            <!--<div id="logginBttn">
            <a href="forgot_pwd.php" class="tahoma_11_blue"> Forgot Password</a>
            </div>-->
            </form>
			  </div>
              
			  <div id="ashCorner"><img src="../../../images/ashRght.png" /></div>
		  </div>
          
	</div>
        
  </div>	
    
   
</div>
     <div id="footerMain">
    <div class="lineBottom"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
</html>
