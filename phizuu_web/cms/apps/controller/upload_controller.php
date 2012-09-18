<?php
@session_start();
include 'boxnet/class.curl.php';
include('../config/config.php');
require_once '../config/database.php';

include('../controller/db_connect.php');
include('../controller/helper.php');
require_once('../controller/music_controller.php');
include('../model/music_model.php');
include('../model/admin_users_model.php');
include('../config/error_config.php');
include('getid3/getid3/getid3.php');
include('../controller/limit_files_controller.php');
include('../model/limit_files_model.php');

if (isset($_POST["PHPSESSID"])) {
    session_id($_POST["PHPSESSID"]);
}

$app_wizard = false;
if (isset($_POST['app_wizard'])) {
    $app_wizard = true;
}

$music= new Music();

$limitFiles= new LimitFiles();

$bankmusic_count=sizeof($music->listBankMusic($_POST["user_id"]));

$iphone_images_count = sizeof($music->listIphoneMusic($_POST["user_id"]));

$limit_count=$limitFiles->getLimit($_POST["user_id"],'music');


if ($app_wizard && ($iphone_images_count>=$limit_count->music_limit)) {
    $response1="Max upload Limit Exceeded";
    HandleError($response1);
    exit;
}

$stored_file=$music->getMusicStorage($_POST["user_id"]);
$new_file_size=$_FILES["Filedata"]["size"];
$sum='SUM(file_capacity)';


if( (($stored_file ->$sum) + $new_file_size) <= $limit_count ->music_storage_limit) {
    $usr = new UserModel();
    $user_det=$usr -> getUser($_POST["user_id"]);


    $folder_url = "http://www.box.net/api/1.0/rest?action=create_folder&api_key=".$_ENV['box_key']."&auth_token=".$_POST["auth_token"]."&parent_id=0&share=0&name=". $user_det->app_id."";
    $folder = new SimpleXMLElement(file_get_contents($folder_url));
    $folder_id = (string)$folder->folder->folder_id;



    // The Demos don't save files

    $upload_url = "http://upload.box.net/api/1.0/upload/".$_POST["auth_token"]."/".$folder_id;


    // Initialize getID3 engine
    $getID3 = new getID3;
    $ThisFileInfo = $getID3->analyze($_FILES["Filedata"]["tmp_name"]);


    if ($ThisFileInfo['audio']['bitrate']>128000) {
        $response1="bitrate_error";
	HandleError($response1);
        exit;
    }

    file_put_contents('finfo.txt', var_export($ThisFileInfo, true));

    getid3_lib::CopyTagsToComments($ThisFileInfo);
    $tags = null;

    if (isset($ThisFileInfo['id3v3']['comments'])) {
        $tags = $ThisFileInfo['id3v3']['comments'];
    } else if (isset($ThisFileInfo['id3v2']['comments'])) {
        $tags = $ThisFileInfo['id3v2']['comments'];
    } else if (isset($ThisFileInfo['id3v1']['comments'])) {
        $tags = $ThisFileInfo['id3v1']['comments'];
    } else if (isset($ThisFileInfo['tags']['id3v3'])) {
        $tags = $ThisFileInfo['tags']['id3v3'];
    } else if (isset($ThisFileInfo['tags']['id3v2'])) {
        $tags = $ThisFileInfo['tags']['id3v2'];
    } else if (isset($ThisFileInfo['tags']['id3v1'])) {
        $tags = $ThisFileInfo['tags']['id3v1'];
    }


    $useCURL = in_array('curl', get_loaded_extensions());
    $ch = curl_init();



    $MAX_FILENAME_LENGTH = 260;
    $valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';
    $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES["Filedata"]["name"]));
    if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
        HandleError("Invalid file name");
        exit(0);
    }


    $new_file_loc="/tmp/".$file_name;
    rename($_FILES["Filedata"]["tmp_name"],$new_file_loc);

    $data = array('share' => '1', 'file' => '@'.$new_file_loc,'message' => '');

 


    curl_setopt($ch, CURLOPT_URL, $upload_url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $file = new SimpleXMLElement(curl_exec($ch));

    if($file->status == "upload_ok") {

        $stream_url = "http://www.box.net/shared/static/".$file->files->file["public_name"].".mp3";

        //update database

        $music= new Music();
        $play_val[0] = array('name' => $tags['title'][0], 'album' => $tags['album'][0], 'duration' => @$ThisFileInfo['playtime_seconds'], 'stream_uri' => $stream_url, 'year' => $tags['year'][0],'file_id' =>$file->files->file["id"],'user_id' =>$_POST["user_id"],'size' =>$new_file_size, 'genre'=>$tags['genre'][0],'app_wizard'=>$app_wizard);

        $music_det = $music->uploadMusic($play_val);

        //end update database

        $bmusic->id = $music_det;
        $bmusic->title = isset($tags['title'][0])?$tags['title'][0]:'';
        $bmusic->duration = @$ThisFileInfo['playtime_seconds'];
        $bmusic->note = '';

        if ($app_wizard) {
            $_SESSION['last_music'] = $bmusic->id;
        }

        include '../view/user/music/bank_list_new.php';
    }

}
else {

    $response1="Max upload Limit Exceeded";
    HandleError($response1);
}
exit(0);


function HandleError($message) {
    echo $message;

}
?>