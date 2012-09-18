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
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
            <div id="middleDiv">

                <form action="AppWizardControllerNew.php?action=application_settings_save" method="post" onsubmit="javascript: return validate()">
                <div id="body">
                    <div id="indexBodyLeft">
                        <br />
                        <div id="bodyLeftWizard"  style="width: 950px">
                         <div class="wizardTitle" >
                          
                            <div class="middle" style="width: 900px;padding-top: 5px">Welcome to the phizuu app wizard</div>
                   
                        </div>
                            <br/>
                            <br/>
                            
                                <div id="textBarWizard" style="height: 60px">
                                    <div class="tahoma_12_blue" id="title" style="width: 327px">
                                        <div>What would you like the name of your application to be?</div>
                                        <div style="text-align: right; font-size: 10px; padding-right: 0px;width: 400px; padding-top: 10px">**Recommendation is to be 12 characters or less</div>
                                    </div>
                                    
                                    <div id="noteTxtFld"><input name="appName" id="appName" type="text" class="textFeildBoarder" style="width:200px; height:20px;"/></div>
                                </div>
                        </div>
                        <?php if ($popArray['packageInfo']['package_id']==3) {?>
                        <div id="textBarWizard" style="height: 50px">
                            <div class="tahoma_12_blue" id="title" style="width: 322px">Would you like to use push notifications in your application?</div>
                            <div id="noteTxtFld" style="clear: both;margin: -11px 0 0 112px"><input name="pushNotifications" type="checkbox" class="textFeildBoarder"/></div>
                        </div>
                    </div>
                        <?php } ?>
                    <div id="bodyLeftWizard">
                        <div class="wizardBtn"><input type="image" src="../../../images/btn_next.png" width="99" height="33" /></div>
                    </div>
                </div>

                </form>
                <br />

                <!--<div id="indexBodyRight"></div>-->
            </div>
            <div id="buttonContainer">&nbsp;</div>
        </div>

          <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    </body>
</html>

