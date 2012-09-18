<?php
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/news_controller.php');
include('../../../model/news_model.php');
include('../../../config/error_config.php');

@session_start();
$news= new News();
$news_det = $news->getNews($_GET['id']);
$count=1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<SCRIPT language="JavaScript">
function submitform()
{
    document.EditForm.submit();
}
</SCRIPT> 
<script type="text/javascript" src="../../../js/calendar/jquery-1.3.2.js"></script>
<script type="text/javascript" src="../../../js/calendar/jquery.datepick.js"></script>
<script type="text/javascript">
$(function() {
//	$.datepick.setDefaults({useThemeRoller: true});
	$('#date').datepick();
	//$('#inlineDatepicker').datepick({onSelect: showDate});
});

function showDate(date) {
	alert('The date chosen is ' + date);
}
</script>
<style type="text/css">
@import "../../../css/calendar/jquery.datepick.css";

</style>
</head>

<body>
<form method="post" action="../../../controller/news_add_iphone_controller.php" name="EditForm">
<table width="200" border="1">
  <tr>
    <td>Title</td>
    <td><input type="text" name="title" id="title" value="<?php echo $news_det->title;?>" /></td>
  </tr>
  <tr>
    <td>date</td>
    <td><input type="text" id="date" value="<?php echo $news_det->date;?>" /></td>
  </tr>
  <tr>
    <td>Note</td>
    <td><input type="text" name="notes" id="notes" value="<?php echo $news_det->description;?>" /></td>
  </tr>
  <tr>
    <td>
      <input type="button" name="button3" id="button3" value="save" onclick="javascript: submitform();" /></td>
    <td><input type="reset" name="Reset" id="button" value="Cancel" />
      <input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>" />
      <input type="hidden" name="status" id="status" value="edit" />      </td>
  </tr>
</table>
</form>
</body>
</html>
<?
if(isset($_GET['status']) && ($_GET['status'] == 'edited')){

//echo "data edited";
echo "<script>parent.parent.GB_hide();
    window.top.location.reload();</script>";
}

?>