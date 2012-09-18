<?php

require_once "../config/app_key_values.php";
require_once "../../../facebook-php-sdk-6c82b3f/src/facebook.php";
session_start();
require_once ('../config/config.php');
require_once '../database/Dao.php';
require_once 'flickr_controller.php';
require_once('../controller/pic_controller.php');
require_once('../model/pic_model.php');
require_once '../config/database.php';
require_once('../controller/db_connect.php');
require_once('../model/Album.php');
require_once('../model/UserInfo.php');
require_once('../model/StorageServer.php');
require_once ('../model/PhizuuConnectAPI.php');
require_once ('../model/FanPhotos.php');

//require_once ('../common/facebook.php');
if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
    $appId = app_key_values::$APP_ID_LIVE;
    $secretKey = app_key_values::$SECRET_KEY_LIVE;
} elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
    $appId = app_key_values::$APP_ID_TEST;
    $secretKey = app_key_values::$SECRET_KEY_TEST;
} else {
    $appId = app_key_values::$APP_ID_LOCALHOST;
    $secretKey = app_key_values::$SECRET_KEY_LOCALHOST;
}

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$userId = $_SESSION['user_id'];

switch ($action) {
    case 'order':
        $orderedArr = $_GET['id'];
        $dao = new Dao();


        foreach ($orderedArr as $order => $id) {
            $sql = "UPDATE image SET new_one=1 WHERE id='$id' AND `iphone_status`=''";
            $dao->query($sql);
        }

        $sql = "UPDATE image SET `iphone_status`='' WHERE user_id='$userId' ";
        $dao->query($sql);
        foreach ($orderedArr as $order => $id) {
            $sql = "UPDATE image SET `order`='$order',`iphone_status`=1 WHERE id='$id'";
            $dao->query($sql);
        }

        $sql = "UPDATE image SET new_one=0 WHERE user_id='$userId' AND `iphone_status`='' ";
        $dao->query($sql);
        break;

    case 'album_order':
        $orderedArr = $_GET['aid'];
        $dao = new Dao();

        foreach ($orderedArr as $order => $id) {
            echo $id . ",";
            $sql = "UPDATE albums SET `order`='$order' WHERE id='$id' ";
            $dao->query($sql);
        }
        break;

    case 'editcms':
        $parts = explode('_', $_POST['id']);
//        print_r($parts);
        $field = $parts[0];
        $id = $parts[2];
        $value = $_POST['value'];
        switch ($parts[0]) {
            case '1':
                $field = '`name`';
                break;
        }

        if ($field != '') {
            $sql = "UPDATE image SET $field = '$value' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;

    case 'edit':
        $parts = explode('_', $_POST['id']);
//        print_r($parts);
        $field = $parts[0];
        $id = $parts[1];
        $value = $_POST['value'];
        switch ($parts[0]) {
            case '1':
                $field = '`name`';
                break;
        }

        if ($field != '') {
            $sql = "UPDATE image SET $field = '$value' WHERE id=$id";
            $dao = new Dao();
            $dao->query($sql);
            echo $value;
        }
        break;

    case 'list_flickr_pics':

        $facebook = new Facebook(array(
                    'appId' => '116141471859980',
                    'secret' => '2dd464a8579f953663b01d2c8cb46fc4',
                ));
        $accessTocken = $facebook->getAccessToken();
        $fUserId = $facebook->getUser();

        $url = "https://graph.facebook.com/$fUserId/albums?access_token=$accessTocken";
        $responce = file_get_contents($url);

        $arrCount = json_decode($responce);


        echo $responce;

        break;
    case 'list_facebook_albums';
        $facebook = new Facebook(array(
                    'appId' => $appId,
                    'secret' => $secretKey,
                ));
        $accessTocken = $facebook->getAccessToken();
        $userId = $facebook->getUser();

        $defaultKey = $appId . '|' . $secretKey;
        if ($defaultKey != $accessTocken) {
            $url = "https://graph.facebook.com/$userId/albums?access_token=$accessTocken";
            $responce = file_get_contents($url);


            return json_encode($accessTocken);
        }
        return json_encode($accessTocken);
        break;

    case 'get_photos':
        $username = $_POST['username'];
        $setId = $_POST['id'];
        $flickr = new Flickr($username);

        if ($setId == '') {
            $photos = $flickr->getPhotos2();
        } else {
            $photos = $flickr->getPhotos($setId);
        }

        $pics = array();
        $id = 0;

        $album = new Album($userId);

        foreach ($photos as $photo) {
            $pics[$id]->id = "new$id";
            $pics[$id]->name = $photo['title'];
            $pics[$id]->thumb_uri = $photo['thumb'];
            $pics[$id]->uri = $photo['image'];
            $pics[$id]->added = $album->isFlickrImageAlreadyAdded($photo['image']);
            $jsonPics["id_new$id"]->id = "new$id";
            $jsonPics["id_new$id"]->name = $photo['title'];
            $jsonPics["id_new$id"]->thumb_uri = $photo['thumb'];
            $jsonPics["id_new$id"]->uri = $photo['image'];
            $jsonPics["id_new$id"]->pid = $photo['pid'];
            $id++;
        }
        ob_start();
        $flickrList = true;
        include("../view/user/app_wizard/supporting/pic_list.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;
        $json->pics = $jsonPics;

        echo json_encode($json);
        break;
    case 'get_photos_facebook':
        $flickr = new Flickr();
        $setId = $_POST['id'];
        $photos = $flickr->getFaceBookPhotos($setId);
        $photos = json_decode($photos);

        $pics = array();
        $id = 0;
        $album = new Album($userId);
        foreach ($photos->{'data'} as $photo) {
            $pics[$id]->id = "new$id";
            $pics[$id]->name = "";
            $pics[$id]->thumb_uri = $photo->{'picture'};
            $pics[$id]->uri = $photo->{'source'};
            $pics[$id]->added = $album->isFlickrImageAlreadyAdded($photo->{'source'});
            $jsonPics["id_new$id"]->id = "new$id";
            $jsonPics["id_new$id"]->name = "";
            $jsonPics["id_new$id"]->thumb_uri = $photo->{'picture'};
            $jsonPics["id_new$id"]->uri = $photo->{'source'};
            $jsonPics["id_new$id"]->pid = 0;
            $id++;
        }
        ob_start();
        $flickrList = true;
        include("../view/user/app_wizard/supporting/pic_list.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;
        $json->pics = $jsonPics;
        echo json_encode($json);

        break;

    case 'get_photos_album':
        $username = $_POST['username'];
        $setId = $_POST['id'];
        $flickr = new Flickr($username);

        if ($setId == '') {
            $photos = $flickr->getPhotos2();
        } else {
            $photos = $flickr->getPhotos($setId);
        }

        $pics = array();
        $id = 0;

        $album = new Album($userId);

        foreach ($photos as $photo) {
            $pics[$id]->id = "new$id";
            $pics[$id]->name = $photo['title'];
            $pics[$id]->thumb_uri = $photo['thumb'];
            $pics[$id]->uri = $photo['image'];
            $pics[$id]->added = $album->isFlickrImageAlreadyAdded($photo['image']);
            $id++;
        }
        ob_start();
        $flickrList = true;
        include("../view/user/pictures/album/pic_list.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;

        echo json_encode($json);
        break;

    case 'get_photos_album_facebook':

        $flickr = new Flickr();
        $setId = $_POST['id'];
        $photos = $flickr->getFaceBookPhotos($setId);
        $photos = json_decode($photos);

        $pics = array();
        $id = 0;
        $album = new Album($userId);
        foreach ($photos->{'data'} as $photo) {
            $pics[$id]->id = "new$id";
            $pics[$id]->name = "";
            $pics[$id]->thumb_uri = $photo->{'picture'};
            $pics[$id]->uri = $photo->{'source'};
            $pics[$id]->added = $album->isFlickrImageAlreadyAdded($photo->{'source'});
            $id++;
        }
        ob_start();
        $flickrList = true;
        include("../view/user/pictures/album/pic_list_wizard.php");
        $out1 = ob_get_contents();
        ob_end_clean();

        $json->html = $out1;

        echo json_encode($json);
        break;

    case 'get_photos_album_fan_photos':
        $pics = array();
        $id = 0;

        $fanPhotos = new FanPhotos($userId);
        $photos = $fanPhotos->getAllEventPhotos();

        $album = new Album($userId);

        if (count($photos) == 0) {
            echo "No fan uploaded photos!";
        }

        foreach ($photos as $photo) {
            $pics[$id]->id = "new$id";
            $pics[$id]->name = $photo->caption;
            $pics[$id]->thumb_uri = $photo->thumb_uri;
            $pics[$id]->uri = $photo->uri;
            $pics[$id]->added = $album->isFlickrImageAlreadyAdded($photo->uri);
            $id++;
        }
        $flickrList = true;
        include("../view/user/pictures/album/pic_list.php");
        break;

    case 'add_pic':

        $pic = new PicModel();
        $play_val[0] = array('name' => "", 'uri' => $_POST['uri'], 'thumb_uri' => $_POST['thumb_uri'], 'play_id' => 0, 'pid' => 0);
        $chk = $pic->addPics($play_val, $_SESSION['user_id']);
        echo $chk;
        break;


    case 'add_pic_album':
        $pic = new PicModel();
        $play_val[0] = array('name' => $_POST['name'], 'uri' => $_POST['uri'], 'thumb_uri' => $_POST['thumb_uri'], 'play_id' => 0, 'pid' => $_POST['pid']);
        $chk = $pic->addPics($play_val, $_SESSION['user_id']);

        $pics = array();
        $pics[0]->id = $chk;
        $pics[0]->name = $_POST['name'];
        $pics[0]->thumb_uri = $_POST['thumb_uri'];
        $pics[0]->uri = $_POST['uri'];
        include("../view/user/pictures/album/pic_list.php");
        break;
    case 'add_pic_album_1':
        $pic = new PicModel();
        $play_val[0] = array('name' => $_POST['name'], 'uri' => $_POST['uri'], 'thumb_uri' => $_POST['thumb_uri'], 'play_id' => 0, 'pid' => $_POST['pid']);
        $chk = $pic->addPics($play_val, $_SESSION['user_id']);

        $pics = array();
        $pics[0]->id = $chk;
        $pics[0]->name = $_POST['name'];
        $pics[0]->thumb_uri = $_POST['thumb_uri'];
        $pics[0]->uri = $_POST['uri'];
        include("../view/user/pictures/album/pic_list_1.php");
        break;
    case 'switch_to_albums':
        $album = new Album($userId);
        $userInfo = new UserInfo();
        if (!$userInfo->isFreeUser()) {
            $album->switchToAlbums();
            header("Location: ../view/user/pictures/album.php");
        } else {
            echo "Unathorized!";
        }
        break;
    case 'delete_album':
        $album = new Album($userId);
        $pics = $album->listPicturesOfAlbum($_POST['id']);
        $album->deleteAlbum($_POST['id']);

        $bankList = false;
        include '../view/user/pictures/album/pic_list.php';
        break;

    case 'add_picture_to_album':
        $userInfo = new UserInfo();
        $limits = $userInfo->getLimits();

        $album = new Album($userId);

        $count = $album->countAllPictures();
        if ($limits['photo_limit'] <= $count) {
            exit;
        }

        $album->addPictureToAlbum($_POST['image_id'], $_POST['album_id']);
        break;

    case 'open_album':
        $album = new Album($userId);
        $bankList = false;
        //$forAlbum = true;
        $pics = $album->listPicturesOfAlbum($_POST['album_id']);
        include '../view/user/pictures/album/pic_list.php';
        break;

    case 'remove_picture_from_album':
        $album = new Album($userId);
        $album->removePictureFromAlbum($_POST['image_id'], $_POST['album_id']);
        break;
    case 'updateIphoneStatus':
        $album = new Album($userId);
        $album->updateIphoneStatus($_POST['image_id']);
        break;
    case 'refresh_thumb':
        $album = new Album($userId);
        $url = $album->refreshThumb($_POST['album_id']);
        $album->updateThumb($_POST['album_id'], $url);
        echo $url . "?prevent_cache=" . microtime();
        break;

    case 'add_album':
        $userInfo = new UserInfo();
        $limits = $userInfo->getLimits();

        $album = new Album($userId);

        if (count($album->listAlbums()) >= $limits['album_limit']) {
            $json->error = FALSE;
            echo json_encode($json);
            exit;
        }

        $id = $album->createAlbum($_POST['name'], $_POST['date'], $_POST['location'], $_POST['description'], $_POST['image_url']);

        if ($id == FALSE) {
            $json->error = TRUE;
        } else {
            $albums = $album->listAlbums($id);

            ob_start();
            $bankList = true;
            include("../view/user/pictures/album/list_album.php");
            $out1 = ob_get_contents();
            ob_end_clean();

            $json->html = $out1;
            $json->error = FALSE;
        }
        echo json_encode($json);
        break;

    case 'edit_album':
        $album = new Album($userId);
        $id = $album->editAlbum($_POST['album_id'], $_POST['name'], $_POST['date'], $_POST['location'], $_POST['description'], $_POST['image_url']);

        if ($id == FALSE) {
            $json->error = TRUE;
        } else {
            $albums = $album->listAlbums($id);

            $json->html = $_POST['name'];
            $json->error = FALSE;
        }

        echo json_encode($json);
        break;

    case 'get_album_details_ajax':
        $album = new Album($userId);
        $albums = $album->listAlbums($_POST['album_id']);

        echo json_encode($albums[0]);

        break;

    case 'delete_picture':
        $album = new Album($userId);
        $albums = $album->deletePicture($_POST['id']);

        break;

    case 'upload_temp_image':
        if (isset($_FILES['image']) && $_FILES['image']['size'] != 0) {
            $album = new Album($userId);
            list($url, $path) = $album->uploadTempCoverImage($_FILES['image']['tmp_name']);

            include '../view/user/pictures/album/file_uploaded_response.php';
        } else {
            echo "Error occured. Try again!";
        }
        break;

    case 'upload_image_from_web':
        if (isset($_POST['image_url'])) {
            $album = new Album($userId);
            list($url, $path) = $album->uploadTempCoverImage($_POST['image_url'], FALSE);
            $rtnObj = new stdClass();
            $rtnObj->url = $url;
            $rtnObj->path = $path;
            echo json_encode($rtnObj);
        } else {
            echo "Error occured. Try again!";
        }
        break;

    case 'get_select_pic_list':
        $album = new Album($userId);
        $albumId = $_POST['editing_album'];
        if ($albumId == '-1')
            $albumId = NULL;
        $pics = $album->listPhotosToSelectForAlbumCover($albumId);
        include("../view/user/pictures/album/list_select_photos.php");
        break;

    case 'create_album_on_facebook':
        $facebook = new Facebook(array(
                    'appId' => $appId,
                    'secret' => $secretKey,
                    'cookie' => true,
                ));
        $session = $facebook->getUser();

        $name = $_POST['album_name'];

        $error = FALSE;
        if ($session) {
            try {
                $uid = $facebook->getUser();
                $album = $facebook->api('me/albums', 'POST', array('name' => "$name", 'message' => 'Uploaded by phizuu CMS (http://phizuu.com)'));
            } catch (FacebookApiException $e) {
                $error = TRUE;
                error_log($e);
            }
        } else {
            $error = TRUE;
        }

        if (!$error) {
            echo $album['id'];
        } else {
            echo 'error';
        }
        break;
    case'upload_image_local':

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
        echo 'ok';
        break;
    case 'upload_image_to_facebook_album':
        $facebook = new Facebook(array(
                    'appId' => $appId,
                    'secret' => $secretKey,
                    'cookie' => true,
                ));
        $session = $facebook->getUser();
        $accessToken = $facebook->getAccessToken();

        $id = $_POST['album_id'];
        $picURL = $_POST['url'];
        $name = $_POST['name'];

        $error = FALSE;
        if ($session) {
            try {
                $uid = $facebook->getUser();
                $me = $facebook->api('me/');
            } catch (FacebookApiException $e) {
                $error = TRUE;
                error_log($e);
            }
        } else {
            $error = TRUE;
        }

        if (!$me) {
            echo 'error';
            exit;
        }
        $userId=$_SESSION['user_id'];
        $file = $picURL;
        $fileExtention = substr($picURL, strrpos($picURL, '.'));
        $newfile = "../../../static_files/$userId/images/temp_images/$name.$fileExtention";
        
        if (copy($file, $newfile)) {
            $url = "https://graph.facebook.com/$id/photos";
            //echo $url;

            $lineFeed = "\r\n";
            $headers = array("Content-type: multipart/form-data; boundary=---------------daAKdfkfsdkKdf8s");

            //First Section
            $data = $lineFeed . "-----------------daAKdfkfsdkKdf8s" . $lineFeed;
            $data .= "Content-Disposition: form-data; name=\"message\"" . $lineFeed . $lineFeed;
            $data .= $name . $lineFeed; //Data
            $data .= "-----------------daAKdfkfsdkKdf8s" . $lineFeed;

            //Second Section
            $data .= "Content-Disposition: form-data; name=\"access_token\"" . $lineFeed . $lineFeed;
            $data .= $accessToken . $lineFeed; //Data
            $data .= "-----------------daAKdfkfsdkKdf8s" . $lineFeed;

            //Third Section
            $data .= "Content-Disposition: form-data; name=\"source\"; filename=\"fanphoto.jpg\"" . $lineFeed;
            $data .= "Content-Type: application/octet-stream" . $lineFeed . $lineFeed;
            $fileContent = file_get_contents($newfile);
            $data .= $fileContent . $lineFeed; //Data
            $data .= "-----------------daAKdfkfsdkKdf8s" . $lineFeed;

            //Sending Data
            $ch = curl_init(); // initialize curl handle

            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // set url to post to
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 60); // times out after 20s
            curl_setopt($ch, CURLOPT_POST, 1); // set POST method
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch); // run the whole process

            $result = json_decode($result);
            
            curl_close($ch);
            unlink($newfile);
            if ($result && isset($result->id)) {
                echo 'ok';
            } else {
                echo 'error';
            }
        }
        break;

    default:
        echo "Error! No valid action";
}
?>