<?php
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/video_controller.php');
include('../../../model/video_model.php');
include('../../../config/error_config.php');

$video= new Video();
$video_det = $video->getVideo($_GET['id']);
$count=1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<SCRIPT language="JavaScript">
function submitform()
{
    document.EditForm.submit();
}
function hideForm(){
parent.parent.GB_hide();
    window.top.location.reload();
}
</SCRIPT> 
</head>
<body>
<div id="bodyLeftPhotoEditing">
	<div id="titlePhoteEdit">
		<div class="tahoma_14_white" id="title">Edit Videos </div>
	</div>
	<div id="formHolderPhotoEditing">
	<div id="addMusicBttn2">
    <form method="post" action="../../../controller/video_add_iphone_controller.php" name="EditForm">

		<div id="formRow">
			<div class="tahoma_12_blue" id="formName2">Title</div>
			<div id="formSinFeild2"><input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="title" id="title" value="<?php echo $video_det->title;?>" /><?php if(isset($_REQUEST['msg_error'])){echo $msg_error;}?></div>
		</div>
		<div id="formRow">
			<div class="tahoma_12_blue" id="formName2">Duration</div>
			<div id="formSinFeild2"><input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="duration" id="duration" value="<?php echo $video_det->duration;?>" /></div>
		</div>
		<div id="formRow">
			<div class="tahoma_12_blue" id="formName2">Year </div>
			<div id="formSinFeild2"><input type="text" class="textFeildBoarder" style="width:227px; height:21px;" name="year" id="year" value="<?php echo $video_det->year;?>" /></div>
		</div>
		<div id="formRow">
			<div class="tahoma_12_blue" id="formName2">Note </div>
			<div id="formSinFeild2"><input type="text" class="textFeildBoarder" style="width:227px; height:21px;"  name="note" id="note" value="<?php echo $video_det->note;?>" />
			</div>
		</div>
		<div id="formRowButtons">
			<div class="tahoma_12_blue" id="formName2"></div>
			<div id="formSinFeild2"><input type="image" src="../../../images/save.png" name="button" id="button"width="83" height="25"  onclick="javascript: submitform();"/>&nbsp;&nbsp;<input type="image" src="../../../images/cancel.png" name="button1" id="button1"width="83" height="25" onclick="javascript: hideForm();" />
      <input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
      <input type="hidden" name="status" id="status" value="edit" />
            </div>
		</div>
        </form>
	</div>
	</div>
</div>
</body>
</html>
<?
if(isset($_GET['status']) && ($_GET['status'] == 'edited')){

echo "<script>parent.parent.GB_hide();
    window.top.location.reload();</script>";

}

?>