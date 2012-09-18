<?php

 $response ='<div id="page_contents"><table border="0"  width="100%">
  
  	<div id="log">
	<div id="log_res">
		<!-- spanner -->
	</div>
	</div>
  <tr>
    <td>'.$response1.'</td>
    <td>&nbsp;</td>
  </tr>
  <div id="photoHolderBox">';

  $count=0;

 for($x=0;$x<count($playlists); $x++){

	$title=$playlists[$x]['title'];
	$note=$playlists[$x]['note'];
	$year=$playlists[$x]['year'];
	$uri=$playlists[$x]['uri'];
	$duration=$playlists[$x]['duration'];
	//$description=$playlists[$x]['description'];
	$thumb=$playlists[$x]['thumb'];
	$vid=$playlists[$x]['vid'];
	$vid_gp3=$playlists[$x]['vid_gp3'];



$video= new Video();
$video_det = $video->getVideoByUri($vid);

  	$response .='
	<!--<div id="form_box">-->
	<div id="photoBoxVideo">
		  	<div class="photoNone"><img src="'.$thumb.'" width="75" height="75" /></div>	
	';
	
	$response .='';

if(empty($video_det->video_id)){
    $response .='<div id="vidAddBut">
	<a href="#" onclick="showHint2(\''.addslashes('../../../controller/video_add_controller').'\',\''.$title.'\',\''.$note.'\',\''.$year.'\',\''.urlencode($uri).'\',\''.$duration.'\',\''.$id.'\',\''.$vid.'\',\''.urlencode($thumb).'\',\''.urlencode($vid_gp3).'\')"><img src="../../../images/btn_add.png" width="83" height="25" border="0" />
			 </a>
			 </div>';

	}
	else{
	$response .='
	<div id="vidAddBut">
	<img src="../../../images/btn_added.png" alt="This video is already added to the list!" width="83" height="25" border="0" onclick="javascript:alert(\'This video is already added to the list!\')"/></div>';
	}
     
	 $response .='<span id="txtHint"></span>';
$response .='</div>';
  $count++;
  if($count % 5 == 0){
  $response .='</div><div id="photoHolderBox">';
  }
  else{
 $response .='';
  }
  

  
  }
 $response .=' 
</table>';
$response .= '
 <table  border="0" width="100%"><tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>
	<div id="photoHolderBox">
			<div id="addAll">
	<a href="#" onclick="showHint2(\''.addslashes('../../../controller/video_add_controller').'\',\'\',\'\',\'\',\'\',\'\',\''.$id.'\',\'\',\'\',\'\')"> <img src="../../../images/btn_add_all.png" width="96" height="25" border="0" /></a>
	</div>
		</div>
	</td>
    <td>&nbsp;</td>
  </tr>
  </table></div>
';

?>