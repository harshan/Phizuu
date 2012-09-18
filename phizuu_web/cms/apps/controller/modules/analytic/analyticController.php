<?php

session_start();
require_once "../../../config/app_key_values.php";

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == 'geo') {

    echo $countryArray;
}

if ($action == 'visits') {

//Getting app visits details
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/week/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appArray = curl_exec($ch);
    $appArray = json_decode($appArray);

    $visitsArray = '
    {
  "cols": [
        {"id":"","label":"Year","type":"string"},
        {"id":"","label":"Visits","type":"number"},
      ],"rows": [';


    foreach ($appArray->{'total'}->{'array'}[0] as $k => $v) {
        $visitsArray.= '{"c":[{"v":"' . $k . '"},{"v":' . $v . '}]},';
    }

    echo $visitsArray.= ']}';
}

if ($action == 'getCounts') {
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/week/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appCount = curl_exec($ch);
    $appCount = json_decode($appCount);
    //echo $appCount->{'total'}->{'count'};
    echo '{"total":"' . $appCount->{'total'}->{'count'} . '","uniqe":"' . $appCount->{'uniqe'}->{'count'} . '","newvisite":"' . $appCount->{'new'}->{'count'} . '"}';
}

if ($action == 'reloadChart') {
    $chartType = $_GET['chartType'];
    $timePeriod = $_GET['timePeriod'];



    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/$timePeriod/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appArray = curl_exec($ch);
    $appArray = json_decode($appArray);


    $visitsArray = '
    {
  "cols": [
        {"id":"","label":"Year","type":"string"},
        {"id":"","label":"Visits","type":"number"},
      ],"rows": [';


    foreach ($appArray->{$chartType}->{'array'}[0] as $k => $v) {
        $visitsArray.= '{"c":[{"v":"' . $k . '"},{"v":' . $v . '}]},';
    }

    echo $visitsArray.= ']}';
}
if ($action == 'getCountsType') {

    $timePeriod = $_GET['timePeriod'];
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/" . $timePeriod . "/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appCount = curl_exec($ch);
    $appCount = json_decode($appCount);
    echo '{"total":"' . $appCount->{'total'}->{'count'} . '","uniqe":"' . $appCount->{'uniqe'}->{'count'} . '","newvisite":"' . $appCount->{'new'}->{'count'} . '"}';
}
if ($action == 'reloadChartAllOther') {
    $timePeriodAllOther = $_GET['timePeriodAllOther'];
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/likecommentshare/$timePeriodAllOther/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appArrayAllOther = curl_exec($ch);
    $appArrayAllOther = json_decode($appArrayAllOther);

    $commentsArray = array();
    foreach ($appArrayAllOther->{'comments'}->{'array'}[0] as $k => $v) {
        array_push($commentsArray, array($k, $v));
    }
    $sharesArray = array();
    foreach ($appArrayAllOther->{'shares'}->{'array'}[0] as $k => $v) {
        array_push($sharesArray, array($k, $v));
    }

    $visitsArray = '
    {
    "cols": [
        {"id":"","label":"Year","type":"string"},
        {"id":"","label":"Likes","type":"number"},
        {"id":"","label":"Comments","type":"number"},
        {"id":"","label":"Shares","type":"number"},
      ],"rows": [';

    $i = 0;
    foreach ($appArrayAllOther->{'likes'}->{'array'}[0] as $k => $v) {
        $visitsArray.= '{"c":[{"v":"' . $k . '"},{"v":' . $v . '},{"v":' . $commentsArray[$i][1] . '},{"v":' . $sharesArray[$i][1] . '}]},';
        $i++;
    }

    echo $visitsArray.= ']}';
}
if ($action == 'getCountsModule') {
    $timePeriod = $_GET['timePeriod'];
    $module = $_GET['module'];
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/" . $timePeriod . "/" . $module);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appCount = curl_exec($ch);
    $appCount = json_decode($appCount);
    echo '{"total":"' . $appCount->{'total'}->{'count'} . '","uniqe":"' . $appCount->{'uniqe'}->{'count'} . '","newvisite":"' . $appCount->{'new'}->{'count'} . '"}';
}

if ($action == 'viewChartModule') {
    $chartType = $_GET['chartType'];
    $timePeriod = $_GET['timePeriod'];
    $module = $_GET['module'];


    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/views/$timePeriod/$module");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appArray = curl_exec($ch);
    $appArray = json_decode($appArray);


    $visitsArray = '
    {
  "cols": [
        {"id":"","label":"Year","type":"string"},
        {"id":"","label":"Visits","type":"number"},
      ],"rows": [';


    foreach ($appArray->{$chartType}->{'array'}[0] as $k => $v) {
        $visitsArray.= '{"c":[{"v":"' . $k . '"},{"v":' . $v . '}]},';
    }

    echo $visitsArray.= ']}';
}

if ($action == 'reloadChartAllOtherModule') {
    $timePeriodAllOther = $_GET['timePeriodAllOther'];
    $module = $_GET['module'];
    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/likecommentshare/$timePeriodAllOther/$module");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $appArrayAllOther = curl_exec($ch);
    $appArrayAllOther = json_decode($appArrayAllOther);

    $commentsArray = array();
    foreach ($appArrayAllOther->{'comments'}->{'array'}[0] as $k => $v) {
        array_push($commentsArray, array($k, $v));
    }
    $sharesArray = array();
    foreach ($appArrayAllOther->{'shares'}->{'array'}[0] as $k => $v) {
        array_push($sharesArray, array($k, $v));
    }

    $visitsArray = '
    {
    "cols": [
        {"id":"","label":"Year","type":"string"},
        {"id":"","label":"Likes","type":"number"},
        {"id":"","label":"Comments","type":"number"},
        {"id":"","label":"Shares","type":"number"},
      ],"rows": [';

    $i = 0;
    foreach ($appArrayAllOther->{'likes'}->{'array'}[0] as $k => $v) {
        $visitsArray.= '{"c":[{"v":"' . $k . '"},{"v":' . $v . '},{"v":' . $commentsArray[$i][1] . '},{"v":' . $sharesArray[$i][1] . '}]},';
        $i++;
    }

    echo $visitsArray.= ']}';
}


if ($action == 'getTableDataList') {

    require_once "../../music_controller.php";
    require_once "../../../controller/pic_controller.php";
    require_once "../../../model/music_model.php";
    require_once "../../../model/pic_model.php";
    require_once "../../../config/config.php";
    require_once '../../../config/database.php';
    require_once "../../../controller/db_connect.php";
    require_once "../../../database/Dao.php";
    require_once "../../../model/video_model.php";
    require_once "../../../controller/video_controller.php";
    require_once "../../../controller/tours_controller.php";
    require_once "../../../model/tours_model.php";
    require_once "../../../controller/news_controller.php";
    require_once "../../../model/news_model.php";
    require_once "../../../controller/link_controller.php";
    require_once "../../../model/Links.php";

    $misArray = array();
    $pageLoad = $_GET['pageLoad'];
    $noOfRecoreds = 0;
    $recordsPerPage = app_key_values::$NO_OF_RECORDS_PER_PAGE_ANALYTIC;
    $noOfPages = 0;
    $startRecordNo = 0;
    $endingRecordNo = 0;
    $pageNo = $_GET['pageNo'];
    $module = $_GET['module'];

    $ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/" . $module . "/getall");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $dataArray = curl_exec($ch);
    $dataArray = json_decode($dataArray);



    $data = '';

    if (isset($dataArray->{$module})) {
        foreach ($dataArray->{$module} as $value) {
            if ($module == 'music') {
                $musicController = new Music();
                $name = $musicController->getMusicName($value->{'item_id'});
                if (isset($name)) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $name, $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
            if ($module == 'image') {
                $photoController = new Picture();
                $thumbUrl = $photoController->getPicThumbUri($value->{'item_id'});
                if (isset($thumbUrl[1])) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $thumbUrl[1], $thumbUrl[0], $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
            if ($module == 'video') {
                $videoController = new Video();
                $recDetails = $videoController->getVideoNameById($value->{'item_id'});
                if (isset($recDetails[0])) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $recDetails[1], $recDetails[0], $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
            if ($module == 'event') {
                $toursController = new Tours();
                $name = $toursController->getTourTitleById($value->{'item_id'});
                if (isset($name)) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $name, $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
            if ($module == 'news') {
                $newsController = new News();
                $name = $newsController->getNewsTitleById($value->{'item_id'});
                if (isset($name)) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $name, $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
            if ($module == 'links') {
                $linksController = new link_controller();
                $name = $linksController->getLinkTitleById($value->{'item_id'});
                if (isset($name)) {
                    $noOfRecoreds++;
                    array_push($misArray, array($noOfRecoreds, $name, $value->{'view_count'}, $value->{'like_count'}, $value->{'share_count'}, $value->{'comment_count'}));
                }
            }
        }
    }




    $noOfPages = ceil($noOfRecoreds / $recordsPerPage);
    $startRecordNo = $recordsPerPage * ($pageNo - 1) + 1;
    $endingRecordNo = $recordsPerPage * $pageNo;
    $tableHieght = $recordsPerPage * 50;

    if (isset($misArray[0][0])) {


        $data.='<div style="height:' . $tableHieght . 'px">';


        foreach ($misArray as $value) {

            if ($value[0] >= $startRecordNo & $value[0] <= $endingRecordNo) {
                $data.= '<div>';
                if ($module == 'music') {
                    $data.='
                    <div id="analytic_name_data">' . $value[1] . '</div>
                    <div id="analytic_view_data">' . $value[2] . '</div>
                    <div id="analytic_likes_data">' . $value[3] . '</div>
                    <div id="analytic_share_data">' . $value[4] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
                if ($module == 'image') {
                    $data.='
                   <div id="analytic_pic_data"><img src="' . $value[1] . '" style="width: 30px; height: 30px"/></div>
                    <div id="analytic_title_data">' . $value[2] . '</div>
                    <div id="analytic_view_data">' . $value[3] . '</div>
                    <div id="analytic_likes_data">' . $value[4] . '</div>
                    <div id="analytic_share_data">' . $value[5] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
                if ($module == 'video') {
                    $data.='
                   <div id="analytic_pic_data"><img src="' . $value[1] . '" style="width: 30px; height: 30px"/></div>
                    <div id="analytic_title_data">' . $value[2] . '</div>
                    <div id="analytic_view_data">' . $value[3] . '</div>
                    <div id="analytic_likes_data">' . $value[4] . '</div>
                    <div id="analytic_share_data">' . $value[5] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
                if ($module == 'event') {
                    $data.='
                    <div id="analytic_name_data">' . substr($value[1], 0, 75) . '</div>
                    <div id="analytic_view_data">' . $value[2] . '</div>
                    <div id="analytic_likes_data">' . $value[3] . '</div>
                    <div id="analytic_share_data">' . $value[4] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
                if ($module == 'news') {
                    $data.='
                    <div id="analytic_name_data">' . substr($value[1], 0, 75) . '</div>
                    <div id="analytic_view_data">' . $value[2] . '</div>
                    <div id="analytic_likes_data">' . $value[3] . '</div>
                    <div id="analytic_share_data">' . $value[4] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
                if ($module == 'links') {
                    $data.='
                    <div id="analytic_name_data">' . substr($value[1], 0, 75) . '</div>
                    <div id="analytic_view_data">' . $value[2] . '</div>
                    <div id="analytic_likes_data">' . $value[3] . '</div>
                    <div id="analytic_share_data">' . $value[4] . '</div>
                    <div id="analytic_comment_data">' . $value[5] . '</div>';

                    $data.='</div>';
                }
            }
        }

        $data.='</div><div class="page_no_div">';
        for ($i = 1; $i <= $noOfPages; $i++) {
            if ($noOfPages > 1) {
                if ($pageNo == $i) {
                    $data.='<div  style="float:left;"><span style="width:15px;" id="' . $i . '">' . $i . '</span>';
                } else {
                    $data.='<div  style="float:left;"><span style="width:15px;cursor: pointer;font-weight: bold" id="' . $i . '">' . $i . '</span>';
                }

                if ($noOfPages > $i) {
                    $data.='&nbsp;|&nbsp;';
                }
                $data.='</div>';
            }
        }
        $data.='</div>';
    }
    echo $data;
}
?>
