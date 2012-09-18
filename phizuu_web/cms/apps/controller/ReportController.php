<?php
session_start();
//sleep(1);
require_once ('../model/reports/Reports.php');
require_once ('../model/reports/GraphBuilder.php');
require_once ('../model/reports/DateProcessor.php');
require_once ('../database/Dao.php');
include_once '../common/ofc-library/open_flash_chart_object.php';
include_once '../common/ofc-library/open-flash-chart.php';

$userArr = NULL;
if (isset($_GET['user_id'])) {
	if (isset($_GET['user_id']) && $_GET['user_id'] != 'clear') {
		$dao = new Dao();
		$sql = "SELECT * FROM `user` WHERE `id` = {$_GET['user_id']}";
		$res = $dao->query($sql);
		$userArr = $dao->getArray($res);
		$userArr = $userArr[0];
		$_SESSION['userArr'] = $userArr;
	} else {
		unset($_SESSION['userArr']);
	}
} else if (isset($_SESSION['userArr'])) {
	$userArr = $_SESSION['userArr'];
}

if ($userArr != NULL) {
	$report = new Reports($userArr['app_id']);
} else {
	$report = new Reports();
}
//echo $reports->findNumUniqUUIDs('2009-10-28','2009-10-30') . "<br/>";
//print_r( $reports->findVisitsByLocations('2009-10-28','2009-10-30') );//. "<br/>";

$action = "";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

switch ($action) {
	case 'main_view':
		$popArray = array();
		$dateProcessor = new DateProcessor();
		$popArray['thisMonth'] = $dateProcessor->getThisMonthName();
		$popArray['lastMonth'] = $dateProcessor->getLastMonthName();
		$popArray['userDetails'] = $userArr;
		include ('../view/common/analytics_home.php');
		break;
		
	case 'ajax_show_total_visit_graph_data':
		$graph = new GraphBuilder();
		echo $graph->createLineGraph($report, TOTAL_VISITS_FUNCTION, $_GET);
		break;
		
	case 'ajax_show_uniq_visit_graph_data':
		$graph = new GraphBuilder();
		echo $graph->createLineGraph($report, UNIQ_VISITS_FUNCTION, $_GET);
		break;
		
	case 'ajax_show_new_visit_graph_data':
		$graph = new GraphBuilder();
		echo $graph->createLineGraph($report, NEW_VISITS_FUNCTION, $_GET);
		break;
		
	case 'ajax_show_os_graph_data':
		$graph = new GraphBuilder();
		echo $graph->createOSChart($report, $_GET);
		break;	
		
	case 'ajax_show_uniq_visit_report':
		$duration = $_POST['duration'];
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { 
			$from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}
		
		//$report = new Reports($appId);
		$visits = $report->findNumUniqUUIDs ($from, $to);
		
		echo ("$visits");
		break;
		
	case 'ajax_show_total_visit_report':
		$duration = $_POST['duration'];
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}
		
		//$report = new Reports($appId);
		$visits = $report->findNumTotalUUIDs ($from, $to);
		
		echo ("$visits");
		break;			
		
	case 'ajax_show_new_visit_report':
		$duration = $_POST['duration'];
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}
		
		//$report = new Reports($appId);
		$visits = $report->findNumNewUUIDs ($from, $to);
		
		echo ("$visits");
		break;	
			
	case 'ajax_show_os_usage_report':
		$duration = $_POST['duration'];
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}

		//$report = new Reports($appId);
		$oses = $report->findOSUsagePercentage ($from, $to);
		
                include '../view/common/os_report.php';
		
		break;			
		
	case 'ajax_show_url_usage_report':
		$duration = $_POST['duration'];
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}
		
		//$report = new Reports($appId);
		$urls = $report->findNumUniqURIAccess ($from, $to);
		include '../view/common/url_report.php';

		break;		
		
	case 'ajax_show_location_report':
		$duration = $_POST['duration'];

                if (isset ($_POST['limit']))
                    $limit = $_POST['limit'];
                else
                    $limit = false;
	
		$appId = NULL;
		if (isset($_SESSION['user_id'])) {
			//TODO: Get the app id for the user; Leave it NULL to get all App Ids
		}
		
		$dateProcessor = new DateProcessor();
		
		if ($duration == 'last_month') {
			$from = $dateProcessor->getFromDateByText($duration);
			$to = $dateProcessor->getToDateByText($duration);
		} else if ($duration != 'from_to') { $from = $dateProcessor->getFromDateByText($duration);
			$to = NULL; // For now for all special texts this should be null. If you want change for specific texts
		} else {
			$to = $_POST['to'];
			$from = $_POST['from'];
		}
		
		//$report = new Reports($appId);
		$countries = $report->findVisitsByLocations ($from, $to, $limit);
		
		include '../view/common/location_report.php';


		break;				
	default:
		echo "Error! No valid action";
}
?>