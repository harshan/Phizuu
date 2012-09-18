<?php
if(isset($_GET['update'])){
include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../config/error_config.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');
}
@session_start();

$ipic= new Picture();
$iphone_pic = $ipic->listIphonePics($_SESSION['user_id']);
$icount=1;

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'picture');

$response_listi ='
<div class="photoHolderBox">
<ul  id="list_2">
';

	   if(sizeof($iphone_pic) >0){

		 for($x=0; $x<sizeof($iphone_pic); $x++){

       $response_listi .=' <li id="list_2_item_'.$iphone_pic[$x] ->id.'">
	   <table border="0">
      <tr>
      <td>
	   <div class="photoBox">
        <div class="photo"><img src="'.$iphone_pic[$x] ->thumb_uri.'" width="75" height="75" /></div>
		<div class="photoLower">
				<div class="photoName edit" id="1_'.$iphone_pic[$x] ->id.'">'.$iphone_pic[$x] ->name .'</div>
				<div id="icon"><a href="../../../controller/pic_add_iphone_controller.php?id='.$iphone_pic[$x]->id.'&status=remove"  onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
			  </div>
		</div>
		 </td>
      </tr>
      </table>
	  </li>';
          
      
 
	   if($icount % 5 == 0){
	 $response_listi .='<br>';
	  }
	  
	  $icount++;
	  
	 
	  }
	  }

$response_listi .='</ul>
</div>
';
		
		echo $response_listi;
        ?>