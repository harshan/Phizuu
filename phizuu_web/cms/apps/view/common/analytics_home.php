<?php
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');



$menu_item = 'analytics';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Analytics</title>

<link rel="STYLESHEET" type="text/css" href="../../../common/calender/dhtmlxcalendar.css"/>
<script type="text/javascript">

window.dhx_globalImgPath = "../../../common/calender/imgs/";

</script>
<script type="text/javascript" src="../../../common/calender/dhtmlxcommon.js"></script>
<script type="text/javascript" src="../../../common/calender/dhtmlxcalendar.js"></script>


<style type="text/css">




</style>
<link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>



<script type="text/javascript" src="../../../common/glm-ajax.js"></script>

<script type="text/javascript">

var currentGraph = 1;
var lastDuration = 'last_7_days';

var showGraphDisableSrc = '../../../images/btn_show_graph_disable.png';
var showGraphEnableSrc = '../../../images/btn_show_graph.png';

function changeGraph(newType) {

	if (currentGraph != newType) {
		getEl('showGraphImg'+currentGraph).src = showGraphEnableSrc;
		getEl('showGraphImg'+newType).src = showGraphDisableSrc;
		currentGraph = newType;
		lastDuration = '';
		showVisitsGraph (true);
	}
}

function durationChanged() {
	if (getEl('durationAll').value == 'from_to') {
		getEl('fromToDiv').style['display'] = 'inline';
	} else {
		getEl('fromToDiv').style['display'] = 'none';
		createReports();
	}
}

function createReports(firstTime) {
    if (!firstTime) {
        showOSGraph();
        showVisitsGraph(true);
        showURLGraph();
    }
    showUniqVisitsReport(true);
    showNewVisitsReport (true);
    showURLReport(true);
    showLocationReport(true);
    showTotalVisitsReport (true);
    showOSReport(true);
}

function showMoreCountryInfo() {
    showLocationReport(false);
}

function showOSGraph() {
    var tmp = findSWF("chart_2");
    var duration = getEl('durationAll').value;

    var args = "duration=" + duration + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;
    //alert("http://localhost/phizuu_analytics/controllers/" + "ReportController.php?action=ajax_show_os_graph_data&" + args);
    x = tmp.reload("ReportController.php?action=ajax_show_os_graph_data&" + args);
}

function showURLGraph() {
	var tmp = findSWF("chart_3");
        var duration = getEl('durationAll').value;

	var args = "duration=" + duration + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;
	//alert("ReportController.php?action=ajax_show_uri_graph_data&" + args);
  	x = tmp.reload("ReportController.php?action=ajax_show_uri_graph_data&" + args);
}

// Total user report
function showVisitsGraph(all) {
	var duration = getEl('durationAll').value;
	
	if (duration == 'today' || duration == 'yesterday') {
		duration = 'last_7_days';
	}
	
	if (duration != 'from_to' && lastDuration == duration) {
		return;
	}

	tmp = findSWF("chart");
	
	var action = '';
	if (currentGraph == 1) {
		action = 'ajax_show_total_visit_graph_data';
	} else if (currentGraph == 2) {
		action = 'ajax_show_uniq_visit_graph_data';
	} else if (currentGraph == 3) {
		action = 'ajax_show_new_visit_graph_data';
	}
	
	lastDuration = duration;

	var args = "duration=" + duration + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;
	//alert("http://localhost/phizuu_analytics/controllers/" + "ReportController.php?action=" + action + "&" + args);
  	x = tmp.reload("ReportController.php?action=" + action + "&" + args);
}

function findSWF(movieName) {
  if (navigator.appName.indexOf("Microsoft")!= -1) {
    return window["ie_" + movieName];
  } else {
    return document[movieName];
  }
}

// Total user report
function showTotalVisitsReport(all) {
	setLoadingReportText(getEl('divTotalVisits'));
	
	if (all) {//If all get values from common controls
		var ajax = new GLM.AJAX();
		ajax.onError = showTotalVisitsReportError;
		var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;
		ajax.callPage( 'ReportController.php?action=ajax_show_total_visit_report', showTotalVisitsReportCallBack, 'POST', args, true);
	}
}

function showTotalVisitsReportCallBack(resp) {
	if (resp == '') {
		showTotalVisitsReportError();
	} else {
		getEl('divTotalVisits').innerHTML = resp;
	}
}

function showTotalVisitsReportError() {
	getEl('divTotalVisits').innerHTML = 'Error loading report!';
}

// Uniq user report
function showUniqVisitsReport(all) {
	setLoadingReportText(getEl('divUniqVisits'));
	
	if (all) {//If all get values from common controls
		var ajax = new GLM.AJAX();
		ajax.onError = showUniqVisitsReportError;
		var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;
		ajax.callPage( 'ReportController.php?action=ajax_show_uniq_visit_report', showUniqVisitsReportCallBack, 'POST', args, true);
	}
}

function showUniqVisitsReportCallBack(resp) {
	if (resp == '') {
		showUniqVisitsReportError();
	} else {
		getEl('divUniqVisits').innerHTML = resp;
	}
}

