<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu - Application Wizard</title>
 <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function validate() {
	if (document.getElementById('appName').value=='') {
		alert("Please enter your application name!");
		return false;
	}
}
</script>

</head>
	

<body>
<div id="mainWideDiv">
     <div id="header">
        <div style="width: 800px;height: 90px;margin: auto">
                        <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logoInner.png" width="350" height="35" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                       
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
  <div id="middleDiv2">
  	
	<div id="body">
            <div style="font-family: Tahoma; color: #1e1f1f; font-size: 20px; padding-top: 100px">
            Thank you for creating an application with phizuu. Your application was submitted to Apple for review.
            </div>
        <br />

	<!--<div id="indexBodyRight"></div>-->
	</div>
	<div id="buttonContainer">&nbsp;</div>
  </div>
</div>
  <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
</html>

