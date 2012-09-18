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
<script src="../../../js/analytic_module.js"></script>


<script type="text/javascript">

    var selectedPageNo = 1;
    var module = 'music';
    $.ajax({
        url: '../../../controller/modules/analytic/analyticController.php?action=getTableDataList&pageLoad=true&pageNo='+selectedPageNo+'&module='+module,
        success: function(data) {
            $('#dataTable').html(data);
            $("#1").css("color","gray");
            
            pageload();
            
        }
     

    });
    
    function pageload(){
        
        
        $('.page_no_div div span').click(function(){
        
        if(selectedPageNo!=$(this).attr('id')){
            selectedPageNo = $(this).attr('id');
          
        $('#dataTable').html("<div style=height:<?php echo app_key_values::$NO_OF_RECORDS_PER_PAGE_ANALYTIC*60; ?>px;padding-left:300px;padding-top:100px><img src='../../../images/ajax-loader-table.gif'/></div>");
            
            $.ajax({
                url: '../../../controller/modules/analytic/analyticController.php?action=getTableDataList&pageLoad=false&pageNo='+selectedPageNo+'&module='+module,
                success: function(data) {
                    $('#dataTable').html(data);
                    $('.page_no_div div span').css("color","black");
                    $("#"+selectedPageNo).css("color","gray");
                    pageload()
                   
                    
                }
     

            });
            
            }
       
        });
        

    
        
    }

</script>
<input type="hidden" id="timePeriod" value="week"/>
<input type="hidden" value="music" id="module"/>
<div id="analytic_body">
    <div>
        <div id="analytic_header">REPORT FOR MUSIC MODULE VISITS</div>
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
            <div id="analytic_header">REPORT FOR MUSIC MODULE </div>
            <div class="menu3">
                <a href="#1" id="week">LAST 7 DAYS</a>
                <a href="#2" id="month">MONTH </a>
                <a href="#3" id="year">YEAR</a>

            </div>
            <div id="tabs">

                <div id="chart_div_visits_all_other" style="width: 750px; height: 300px;text-align: center"><img src="../../../images/progress-bar.gif" style="height: 20px;width: 300px;padding-top: 100px" id="tt"/></div>
            </div>

        </div>

        <div style="50px">&nbsp;</div>
        <div style="clear: both;margin-top: 0px">
            <div id="analytic_header">LIST OF MUSIC</div>  
            <div>
                <div id="analytic_name_col">NAME</div>
                <div id="analytic_view_col">NO OF VIEWS</div>
                <div id="analytic_likes_col">NO OF LIKES</div>
                <div id="analytic_share_col">SHARE COUNT</div>
                <div id="analytic_comment_col">COMMENT COUNT</div>
            </div>

        </div>
        
        <div id="dataTable" style="background-color: #d2d2d2;" >

        </div>



    </div>

</div>

