<?php
if(!isset ($_GET['action'])){
    echo "Add action -- action=check|change";
    exit;
}

$action = $_GET['action'];

require_once '../config/config.php';
require_once '../database/Dao.php';

$dao = new Dao();

$sql = "SELECT * FROM song";
$res = $dao->query($sql);

$songs = $dao->getArray($res);


echo "Song ID - User ID - Response Code - Full Image - Medium Image" . "<br><br>";


foreach ($songs as $song) {
    if ($song['image_uri']!='') {
        $mediumURL = getMediumImageURL($song['image_uri']);

        $responseCode = getResponseCode($mediumURL);
        if ($action == 'check') {
            echo "{$song['id']} - {$song['user_id']} - $responseCode - {$song['image_uri']} - $mediumURL" . "<br>";
        } elseif ($action == 'change' && $responseCode == '200'){
            echo "Changed: {$song['id']} - {$song['user_id']} - $responseCode - {$song['image_uri']} - $mediumURL" . "<br>";
            $sql = "UPDATE song SET image_uri='$mediumURL' WHERE id = {$song['id']}";
            $res = $dao->query($sql);
        }
    }
}

$sql = "SELECT * FROM album_cover";
$res = $dao->query($sql);

$covers = $dao->getArray($res);


echo "<br><br>User ID - Response Code - Full Image - Medium Image" . "<br><br>";


foreach ($covers as $cover) {
    if ($cover['cover_url']!='') {
        $mediumURL = getMediumImageURL($cover['cover_url']);

        $responseCode = getResponseCode($mediumURL);
        if ($action == 'check') {
            echo "{$cover['user_id']} - $responseCode - {$cover['cover_url']} - $mediumURL" . "<br>";
        } elseif ($action == 'change' && $responseCode == '200'){
            echo "Changed: {$cover['user_id']} - $responseCode - {$cover['cover_url']} - $mediumURL" . "<br>";
            $sql = "UPDATE album_cover SET cover_url='$mediumURL' WHERE user_id = {$cover['user_id']}";
            $res = $dao->query($sql);
        }
    }
}

function getMediumImageURL ($fullImageURL) {
    $suffix = substr($fullImageURL, strlen($fullImageURL)-4);
    $prefix = substr($fullImageURL, 0, strlen($fullImageURL)-4);
    return $prefix . "_m" . $suffix;
}

function getResponseCode($url) {
    $ch= curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_exec($ch);

    $details = curl_getinfo($ch);

    curl_close($ch);
    return $details['http_code'];
}
?>
