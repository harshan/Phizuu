<?php
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';


$sql = "SELECT * FROM video";
$dao = new Dao();
$res = $dao->query($sql);

$arr = $dao->getArray($res);

foreach ($arr as $item) {
    $parts = explode("=", $item['stream_uri']);
    $id = $parts[1];

    $thumb = "http://img.youtube.com/vi/$id/default.jpg";

    $sql = "UPDATE video SET thum_uri = '$thumb' WHERE id = {$item['id']}";
    $dao->query($sql);
}

?>