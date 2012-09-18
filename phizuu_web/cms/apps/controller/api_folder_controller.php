<?php
class ApiStructure
{
public function create_structure($chk)
{ 		
		if(mkdir("../api/".$chk,0777)){
		//main index
				$file_content='{
						"image_set_uri" : "images/",
						"audio_playlist_uri" : "music/",
						"video_playlist_uri" : "videos/",
						"news_uri" : "news/",
						"twitter_uri" : "http://twitter.com/statuses/user_timeline/42182589.rss",
						"events_uri" : "tours/",
						"flyer_image_set_uri" : "flyers/",
						"links_uri" : "links/"
						}';
		
		$file_opened=fopen("../api/".$chk."/index.html",'w');
		fwrite($file_opened,$file_content);

		
		//create sub folders
		
		$folder_arr = array('music' ,'video','news' ,'tours','pictures','setting','package');
		
			for($x=0; $x<sizeof($folder_arr); $x++){

			mkdir("../api/".$chk."/".$folder_arr[$x],0777);
			$file_sub_content='';
			
			$file_sub_opened=fopen("../api/".$chk."/".$folder_arr[$x]."/index.html",'w');
			fwrite($file_sub_opened,$file_sub_content);
			}
	
		
		}
	}
	
	
	public function create_subfolder_only($appid,$folder)
	{
			//create sub folders
		
		$folder_arr = array($folder);
		
			for($x=0; $x<sizeof($folder_arr); $x++){

			mkdir("../api/".$appid."/".$folder_arr[$x],0777);
			
			}
	}
	
	public function write_file($content,$appid,$folder)
	{
			$file_sub_content=$content;
			$structure = new ApiStructure();
			//folder exists
			if(!file_exists("../api/".$appid."/")){
			
			$structure->create_structure($appid);
			}
			else if(!file_exists("../api/".$appid."/".$folder."/")){
			
			$structure->create_subfolder_only($appid,$folder);
			}
			
			$file_sub_opened=fopen("../api/".$appid."/".$folder."/index.html",'w');
			fwrite($file_sub_opened,$file_sub_content);
	}
	
}	
?>