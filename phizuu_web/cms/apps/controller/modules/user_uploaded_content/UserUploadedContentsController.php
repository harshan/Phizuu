<?php
session_start();
//sleep(1);

require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../model/fan_content/FanContent.php');
require_once ("../../../controller/session_controller.php");

$userArr = UserInfo::getUserInfoDirect();


$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

$fanContent = new FanContent($userArr['app_id'], $userArr['id']);

switch ($action) {
    case 'main_view':
        $tours = $fanContent->getListOfTours();
        include ('../../../view/user/user_uploaded_contents/fan_photos.php');
        break;
    case 'fan_wall_view':
        $commentObj = $fanContent->getWallPosts();
        include ('../../../view/user/user_uploaded_contents/fan_wall.php');
        break;
    case 'get_fan_photos_ajax':
        $photos = $fanContent->getFanPhotos($_POST['id']);
        $tourId = $_POST['id'];
        include ('../../../view/user/user_uploaded_contents/fan_photos_list.php');
        break;
    case 'delete_fan_photo_ajax':
        $photos = $fanContent->deleteFanPhoto($_POST['tour_id'], $_POST['photo_id']);
        break;
    case 'get_wall_posts_ajax':
        $commentObj = $fanContent->getWallPosts($_POST['next_id']);
        include '../../../view/user/user_uploaded_contents/fan_wall_post_list.php';
        break;
    case 'get_replies_ajax':
        $commentObj = $fanContent->getReplies($_POST['comment_id']);
        include '../../../view/user/user_uploaded_contents/fan_wall_replies_list.php';
        break;
    case 'delete_comment_ajax':
        $commentObj = $fanContent->deleteComment($_POST['comment_id']);
        break;
    default:
            echo "Error! No valid action";
}
?>
