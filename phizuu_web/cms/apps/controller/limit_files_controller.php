<?php
class LimitFiles
{
	public static function getLimit($id,$type){
	
	$limitFiles = new LimitFilesModel();
	return $data_tours=$limitFiles -> getLimit($id,$type);
	
	}

}
?>