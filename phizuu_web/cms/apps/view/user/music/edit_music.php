<?php
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/music_controller.php');
include('../../../model/music_model.php');
include('../../../config/error_config.php');
include("../../../controller/flickr_controller.php");
include('../../../controller/settings_controller.php');
include('../../../model/settings_model.php');

if(isset($_GET['id'])){
$_SESSION['music_id']=$_GET['id'];
}else{
$_GET['id']=$_SESSION['music_id'];
}

$music= new Music();
$music_det = $music->getMusic($_GET['id']);
$count=1;


//flickr

	$settings= new Settings();
	$get_prefered = $settings->getPrefered('3');
	
	if(!empty($get_prefered ->id)){
	$flickr= new Flickr();
	
	$user = $flickr->Flickr($get_prefered ->value);
	
	$playlists = $flickr->getPhotos2($user);
	
		if(!empty($get_prefered ->flickr_auth)){
				$_SESSION['token']=$get_prefered ->flickr_auth;
		
		}
	
	}

$flickr= new Flickr();
$url_up = $flickr->upload_url($_SESSION['frob'],$_SESSION['token']);

 
$_SESSION['redirect_page_name']='music/edit_music.php?id='.$_GET['id'].'';
$_SESSION['request_page_name']='music/edit_music.php?id='.$_GET['id'].'';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />
<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script language="javascript">
function select_image(ele,va) {
 selected= ele.id.split('_')[1];

var inputs = document.getElementsByTagName("div");
for(i=0; i<inputs.length; i++)
{
img=inputs[i].getAttribute('id');
if(img != null){

		if(img.split('_')[0] == "image")
		{
		document.getElementById(img).style.border = '3px solid #000';
		}
}
}
  document.getElementById(ele.id).style.border = '3px solid #CC0000';
  document.getElementById('pic_selected').value=selected;
  document.getElementById('pic_uri').value=va;
}

