<?php
require_once("../../../config/config.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/news_controller.php');
require_once('../../../controller/pagination_controller.php');
require_once('../../../model/news_model.php');
require_once('../../../config/error_config.php');


$news= new News();
$count=1;


 $numrows = $news->listNewsAll($_SESSION['user_id']);
$news_list = $news->listNews($_SESSION['user_id'],0,$numrows);

 $response='';
  	  
      if(sizeof($news_list) >0){
	  foreach($news_list as $lst_news){
 $response .=   '<li id="id_'.$lst_news->id.'">
                    <div class="dragHandle"></div>
                    <div class="title click" id="div1_'.$lst_news->id.'_'.$count.'">'.$lst_news -> title.'</div>
                    <div class="date"><input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$lst_news -> date.'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$lst_news->id.'_'.$count.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$lst_news->id.'_'.$count.'\');" /></div>
                    <div class="description editable_textarea" id="div3_'.$lst_news->id.'_'.$count.'">'.$lst_news -> description.'</div>
                <div class="action" ><img src="../../../images/cross.png" style="cursor: pointer" onclick="deleteItem('.$lst_news->id.')"/></div>
    </li>';
   
	  $count++;
	  }
	  }

echo $response;
?>