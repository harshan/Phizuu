<?php
if(isset($_GET['name'])){
require_once('pagination_controller.php');
}else{
require_once('../../../controller/pagination_controller.php');
}


//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$recpage = 10;//number of records per page
$ending=$starting+$recpage;
$numrows =sizeof($file);


$pagename="list_music_by_cat_a_tbl.php?id=$id";

$obj = new pagination_class($numrows,$starting,$recpage,$pagename);

  $response='<div id="page_contents">
<table width="200" border="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>';
    $count=0;

  for ($i=$starting, $tree_count; $i<$ending; $i++) {
	
if ($file[$i]['file_name'] != ''){

$file_id=$file[$i]['file_id'];
$music= new Music();
$music_det = $music->getMusicByUri($file_id);

 $response .=' <tr>
    <td><img  border="0" alt="'.$title.'" src="'.$thumb.'"  width="100" height="100"/>'.$title."aaa".'</td>
    <td>'. $file[$i]['file_name'].$file[$i]['file_id'].'('.$count.')
	<br>';
	if(empty($music_det->music_id)){
	 $response .= '<a href="#"  onclick="showHint2(\''.addslashes('../../../controller/music_add_controller').'\',\''.$file[$i]['file_name'].'\',\''.$file[$i]['file_id'].'\',\''.$folder_id1.'\')">Add</a>';
	 }else{
	$response .='Added';
	}
	
 $response .=	'</td>
  </tr>';
    $count++;
}//if
 
   if($count % 2 == 0){
  $response .='</tr><tr>';
  }
  else{
  $response .='<td>';
  }
 }//for
 $response .='
 
</table>';

$response .= '
 <table  border="1" width="600"><tr>
    <td>'.$obj->anchors.'</td>
  </tr>
  <tr>
    <td>'.$obj->total.'</td>
  </tr>
 <tr>
    <td colspan="2"><a href="#" onclick="showHint2(\''.addslashes('../../../controller/music_add_controller').'\',\'\',\'\',\''.$folder_id1.'\')"> Add All</a></td>
    <td>&nbsp;</td>
  </tr>
  </table></div>';
?>