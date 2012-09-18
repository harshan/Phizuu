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
        getEl('musicImage').value = '../../' + path;
        getEl('imgMusic').innerHTML = "<img src='../../" + path + "' width='320' height='191'/>";
        scrollWin('scrollToHere4');
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
        if(getEl('musicImage').value=='') {
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


        </script>

    </head>


    <body>
        <div id="mainWideDiv">
             <div id="header">
        <div style="width: 800px;height: 90px;margin: auto;">
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
               
                <form action="AppWizardControllerNew.php?action=music_module_cover_save" method="post" onsubmit="javascript: return validate()">
                    <input type="hidden" name="musicImage" id="musicImage" value=""/>


                    <div id="body">
                        <br/>

                        <div class="wizardTitle" >
                            
                            <div class="middle" style="width: 889px;height: 25px;padding-top: 5px;margin-left: 10px">Please click 'Upload' button to choose as your Music Banner Image</div>
                            
                        </div>

    <div class="coda_bubble wizardSecondTitle">
        <div>
            <p><div style="float:left">Once image is on canvas place the square over the area you would like to use as the icon and click Crop</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <div style="height: 20px">Quick Video Tutorial (18 seconds) - to hide move the mouse away</div>
<object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/uTipLnCVdsM&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/uTipLnCVdsM&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>           </div>
        </div>
        
    </div>

                        <div id="lightBlueHeader" class="wizardItemList">
                            
                            <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px;margin-left: 15px">Required Size 320 X 191</div>
                         
                        </div>
                        <!-- Image crop start -->

<!-- Image crop start -->
<?php
$width = 320;
$height = 191;
?>

<script type="text/javascript">
    AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0','name','croper','width','963','height','650','align','middle','id','croper','src','croper','quality','high','bgcolor','#f5f4f4','allowscriptaccess','sameDomain','allowfullscreen','false','pluginspage','http://www.adobe.com/go/getflashplayer','flashvars','_w=<?php echo $width; ?>&_h=<?php echo $height; ?>','movie','croper' ); //end AC code
    </script><noscript><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" name="croper" width="948" height="650" align="middle" id="croper">
    <param name="allowScriptAccess" value="sameDomain" />
    <param name="allowFullScreen" value="false" />
    <param name="movie" value="croper.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
    <param name="flashvars" value="_w=<?php echo $width; ?>&_h=<?php echo $height; ?>"/>
    <embed src="croper.swf" width="948" height="650" align="middle" quality="high" bgcolor="#ffffff" name="croper" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="_w=<?php echo $width; ?>&_h=<?php echo $height; ?>" />
    </object></noscript>
    <!-- Image crop end -->

<br />
                        <div id="scrollToHere" class="wizardTitle" >
                        
                            <div class="middle" style="width: 884px;margin-left: 13px;height: 25px;padding-top: 5px">Preview</div>
                          
                        </div>
                        <!-- iphone preview start -->

      <div class="previewWrapper" style="width: 832px;margin-left: 13px">
           <div style="position: relative; height: 770px; width: 425px" id="scrollToHere4">

            <img src="../../../images/app_wizard_music.png" style="position: absolute; height: 742px; width: 387px; top:0px; left:0px; z-index: 4;"/>
            <div id="imgMusic" style="position: absolute; height: 191px; width: 320px; background-image:url(../../../images/empty_bg.png); left: 38px; top: 200px; z-index:3; "></div>
          </div>
      </div>
<!-- iphone preview end -->

                        <div id="bodyLeftWizard" >
                            <div class="nextButton" style="width: 912px;margin-left: 13px;"><input type="image" src="../../../images/btn_next.png" width="99" height="33" /></div>
                        </div>
</div>
                    
</form>
   	 </div><br class="clear"/>
                
            </div>




      <br class="clear"/><br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>

<!-- Warning dialogs -->
<div id="noContent" title="Error!" style="text-align:center">
	<p>Please select an image before continue. This is your music cover image for the application. So, you can't skip this, since you have selected Music module.</p>
</div>


    </body>
</html>

