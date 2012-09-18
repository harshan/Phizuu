<?php
include('../config/config.php');
require_once '../config/database.php';
include('../controller/news_controller.php');
include('../model/news_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

$news= new News();
if(isset($_POST['name'])) {
$play_val[0] = array('title' => $_POST['name'],'date' =>$_POST['date'],'notes' => $_POST['notes']);
$newId = $news->addNews($play_val);
$count = 500;
$lst_news = array('id'=>$newId,'title'=>$_POST['name'], 'description'=>$_POST['notes'], 'date' =>$_POST['date']);
 $text = '<li id="id_'.$newId.'">
                    <div class="dragHandle"></div>
                    <div class="title click" id="div1_'.$newId.'_'.$count.'">'.$_POST['name'].'</div>
                    <div class="date"><input type="text" name="hid2_'.$count.'" id="hid2_'.$count.'" value="'.$_POST['date'].'"  maxlength="10" size="12" readonly /><img src="../../../images/cal.gif" id="btn2_'.$count.'" onclick="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$newId.'_'.$count.'\');" onmouseover="calendar(\'btn2_'.$count.'\',\'hid2_'.$count.'\',\'div4_'.$newId.'_'.$count.'\');" /></div>
                    <div class="description click" id="div3_'.$newId.'_'.$count.'">'.$_POST['notes'].'</div>
<div class="action" ><img src="../../../images/cross.png" style="cursor: pointer" onclick="deleteItem('.$newId.')"/></div>
</li>';
   $arr = array ('status'=>'success','text'=>$text);

echo json_encode($arr);
}
else {

     $arr = array ('status'=>'failed','text'=>$text);

echo json_encode($arr);
}

function write_json(){
$json_class= new jsonClass();
$api_structure= new ApiStructure();
$json_news_stream = $json_class->streamNews($_SESSION['user_id']);
$api_structure->write_file($json_news_stream,$_SESSION['app_id'],'news');
}
?>
