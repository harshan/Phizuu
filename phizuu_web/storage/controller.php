<?php

$baseURL = '../static_files/storage_service';
//$baseURLPath = "http://174.121.85.124/~phizuu";


if ($_SERVER["SERVER_NAME"] == 'phizuu.com') {
   $baseURLPath = "http://phizuu.com";
} elseif ($_SERVER["SERVER_NAME"] == '174.121.85.124') {
   $baseURLPath = "http://174.121.85.124/~phizuu";
} else {
   $baseURLPath = "http://http://localhost/phizuu_web";
}


$url = $_GET['url'];

if ($url != '' && $url[strlen($url) - 1] == '/') {
    $url = substr($url, 0, strlen($url) - 1);
}

$parts = explode('/', $url);

if (count($parts) != 3) {
    handleError('400', 'Invalid Number of Parameters');
}

$contentType = $parts[0];
$type = $parts[1];
$name = $parts[2];

if (!file_exists("$baseURL/$contentType")) {
    handleError('400', 'Invalid content type');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fileData = file_get_contents('php://input');
    if ($fileData == '') {
        handleError('400', 'Empty file');
    } else {
        if (!file_exists("$baseURL/$contentType/$type")) {
            mkdir("$baseURL/$contentType/$type");
        }

        if (file_exists("$baseURL/$contentType/$type/$name")) {
            handleError('409', 'File exists');
        } else {
            file_put_contents("$baseURL/$contentType/$type/$name", $fileData);
            $jsonObj->status = "ok";
            $jsonObj->url = "$baseURLPath/static_files/storage_service/$contentType/$type/$name";
            handleSuccess('200', $jsonObj);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (file_exists("$baseURL/$contentType/$type/$name")) {
        $jsonObj->status = "exist";
        $jsonObj->url = "$baseURLPath/static_files/storage_service/$contentType/$type/$name";
        handleSuccess('200', $jsonObj);
    } else {
        $jsonObj->status = "non-exist";
        handleSuccess('404', $jsonObj);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (file_exists("$baseURL/$contentType/$type/$name")) {
        unlink("$baseURL/$contentType/$type/$name");
        $jsonObj->status = "ok";
        handleSuccess('200', $jsonObj);
    } else {
        $jsonObj->status = "non-exist";
        handleSuccess('404', $jsonObj);
    }
}

function handleSuccess($code, $jsonObject) {
    header("HTTP/1.0 $code");
    echo json_encode($jsonObject);
}

function handleError($errorCode, $message) {
    header("HTTP/1.0 $errorCode");
    echo $message;
    exit;
}

?>
