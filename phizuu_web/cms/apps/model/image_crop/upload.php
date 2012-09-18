<?php
session_start();
//sleep(1);

require_once ('../../config/config.php');
require_once ('../../database/Dao.php');

$userArr = NULL;
if (isset($_SESSION['user_id'])) {
    $dao = new Dao();
    $sql = "SELECT * FROM `user` WHERE `id` = {$_SESSION['user_id']}";
    $res = $dao->query($sql);
    $userArr = $dao->getArray($res);
    $userArr = $userArr[0];
} 

include("functions.php");
$path = '../../temporary_files/bulk_images/';

$files = getFiles($path);
foreach($files as $file) {
    //echo $file . "<br/>";
}




if ( isset( $_REQUEST[ 'image' ] ) ) {
      $image = $_REQUEST['image'];
  $uniId = $_REQUEST['uniId'];
    $uploaddir = "../../temporary_files/bulk_images/";
    file_put_contents($uploaddir.$uniId.".txt","&m=x&");
  $image = $_REQUEST['image'];

  $tmp_name = $_FILES['Filedata']["tmp_name"];

  if (!empty($tmp_name)) {
      move_uploaded_file($tmp_name, $uploaddir.$image);  
	  
	  $size = getimagesize  ($uploaddir.$image);
	  
	  if ($size[0]>920 || $size[1]>530) {
	  	smart_resize_image($uploaddir.$image, 920, 530, true);
	  }
  }
  file_put_contents($uploaddir.$uniId.".txt","&m=xx&");
}
else {
  $uniId = microtime(true)*100 . "_" .$userArr['id'];
  echo("&uniId=".$uniId);  
}


function getFiles($directory,$exempt = array('.','..','.ds_store','.svn'),&$files = array()) {
    $handle = opendir($directory);
    while(false !== ($resource = readdir($handle))) {
        if(!in_array(strtolower($resource),$exempt)) {
            if(is_dir($directory.$resource.'/'))
                array_merge($files,
                    self::getFiles($directory.$resource.'/',$exempt,$files));
            else
                $files[] = $directory.$resource;
        }
    }
    closedir($handle);
    return $files;
} 
?>