function showUniqVisitsReportError() {
	getEl('divUniqVisits').innerHTML = 'Error loading report!';
}

// New user report
function showNewVisitsReport(all) {
	setLoadingReportText(getEl('divNewVisits'));
	
	if (all) {//If all get values from common controls
		var ajax = new GLM.AJAX();
		ajax.onError = showNewVisitsReportError;
		var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;

		ajax.callPage( 'ReportController.php?action=ajax_show_new_visit_report', showNewVisitsReportCallBack, 'POST', args, true);
	}
}

function showNewVisitsReportCallBack(resp) {
	if (resp == '') {
		showNewVisitsReportError();
	} else {
		getEl('divNewVisits').innerHTML = resp;
	}
}

function showNewVisitsReportError() {
	getEl('divNewVisits').innerHTML = 'Error loading report!';
}

// New OS report
function showOSReport(all) {
	setLoadingReportText(getEl('divOSUsage'));
	
	if (all) {//If all get values from common controls
		var ajax = new GLM.AJAX();
		ajax.onError = showOSReportError;
		var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;

		ajax.callPage( 'ReportController.php?action=ajax_show_os_usage_report', showOSReportCallBack, 'POST', args, true);
	}
}

function showOSReportCallBack(resp) {
	if (resp == '') {
		showOSReportError();
	} else {
		getEl('divOSUsage').innerHTML = resp;
	}
}

function showOSReportError() {
	getEl('divOSUsage').innerHTML = 'Error loading report!';
}

// New URL report
function showURLReport(all) {
	setLoadingReportText(getEl('divURLAccess'));
	
	if (all) {//If all get values from common controls
		var ajax = new GLM.AJAX();
		ajax.onError = showURLReportError;
		var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value;

		ajax.callPage( 'ReportController.php?action=ajax_show_url_usage_report', showURLReportCallBack, 'POST', args, true);
	}
}

function showURLReportCallBack(resp) {
	if (resp == '') {
		showURLReportError();
	} else {
		getEl('divURLAccess').innerHTML = resp;
	}
}

function showURLReportError() {
	getEl('divURLAccess').innerHTML = 'Error loading report!';
}

// New Location report
function showLocationReport(limit) {
	setLoadingReportText(getEl('divVisitsByLocation'));

        if (limit) {
            limitText = "&limit=10";
        } else {
            limitText = "";
        }
	

        var ajax = new GLM.AJAX();
        ajax.onError = showLocationReportError;
        var args = "duration=" + getEl('durationAll').value + "&from=" + getEl('fromAll').value + "&to=" + getEl('toAll').value + limitText;

        ajax.callPage( 'ReportController.php?action=ajax_show_location_report', showLocationReportCallBack, 'POST', args, true);

}

function showLocationReportCallBack(resp) {
	if (resp == '') {
		showLocationReportError();
	} else {
		getEl('divVisitsByLocation').innerHTML = resp;
	}
}

function showLocationReportError() {
	getEl('divVisitsByLocation').innerHTML = 'Error loading report!';
}

//Common funtions
function getEl (id) {
	return document.getElementById(id);
}

function setLoadingReportText (elem) {
	elem.innerHTML = "<img src='../../../images/bigrotation2.gif' width='32' height='32' />";
}


//Preload images
var ajaxLoader = new Image;
ajaxLoader.src = "../../../images/bigrotation2.gif";
</script>
</head>

