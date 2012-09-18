<?php
@session_start();
		include 'boxnet/class.curl.php';
		include('../config/config.php');
		require_once '../config/database.php';
		include('../controller/db_connect.php');
		include('../controller/helper.php');
		require_once('../controller/pic_controller.php');
		include('../model/pic_model.php');
		include('../config/error_config.php');
	
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}
		

	// The Demos don't save files

	$upload_url = "http://api.flickr.com/services/upload/";


      $useCURL        = in_array('curl', get_loaded_extensions());
	
	    $ch = curl_init();
		$new_file_loc="/tmp/".$_FILES["Filedata"]["name"];
		rename($_FILES["Filedata"]["tmp_name"],$new_file_loc);
       $data = array('api_key' => $_POST["api_key"], 'auth_token' => $_POST["auth_token"],'api_sig' => $_POST["api_sig"],'submit' => $_POST["submit"],'photo' => '@'.$new_file_loc);
	   print_r($data);


        curl_setopt($ch, CURLOPT_URL, $upload_url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       $file = new SimpleXMLElement(curl_exec($ch));

	function HandleError($message) {
	//this message displayede in the upload box - status
	echo $message;
}
?>