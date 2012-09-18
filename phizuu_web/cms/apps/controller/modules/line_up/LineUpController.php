<?php
sleep(1);
require_once ('../../../config/config.php');
require_once("../../../controller/session_controller.php");
require_once ('../../../database/Dao.php');
require_once ('../../../model/line_up/LineUp.php');
require_once ('../../../model/StorageServer.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../model/StorageServer.php');

$userArr = UserInfo::getUserInfoDirect();

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

$lineUp = new LineUp();

switch ($action) {
    case 'main_view':
        include ('../../../view/user/line_up/main.php');
        break;
    case 'save_artist':
	//$x = $lineUp->createNewArtist($_POST, $userArr['id'], $userArr['app_id']);
	//echo $x;
        if ($lineUp->createNewArtist($_POST, $userArr['id'], $userArr['app_id']))
            echo "OK";
        else
            echo "ERROR";
        break;
    case 'search_artists':
        list($artistArray, $total) = $lineUp->searchArtists($_POST);
        $data = new stdClass();
        if ($artistArray === false) {
            $data->error = true;
        } else {
            $data->error = false;

            ob_start();
            include ('../../../view/user/line_up/artist_line_inc.php');
            $outHtml = ob_get_contents();
            ob_end_clean();
            
            $data->html = $outHtml;
            $data->totalRecords = $total;
        }

        echo json_encode($data);
        break;
    case 'delete_artist':
        if ($lineUp->deleteArtist($_POST))
            echo "OK";
        else
            echo "ERROR";
        break;
    case 'get_artist_info':
       $row = $lineUp->getArtistInfo($_POST);
       $data = new stdClass();
        if ($row === false) {
            $data->error = true;
        }else {
            $data->error = false;
            $data->data = $row;
        }
        
       echo json_encode($data);
       break;
    case 'update_artist_info':
        if ($lineUp->updateArtist($_POST, $userArr['id']))
            echo "OK";
        else
            echo "ERROR";
        break;
    case 'save_festival_day':
	if (($id = $lineUp->createFestivalDay($_POST)) === false)
            echo "ERROR";
        else
            echo $id;
        break;
    case 'delete_festival_day':
	if ($lineUp->deleteFestivalDay($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'get_festival_days':
	$festival_days = $lineUp->getFestivalDays();
        $data = new stdClass();
        if ($festival_days === false) {
            $data->error = true;
        } else {
            $data->error = false;
            $data->festival_days = $festival_days;
        }
        echo json_encode($data);
	break;
    case 'update_festival_day':
	if ($lineUp->updateFestivalDay($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'save_stage':
	list($id, $image_url) =  $lineUp->createNewStage($_POST, $userArr['id'], $userArr['app_id']);
        $data = new stdClass();
        if ($id === false) {
            $data->error = true;
        } else {
            $data->error = false;
	    $data->id = $id;
            $data->image_url = $image_url;
        }
        echo json_encode($data);;
        break;
    case 'get_stages':
	$stages = $lineUp->getStages();
        $data = new stdClass();
        if ($stages === false) {
            $data->error = true;
        } else {
            $data->error = false;
            $data->stages = $stages;
        }
        echo json_encode($data);
	break;
    case 'delete_stage':
	if ($lineUp->deleteStage($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'update_stage':
	if ($lineUp->updateStage($_POST, $userArr['id']))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'save_festival_day_stage':
	if ($lineUp->addFestivalDayToStage($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'load_stages_id':
	$stages_id = $lineUp->loadStagesId($_POST);
        $data = new stdClass();
        if ($stages_id === false) {
            $data->error = true;
        } else {
            $data->error = false;
            $data->stages_id = $stages_id;
        }
        echo json_encode($data);
	break;
    case 'delete_festival_day_stage':
	if ($lineUp->deleteFestivalDayStage($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'save_show':
	$result = $lineUp->creatShow($_POST);
        $data = new stdClass();
        if ($result === false) {
            $data->error = true;
	    $data->result = $result;
	    $data->duplicate = false;
        } elseif ($result === 1) {
            $data->error = true;
	    $data->result = $result;
	    $data->duplicate = true;
        }  else {
            $data->error = false;
            $data->result = $result;
	    $data->duplicate = false;
        }
        echo json_encode($data);
	break;
    case 'delete_show':
	if ($lineUp->deleteShow($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'update_show':
	if ($lineUp->updateShow($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'update_show_end_time':
	if ($lineUp->updateShowEndTime($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;
    case 'update_stages_order_indexes':
	if ($lineUp->updateOrderOfStagesInDay($_POST))
	    echo "OK";
        else
            echo "ERROR";
        break;	
    case 'load_shows':
	$results = $lineUp->loadShows($_POST);
	$data = new stdClass();
        if ($results === false) {
            $data->error = true;
        } else {
            $data->error = false;
            $data->results = $results;
        }
        echo json_encode($data);
	break;
    default:
            echo "Error! No valid action";
}
?>