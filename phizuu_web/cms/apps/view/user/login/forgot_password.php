<?php include('../../../config/error_config.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
	

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
    <div id="header">
        <div id="headerContent">
            <?php include("../../../view/user/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
    <div id="mainWideDiv">
  <div id="middleDiv2">
  	
	<div id="navigator">
	  
	</div>
	<div id="body">
            <?php if(isset($message)) { ?>
            <div class="tahoma_12_blue" style="float: left; padding-top: 30px; width: 100%; font-size: 14px; text-align: center" ><?php echo $message; ?></div>
            <?php } ?>
            <div id="buttonContainerSign" style="padding-left: 8px">
		<div id="bodyLeftPhotoEditing">
		
		<div id="lightBlueHeader">
	  	
                    <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 905px ">Forgot Password</div>
	  	
	  </div>
		
	    <div id="formHolderPhotoEditing">
	<div id="addMusicBttn2">
            <form action="../../../controller/modules/login/?action=recover_password" method="post">
                <div id="formRow">
                    <div class="tahoma_12_blue" id="formName2">Username</div>
                    <div id="formSinFeild2"><input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="username" id="textfield" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"/>
                    </div>

                </div>
                <div id="formRow">
                    <div class="tahoma_12_blue" id="formName2">
                        
                    </div>
                    <div id="formSinFeild2" class="tahoma_12_blue">
                    <?php if(isset($error) && $error!='') {

                            echo $error;
                            ?>

                        <?php } ?>
                    </div>
                </div>
                <div id="formRowButtons">
                    <div class="tahoma_12_blue" id="formName2"></div>
                    <div id="formSinFeild2"><input type="image" src="../../../images/btn_submit.png" name="button" id="button" width="84" height="28"/></div>
                </div>
            </form>
	</div>
	</div>
</div>
	</div>
	</div>
	<div id="buttonContainer">&nbsp;</div>
  </div><br class="clear"/> 
</div>
  <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
</html>
