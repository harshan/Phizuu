<?php
if(isset($_GET['update'])){
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../config/error_config.php');
}
@session_start();
$bpic= new Picture();
$bank_pic = $bpic->listBankPics($_SESSION['user_id']);
$count=1;


$response_listb =' 
		<div class="photoHolderBox">
<ul  id="list_1">
';
    
	  if(sizeof($bank_pic) >0){
	  foreach($bank_pic as $bpic){
      
    $response_listb .='  <li id="list_1_item_'.$bpic->id.'" >
	<table border="0">
      <tr>
      <td>
	<div class="photoBox">
	  <div class="photo"><img src="'.$bpic->thumb_uri.'" width="75" height="75" /></div>
	  <div class="photoLower">
				<div class="photoName edit" id="1_'.$bpic->id.'">'.$bpic->name .'</div>
				<div id="icon"><a href="../../../controller/pic_add_iphone_controller.php?id='.$bpic->id.'&status=delete"  onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
			</div>
	  	</div>
		 </td>
      </tr>
      </table>
		</li>';
      
      
	   if($count % 5 == 0){
	  $response_listb .='<br>';
	  }
	  
	  $count++;
	  
	 
	  }
	  }
     $response_listb .=' </ul>
	 </div>
      ';
	  
	  echo $response_listb;
	  ?>