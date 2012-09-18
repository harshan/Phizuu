<?php
@session_start();
if(empty($_REQUEST['flickrUser'])){
}
else{
$_SESSION['flickr_User']=$_REQUEST['flickrUser'];

 include("../../../config/config.php");
 include("../../../controller/flickr_controller.php");
}

$flickr= new Flickr();
if(!empty($_SESSION['flickr_User'])){
	$user = $flickr->Flickr($_SESSION['flickr_User']);
	
	 $_SESSION['flickr_User_id']=$user;
	 $playlists = $flickr->getPhotoSets($_SESSION['flickr_User_id']);
	 $response_cat='';
	  $sets = $playlists['sets'];
	 
		 foreach($sets as $playlist_val){
			if(!empty($playlist_val)){
			   $title=$playlist_val['title'];
			   $id=$playlist_val['id'];
			   $description=$playlist_val['description'];
			   $count=$playlist_val['photos'];
			   $thumb=$playlist_val['image'];
			 
			 $response_cat .=' 
			 <div id="photoHolderBox">
					  <div class="photoBox">
						<div class="photoNone"><img  alt="'.$title.'" src="'.$thumb.'" width="75" height="75" /></div>
					  </div>
						<div class="tahoma_12_ash" id="videoYoutubeText"><a href="#" onclick="showHint(\'list_photos_by_cat_a_tbl\',\''.$id.'\')"   class="tahoma_12_ash">'. $description.'('.$count.')</a></div>
					</div>
					 ';
			  }
	  }
 
 //all category 
 
   $playlists2 = $flickr->getPhotos2();
   $response_cat .='
   <div id="photoHolderBox">
   
		  <div class="photoBox">
		  	<div class="photoNone"><img  src="'.$playlists2[0]['thumb'].'" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="#" onclick="showHint(\'list_photos_by_cat_a_tbl\',\'\')"  class="tahoma_12_ash">All Photos ('.sizeof($playlists2).')
	</a></div>
	  	</div>
  ';
  //end all category
}else{
  $response_cat .='
   <div id="photoHolderBox">
   
		  <div class="photoBox">
		  	<div class="photoNone"><img  src="../../../images/photo.jpg" width="75" height="75" /></div>
		  </div>
			<div class="tahoma_12_ash" id="videoYoutubeText"><a href="../settings/settings_new.php"  class="tahoma_12_ash">Please click here to add Flickr account to select Photos
	</a></div>
	  	</div>
  ';
		
}
echo $response_cat;
?>