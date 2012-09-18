<?php
class Tours
{
	public static function addTours($tours_arr){
	$tours = new ToursModel();
	
	return $chk_user=$tours -> addTours($tours_arr);
	
	}
	
	public static function addAlltours($tours_arr,$play_list){
	$tours = new ToursModel();
	$chk_user=$tours -> addAlltours($tours_arr,$play_list);
	
	}

	
	public static function listTours($user_id,$starting,$recpage, $hideOld = FALSE){
	$tours = new ToursModel();
	
	return $list_tours=$tours -> listTours($user_id,$starting,$recpage, $hideOld);
		
	}
	
	public static function listToursAll($user_id){
	$tours = new ToursModel();
	
	return $list_tours=$tours -> listToursAll($user_id);
		
	}
	
	public static function listIphonetours($user_id){
	$tours = new ToursModel();
	
	return $iphone_tours=$tours -> listIphonetours($user_id);
	
	}
	
	public static function listBankTours($user_id){
	$tours = new ToursModel();
	
	return $bank_tours=$tours -> listBankTours($user_id);
	
	}
	
	public static function getTours($id){
	$tours = new ToursModel();
	return $data_tours=$tours -> getTours($id);
	
	}

	public static function editTours($tours_arr){
		$tours = new ToursModel();
		$effected = $tours->editTours($tours_arr);
	
	}
	
	public static function editInlineTours($tours_arr){
		$tours = new ToursModel();
		$effected = $tours->editInlineTours($tours_arr);
		
	
	}
        
        public static function getTourTitleById($id){
		$tours = new ToursModel();
		return $tours->getTourNameById($id);
		
	
	}

}
?>