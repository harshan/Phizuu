<?php
@session_start();
include("../../../config/config.php");
include '../../../controller/boxnet/box_config.php';
require_once '../../../config/database.php';
include('../../../controller/music_controller.php');
include('../../../model/music_model.php');
include('../../../config/error_config.php');
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');



// Get Ticket to Proceed
if($_SESSION['auth_token'] == ''){

	$ticket_return = $box->getTicket ();
	
	if ($box->isError()) {
		echo $box->getErrorMsg();
	} else {
		
		$ticket = $ticket_return['ticket'];
	
	}
}

$id=$_REQUEST['id'];

$tree = $box->getAccountTree ();
$file = $box->getFileList ($tree,$tree_count);

include('list_music_by_cat_a_tbl_inc.php');

echo $response;
?>