</script>


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
				post_params: {"PHPSESSID" : "<?php echo "1"; ?>","api_key" : "27709d2122ded1e92e8c083aa708b181","auth_token" : "<?php echo $_SESSION['token']; ?>","api_sig" : "<?php echo $url_up; ?>","submit" : "Flickr"},

				// File Upload Settings
				file_size_limit : "102400",	// 100MB
				file_types : "*.mp3",
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
<form enctype="multipart/form-data" action="../../../controller/music_add_iphone_controller.php" name="Editform" id="Editform" method="post">
<div id="musicEditArea">
	<div id="musicEditInner">
	<div id="musicEditTxtAreaL">
		<div class="tahoma_12_blue" id="title">Title</div>
			<div id="musicEditTxtArea"><input type="text" class="textfield" style="width:400px" name="title" id="title" value="<?php echo $music_det->title;?>"  /></div>
			<div class="tahoma_12_blue" id="title">Duration</div>
			<div id="musicEditTxtArea"><input type="text" class="textfield" style="width:400px" name="duration" id="duration" value="<?php echo $music_det->duration;?>" /></div>
			<div class="tahoma_12_blue" id="title">iTunes uri </div>
			<div id="musicEditTxtArea"><input type="text" class="textfield" style="width:400px" name="itune_uri" id="itune_uri" value="<?php echo $music_det->itunes_uri;?>" /></div>
			<div class="tahoma_12_blue" id="title">Album</div>
			<div id="musicEditTxtArea"><input type="text" class="textfield" style="width:400px" name="album" id="album" value="<?php echo $music_det->album;?>" /></div>
			<div class="tahoma_12_blue" id="title">Year</div>
			<div id="musicEditTxtArea"><input type="text" class="textfield" style="width:400px" name="year" id="year" value="<?php echo $music_det->year;?>"  /></div>
			<div class="tahoma_12_blue" id="title">Note</div>
			<div id="musicEditTxtArea"><textarea rows="5" class="textfield" style="width:400px" name="note" id="note" value="<?php echo $music_det->note;?>"></textarea></div>
	  </div>
		<div id="musicEditTxtAreaR"><img src="../../../images/x_close.png" width="18" height="17" /></div>
	</div>
	
	<div id="musicEditInnerPlain">
	<div id="musicEditFileUploadMain">
    
	   
		<div id="musicEditFileUploadL" <?php if(empty($get_prefered ->id)){?> style="visibility:hidden" <?php }?>>
        
        <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
            
           		<div>
						<div class="fieldset flash" id="fsUploadProgress1"  style="width:255px">
							
						</div>
						<div style="padding-left: 5px;" class="tahoma_12_blue">
							<span id="spanButtonPlaceholder1"></span>
							<input id="btnCancel1" type="button"  value="                          " onclick="cancelQueue(upload1);" disabled="disabled" width="83" height="40" style="margin-left: 0px; height: 27px; font-size: 8pt; background-image:url(../../../images/cancel.png); width:83; border:0; background-repeat:no-repeat;" />
						
						</div>
				</div>
            </form>
          
		</div>
        
		<div id="musicEditFileUploadL">
			<div id="musicEditFileUTxtArea">&nbsp;</div>
			<div id="musicEditFileUTxtArea">&nbsp;</div>
			<div id="musicEditFileUTxtArea">&nbsp;</div>
	  </div>
      		<div id="musicEditFileUploadL">
			<div id="musicEditFileUTxtArea">&nbsp;</div>
			<div id="musicEditFileUTxtArea">&nbsp;</div>
			<div id="musicEditFileUTxtArea">&nbsp;</div>
	  </div>
	  <div id="musicEditFileUploadL">
			<div id="musicEditFileUTxtArea">&nbsp;</div>
			<div id="musicEditFileUTxtArea"><!--<input type="image" src="../../../images/save2.png" onclick="Editform.submit();" width="83" height="25" />--><a href="#" onclick="Editform.submit();"><img src="../../../images/save2.png" width="83" height="25" border="0" align="top" /></a> &nbsp;&nbsp; <a href="index.php"><img src="../../../images/cancel.png" width="83" height="25" border="0" align="top" /></a></div>
	  </div>
		</div>
		<div id="musicEditFileUploadR">
        <div id="musicEditFileUploadR">
        <input type="hidden" name="pic_selected" id="pic_selected" />
        <input type="hidden" name="pic_uri" id="pic_uri" value="<?php echo $music_det->image_uri;?>" />
		<?php
		  if(!empty($get_prefered ->id)){
            foreach($playlists as $play_val){

			$title=$play_val['title'];
			$thumb=$play_val['thumb'];
			$image=$play_val['image'];
			$pid=$play_val['pid'];
			$photo_id=$play_val['pid'];
			
		
		?>
                        
        <div id="photoBoxVideo2">
            <div class="photoNone">
                <div id="image_<?php echo $photo_id; ?>" onclick="select_image(this,'<?php echo $image;?>')" class="image_div">
                    <img border="0" alt="<?php echo $title; ?>" src="<?php echo $thumb; ?>"  width="75" height="75" />
                </div>
            </div>
        </div>

	
        <?php	
			if($count % 5 == 0){
			echo'</div><div id="musicEditFileUploadR">';
			  }
	  
	  $count++;
	  ?>

<?php
			
			if($photo_id == $music_det->image_id){
		echo"<script>";

		echo "document.getElementById('image_".$photo_id."').style.border = '3px solid #CC0000';";
  		echo "document.getElementById('pic_selected').value=".$photo_id.";";
		echo"</script>";
		}
			}
		}
	?>
    </div><!--for count5-->
		
        </div>
	</div>
	
	
	
</div>
<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
      <input type="hidden" name="status" id="status" value="edit" />
</form>
</body>
</html>
<?
if(isset($_GET['status']) && ($_GET['status'] == 'edited')){
echo "test";
echo "<script>parent.parent.GB_hide();
    window.top.location.reload();</script>";
}

?>