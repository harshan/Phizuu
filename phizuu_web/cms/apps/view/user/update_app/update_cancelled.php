<?php
$menu_item = "app_update";

require_once("../../../controller/session_controller.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.bodyRow {
    width: 100%;
    float: left;
    min-height: 20px;
    overflow: hidden;
    font-family: Tahoma;
    color: #1e1f1f;
    font-size: 12px;
    padding-bottom: 4px;
}

.bodyRow a {
    text-decoration: underline;
    color: #07738A;
}

.bodyRow a:hover {
    text-decoration: none;
    color: #00B4E9;
}
</style>

</head>


    <body>
          <div id="header">
        <div id="headerContent">
            <?php include("../../../view/user/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">
                
                <?php include("../../../view/user/common/navigator.php");?>
                <div class="bodyRow">
                    
                </div>
                <div class="bodyRow">
                    <div id="lightBlueHeader" style="width: 100%">
                       
                        <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 932px">Update Contents of the Application (Icon, Home Screen Images, Loading Image, etc;)</div>
                       
                    </div>
                </div>
                <div class="bodyRow" style="height: 400px; padding: 5px">
                    You have canceled Content Update Wizard. Please click <a href="../../../controller/modules/update_app/UpdateAppController.php?action=main_view">here</a> to restart the Content Update Wizard.
                </div>
            </div>
        </div>

               <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>


    </body>
</html>