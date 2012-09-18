<?php
require_once('../controller/json_controller.php');
require_once '../config/database.php';
include('../config/error_config.php');
include('../config/config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

include('../model/video_model.php');
include('../model/UserInfo.php');
include('../model/music_model.php');
include('../model/pic_model.php');
include('../model/news_model.php');
include('../model/tours_model.php');
include('../model/Album.php');
include('../model/fan_wall/FanWall.php');
include('../model/discography/Discography.php');
include('../model/API.php');
include '../database/Dao.php';


$parts = explode('/', $_REQUEST['url']);

if ( isset($parts[1]) && $parts[1] != '') {
    $parts = explode('/', $_REQUEST['url']);
    $component = $parts[1];
    $app_id = $parts[0];
    if (isset($parts[2]))
        $phone = $parts[2];
    else
        $phone = 'iphone';
} else {
    $app_id = $parts[0];
    include('api_main_controller.php');
    exit;
}

$json_class= new jsonClass();

if ($app_id == 'comment') {
    $fanWall = new FanWall(NULL);

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        if (isset($parts[1]) && $parts[1]!='') {
            $postText = trim(file_get_contents('php://input'));
            $commentObj = json_decode ($postText);
            $commentId = $fanWall->addReply($commentObj, $parts[1]);

            if ($commentId === FALSE) {
                echo badRequest("");
                exit;
            } else {
                $returnObj->id = $commentId;
                header("Content-Type: application/vnd.com.phizuu.connect.CommentID+json");
                $json_stream = json_encode($returnObj);
            }
        } else {
            echo badRequest("");
            exit;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' ) {
        if (isset($parts[1]) && $parts[1]!='') {
            if ($parts[1]=='replies') {
                if (isset($parts[2]) && $parts[2]!='') {
                    $replies = $fanWall->getReplys($parts[2]);
                    $returnObj->replies = $replies;
                    header("Content-Type: application/vnd.com.phizuu.connect.CommentReplies+json");
                    $json_stream = json_encode($returnObj);
                } else {
                    echo badRequest("");
                    exit;
                }
            } else {
                $comments = $fanWall->getComment($parts[1]);
                $returnObj = $comments[0];
                header("Content-Type: application/vnd.com.phizuu.connect.Comment+json");
                $json_stream = json_encode($returnObj);
            }
        } else {
            echo badRequest("");
            exit;
        }
    }
} else if ($component == "music") {
    $json_stream = $json_class->streamMusic($app_id);
} else if ($component == "video") {
    $json_stream = $json_class->streamVideo($app_id,$phone);
} else if ($component == "images") {
    $json_stream = $json_class->streamImage($app_id);
}else if($component == "news") {
    $json_stream = $json_class->streamNews($app_id);
}else if($component == "mailinglist") {
        $postText = trim(file_get_contents('php://input'));
        $json = json_decode ($postText);
        $json_stream = $json_class->addtoMailingList($json);
        //return
}else if($component == "tours") {
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        $postText = trim(file_get_contents('php://input'));
        $json = json_decode ($postText);
        $json_stream = $json_class->addRegisteration($json);
    } elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
	$api = new API();
        $json_stream = $api->streamTours($app_id);
    }
}else if($component == "links") {
    $json_stream = $json_class->streamMusic($app_id);
}else if($component == "discography" ) {
    $api = new API();
    $userArr = UserInfo::getUserInfoDirectByAppId($app_id);

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($parts[2]) && $parts[2]=='ilike' ) {
        $discographyId = $parts[3];

        $postText = trim(file_get_contents('php://input'));
        $json = json_decode ($postText);
        $json_stream = $api->addDiscographyLike($json->uuid, $discographyId);
    } elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
        $json_stream = $api->streamDiscography($userArr['id']);
    }

}else if($component == "flyers") {
    $json_stream = $json_class->streamMusic($app_id);
} elseif ($component == 'wall') {
    $fanWall = new FanWall($app_id);

    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        $postText = $_POST['json'];
        $postText = stripslashes($postText);
        //echo $postText;

        $commentObj = json_decode ($postText);

        if(isset($_FILES['image']) && isset($_FILES['image']['tmp_name'])) {
            $commentObj->image_attachment = $_FILES['image']['tmp_name'];
        }

        $commentId = $fanWall->addMessage($commentObj);

        if ($commentId === FALSE) {
            echo badRequest("");
            exit;
        } else {
            header("Content-Type: application/vnd.com.phizuu.connect.CommentID+json");
            $returnObj->id = $commentId;
            $json_stream = json_encode($returnObj);
        }

    } elseif ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
        $limit = 20;

        if (isset($parts[2]) && $parts[2]=='location') {
            $nextStart = 0;

            if(isset($parts[4]) && $parts[4]!='') {
                $messages = $fanWall->getMessages($limit+1,$parts[3],$parts[4]);
                $nextStart = $parts[4]+$limit;
            } else {
                $messages = $fanWall->getMessages($limit+1,$parts[3]);
                $nextStart = $limit;
            }

            if (isset($messages[$limit])) {
                $nextId = (string)$nextStart;
                unset ($messages[$limit]);
            } else {
                $nextId = "";
            }
        } else {
            if (isset($parts[2]) && $parts[2]!='') {
                $startComment = $parts[2];
            } else {
                $startComment = NULL;
            }

            $messages = $fanWall->getMessages($limit+1,NULL,$startComment);

            if (isset($messages[$limit])) {
                $nextId = $messages[$limit]->comment_id;
                unset ($messages[$limit]);
            } else {
                $nextId = "";
            }
        }



        $messagesObj->comments = $messages;
        $messagesObj->next_message = $nextId;

        header("Content-Type: application/vnd.com.phizuu.connect.FanWall+json");
        $json_stream = json_encode($messagesObj);
    }
 } else {
    $error->code = 404;
    $error->message = 'Not Found';
    $errorObj->error = $error;
    header("HTTP/1.0 404 Not Found");
    $json_stream = json_encode($errorObj);
}

echo $json_stream;

function badRequest($message) {
    $error->code = 400;
    $error->message = 'Bad Request';
    $errorObj->error = $error;
    header("HTTP/1.0 400 Bad Request");
    return json_encode($errorObj);
}
?>