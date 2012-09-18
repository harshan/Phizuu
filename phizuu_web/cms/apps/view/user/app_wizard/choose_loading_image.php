<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link href="../../../common/tooltip/bubble.css" rel="stylesheet" type="text/css" media="all" />
        <script src="../../../common/dw_script/AC_RunActiveContent.js" type="text/javascript"></script>
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../common/tooltip/jquery.codabubble.js"></script>

        <script type="text/javascript">

    function fileUploaded(path) {
        getEl('loadImage').value = '../../' + path;
        getEl('imgHome').innerHTML = "<img src='../../" + path + "' width='320' height='480'/>";
        scrollWin('scrollToHere');
    }

    function scrollWin(div){
        $('html, body').animate({
            scrollTop: $("#"+div).offset().top
        }, 1000);
    }

    function scrollToTop() {
        $('html, body').animate({
            scrollTop: 0
        }, 1000);
    }

    function getEl (id) {
        return document.getElementById(id);
    }

    function validate() {
        if(getEl('loadImage').value=='') {
            $("#noContent").dialog( "open" );
            return false;
        } else {
            return true;
        }
    }

    $(function(){

        $("#noContent").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });
        opts = {
            distances : [-153],
            leftShifts : [410],
            bubbleTimes : [400],
            hideDelays : [500],
            bubbleWidths : [640],
            msieFix : true
        };
        $('.coda_bubble').codaBubble(opts);


    });

function takeAction(action) {
    if (action=='skip') {
         window.location = "AppWizardControllerNew.php?action=loading_image_skip";
    } else if (action=='save') {
        if (validate())
            document.getElementById('mainForm').submit();
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
            <div id="middleDiv2">
                
                
                <form id="mainForm" action="AppWizardControllerNew.php?action=loading_image_save" method="post">
                    <input type="hidden" name="loadImage" id="loadImage" value=""/>


                    <div id="body">
                        <br/>
                        <?php if(isset($_SESSION['update_contents']) && $_SESSION['update_contents']=='yes') {
                            include '../../../view/user/app_wizard/supporting/contect_update_inc.php';
                        }?>

                       
                        <div class="wizardTitle" >
                           
                            <div class="middle" style="width: 910px">Please click 'Upload' button to choose an image as your loading screen</div>
                           
                        </div>

   <div class="coda_bubble wizardSecondTitle">
        <div>
            <p><div style="float:left">Once image is on canvas place the square over the area you would like to use as the icon and click Crop</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <div style="height: 20px">Quick Video Tutorial (17 seconds) - to hide move mouse away</div>
<object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/zd4t2OdyumE&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/zd4t2OdyumE&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>           </div>
        </div>
    </div>


                        <div id="lightBlueHeader" class="wizardItemList">
                           
                            <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px;margin-left: 15px">Required Size 320 X 480</div>
                            
                        </div>
                        <!-- Image crop start -->

<!-- Image crop start -->
<?php
$width = 640;
$height = 920;
?>

<script type="text/javascript">
    AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0','name','croper','width','962','height','650','align','middle','id','croper','src','croper','quality','high','bgcolor','#f5f4f1','allowscriptaccess','sameDomain','allowfullscreen','false','pluginspage','http://www.adobe.com/go/getflashplayer','flashvars','_w=<?php echo $width; ?>&_h=<?php echo $height; ?>','movie','croper' ); //end AC code
    </script><noscript><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" name="croper" width="948" height="650" align="middle" id="croper">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="allowFullScreen" value="false" />
    <param name="movie" value="croper.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
    <param name="flashvars" value="_w=<?php echo $width; ?>&_h=<?php echo $height; ?>"/>
    <embed src="croper.swf" width="962" height="650" align="middle" quality="high" bgcolor="#ffffff" name="croper" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="_w=<?php echo $width; ?>&_h=<?php echo $height; ?>" />
    </object></noscript>
    <!-- Image crop end -->

<br />
                        <div id="scrollToHere" class="wizardTitle" >
                            
                            <div class="middle" style="width: 884px;margin-left: 13px">Preview</div>
                           
                        </div>
                        <!-- iphone preview start -->

      <div class="previewWrapper" style="width: 832px;margin-left: 13px">
          <div style="position: relative; height: 782px; width: 274px;">
            <img src="../../../images/home_preview.png" style="position: absolute; height: 742px; width: 384px; top:0px; left:0px; z-index: 4;"/>
            <div id="imgHome" style="position: absolute; height: 480px; width: 320px; background-image:url(../../../images/empty_bg.png);   left: 36px; top: 139px; z-index:5;"></div>
              </div>
      </div>
<!-- iphone preview end -->

                        <div id="bodyLeftWizard">
                            <div class="nextButton" style="width: 912px;margin-left: 13px">
                                <?php
                                if(isset($_SESSION['update_contents'])) { ?>
                                    <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="33" />
                                <?php } ?>
                                    <img class="wizardButton" src="../../../images/btn_next.png" width="99" height="33"  onclick="javascript: takeAction('save');" />
                            </div>
                        </div>
</div>
                    
</form>
   	 </div><br class="clear"/><br class="clear"/>
                
            </div>




          <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>

<!-- Warning dialogs -->
<div id="noContent" title="Error!" style="text-align:center">
     	<p>
        <?php
        if(isset($_SESSION['update_contents'])) {
            echo "Please select an image before continue. If you don't want to change the loading of the current application, please choose skip";
        } else {
            echo "Please select an image before continue. This is your loading image for the application. So, you can't skip this.";
        }
        ?>
        </p>
</div>


    </body>
</html>

