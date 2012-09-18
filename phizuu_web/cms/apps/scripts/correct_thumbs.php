<?php
/* This is a script to parse data in the log file to the database */
require_once ('../config/config.php');
require_once ('../database/Dao.php');

$sql = "SELECT * FROM video";

$dao = new Dao();
$res = $dao->query($sql);
$arr = $dao->getArray($res);

foreach ($arr as $item) {
    $uri = $item['stream_uri'];

    $parts = explode('v=', $uri);
    $parts = explode('&', $parts[1]);

    $id = $parts[0];

    $thumURL = "http://img.youtube.com/vi/$id/default.jpg";
    $thumURL = addslashes($thumURL );

    $sql = "UPDATE video SET thum_uri='$thumURL' WHERE id={$item['id']}";
    $dao->query($sql);
}
?>