<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/tours_controller.php');
include('../model/tours_model.php');
include('../config/error_config.php');

include('../controller/db_connect.php');
include('../controller/helper.php');



		$id_arr=explode("_",$_POST['id']);
				
		if($id_arr[0] == "div1"){
		 $key='name';
		}
		else if($id_arr[0] == "div2"){
		 $key='location';
		
		}
		else if($id_arr[0] == "div3"){
		 $key='description';
		
		}
		else if($id_arr[0] == "div4"){
		 $key='date';
		
		}

$tours= new Tours();
if(isset($_POST['value'])) {
		$play_val[0] = array('key' => $key,'value' => $_POST['value'],'id' => $id_arr[1]);
		$chk = $tours->editInlineTours($play_val);
}
else {

}

echo $value=$_POST['value'];
?>
