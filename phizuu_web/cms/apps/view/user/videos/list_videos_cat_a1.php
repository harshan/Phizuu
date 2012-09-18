<?php
@session_start();
if(empty($_REQUEST['youUser'])) {
}
else {
    $_SESSION['YouTube_User']=$_REQUEST['youUser'];
    include("../../../controller/youTube_controller.php");
}

$youtube= new YouTube();




if(!empty($_SESSION['YouTube_User'])) {
    $playlists = $youtube->playlists($_SESSION['YouTube_User']);

                $response_cat ='<div id="photoHolderBox">
		  <div class="photoBox">
		  	<div class="photoNone"><img src="../../../images/video_utube.jpg" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="#" onclick="showHint(\'list_videos_by_cat_a1\',\'\')"  class="tahoma_12_ash">All
	</a></div>
	  	</div>
		';

                $response_cat .='<div id="photoHolderBox">
		  <div class="photoBox">
		  	<div class="photoNone"><img src="../../../images/video_utube.jpg" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="#" onclick="showHint(\'list_videos_by_cat_a1\',\'Favorites\')"  class="tahoma_12_ash">Favorites
	</a></div>
	  	</div>
		';

    if (!empty ($playlists )) {
        //echo "<div class='tahoma_12_ash'>Invalid Username!</div>";

    foreach($playlists as $playlist_val) {
        if(!empty($playlist_val)) {
            $title=$playlist_val['title'];
            $id=$playlist_val['id'];
            $description=$playlist_val['description'];
            $count=$playlist_val['count'];

            $response_cat .='<div id="photoHolderBox">
		  <div class="photoBox">
		  	<div class="photoNone"><img src="../../../images/video_utube.jpg" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="#" onclick="showHint(\'list_videos_by_cat_a1\',\''.$id.'\')"  class="tahoma_12_ash">'. $description.' ('.$count.')
	</a></div>
	  	</div>
		';
        }
    }
    }

}//end if size of $_SESSION['YouTube_User']
else {
    //link to settings add youTube account page
    $response_cat .='<div id="photoHolderBox">
		  <div class="photoBox">
		  	<div class="photoNone"><img src="../../../images/video_utube.jpg" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="../settings/settings_new.php"  class="tahoma_12_ash">Please click here to add youtube account to select videos
	</a></div>
	  	</div>
		';
}
$response_cat .='';
echo $response_cat;
?>