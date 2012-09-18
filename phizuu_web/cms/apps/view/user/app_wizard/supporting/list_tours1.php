<?php
require_once("../../../config/config.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/tours_controller.php');
require_once('../../../model/tours_model.php');

require_once('../../../config/error_config.php');


$tours= new Tours();
$numrows = $tours->listToursAll($_SESSION['user_id']);
$tours_list = $tours->listTours($_SESSION['user_id'],0,$numrows);
$count=1;


// $response='<table cellpadding="0" cellspacing="0">';
  	  $response = '';
      if(sizeof($tours_list) >0){
	  foreach($tours_list as $lst_tours){
// $response .= '<tr id="textBarNews">
//    <td  class="tahoma_12_blue" id="titleToursName"><div class="click" id="div1_'.$lst_tours->id.'_'.$count.'" >'.$lst_tours -> name.'</div></td>
//    <td class="tahoma_12_blue" id="dateToursText">
//	<input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$lst_tours -> date.'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$lst_tours->id.'_'.$count.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$lst_tours->id.'_'.$count.'\');" />
//	</td>
//	<td class="tahoma_12_blue" id="locationToursText"><div class="click" id="div2_'.$lst_tours->id.'_'.$count.'">'.$lst_tours -> location.'</div></td>
//    <td class="tahoma_12_blue" id="toursDescriptionText"><div class="click" id="div3_'.$lst_tours->id.'_'.$count.'">'.$lst_tours -> description.'</div></td>
//	 </tr>';
              $imageText = '';
              
              if ($lst_tours->thumb_url=='') {
                    $imageText .= "<img style='border: #043F53 2px solid' title='Click to edit..' alt='Click to edit..' onclick='editPic({$lst_tours->id})'  height=46 width=46 src='{$defaultImage}'/>";
              } else {
                 $imageText .= "<img style='border: #043F53 2px solid' title='Click to edit..' alt='Click to edit..' onclick='editPic({$lst_tours->id})'  height=46 width=40 src='{$lst_tours->thumb_url}'/>";
              }
         $response .= '
                <li id="id_'.$lst_tours->id.'">
                    <div class="dragHandle"></div>
                    <div style="float:left;background-color: #d3d3d3;height:64px"><div class="title edit" id="1_'.$lst_tours->id.'">'.$lst_tours -> name.'</div></div>
                    <div class="date"><input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$lst_tours -> date.'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'2_'.$lst_tours->id.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'2_'.$lst_tours->id.'\');" /></div>
                    <div class="location edit" id="3_'.$lst_tours->id.'">'.$lst_tours -> location.'</div>
                    <div style="float:left;background-color: #d3d3d3;height:64px"><div class="description edit" id="4_'.$lst_tours->id.'">'.$lst_tours -> description.'</div></div>
                    <div class="ticketURLWizard edit" id="5_'.$lst_tours->id.'">'.$lst_tours->ticket_url.'</div>
                    <div class="thumb" id="6_'.$lst_tours->id.'">'.$imageText.'</div>
                             <div class="ttip" style="float:left">
                    <div class="action" id="delete_'.$lst_tours->id.'" style="width:26px"><img src="../../../images/cross.png" style="cursor: pointer;" onclick="deleteItem('.$lst_tours->id.')"/></div>
                        </div>
                          <div id="div_tooltip_common_'.$lst_tours->id.'" class="div_tooltip_common">Delete</div>
                </li>
                ';
	  $count++;
	  }
	  }
// $response .= '</table>
//';

//	// $response .= '<!--<div id="body">
//	  <div id="bodyLeft">
//	  	<div id="titleBox">
//		  <div class="tahoma_14_white" id="title">a</div>
//		  <div class="tahoma_14_white" id="duration">b</div>
//		  <div class="tahoma_14_white" id="note">c</div>
//		</div></div></div>-->';
echo $response;
?>