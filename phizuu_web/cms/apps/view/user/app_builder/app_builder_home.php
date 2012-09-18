<?php
	
	 $numHomeScreens = $popArray['packageInfo']['home_screen_images'] ;
         $modules = $popArray['listOfModules'];

         if (array_search('Music', $modules))
            $musicModuleChoosen = true;
         else
            $musicModuleChoosen = false;
	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
	

<script src="../../../common/dw_script/AC_RunActiveContent.js" type="text/javascript"></script>
<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>

<style type="text/css">

.homeImageSt {
	display: inline-block; height: 387px; width: 320px; margin: 5px; border:#000033 solid 2px;
}

.homeImageSt {display: inline !ie}

.homeScreenInner {
	height: 367px; width: 320px; background-image: url(../../../images/empty_bg.png);
}

</style>
<script type="text/javascript" src="../common/glm-ajax.js"></script>

<script type="text/javascript">
    var homeImages = <?php echo $numHomeScreens ?>;

    function fileUploaded(path) {
        var $tabs = $('#tabs').tabs();
        var selected = $tabs.tabs('option', 'selected');
        
        if (selected == 0) {
            var data = eval('(' + path + ')');
            getEl('iconImage').value = '../' + data.iconPath;
            getEl('iTunesArtWork').value = '../' + data.iTunesArtWorkPath;
            getEl('faceBookPostImage').value = '../' + data.faceBookImagePath;
            getEl('imgImageBlank').src = '../'+ data.iconPath;
            scrollWin('scrollToHere1');
        } else if (selected == 1) {
            getEl('loadImage').value = '../../' + path;
            getEl('imgHome').innerHTML = "<img src='../../" + path + "' width='320' height='480'/>";
            scrollWin('scrollToHere2');
        } else if (selected == 2) {
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
            if (!imageSet)
                alert("You are allowed to use only <?php echo $numHomeScreens ?> home screen images! Please clear an image if you want to replace..")
        } else if (selected == 3) {
            //alert(selected + "," + path);
            getEl('musicImage').value = '../../' + path;
            getEl('imgMusic').innerHTML = "<img src='../../" + path + "' width='320' height='191'/>";
            scrollWin('scrollToHere4');
        }
        
        
    }

    function clearHomeImage(number) {
        getEl("homeImage-" + number).value = '';
        getEl("homeScreenDiv-" + number).innerHTML = "";
    }

    //Common funtions
    function getEl (id) {
        return document.getElementById(id);
    }


    $(function(){

            // Tabs
            $('#tabs').tabs();


            // Dialog
            /*$('#dialog').dialog({
                    autoOpen: false,
                    width: 600,
                    buttons: {
                            "Ok": function() {
                                    $(this).dialog("close");
                            },
                            "Cancel": function() {
                                    $(this).dialog("close");
                            }
                    }
            });

            // Dialog Link
            $('#dialog_link').click(function(){
                    $('#dialog').dialog('open');
                    return false;
            });

            //hover states on the static widgets
            $('#dialog_link, ul#icons li').hover(
                    function() { $(this).addClass('ui-state-hover'); },
                    function() { $(this).removeClass('ui-state-hover'); }
            );*/

    });

function scrollWin(div){
    $('html, body').animate({
    scrollTop: $("#"+div).offset().top
    }, 1000);
}

function nextTab() {
    var $tabs = $('#tabs').tabs();
    var selected = $tabs.tabs('option', 'selected');
    if (selected==0) {
        selected = <?php echo $popArray['packageInfo']['package_id']==1?"2":'1' ?>;
    } else if (selected==2) {
        selected = <?php echo $musicModuleChoosen?"3":'4' ?>;
    } else if(selected<5) {
        selected++;
    }
    
    $tabs.tabs('select', selected);
    scrollToTop();
}

function scrollToTop() {
    
    $('html, body').animate({
    scrollTop: 0
    }, 1000);
}