<body onload="javascript: createReports(true);">
      <div id="header">
        <div id="headerContent">
           <?php include("../../../view/user/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
<div id="mainWideDiv">
  <div id="middleDiv3">
  	
	<?php include("../../../view/user/common/navigator.php");?>
<?php
$userArr = $popArray['userDetails'];
if ($userArr != NULL) 
	$header = "Report for the application '" .$userArr['app_name'] . "'" ;
else 
	$header = "Report for all applications" ;
?>

      <p>&nbsp;</p>

					<div id="lightBlueHeader2">
	  	
                                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle2" style="border-radius: 3px 3px 0 0;-moz-border-radius: 3px 3px 0 0;"><?php echo $header ?></div>
	  	
	  </div>
<p>
  <select name="durationAll" id="durationAll" onchange="javascript: durationChanged();">
    <!-- <option value="today" selected="selected">Today</option> -->
    <option value="yesterday">Yesterday</option>
    <option value="last_7_days" selected="selected">Last 7 Days</option>
    <option value="this_month">This Month - (<?php echo $popArray['thisMonth']; ?>)</option>
    <option value="last_month">Last Month - (<?php echo $popArray['lastMonth']; ?>)</option>
    <option value="from_to">Specific Period</option>
  </select>
  <div id='fromToDiv' style="display: none">
  <div id="dhtmlxDblCalendar"></div>
  <input type="hidden" name="fromAll" id="fromAll" /> 
  <input type="hidden" name="toAll" id="toAll" /> <input type="button" value="Go!" onclick="javascript: createReports();" />
  </div>
</p>
<p>
<div id='divLineGraph'><?php open_flash_chart_object( '100%', 300, "ReportController.php?action=ajax_show_total_visit_graph_data&duration=last_7_days", false); ?></div></p>


<table width="945" height="70" border="0" cellpadding="4" cellspacing="4" >
  <tr class="tahoma_12_blue">
    <td width="31%"><a href="javascript: changeGraph(1);"><img src="../../../images/btn_show_graph_disable.png" alt="Show Graph" name="showGraphImg1" width="137" height="25" border="0" align="absmiddle" id="showGraphImg1" /></a><strong> Total Visits: </strong><span id='divTotalVisits'></span></td>
    
    <td width="40%"><a href="javascript: changeGraph(2);"><img src="../../../images/btn_show_graph.png" alt="Show Graph" name="showGraphImg2" width="137" height="25" border="0" align="absmiddle" id="showGraphImg2" /></a> <strong> Absolute Unique Visits:</strong> <span id='divUniqVisits'></span></td>
    
    <td width="29%"><a href="javascript: changeGraph(3);"><img src="../../../images/btn_show_graph.png" alt="Show Graph" name="showGraphImg3" width="137" height="25" border="0" align="absmiddle" id="showGraphImg3" /></a> <strong>New Visits:</strong> <span id='divNewVisits'></span></td>
  </tr>
</table>
	<div id="analyticsArea">
		<div id="analyticsBody" >

			<div id="analyticsLightBlueHeader">
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
				<div id="analyticsLightBlueHeaderMiddle">
					<div id="headerMiddleContentL" class="tahoma_14_white">OS Usage Percentage:</div>
			  </div>
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>

                    <?php open_flash_chart_object( 300, 300, "ReportController.php?action=ajax_show_os_graph_data&duration=last_7_days", false); ?>
       
		</div>
		<div id="analyticsBodyMiddle">
			<div id="analyticsLightBlueHeader">

				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
				<div id="analyticsLightBlueHeaderMiddle">
					<div id="headerMiddleContentL" class="tahoma_14_white">Unique number of Vists for URLs</div>
			  </div>
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>

			                   <?php open_flash_chart_object( 300, 300, "ReportController.php?action=ajax_show_uri_graph_data&duration=last_7_days", false); ?>
  
		</div>

	</div>

	<div id="analyticsArea">
		<div id="analyticsBody">

			<div id="analyticsLightBlueHeader">
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
				<div id="analyticsLightBlueHeaderMiddle">
					<div id="headerMiddleContentL" class="tahoma_14_white">OS Usage Percentage:</div>
			  </div>
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>
			<div id="analyticsTitleBox">

				<div id="headerMiddleContentL" class="tahoma_14_white">OS</div>
				<div id="headerMiddleContentR" class="tahoma_14_white">Precentage</div>
			</div>
                    <div id='divOSUsage'></div>
		</div>
		<div id="analyticsBodyMiddle">
			<div id="analyticsLightBlueHeader">

				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
				<div id="analyticsLightBlueHeaderMiddle">
					<div id="headerMiddleContentL" class="tahoma_14_white">URL access</div>
			  </div>
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>
			<div id="analyticsTitleBox">
				<div id="headerMiddleContentL" class="tahoma_14_white">URL</div>

				<div id="headerMiddleContentR" class="tahoma_14_white">Visits</div>
                        </div>
			<div id='divURLAccess'></div>
		</div>
		<div id="analyticsBody">
			<div id="analyticsLightBlueHeader">
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>

				<div id="analyticsLightBlueHeaderMiddle">
					<div id="headerMiddleContentL" class="tahoma_14_white">Visits By Location</div>
			  </div>
				<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
			</div>
			<div id="analyticsTitleBox">
				<div id="headerMiddleContentL" class="tahoma_14_white">Country</div>
				<div id="headerMiddleContentR" class="tahoma_14_white">Visits</div>

		  </div>
			<div id='divVisitsByLocation'></div>
		</div>
	</div>
<p>
        
</p>
<script type="text/javascript">
var mDCal;

mDCal = new dhtmlxDblCalendarObject('dhtmlxDblCalendar', false, {
    isMonthEditable: true,
    isYearEditable: true
});
mDCal.setYearsRange(<?php echo date('Y',strtotime('-2 years')); ?>, <?php echo date('Y'); ?>);
    //mCal.setSensitive('<?php echo date('Y.m.d',strtotime('-2 years')); ?>,<?php echo date('Y.m.d'); ?>');
mDCal.setDateFormat("%d/%m/%Y");
mDCal.setDate("06/01/2010","15/01/2010");
mDCal.draw();

mDCal.setOnClickHandler(function(date,obj,type){
                    var textDate = new Date(date);

                    if (type=='left')
                            getEl('fromAll').value = textDate.getFullYear() + "-" + (textDate.getMonth()+1) + "-" + textDate.getDate();
                    else
                            getEl('toAll').value = textDate.getFullYear() + "-" + (textDate.getMonth() +1) + "-" + textDate.getDate();

                    //createReports();
      }) ;
</script>

  </div>
<br class="clear"/><br class="clear"/>
</div>
 <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
</html>
