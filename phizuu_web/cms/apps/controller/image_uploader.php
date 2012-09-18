<?php
require_once ('../config/config.php');
require_once ('../database/Dao.php');
require_once ('../model/StorageServer.php');
require_once ('../model/UserInfo.php');
require_once ('../model/Album.php');
require_once ('../common/SampleImage.php');
require_once ('../controller/pic_controller.php');
require_once ('../model/pic_model.php');

// Code for Session Cookie workaround
if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
} else if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
}

$POST_MAX_SIZE = ini_get('post_max_size');
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
    header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
    echo "POST exceeded maximum allowed size.";
    exit(0);
}

// Settings
$save_path = "../temporary_files/songs/";				// The path were we will save the file (getcwd() may not be reliable and should be tested in your environment)
$upload_name = "Filedata";
$max_file_size_in_bytes = 1048576;				// 1MB in bytes
$extension_whitelist = array("gif","jpg","jpeg","png");	// Allowed file extensions
$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)

// Other variables
$MAX_FILENAME_LENGTH = 260;
$file_name = "";
$file_extension = "";
$uploadErrors = array(
        0=>"There is no error, the file uploaded with success",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
);


// Validate the upload
if (!isset($_FILES[$upload_name])) {
    HandleError("No upload found in \$_FILES for " . $upload_name);
    exit(0);
} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
    HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
    exit(0);
} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
    HandleError("Upload failed is_uploaded_file test.");
    exit(0);
} else if (!isset($_FILES[$upload_name]['name'])) {
    HandleError("File has no name.");
    exit(0);
}

// Validate the file size (Warning: the largest files supported by this code is 2GB)
$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
if (!$file_size || $file_size > $max_file_size_in_bytes) {
    HandleError("File exceeds the maximum allowed size");
    exit(0);
}

if ($file_size <= 0) {
    HandleError("File size outside allowed lower bound");
    exit(0);
}


// Validate file name (for our purposes we'll just remove invalid characters)
$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
    HandleError("Invalid file name");
    exit(0);
}


// Validate that we won't over-write an existing file
//	if (file_exists($save_path . $file_name)) {
//		HandleError("File with this name already exists");
//		exit(0);
//	}

// Validate file extension
$path_info = pathinfo($_FILES[$upload_name]['name']);
$file_extension = $path_info["extension"];
$is_valid_extension = false;
foreach ($extension_whitelist as $extension) {
    if (strcasecmp($file_extension, $extension) == 0) {
        $is_valid_extension = true;
        break;
    }
}
if (!$is_valid_extension) {
    HandleError("Invalid file extension");
    exit(0);
}

$album = new Album($_SESSION['user_id']);

$storage = new StorageServer('');

$pic= new PicModel();
$filePathInfo = pathinfo($file_name);
$play_val[0] = array('name' => $filePathInfo['filename'],'uri' =>'','thumb_uri' => '','play_id' => -1,'pid' => -1,'image_size'=>$file_size);
$chk = $pic->addPics($play_val,$_SESSION['user_id']);

$url = $storage->getURLForPath('images', 'gallery_images', $chk . '.jpg');
$thumbUrl = $storage->getURLForPath('images', 'gallery_thumb_images', $chk . '.jpg');

$fileSize = $album->uploadImage($_FILES[$upload_name]["tmp_name"], $chk);
if ($fileSize<=0) {
    HandleError("Error while saving images!");
    exit(0);
}

$sql = "UPDATE image SET thumb_uri = '$thumbUrl', uri = '$url', `file_size`='$fileSize' WHERE id = $chk";
$dao = new Dao();
$dao->query($sql);

$pics = array();
$pics[0] = new stdClass();
$pics[0]->id = $chk;
$pics[0]->name = $filePathInfo['filename'];
$pics[0]->thumb_uri = $thumbUrl;
$pics[0]->uri = $url;
ob_start();
include '../view/user/pictures/album/pic_list.php';
$html = ob_get_contents();
ob_end_clean();

$msg = new stdClass();
$msg->error = false;
$msg->html = $html;

echo json_encode($msg);
exit;


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
    $msg = new stdClass();
    $msg->error = true;
    $msg->error_message = $message;

    echo json_encode($msg);
}

?>