<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
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
                        <div id="bodyLeftWizard">
                            <div id="lightBlueHeaderWizard">
                                <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
                                <div class="tahoma_14_white" id="lightBlueHeaderMiddleWizard">Application Settings</div>
                                <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
                            </div>
                            <form action="AppWizardController.php?action=application_settings_save" method="post" onsubmit="javascript: return validate()">
                                <div id="textBarWizard">
                                    <div class="tahoma_12_blue" id="title">Aplication Name </div>
                                    <div id="noteTxtFld"><input name="appName" id="appName" type="text" class="textFeildBoarder" style="width:200px; height:20px;"/></div>
                                </div>
                        </div>
                        <?php if ($popArray['packageInfo']['package_id']!=1) {?>
                        <div id="textBarWizard">
                            <div class="tahoma_12_blue" id="title">Push Notifications</div>
                            <div id="noteTxtFld"><input name="pushNotifications" type="checkbox" class="textFeildBoarder"/></div>
                        </div>
                    </div>
                        <?php } ?>
                    <div id="bodyLeftWizard">
                        <div class="wizardBtn"><input type="image" src="../../../images/wizard_btn_start.png" width="75" height="25" /></div>
                    </div>
                </div>

                </form>
                <br />

                <!--<div id="indexBodyRight"></div>-->
            </div>
            <div id="buttonContainer">&nbsp;</div>
        </div>
        </div>
        <div id="footerMain">
            <div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>
    </body>
</html>

