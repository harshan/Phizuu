<?php
class Video
{
	public static function addVideos($video_arr,$play_list){
	$video = new VideoModel();
	
	return $video -> addVideos($video_arr,$play_list);
	
	}
	
	public static function addAllVideos($video_arr,$play_list){
	$video = new VideoModel();
	
	$chk_user=$video -> addAllVideos($video_arr,$play_list);
	
	}
	
	public static function listBankVideos($user_id){
	$video = new VideoModel();
	
	return $bank_videos=$video -> listBankVideos($user_id);

	}
	
	public static function listIphoneVideos($user_id){
	$video = new VideoModel();
	
	return $iphone_videos=$video -> listIphoneVideos($user_id);
	
	}
	
	public static function getVideo($id){
	$video = new VideoModel();
	return $data_video=$video -> getVideo($id);
	
	}
	
	public static function getVideoByUri($uri){
	$video = new VideoModel();
	return $data_video=$video -> getVideoByUri($uri);
	
	}
        
        public static function getVideoNameById($id){
            $video = new VideoModel();
            return $video->getVideoNameById($id);
        }
}
?>