function onSubmit() {
    var msg = '';
    if ($('#iconImage').val() == '') {
        msg += "- Select icon image (Step 1) \n";
    }

    if ($('#loadImage').val() == '') {
        msg += "- Select loading screen image (Step 2) \n";
    }

    var imageNotSet = true;
    for (i=1; i<=homeImages; i++) {
        if (getEl("homeImage-" + i).value != '') {
            imageNotSet = false;
            break;
        }
    }
    
    if ( imageNotSet)
        msg += "- Please select at least one home screen image (Step 3)\n";

    if (musicSelected && $('#iconImage').val() == '')
        msg += "- Please select music cover image (Step 4)\n";

    if ($('#aboutText').val() == '' || $('#bioText').val() == '') {
        msg += "- Please fill out about text and bio text (Step 5) \n";
    }

    if (msg!='') {
        alert ("You have following errors in the Application Wizard: \n\n" + msg + "\nPlease correct all errors before continue!");
        return false;
    } else {
        return true;
    }
}

var musicSelected = <?php echo $musicModuleChoosen?'true':'false'; ?>;
</script>

</head>
    
<body>
<div id="mainWideDiv">
  <div id="middleDiv2">
      <div id="body2">
  	<div id="header" style="width: 980px;">
	  <div id="logoContainer"><a href="index2.html"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
          <div class="tahoma_12_white2" id="loginBox" style="text-align: right; width: 280px;">
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>

        </div>
	<div id="body">

    
    <form action="AppWizardController.php?action=write_xml" method="post" onsubmit="return onSubmit();" >
        <input type="hidden" name="iconImage" id="iconImage"/>
        <input type="hidden" name="iTunesArtWork" id="iTunesArtWork"/>
        <input type="hidden" name="faceBookPostImage" id="faceBookPostImage"/>
        <input type="hidden" name="loadImage" id="loadImage" value="<?php echo $popArray['packageInfo']['package_id']==1?'../../../images/free.jpg':'' ?>"/>
        <input type="hidden" name="musicImage" id="musicImage" value=""/>
        <input type="hidden" name="homeImageCount" id="homeImageCount" value="<?php echo $numHomeScreens ?>"/>
   <?php for($i=1; $i<=$numHomeScreens; $i++) { ?>
   		<input type="hidden" name="homeImage-<?php echo $i; ?>" id="homeImage-<?php echo $i; ?>" value=""/>

   <?php } ?>        
<div id="tabs" style="width:975px;">
    <ul>
        <li><a href="#tabs-1">Icon</a></li>
        <li><a href="#tabs-2" <?php echo $popArray['packageInfo']['package_id']==1?"style='display: none'":'' ?>>Loading Image</a></li>
        <li><a href="#tabs-3">Home Images</a></li>
        <li><a href="#tabs-4" <?php echo !$musicModuleChoosen?"style='display: none'":'' ?>>Music Cover</a></li>
        <li><a href="#tabs-5">Information</a></li>
    </ul>

<!-- ******************************** Step 1 *********************************** -->

    <div id="tabs-1" style="height: 1120px;">
    
    <div class="awTitleText">Choose Application Icon</div>
 <!-- Image crop start -->
<?php
$width = 512;
$height = 512;
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
<div id="scrollToHere1"></div>
<!-- iphone preview start -->
       <div style="position: relative; height: 410px; width: 274px;">

   	  <img src="../../../images/icon_preview.png" style="position: absolute; height: 399px; width: 398px; top:0px; left:0px; z-index: 5;"/>
        <img src="../../../images/icon_blank.png" id="imgImageBlank" style="position: absolute; height: 57px; width: 57px; background:#993300; left: 202px; top: 245px; z-index:4"/>
       </div>
<!-- iphone preview end -->
<img src="../../../images/wizard_btn_next_step.png" onclick="nextTab();" style="cursor:pointer"/>
    </div>


