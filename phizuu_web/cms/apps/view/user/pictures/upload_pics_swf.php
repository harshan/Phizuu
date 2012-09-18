<?php
$menu_item="photos";

include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../config/error_config.php');
require_once('../../../controller/flickr_controller.php');

$flickr= new Flickr();
$url = $flickr->upload_url($_SESSION['frob'],$_SESSION['token']);

?>
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
<link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../../js/swf_up/swfupload.js"></script>
<script type="text/javascript" src="../../../js/swf_up/swfupload.queue.js"></script>
<script type="text/javascript" src="../../../js/swf_up/fileprogress.js"></script>
<script type="text/javascript" src="../../../js/swf_up/handlers.js"></script>
<script type="text/javascript">
		var upload1, upload2;
		window.onload = function() {
		
			upload1 = new SWFUpload({
				// Backend Settings
				upload_url: "../../../controller/upload_controller_pics.php",
				post_params: {"PHPSESSID" : "<?php echo "1"; ?>","api_key" : "<?php echo $_ENV['flickr_key'];?>","auth_token" : "<?php echo $_SESSION['token']; ?>","api_sig" : "<?php echo $url; ?>","submit" : "Flickr"},

				// File Upload Settings
				file_size_limit : "102400",	// 100MB
				file_types : "*.jpg;*.gif;*.png",
				//file_types : "*.*",
				//file_types : "*.jpg;*.gif;*.png",
				file_types_description : "Image Files",
				file_upload_limit : "10",
				file_queue_limit : "0",

				// Event Handler Settings (all my handlers are in the Handler.js file)
				file_dialog_start_handler : fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "../../../images/up3.jpg",
				button_placeholder_id : "spanButtonPlaceholder1",
				button_width: 96,
				button_height: 25,
				
				// Flash Settings
				flash_url : "../../../flash/swf_up/swfupload.swf",
				

				custom_settings : {
					progressTarget : "fsUploadProgress1",
					cancelButtonId : "btnCancel1"
				},
				
				// Debug Settings
				debug: false
			});

			}
	</script>
</head>
	

<body>
<div id="mainWideDiv">
  <div id="middleDiv2">
    <?php include("../common/header.php");?>
    <?php include("../common/navigator.php");?>
    <div id="bodyPhotos">
      <div id="addMusicBttnAdmin">
        <div id="lightBlueHeader3">
          <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
          <div class="tahoma_14_white" id="lightBlueHeaderMiddle3">Upload Photos </div>
          <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
        </div>
        <div id="formRowUpload">
        <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
            
           		<div>
						<div class="fieldset flash" id="fsUploadProgress1"  style="width:255px">
							
						</div>
						<div style="padding-left: 5px;" class="tahoma_12_blue">
							<span id="spanButtonPlaceholder1"></span>
							<input id="btnCancel1" type="button"  value="                       " onclick="cancelQueue(upload1);" disabled="disabled" width="83" height="40" style="margin-left: 0px; height: 25px; font-size: 8pt; background-image:url(../../../images/cancel.png); width:83; border:0; background-repeat:no-repeat;" />
						
						</div>
				</div>
            </form>
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