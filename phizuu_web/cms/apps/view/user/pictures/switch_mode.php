<?php
$menu_item="photos";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Photos - phizuu CMS</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <style type="text/css">
            #agreeDiv a {
                text-decoration: none;
}

            #agreeDiv a:hover {
                text-decoration: underline;

}
        </style>
    </head>
    <body>
        <div id="header">
        <div id="headerContent">
            <?php include("../common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">
               
                <?php include("../common/navigator.php");?>
                <div id="bodyPhotos">
                    <div style="float:left; width: 100%">
                        <img src="../../../images/album_trans.jpg"></img>
                    </div>
                    <div style="float:left; width: 100%; font-family: Tahoma; font-size: 24px">
                        <b>Do you want to Switch to Albums?</b>
                    </div>
                    <div style="float:left; width: 100%; font-family: Tahoma; font-size: 16px">
                        <ul>
                            <li>Album is a nice way to organize your photos</li>
                            <li>Your all the images currently in the iPhone list will be moved to the bank list</li>
                            <li>You will have to create at least an album with at least one image, to be able to view it in you application</li>
                            <li>You will not be able to switch back to normal photos mode after switching to the album mode</li>
                            <li>If you don't need it now, you can switch later</li>
                        </ul>
                    </div>
                    <div id="agreeDiv" style="float:left; width: 100%; font-family: Tahoma; font-size: 36px; text-align: center">
                        <a href="photos.php" style="color: #AAAAAA">No, Thanks</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="../../../controller/photo_all_controller.php?action=switch_to_albums" style="color: #04455A">I need Albums</a>
                    </div>
                </div>  <br class="clear"/>

            </div>  <br class="clear"/>
        </div>  <br class="clear"/>
         <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    </body>
</html>