<?php
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/tours_controller.php');
include('../../../model/tours_model.php');
include('../../../config/error_config.php');

@session_start();
$tours= new Tours();
$tours_det = $tours->getTours($_GET['id']);
$count=1;

$response ='

<form name="addTours" id="addTours"  method="get" onSubmit="showHint(this)">
<table width="200" border="1">
  <tr>
    <td>Name</td>
    <td><input type="text" name="name" id="name" value="'.$tours_det->name.'" />';
	 if(isset($_REQUEST['msg_error'])){echo $msg_error;}
	 
	$response .=' </td>
  </tr>
  <tr>
    <td>Date</td>
    <td><input type="text" id="date" value="'.$tours_det->date.'" /><img src="../../../images/cal.gif" id="f_btn1" onclick="calendar();" onmouseover="calendar();" /></td>
  </tr>
    <tr>
    <td>Location</td>
    <td><input type="text" name="location1" id="location1" value="'.$tours_det->location.'" /></td>
  </tr>
  <tr>
    <td>Note</td>
    <td><input type="text" name="notes" id="notes" value="'.$tours_det->description.'" /></td>
  </tr>
  <tr>
    <td><!--<input type="submit" name="button2" id="button2" value="Save" />-->
      <input type="submit" name="button" id="button" value="Submit" /></td>
    <td><input type="reset" name="Reset" id="button" value="Cancel" />
      <input type="hidden" name="id" id="id" value="'. $_GET['id'].'" />
      <input type="hidden" name="status" id="status" value="edit" />
	  <input type="hidden" name="starting" id="starting" value="'. $_GET['starting'].'" />      </td>
  </tr>
</table>
</form>
';

echo $response;
?>