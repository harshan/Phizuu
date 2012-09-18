<?php
require_once('../controller/settings_controller.php');
require_once '../config/database.php';
require_once('../config/error_config.php');
require_once('../config/config.php');
require_once('../controller/db_connect.php');
require_once('../controller/helper.php');

require_once('../model/settings_model.php');


//twitter
$setting_type=$_ENV['setting_twiter'];


$settings= new Settings();
$settingModel = new SettingsModel();

$rssId= $_ENV['setting_rssfeed'];
$rss_list = $settingModel->getRssFeed($rssId, $app_id);
if(sizeof($rss_list) >0) {
    $newsURL= $rss_list[count($rss_list)-1]->value;
} else {
    $newsURL= 'news\/';
}

$settings_list = $settings->listSettingsApi($app_id, $setting_type);

if(sizeof($settings_list) >0){
	  foreach($settings_list as $lst_settings){
		  if($lst_settings -> preferred == '1'){
		  $twitter_pref=$lst_settings -> value;
		  }
		  else{
		  $twitter=$lst_settings -> value;
		  }
	  }
	  
	  if(empty($twitter_pref)){
	  $twitter_pref=$twitter;
	  }
}
else{
$twitter_pref='';
}
$file_content='{
					"image_set_uri" : "images\/",
					"audio_playlist_uri" : "music\/",
					"video_playlist_uri" : "videos\/",
					"news_uri" : "'.$newsURL.'",
					"twitter_uri" : "'.addslashes($twitter_pref).'",
					"events_uri" : "tours\/",
					"flyer_image_set_uri" : "flyers\/",
					"links_uri" : "links\/"
				}';


echo $file_content;

?>