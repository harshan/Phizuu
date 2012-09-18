<?php
$homeImageCount = $popArray['packageInfo']['home_screen_images'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>phizuu - Application Wizard</title>
        <link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
        <link href="../../../common/tooltip/bubble.css" rel="stylesheet" type="text/css" media="all" />
        <script src="../../../common/dw_script/AC_RunActiveContent.js" type="text/javascript"></script>
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../common/tooltip/jquery.codabubble.js"></script>

        <script type="text/javascript">
    var homeImages = <?php echo $homeImageCount ?>;

    function fileUploaded(path) {
        var imageSet = false;
        for (i=1; i<=homeImages; i++) {
            if (getEl("homeImage-" + i).value == '') {
                imageSet = true;
                getEl("homeImage-" + i).value = '../../' + path;
                getEl("homeScreenDiv-" + i).innerHTML = "<img src='../../" + path + "' width='320' height='367'/>";
                scrollWin("homeScreenDiv-" + i);
                break;
            }
        }

        if (!imageSet) {
            $("#maxImages").dialog( "open" );
        }
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
        var imageNotSet = true;
        for (i=1; i<=homeImages; i++) {
            if (getEl("homeImage-" + i).value != '') {
                imageNotSet = false;
                break;
            }
        }

        if(imageNotSet) {
            $("#noContent").dialog( "open" );
            return false;
        } else {
            return true;
        }
    }

    $(function(){

        $("#noContent, #maxImages").dialog({
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

    function clearHomeImage(number) {
        getEl("homeImage-" + number).value = '';
        getEl("homeScreenDiv-" + number).innerHTML = "";
    }

function takeAction(action) {
    if (action=='skip') {
         window.location = "AppWizardControllerNew.php?action=home_images_skip";
    } else if (action=='save') {
        if (validate())
            document.getElementById('mainForm').submit();
    }
}

        </script>
<style type="text/css">

.homeImageSt {
float:left;
height:800px;
margin:5px;
width:420px;
position: relative;

}

.homeScreenInner {
	height: 367px; width: 320px; background-image: url(../../../images/empty_bg.png);
        position: absolute;
        z-index: 4;
        left: 36px;
        top: 203px;
}
.previewWrapper {
border-style:none solid solid;
border-width:medium 1px 1px;
float:left;
overflow:hidden;
padding:17px 17px 17px 58px;
width:863px;
}
.appTitle {
    position: absolute;
    width: 320px;
    text-align: center;
    font-size: 18px;
    font-family: Tahoma;
    color: #FFFFFF;
    top: 168px;
    left: 36px;
    z-index: 6;
}

.homeScreenBottom {
    text-align: right;
    padding-right: 3px;
    position: absolute;
    top: 749px;
    left:89px;

}

.homeScreenBottom a:link, .homeScreenBottom a:visited {
    color: #07738A;
    text-decoration: none;
    font-family: Tahoma;
    font-size: 12px;
}

.homeScreenBottom a:hover {
    text-decoration: underline;
}

.homeScreenInnerPhone {
    position: absolute;
    z-index: 5;
}
</style>
    </head>


    <body>
        <div id="mainWideDiv">
            <div id="middleDiv2">
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
                <form action="AppWizardControllerNew.php?action=home_images_save" method="post"  id="mainForm">
   <?php for($i=1; $i<=$homeImageCount; $i++) { ?>
                    <input type="hidden" name="homeImage-<?php echo $i; ?>" id="homeImage-<?php echo $i; ?>" value=""/>

   <?php } ?>     
                    <div id="body">
                        <br/>
                        <?php if(isset($_SESSION['update_contents']) && $_SESSION['update_contents']=='yes') {
                            include '../../../view/user/app_wizard/supporting/contect_update_inc.php';
                        }?>

                        <div class="wizardTitle" >
                            <div class="left"><img src="../../../images/wizTitleLeft.png" width="10" height="34"/></div>
                            <div class="middle" style="width: 870px">Please click <img src="../../../images/upload_btn.png" align="top" style="margin-top:-5px"></img> to choose an image as your homescreen</div>
                            <div class="right"><img src="../../../images/wizTitleRight.png" width="10" height="34"/></div>
                        </div>


    <div class="coda_bubble wizardSecondTitle">
        <div>
            <p><div style="float:left">Once image is on canvas place the square over the area you would like to use as the icon and click Crop</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <div style="height: 20px">Quick Video Tutorial (48 seconds) - to hide move the mouse away</div>
<object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/undWOM6odJA&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/undWOM6odJA&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
           </div>
        </div>
    </div>


                        <div id="lightBlueHeader" class="wizardItemList">
                            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
                            <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Required Size 320 X 367</div>
                            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
                        </div>

                        <div id="lightBlueHeader" class="wizardItemList">
                            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
                            <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Maximum <?php echo $homeImageCount ?> image<?php echo $homeImageCount==1?'':'s'?></div>
                            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
                        </div>

                        <!-- Image crop start -->

<!-- Image crop start -->
    <?php
    $width = 640;
    $height = 734;
    ?>

<script type="text/javascript">
    AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0','name','croper','width','948','height','650','align','middle','id','croper','src','croper','quality','high','bgcolor','#ffffff','allowscriptaccess','sameDomain','allowfullscreen','false','pluginspage','http://www.adobe.com/go/getflashplayer','flashvars','_w=<?php echo $width; ?>&_h=<?php echo $height; ?>','movie','croper' ); //end AC code
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
                            <div class="left"><img src="../../../images/wizTitleLeft.png" width="10" height="34"/></div>
                            <div class="middle" style="width: 870px">Preview</div>
                            <div class="right"><img src="../../../images/wizTitleRight.png" width="10" height="34"/></div>
                        </div>
                        <!-- iphone preview start -->

      <div class="previewWrapper">
           <?php for($i=1; $i<=$homeImageCount; $i++) { ?>
           <div class="homeImageSt">
                <div class="homeScreenInnerPhone"><img src="../../../images/home_preview.png"/></div>
                <div class="homeScreenInner" id="homeScreenDiv-<?php echo $i; ?>"></div>
                <div class="appTitle"><?php echo $popArray['app_name']; ?></div>
                <div class="homeScreenBottom"><a href="javascript: clearHomeImage(<?php echo $i; ?>)">
                        <img src ="../../../images/btn_clear.png" border="0"/>
                    </a> <a href="javascript: scrollToTop();"><img src ="../../../images/btn_top.png" border="0"/></a></div>
            </div>
           <?php } ?>

      </div>
<!-- iphone preview end -->

                        <div id="bodyLeftWizard">
                            <div class="nextButton">
                                <?php
                                if(isset($_SESSION['update_contents'])) { ?>
                                    <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="143" height="25" />
                                <?php } ?>
                                    <img class="wizardButton" src="../../../images/btn_next.png" width="83" height="25"  onclick="javascript: takeAction('save');" />
                            </div>
                        </div>
</div>
                    
</form>
   	 </div>
                
            </div>




        <div id="footerMain">
            <div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>

<!-- Warning dialogs -->
<div id="noContent" title="Error!" style="text-align:center">
	<p></p>
     	<p>
        <?php
        if(isset($_SESSION['update_contents'])) {
            echo "Please select at least one image before continue. If you don't want to change the home screens of the current application, please choose skip";
        } else {
            echo "Please select at least one image before continue. The application needs at least one home screen image. So, you can't skip this.";
        }
        ?>
        </p>
</div>
<div id="maxImages" title="Error!" style="text-align:center">
	<p>We are sorry! Your package is only allowed to use maximum of <?php echo $homeImageCount ?> home screen image<?php echo $homeImageCount==1?'':'s'?>! Please clear an image if you want to replace</p>
</div>

    </body>
</html>

