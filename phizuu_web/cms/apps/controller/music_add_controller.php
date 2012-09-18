<?php
@session_start();
include('../config/config.php');
require_once '../config/database.php';
include('../controller/music_controller.php');
include('../model/music_model.php');
include('../config/error_config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');
include '../controller/boxnet/boxlibphp5.php';
include('../controller/limit_files_controller.php');
include('../model/limit_files_model.php');


$box =& new boxclient($_SESSION['api_key'], $_SESSION['auth_token']);
$play_val[0] = array('name' => $_GET['name'],'file_id' =>$_GET['file_id'],'folder_id' =>$_GET['folder_id']);

$music= new Music();

$limitFiles= new LimitFiles();


$bankmusic_count=sizeof($music->listBankMusic($_SESSION['user_id']));
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');

if(empty($_GET['file_id']) && empty($_GET['name'])){

if((($limit_count ->music_limit) > $bankmusic_count) && (($limit_count ->music_limit) >= ($bankmusic_count + 1))){

$tree_all = $box->getAccountTree ();


  for ($i=0, $tree_count; $i<$tree_count; $i++) {
  
	 $folder_id=$tree_all['folder_id'][$i];
			if($folder_id !=''){
			$folder_id1=$folder_id;
			}
	if ($tree_all['file_name'][$i] != ''){
	$status=$music->getMusicByUri($tree_all['file_id'][$i]);


			if(empty($status->id)){

				$tree_val_all_new[0] = array('name' =>$tree_all['file_name'][$i],'file_id' => $tree_all['file_id'][$i],'folder_id' =>$folder_id1);
				
			$chk = $music->addAllMusic($tree_val_all_new);
			
			}
		}
	}
	}//if count
	else{
	 $response1="Limit Exceeded - Sorry Couldn't Add Files";
	}
}
else{

if((($limit_count ->music_limit) > $bankmusic_count) && (($limit_count ->music_limit) >= ($bankmusic_count + 1))){
$chk = $music->addMusic($play_val);

}
	else{

	 $response1="Limit Exceeded - Sorry Couldn't Add Files";
	}
}

//=============================================

$tree = $box->getAccountTree ();
$file = $box->getFileList ($tree,$tree_count);
include('../view/user/music/list_music_by_cat_a_tbl_inc.php');

echo $response;
?>