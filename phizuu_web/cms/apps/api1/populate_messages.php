<?php
require_once('../controller/json_controller.php');
require_once '../config/database.php';
include('../config/error_config.php');
include('../config/config.php');
include('../controller/db_connect.php');
include('../controller/helper.php');

include('../model/video_model.php');
include('../model/music_model.php');
include('../model/pic_model.php');
include('../model/news_model.php');
include('../model/tours_model.php');
include('../model/Album.php');
include('../model/fan_wall/FanWall.php');
include('../model/API.php');
include '../database/Dao.php';


for($appId = 1; $appId<100; $appId++) {
    $fanWall = new FanWall($appId);
    for($i=0;$i<1000;$i++){
        $commentObj = new stdClass();

        $commentObj->user_name = 'idhanu';
        $commentObj->user_type = 'FB';
        $commentObj->comment = "Test Comment".rand();
        $commentObj->location = (rand(-90000,90000)/1000).";".(rand(-90000,90000)/1000);

        $commentId = $fanWall->addMessage($commentObj);

        echo "$commentId,";

        unset ($commentObj);
    }
    unset ($fanWall);
}
?>