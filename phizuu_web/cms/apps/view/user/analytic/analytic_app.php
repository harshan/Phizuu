<?php
session_start();

require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once "../../../controller/music_controller.php";

require_once '../../../database/Dao.php';
require_once "../../../model/music_model.php";

require_once "../../../config/app_key_values.php";
?>

<link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
<link rel="stylesheet" type="text/css" href="../../../css/tab_style.css"/>
<link rel="stylesheet" type="text/css" href="../../../css/tab2_style.css"/>
<script src="../../../js/jquery-1.3.2.min.js"></script>

<!-- google charts start includes -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!-- google charts end includes -->

<!-- ----------------------App visits chart---------------------------------- -->
<script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
    
    function drawChart() {
        $(".menu2 a:first").addClass("current");
        $("#total_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
        $("#total_visits").css("cursor","default");
      
        $.post("../../../controller/modules/analytic/analyticController.php?action=getCounts",
        function(data){
            var response = JSON.parse(data);    
            $('.total').html(response.total);
            $('.unique').html(response.uniqe);
            $('.new').html(response.newvisite);
        
        },'json');
          
        var jsonData = $.ajax({
            url: "../../../controller/modules/analytic/analyticController.php?action=visits",
            dataType:"json",
            async: false
        }).responseText;
      
        
        // Create our data table out of JSON data loaded from server.
      
        var data = new google.visualization.DataTable(jsonData);

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div_visits'));
        chart.draw(data, {width: 700, height: 300, backgroundColor:'#e9e9e9'});
    }
    
    $(document).ready(function(){
        
        function getCount(getCountsType){
            
            $.post("../../../controller/modules/analytic/analyticController.php?action=getCountsType&timePeriod="+getCountsType,
            function(data){
                var response = JSON.parse(data);    
                    
                $('.total').html(response.total);
                $('.unique').html(response.uniqe);
                $('.new').html(response.newvisite);
                    
            },'json');
        }
        function reloadDrowChart(chartType,selectedTimePeriod){
 
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart());
           
            function drawChart() {
                
                var jsonData = $.ajax({
                    url: "../../../controller/modules/analytic/analyticController.php?action=reloadChart&chartType="+chartType+"&timePeriod="+selectedTimePeriod,
                    dataType:"json",
                    async: false
                }).responseText;
                // Create our data table out of JSON data loaded from server.
                
                var data = new google.visualization.DataTable(jsonData);
                           

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('chart_div_visits'));
                chart.draw(data, {width: 750, height: 300, backgroundColor:'#e9e9e9'});
            }
        }
        
        $(".change_chart").click(function(){
            var selectedDiv= $(this).attr('id');
            var selectedTimePeriod = $('#timePeriod').val();
            
            if(selectedDiv =='total'){
                //$(".menu2 a:second").addClass("current");
                $("#total_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
                $("#total_visits").css("cursor","default");
                $("#absolute_unique_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#absolute_unique_visits").css("cursor","pointer");
                $("#new_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#new_visits").css("cursor","pointer");
                reloadDrowChart(selectedDiv,selectedTimePeriod);
            }else if(selectedDiv =='uniqe'){
        
                //$(".menu2 a:second").addClass("current");
                $("#absolute_unique_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
                $("#absolute_unique_visits").css("cursor","default");
                $("#total_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#total_visits").css("cursor","pointer");
                $("#new_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#new_visits").css("cursor","pointer");
                
                reloadDrowChart(selectedDiv,selectedTimePeriod);
            }else if(selectedDiv =='new'){
                //$(".menu2 a:second").addClass("current");
                $("#new_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
                $("#new_visits").css("cursor","default");
                $("#total_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#total_visits").css("cursor","pointer");
                $("#absolute_unique_visits").attr("src","../../../images/show_graph_btn.png"); 
                $("#absolute_unique_visits").css("cursor","pointer");

                reloadDrowChart(selectedDiv,selectedTimePeriod);
            }
            
        });
        var activeModule = 'week';
        $('.menu2 a').mouseover(function(){
               
            $(this).css({
                backgroundColor: '#e8e8e8',
                color: '#434343'
            })
                    
             
        })
        $('.menu2 a').mouseout(function(){

            if(activeModule != $(this).attr('id')){   
                
                $(this).css({
                    backgroundColor: '#757d7f',
                    color:'White'
                })
               
            
            }
             
        })
        $(".menu2 a").click(function(){
            var selectedDiv= $(this).attr('id');
            var chartType = 'total';
            $("#timePeriod").val(selectedDiv);
            $(".menu2 a").css("background-color","#757d7f");
            $(".menu2 a").css("color","White");
            activeModule = selectedDiv;
            $(this).css("background-color","#e8e8e8");
            $(this).css("color","#434343");
             
            
            $("#total_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
            $("#total_visits").css("cursor","default");
            $("#absolute_unique_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#absolute_unique_visits").css("cursor","pointer");
            $("#new_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#new_visits").css("cursor","pointer");
            getCount(selectedDiv);
            reloadDrowChart(chartType,selectedDiv);
            
        });
        
        //        comments-view-share ------------------------------------------------------------------------------
        function reloadDrowChartAllOther(selectedTimePeriod){
 
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart());
           
            function drawChart() {
                
                var jsonData = $.ajax({
                    url: "../../../controller/modules/analytic/analyticController.php?action=reloadChartAllOther&timePeriodAllOther="+selectedTimePeriod,
                    dataType:"json",
                    async: false
                }).responseText;
                // Create our data table out of JSON data loaded from server.
                
                var data = new google.visualization.DataTable(jsonData);
                           

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('chart_div_visits_all_other'));
                chart.draw(data, {width: 750, height: 300, backgroundColor:'#e9e9e9'});
            }
        }
        
        $(".menu3 a:first").addClass("current");
        reloadDrowChartAllOther('week');
        var activeModule = 'week';
        $('.menu3 a').mouseover(function(){
               
            $(this).css({
                backgroundColor: '#e8e8e8',
                color: '#434343'
            })
                    
             
        })
        $('.menu3 a').mouseout(function(){

            if(activeModule != $(this).attr('id')){   
                
                $(this).css({
                    backgroundColor: '#757d7f',
                    color:'White'
                })
               
            
            }
             
        })
        $(".menu3 a").click(function(){
            var selectedDiv= $(this).attr('id');
            
            $(".menu3 a").css("background-color","#757d7f");
            $(".menu3 a").css("color","White");
            activeModule = selectedDiv;
            $(this).css("background-color","#e8e8e8");
            $(this).css("color","#434343");
            reloadDrowChartAllOther(selectedDiv);
        });
    
        
    });
    
</script>

<?php
//Getting all app details
$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$appArray = curl_exec($ch);
$appArray = json_decode($appArray);

//Geogropical information and OS informations
$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/getosandcountry");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$osArray = curl_exec($ch);
$osArray = json_decode($osArray);

//Get top users
$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/topusers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tuArray = curl_exec($ch);
$tuArray = json_decode($tuArray);

//Geo array
if (isset($osArray->{'country'}[0])) {
    $countryArray = "[['Country', 'Fans']";

    foreach ($osArray->{'country'}[0] as $k => $v) {
        $countryArray = $countryArray . ",['$k',$v]";
    }
    $countryArray = $countryArray . "]";
}
//OS array
if (isset($osArray->{'os'}[0])) {
    $osList = "[['OS', 'Devices'],['Android'," . $osArray->{'os'}[0]->{'android'} . "],['Iphone'," . $osArray->{'os'}[1]->{'iphone'} . "]]";

//OS version array



    $osViesionArray = "[['Version', 'Devices']";
    foreach ($osArray->{'os'}[0]->{'os_version'} as $k => $v) {
        $osViesionArray = $osViesionArray . ",['Android $k',$v]";
    }
    foreach ($osArray->{'os'}[1]->{'os_version'} as $k => $v) {
        $osViesionArray = $osViesionArray . ",['Iphone $k',$v]";
    }
    $osViesionArray = $osViesionArray . "]";
}
?>


<input type="hidden" id="timePeriod" value="week"/>
<div id="analytic_body">
    <div>
        <div id="analytic_header">APP SUMMERY</div>
        <div>
            <div id="analytic_app_view">
                <div id="analytic_app_units"><?php if (isset($appArray->{'no_devices'})) echo $appArray->{'no_devices'} ?></div>
                <div id="analytic_app_title">USERS</div>
            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 60px;width: 2px"/></div>
            <div id="analytic_app_view">
                <div id="analytic_app_units"><?php if (isset($appArray->{'counts'}[0]->{'comment_count'})) {
    echo $appArray->{'counts'}[0]->{'comment_count'};
} ?></div>
                <div id="analytic_app_title">TOTAL COMMENTS</div>
            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 60px;width: 2px"/></div>
            <div id="analytic_app_view">
                <div id="analytic_app_units"><?php if (isset($appArray->{'counts'}[0]->{'like_count'})) {
    echo $appArray->{'counts'}[0]->{'like_count'};
} ?></div>
                <div id="analytic_app_title">TOTAL LIKES</div>
            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 60px;width: 2px"/></div>
            <div id="analytic_app_view">
                <div id="analytic_app_units"><?php if (isset($appArray->{'counts'}[0]->{'share_count'})) {
    echo $appArray->{'counts'}[0]->{'share_count'};
} ?></div>
                <div id="analytic_app_title">TOTAL SHARES</div>
            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 60px;width: 2px"/></div>
            <div id="analytic_app_view">
                <div id="analytic_app_units"><?php if (isset($appArray->{'counts'}[0]->{'view_count'})) {
    echo $appArray->{'counts'}[0]->{'view_count'};
} ?></div>
                <div id="analytic_app_title">TOTAL VIEWS</div>
            </div>
        </div>

        <div style="clear: both">&nbsp;</div>
        <div>
            <div id="analytic_header">GEOGRAPHICAL MAP </div>

            <script type='text/javascript'>
                
                google.load('visualization', '1', {'packages': ['geochart']});
                google.setOnLoadCallback(drawRegionsMap);
          
                
                function drawRegionsMap() {
                
                    var data = google.visualization.arrayToDataTable(<?php echo $countryArray; ?>);

                    var options = {
                        backgroundColor:'#e9e9e9'
                    };
                    
                    var chart = new google.visualization.GeoChart(document.getElementById('chart_div_geo_map'));
                    chart.draw(data, options);
                };
                
            </script>
            <div id="chart_div_geo_map" style="width: 750px; height: 300px;text-align: center"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 300px;padding-top: 100px"/></div>

        </div>

        <div style="clear: both">&nbsp;</div>
        <div id="analytic_header">REPORT FOR APP VISITS</div>
        <div class="menu2">
            <a href="#1" id="week">LAST 7 DAYS</a>
            <a href="#2" id="month">MONTH</a>
            <a href="#3" id="year">YEAR</a>

        </div>
        <div id="tabs">

            <div id="chart_div_visits" style="width: 750px; height: 300px;text-align: center"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 300px;padding-top: 100px"/></div>
        </div>
        <div>
            <div id="analytic_totDis">
                <div id="analytic_subtitle">TOTAL VISITS</div>
                <div id="analytic_tot" class="total"><img src="../../../images/ajax-loader.gif" id="show_total_div" /></div>
                <div id="total" class="change_chart"><img src="../../../images/show_graph_btn.png" id="total_visits" style="cursor: pointer"/></div>
            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 115px;width: 2px"/></div>
            <div id="analytic_totDis">
                <div id="analytic_subtitle">ABSOLUTE UNIQUE VISITS</div>
                <div id="analytic_tot" class="unique"><img src="../../../images/ajax-loader.gif" id="show_total_div" /></div>
                <div id="uniqe" class="change_chart"><img src="../../../images/show_graph_btn.png" id="absolute_unique_visits" style="cursor: pointer"/></div>

            </div>
            <div><img src="../../../images/seperator_visits.png" style="position: absolute;height: 115px;width: 2px"/></div>
            <div id="analytic_totDis">
                <div id="analytic_subtitle">NEW VISITS</div>
                <div id="analytic_tot" class="new"><img src="../../../images/ajax-loader.gif" id="show_total_div" /></div>
                <div id="new" class="change_chart"><img src="../../../images/show_graph_btn.png" id="new_visits" style="cursor: pointer"/></div>

            </div>
        </div>
        <div>
            <div style="50px">&nbsp;</div>
            <div id="analytic_header">REPORT FOR APP </div>
            <div class="menu3">
                <a href="#1" id="week">LAST 7 DAYS</a>
                <a href="#2" id="month">MONTH </a>
                <a href="#3" id="year">YEAR</a>

            </div>
            <div id="tabs">

                <div id="chart_div_visits_all_other" style="width: 750px; height: 300px;text-align: center"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 300px;padding-top: 100px"/></div>
            </div>
        </div>
        <div>
            <div style="clear: both">&nbsp;</div>
            <div id="analytic_header">REPORT FOR OS USAGE PERCENTAGE</div>

            <script type="text/javascript">
                google.load("visualization", "1", {packages:["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable(<?php echo $osList; ?>);


                    var options = {
                        title: 'OS Usage Percentage',
                        backgroundColor:'#e9e9e9'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('chart_div_OS'));
                    chart.draw(data, options);
                }
            </script>
            <div id="chart_div_OS" style="width: 375px; height: 300px;float: left"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 200px;padding-top: 100px"/></div>


            <script type="text/javascript">
                google.load("visualization", "1", {packages:["corechart"]});
                google.setOnLoadCallback(drawChart);
                function drawChart() {
                    var data = google.visualization.arrayToDataTable(<?php echo $osViesionArray; ?>);


                    var options = {
                        title: 'OS Usage Percentage',
                        backgroundColor:'#e9e9e9'
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('chart_div_OS_version'));
                    chart.draw(data, options);
                }
            </script>
            <div id="chart_div_OS_version" style="width: 375px; height: 300px;float: left"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 200px;padding-top: 100px"/></div>
        </div>
        <div>
            <div style="clear: both">&nbsp;</div>
            <div id="analytic_header">TOP USERS</div>
            <div>
                <div id="app_name">Name</div>
                <div id="app_total_count" >NO OF VISITS</div>
            </div>
            <div style="clear: both">
<?php

if(isset($tuArray->{"topusers"})){
foreach ($tuArray->{"topusers"} as $key => $val) {
    ?>
                    <div id="app_name_data"><?php echo $key?></div>
                    <div id="app_total_data"><?php echo $val?></div>
<?php }} ?>
            </div>
        </div>


    </div>

</div>