<!-- ******************************** Step 2 *********************************** -->

    <div id="tabs-2" style="height: 1500px;" <?php echo $popArray['packageInfo']['package_id']==1?"style='display: none'":'' ?>>
    <div class="awTitleText"><?php echo $popArray['packageInfo']['package_id']==1?"You can't choose home screen image for free package":'Choose Loading Screen Image' ?></div>
 <!-- Image crop start -->
<?php
$width = 320;
$height = 480;
?>
<?php if ($popArray['packageInfo']['package_id']!=1) {?>
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
<?php } ?>
<!-- iphone preview start -->

       <div style="position: relative; height: 770px; width: 425px" id="scrollToHere2">

   	<img src="../../../images/home_preview.png" style="position: absolute; height: 769px; width: 420px; top:0px; left:0px; z-index: 4;"/>
        <div id="imgHome" style="position: absolute; height: 480px; width: 320px; background-image:url(../../../images/empty_bg.png);   left: 54px; top: 156px; z-index:5; left: 54px;"><?php echo $popArray['packageInfo']['package_id']==1?"<img src='../../../images/free.jpg' width='320' height='480'/>":'' ?></div>
      </div>
<!-- iphone preview end -->
<img src="../../../images/wizard_btn_next_step.png" onclick="nextTab();" style="cursor:pointer"/>
   	 </div>


<!-- ******************************** Step 3 *********************************** -->
<div id="tabs-3">
<div class="awTitleText">Choose home screen images</div>
     <!-- Image crop start -->
    <?php
    $width = 320;
    $height = 367;
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
    <!-- iphone preview start -->
   <div style=" "></div>
   <?php for($i=1; $i<=$numHomeScreens; $i++) { ?>
	<div class="homeImageSt">
    	<div class="homeScreenInner" id="homeScreenDiv-<?php echo $i; ?>"></div>
    	<div><a href="javascript: clearHomeImage(<?php echo $i; ?>)">Clear</a> | <a href="javascript: scrollToTop();">Go to Top</a></div>
    </div>
   <?php } ?>
   
    <!-- iphone preview end -->
<img src="../../../images/wizard_btn_next_step.png" onclick="nextTab();" style="cursor:pointer"/>

</div>

<!-- ******************************** Step 4 *********************************** -->

    <div id="tabs-4" style="height: 1500px;" <?php echo !$musicModuleChoosen?"style='display: none'":'' ?>>
    <div class="awTitleText"><?php echo $musicModuleChoosen?"Please select music module cover image":'You haven\'t selected Music module. Please skip this step.' ?></div>
 <!-- Image crop start -->
<?php
$width = 320;
$height = 191;
?>
<?php if ($musicModuleChoosen) {?>
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

<!-- iphone preview start -->

       <div style="position: relative; height: 770px; width: 425px" id="scrollToHere4">

   	<img src="../../../images/app_wizard_music.png" style="position: absolute; height: 747px; width: 397px; top:0px; left:0px; z-index: 4;"/>
        <div id="imgMusic" style="position: absolute; height: 191px; width: 320px; background-image:url(../../../images/empty_bg.png); left: 38px; top: 205px; z-index:3; "></div>
      </div>
<!-- iphone preview end -->

<?php } ?>
<img src="../../../images/wizard_btn_next_step.png" onclick="nextTab();" style="cursor:pointer"/>
   	 </div>


    <div id="tabs-5">
<div class="awTitleText">Add details to the application</div>
      <table width="533" border="0" cellspacing="4" cellpadding="4">
        <tr>
          <td width="60" valign="top"><strong>About</strong></td>
          <td width="445"><textarea name="aboutText" id="aboutText" cols="45" rows="5"></textarea></td>
        </tr>
        <tr>
          <td valign="top"><strong>Bio</strong></td>
          <td><textarea name="bioText" id="bioText" cols="45" rows="5"></textarea></td>
        </tr>
      </table>

<br/>
<input type="image" src="../../../images/wizard_btn_complete_application.png" value="Complete Application"/>
    </div>
</div>
</form>
	<div id="buttonContainer">&nbsp;</div>
        </div>
  </div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>


</body>
</html>
