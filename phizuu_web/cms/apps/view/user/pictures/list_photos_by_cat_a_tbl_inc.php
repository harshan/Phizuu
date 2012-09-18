<?php


$notAddedCnt = 0;

$response ='
<div id="page_contents"><table border="0"  width="800">
  
  	<div id="log">
	<div id="log_res">
		<!-- spanner -->
	</div>
	</div>
  <tr>
    <td>';
if(isset($response1)) {
    echo $response1;
}
$response .='</td>
    <td>&nbsp;</td>
  </tr>
  <div id="photoHolderBox">';

$count=0;

for($x=0;$x<count($playlists); $x++) {


    $title=$playlists[$x]['title'];
    $thumb=$playlists[$x]['thumb'];
    $image=$playlists[$x]['image'];

    $pid=$playlists[$x]['pid'];

    $pic= new Picture();
    $pic_det = $pic->getPicByUri($pid);

    $response .='
	<div id="photoBoxVideo">
		  	<div class="photoNone"><img src="'.$thumb.'" width="75" height="75" /></div>	
	';

    if(empty($pic_det->pic_id)) {
        $notAddedCnt++;

        $response .='<div id="vidAddBut">
	<a href="#" onclick="showHint2(\''. '../../../controller/pic_add_controller'.'\',\''.$title.'\',\''.urlencode($image).'\',\''.urlencode($thumb).'\',\''.$id.'\',\''.$pid.'\')"><img src="../../../images/btn_add.png" width="83" height="25" border="0" />
			 </a>
			 </div>';
    }
    else {
        $response .='
	<div id="vidAddBut">
	<img src="../../../images/btn_added.png" width="83" height="25" border="0" onclick="javascript:alert(\'This photo is already added to the list!\')"/></div>';
    }
    $response .='<span id="txtHint"></span>';
    $response .='</div>';
    $count++;
    if($count % 5 == 0) {
        $response .='</div><div id="photoHolderBox">';
    }
    else {
        $response .='';
    }


}
$response .='';
if($notAddedCnt>0) {
    $response .= '
 
	<div id="photoHolderBox">
			<div id="addAll"><a href="#" onclick="showHint2(\''.addslashes('../../../controller/pic_add_controller').'\',\'\',\'\',\'\',\''.$id.'\',\'\')"><img src="../../../images/btn_add_all.png" width="96" height="25" border="0" /></a></div>
		</div>
	
  
';
}
?>