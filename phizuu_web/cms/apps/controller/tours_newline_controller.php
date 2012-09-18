<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/tours_controller.php');
include('../model/tours_model.php');
include('../config/error_config.php');
require_once ('../database/Dao.php');

require_once ('../common/SampleImage.php');

include('../controller/db_connect.php');
include('../controller/helper.php');


$tours= new Tours();
if(isset($_POST['name'])) {

    if($_FILES['flyerImage']['size']>0)
        $flyerFileName = $_FILES['flyerImage']['tmp_name'];
    else
        $flyerFileName = '';

    

    $play_val[0] = array('name' => $_POST['name'],'date' =>$_POST['date'],'location' => $_POST['location1'],'notes' => $_POST['notes'], 'ticketURL'=>$_POST['ticketURL'], 'flyerFileName'=>$flyerFileName);
    $tour_new_id = $tours->addTours($play_val);
    
}


if (!isset($_GET['wizard'])) {
   header("Location: ../view/user/tours/tours_new.php");
}else{
   header("Location: ../controller/modules/app_wizard/AppWizardControllerNew.php");
}
//$count=$_POST['count'];
   // add to database or something here
  
// $text = '<table cellpadding="0" cellspacing="0">
// <tr id="textBarNews">
//    <td  class="tahoma_12_blue" id="titleToursName"><div class="click" id="div1_'.$tour_new_id.'_'.$count.'" onclick="test();"  onmouseover="test();">'.$_POST['name'].'</div></td>
//    <td class="tahoma_12_blue" id="dateToursText">
//	<input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$_POST['date'].'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$tour_new_id.'_'.$count.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$tour_new_id.'_'.$count.'\');" />
//	</td>
//	<td class="tahoma_12_blue" id="locationToursText"><div class="click" id="div2_'.$tour_new_id.'_'.$count.'" onclick="test();"  onmouseover="test();">'.$_POST['location1'].'</div></td>
//    <td class="tahoma_12_blue" id="toursDescriptionText"><div class="click" id="div3_'.$tour_new_id.'_'.$count.'" onclick="test();"  onmouseover="test();">'.$_POST['notes'].'</div></td>
//	</tr>
//  </table>';

//          $text = '
//                <li id="id_'.$tour_new_id.'">
//                    <div class="dragHandle"></div>
//                    <div class="title edit" id="1_'.$tour_new_id.'">'.$_POST['name'].'</div>
//                    <div class="date" id="2_'.$tour_new_id.'"><input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$_POST['date'].'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'2_'.$tour_new_id.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'2_'.$tour_new_id.'\');" /></div>
//                    <div class="location edit" id="3_'.$tour_new_id.'">'.$_POST['location1'].'</div>
//                    <div class="description edit" id="4_'.$tour_new_id.'">'.$_POST['notes'].'</div>
//                </li>
//                ';
//
//   $arr = array ('status'=>'success','text'=>$text);
//
//echo json_encode($arr);
//
//}
//else {
//     $arr = array ('status'=>'failed','text'=>$text);
//
//echo json_encode($arr);
//}

?>
