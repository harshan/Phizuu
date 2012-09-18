<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/iphone_themes.css" rel="stylesheet" type="text/css" />
        
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />


        <script type="text/JavaScript">
            function takeAction(theme) {
                window.location = "ThemeController.php?action=choose_package&theme="+theme;
            }

        </script>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
    </head>

    <body>
        <div id="mainWideDiv">
            <div id="middleDiv2" style="width: 973px">
                <div id="header">
                    <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="120" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
                </div>
                <div id="body">
                    <br/>

                    <div class="wizardTitle" >
                        <div class="left"><img src="../../../images/wizTitleLeft.png" width="10" height="34"/></div>
                        <div class="middle" style="width: 903px">Select your main theme interface</div>
                        <div class="right"><img src="../../../images/wizTitleRight.png" width="10" height="34"/></div>
                    </div>
                    <div class="themeSection">
                        <div style="float:left; width: 100%; margin-bottom: 10px;">Click on the theme that you need to go with:</div>
                        
                        <div style="float:left; width: 155px; height: 297px; margin-right: 20px; cursor: pointer;" onclick="javascript: takeAction('phizuu_pro')">
                            <img src="../../../images/pro_theme_preview.jpg"/>
                            <div style="text-align: center">phizuu Pro</div>
                        </div>
                        <div style="float:left; width: 155px; height: 297px; margin-right: 20px; cursor: pointer;" onclick="javascript: takeAction('<?php echo $defaultThemePackage; ?>')">
                            <img src="../../../images/phizuu_classic.jpg"/>
                            <div style="text-align: center">phizuu Classic</div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div id="footerMain">
            <div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>        
    </body>
</html>