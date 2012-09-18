<?php

@session_start();
$appId = $_SESSION['app_id'];
$uploaddir = "../../../../static_files/$appId/images/temp_images/";

if (!file_exists($uploaddir)) {
    mkdir($uploaddir);
}
$file = $uploaddir . basename($_FILES['uploadfile']['name']);


if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
    echo "success";
} else {
    echo "error";
}
?>