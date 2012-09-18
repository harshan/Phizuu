<?php
class Box
{
	public static function addBox($box_arr,$play_list){
	$box = new BoxModel();
	
	$chk_user=$box -> addBox($box_arr,$play_list);
	
	}
	
	public static function checkBox($box_arr){
	$box = new BoxModel();
	
	return $check_box=$box -> checkBox($box_arr);
		
	}
	
	public static function listBox($user_id,$starting,$recpage){
	$box = new BoxModel();
	
	return $list_box=$box -> listBox($user_id,$starting,$recpage);
	}
	
	public static function listBoxAll(){
	$box = new BoxModel();
	return $list_box=$box -> listBoxAll();

	}
	
	public static function listBoxAllRecs(){
	$box = new BoxModel();
	return $list_box=$box -> listBoxAllRecs();

	}
	
	
	public static function listIphonebox($user_id){
	$box = new BoxModel();
	
	return $iphone_box=$box -> listIphonebox($box_arr,$play_list);
	
	}
	
	public static function getBox($id){
	$box = new BoxModel();
	return $data_box=$box -> getBox($id);
	
	}

	public static function editBox($box_arr){
		$box = new BoxModel();
		$effected = $box->editBox($box_arr);
		
		}

}
?>