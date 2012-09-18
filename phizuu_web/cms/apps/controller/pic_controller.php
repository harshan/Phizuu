<?php
class Picture
{
	public static function addPics($pic_arr,$play_list){
	$pic = new PicModel();
	
	return $pic -> addPics($pic_arr,$play_list);

	
	}
	
	public static function addAllPics($pic_arr,$play_list){
	$pic = new PicModel();
	$chk_user=$pic -> addAllPics($pic_arr,$play_list);
	
	}
	
	public static function listBankPics($user_id){
	$pic = new PicModel();
	
	return $bank_pics=$pic -> listBankPics($user_id);
	
	}
	
	public static function listIphonePics($user_id){
	$pic = new PicModel();
	
	return $iphone_pics=$pic -> listIphonePics($user_id);
	
	}
	
	public static function getPic($id){
	$pic = new PicModel();
	return $data_pic=$pic -> getPic($id);
	
	}
	
	public static function getPicByUri($uri){
	$pic = new PicModel();
	return $data_pic=$pic -> getPicByUri($uri);
	
	}
        
        public static function getPicThumbUri($id){
            $pic = new PicModel();
            return $pic->getPictureImageById($id);
        }
}
?>