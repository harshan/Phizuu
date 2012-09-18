<?php
require_once '../config/config.php';
require_once '../database/Dao.php';
require_once '../model/soundcloud/SoundCloudMusic.php';
require_once '../model/soundcloud/soundcloud.php';
require_once '../common/oauth.php';
require_once('../common/id3lib/getid3.php');

$lastId = $_GET['last_id'];

if (isset($_GET['eq']))
    $sign = '=';
else
    $sign = '>=';

$sql = "SELECT * FROM song WHERE stream_uri!='' AND id $sign $lastId ORDER BY `id`";

$dao = new Dao();
$songsArray = $dao->toArray($sql);


echo "<pre>";
foreach($songsArray as $song) {
    
    $url = $song['stream_uri'];
    if(!(preg_match('/^(http\:\/\/media.soundcloud.com\/stream)/', $url))) {
        if ($song['title']=='') {
            $song['title'] = 'No title (' . $song['id'] . ')';
        }

        echo 'Track ' . $song['id'] . ' - ' . $song['title'] . "\n\nDownloading Track..\n";
        flush();
        downloadTrack($url);
        echo "Uploading track to SoundCloud..\n";
        flush();

        $getID3 = new getID3;
        $ThisFileInfo = $getID3->analyze('temp.mp3');
        $duration = isset($ThisFileInfo['playtime_seconds'])?$ThisFileInfo['playtime_seconds']:'NULL';
        
        $response = uploadTrack('temp.mp3', $song['title']);
        $response['old_stream_uri'] = $url;
        updateTrack($response,$duration, $song['id']);
        echo "URL: " . $response['stream-url'] . "\n";
        echo "Track transfered successfully..\n";
        flush();
    } else {
        echo $song['title'] . " - Track from sound cloud. Skipped!\n";
    }

    echo "<hr/>";
}
echo "</pre>";


function progress($clientp,$dltotal,$dlnow,$ultotal,$ulnow){
    echo "$clientp, $dltotal, $dlnow, $ultotal, $ulnow\n";

    return(0);
}

function downloadTrack($url) {
    $ch = curl_init($url);
    $fp = fopen("temp.mp3", "w");

    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

function uploadTrack ($tempFile, $title) {
    $soundcloud = new Soundcloud(
                        SOUNDCOULD_CONSUMER_KEY,
                        SOUNDCOULD_CONSUMER_SECRET,
                        SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN,
                        SOUNDCOULD_PREMIUM_AC_ACCESS_TOKEN_SECRET);


    $post_data = array(
        'track[title]' => stripslashes($title),
        'track[asset_data]' => $tempFile,
        'track[sharing]' => 'private',
        'track[streamable]'=>'true'
    );

    $mime = 'audio/mpeg'; //For MP3 files

    $response = $soundcloud->upload_track($post_data, $mime);

    if ($response) {
        $response = new SimpleXMLElement($response);
        $response = get_object_vars($response);
        $response['file_size'] = filesize($tempFile);
    } else {
        $response = FALSE;
    }

    unlink(realpath($tempFile));

    return $response;
}

function updateTrack($track, $duration,  $trackId) {
    $streamUrl = isset($track['stream-url'])?"'".$track['stream-url']."'":'NULL';
    $permalinkUrl = isset($track['permalink-url'])? "'".$track['permalink-url']."'" : 'NULL';
    $oldUrl = isset($track['old_stream_uri'])? "'".$track['old_stream_uri']."'" : 'NULL';
    $size = isset($track['file_size'])? "'".$track['file_size']."'" : '0';

    $sql = "UPDATE song SET ".
                "`file_capacity`=$size,".
                "`soundcloud_uri` = $streamUrl,".
                "`permalink`= $permalinkUrl,".
                "`duration`= $duration".
            " WHERE id=$trackId;";

    $dao = new Dao();
    $dao->query($sql);
}
?>
