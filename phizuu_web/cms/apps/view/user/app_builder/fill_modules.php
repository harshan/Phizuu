
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu - Application Wizard</title>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            .indicatorDiv {
                width: 17px;
                height: 17px;
            }

            .completed {
                background-image: url('../../../images/wizard_dot_grn.png');
            }

            .incomplete {
                background-image: url('../../../images/wizard_dot_red.png');
            }

            .halfCompleted {
                background-image: url('../../../images/wizard_dot_orng.png');
            }
			
			.links a{
				text-decoration: none;
			}
        </style>
        <script type="text/javascript">
		function onSubmit(val) {
			document.getElementById('action').value = val;
                        
                        if (val=='forward'){
                            if (doValidation())
                                document.getElementById('form1').submit();
                        }else{
                            document.getElementById('form1').submit();
                        }
		}
		</script>
</head>
	

<body>
    <form id="form1" name="form1" method="post" action="AppWizardController.php?action=fill_modules_action" >
<input type="hidden" value="" id='action' name='action' />
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
	<div id="indexBodyLeft"><br />
	  <div id="bodyLeftWizard">
	  <div id="lightBlueHeaderWizard">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddleWizard">Fill up the selected modules or change the list of Modules by going back..</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
	  <div id="lightBlueHeaderWizard"><a href="../../../view/user/settings/settings_new.php" class="tahoma_12_blue">Set your User Account prefferences (YouTube/Flicker) </a></div>
	  	
                        <?php
                $halfFilled = 'false';
                $incomplete = 'false';

                $listOfModules = $popArr['modules'];
                foreach ($listOfModules as $module) {

                    $linkText = '';
                    $fillBoxText = "";
                    if ($module['fill_link']!='') {
                        $linkText = "<a class='tahoma_12_blue' href='{$module['fill_link']}'>Fill</a>";
                        

                        if ($module['module_name']=='Photos' && !$popArr['isFlickerSet']) {
                            $linkText = "<a class='tahoma_12_blue' href=\"javascript: checkAccount('{$module['fill_link']}',1);\">Fill</a>";
                            $fillBoxText = '<div style="width: 110px; float: left">Flickr Username:</div> <input style="width: 150px;" type="text" class="textFeildBoarder" id="flickrAccount"/>';
                        }

                        if ($module['module_name']=='Videos' && !$popArr['isYouTubeSet']) {
                            $linkText = "<a class='tahoma_12_blue' href=\"javascript: checkAccount('{$module['fill_link']}',2);\">Fill</a>";
                            $fillBoxText = '<div style="width: 110px; float: left">YouTube Username:</div><input style="width: 150px;" type="text" class="textFeildBoarder" id="youtubeAccount"/>';
                        }

                    } 

                    if ($module['completed'] == 1) {
                        $completedText = 'completed';
                    } elseif ($module['completed'] == 2) {
                        $completedText = 'halfCompleted';
                        $halfFilled = 'true';
                    } else {
                        $completedText = 'incomplete';
                        $incomplete = 'true';
                    }
                    ?>
        
        	<div id="textBarWizard">
				<div id="wizardColorBlob"><div class="indicatorDiv <?php echo $completedText; ?>"></div></div>
				<div class="tahoma_12_blue" id="title"><?php echo $module['module_name']; ?> </div>
                                <div class="tahoma_12_blue" id="title" class="links" style="width: 50px"><?php echo $linkText ?> </div>
                                <div class="tahoma_12_blue fillBox"><?php echo $fillBoxText; ?></div>
			</div>
                    <?php
                }
                ?>

	  <div id="bodyLeftWizard">
	    <div class="wizardBtn"><img style="cursor:pointer" onclick="onSubmit('backward');" src="../../../images/wizard_btn_goback.png" width="95" height="25" /></div>
	  <div class="wizardBtn"><img style="cursor:pointer" onclick="onSubmit('forward');" src="../../../images/wizard_btn_build_application.png" width="160" height="25" /></div>
	    </div>
	  </div>
	<!--<div id="indexBodyRight"></div>-->
	</div>
	<div id="buttonContainer">&nbsp;</div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
</div>
</form>

        <script type="text/javascript">
            function doValidation() {
                var msg = '';
                if (incomplete && halfFilled)
                    msg = "You have modules those have no content. Also you have modules those are half filled. Please make sure to add content to iPhone list in these modules.";
                else if (incomplete)
                    msg = "You have modules that have no content!";
                else if (halfFilled)
                    msg = "You have modules those are half filled. Please make sure to add content to iPhone list in these modules!";
                
                msg += "\n\nPlease complete all modules or go back and remove unneccessary modules, before build the application!";

                if (incomplete || halfFilled) {
                    alert(msg);
                    return false;
                }
                return true;
            }

            var incomplete = <?php echo $incomplete; ?>;
            var halfFilled = <?php echo $halfFilled; ?>;

            function checkAccount(url, module){
                if (module == 1) {
                    var flickr = document.getElementById('flickrAccount').value;
                    if (flickr == '') {
                        alert ('Please enter your Flickr account username in the box next to Photos module');
                    } else {
                        window.location= 'AppWizardController.php?action=add_acount&url='+url+"&module="+module + "&value="+flickr;
                    }
                } else if (module == 2) {
                    var youtube = document.getElementById('youtubeAccount').value;
                    if (youtube == '') {
                        alert ('Please enter your YouTube account username in the box next to Video module');
                    } else {
                        window.location= 'AppWizardController.php?action=add_acount&url='+url+"&module="+module + "&value="+youtube;
                    }
                }
            }
        </script>
</body>
</html>
