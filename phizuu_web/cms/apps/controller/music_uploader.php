<?php
// Code for Session Cookie workaround
if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
} else if (isset($_GET["PHPSESSID"])) {
    session_id($_GET["PHPSESSID"]);
}

@session_start();

require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once('../model/soundcloud/soundcloud.php');
require_once('../model/soundcloud/SoundCloudMusic.php');
require_once('../model/music_model.php');
require_once('../controller/helper.php');
require_once('../controller/limit_files_controller.php');
require_once('../model/limit_files_model.php');
require_once('../controller/music_controller.php');
require_once('../common/oauth.php');

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
$max_file_size_in_bytes = 104857600;				// 100MB in bytes
$extension_whitelist = array("mp3");	// Allowed file extensions
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

//	if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
//		HandleError("File could not be saved. $save_path.$file_name");
//		exit(0);
//	}


//file_put_contents("upload_test.txt", $_FILES[$upload_name]["tmp_name"] . "\n",FILE_APPEND);

$filename = $_FILES[$upload_name]["tmp_name"];
require_once('../common/id3lib/getid3.php');

// Initialize getID3 engine
$getID3 = new getID3;

// Analyze file and store returned data in $ThisFileInfo
$ThisFileInfo = $getID3->analyze($filename);

// Optional: copies data from all subarrays of [tags] into [comments] so
// metadata is all available in one location for all tag formats
// metainformation is always available under [tags] even if this is not called
getid3_lib::CopyTagsToComments($ThisFileInfo);

//file_put_contents("upload_test.txt", print_r($ThisFileInfo,true));

$title = getCorrectInfo($ThisFileInfo, 'title');
$duration = $ThisFileInfo['playtime_seconds'];

$soundCloud = new SoundCloudMusic();

$appWizard = false;
if (isset($_POST['app_wizard'])) {
    $appWizard = true;
}

if($appWizard) {
    new Dao();
    $imusic= new Music();
    $iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);

    $limitFiles= new LimitFiles();
    $limit_count = $limitFiles->getLimit($_SESSION['user_id'],'music');
    $limit = $limit_count->music_limit;

    if ($limit<=count($iphone_music)) {
        HandleError("Maximum limit of tracks ($limit) exeeded!");
    }
}

$response = $soundCloud->uploadTrack($_FILES[$upload_name]["name"], $filename, $_SESSION['user_id'], $title, $appWizard);




if ($response==FALSE) {
    HandleError("Error occured while transferring song!");
} else {
    $track = $response;
    $track['album'] = getCorrectInfo($ThisFileInfo, 'album');
    $track['release-year'] = getCorrectInfo($ThisFileInfo, 'year');
    $track['description'] = getCorrectInfo($ThisFileInfo, 'comments');
    $track['file_size'] = $_FILES[$upload_name]["size"];
    $track['genre'] = getCorrectInfo($ThisFileInfo, 'genre');
    $track['duration'] = $duration;
    $track['soundcloud-url'] = $track['stream-url'];
    unset($track['stream-url']);

    $musicId = $soundCloud->addMusic($track, $_SESSION['user_id'], $appWizard);

    /**
    * Keeping track temporaly in phizuu.com server
    *
    * Remove these once box.net discontinued
    *
    *
    **/

    $streamURL = "http://localhost/phizuu_web/static_files/temp_music_files/$musicId.mp3";
    move_uploaded_file($filename, "../../../static_files/temp_music_files/$musicId.mp3");

    $sql = "UPDATE song SET stream_uri = '$streamURL' WHERE id=$musicId";
    $dao = new Dao();
    $dao->query($sql);
    /**
    * End
    */


    $bmusic->id = $musicId;
    $bmusic->duration = $duration;
    $bmusic->title = $track['title'];
    $bmusic->note = $track['description'];
    $bmusic->stream_uri = $track['soundcloud-url'];
    $bmusic->permalink = $track['permalink-url'];

    ob_start();
    include '../view/user/music/bank_list_new.php';
    $html = ob_get_contents();
    ob_end_clean();

    $msg->error = false;
    $msg->html = $html;

    if ($appWizard) {
        $_SESSION['last_music'] = $musicId;
    }

    echo json_encode($msg);
    exit;
}

HandleError("Unlisted error!");
exit(0);


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
    $msg->error = true;
    $msg->error_message = $message;

    echo json_encode($msg);
    exit;
}

function getCorrectInfo($fileInfo, $key) {
    if (isset($fileInfo['id3v3']['comments'][$key][0]) && $fileInfo['id3v3']['comments'][$key][0]!='') {
        return $fileInfo['id3v3']['comments'][$key][0];
    } elseif (isset($fileInfo['id3v2']['comments'][$key][0]) && $fileInfo['id3v2']['comments'][$key][0]!='') {
        return $fileInfo['id3v2']['comments'][$key][0];
    } elseif (isset($fileInfo['id3v1']['comments'][$key][0]) && $fileInfo['id3v1']['comments'][$key][0]!='') {
        return $fileInfo['id3v1']['comments'][$key][0];
    } else {
        return '';
    }
}
?>