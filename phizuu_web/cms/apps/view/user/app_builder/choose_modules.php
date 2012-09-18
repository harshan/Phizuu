<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu - Application Wizard</title>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
</head>
	

<body>
<form action="AppWizardController.php?action=save_modules" method="post">
<div id="mainWideDiv">
  <div id="middleDiv2">
  	<div id="header">
	  <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardController.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="120" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
  	</div>
	<div id="body">
	<div id="indexBodyLeft">
    
    <br />
	  <div id="bodyLeft">
	  <div id="lightBlueHeader">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle">Select Modules here</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
              <?php
        $listOfModules = $popArr['modules'];
        $selected = $popArr['selectedModules'];
        foreach ($listOfModules as $module) {
        ?>
              <div id="textBar">
                  <div class="tahoma_12_blue" id="title"><?php echo $module['module_name']; ?> <?php echo $module['default']==1?'(Required)':'' ?></div>
                  <div class="tahoma_12_blue" id="note2">
                      <input type="checkbox" name="modules[]" <?php echo $module['default']==1 || array_search($module['module_name'], $selected)?'checked="checked"':'' ?> value="<?php echo $module['module_name']; ?>" <?php echo $module['default']==1?'onclick="return false"':'' ?>/>
                  </div>
              </div>
        <?php
        }
        ?>
	  </div>
	<div id="bodyLeft">
		<div class="wizardBtn"><input type="image" src="../../../images/wizard_btn_goto_and_fill_data.png" width="160" height="25" /></div>
	</div>
	  </div>
	<div id="indexBodyRight"></div>
	</div>
	<div id="buttonContainer">&nbsp;</div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
</form>
</body>
</html>
