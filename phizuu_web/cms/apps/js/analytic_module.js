google.load('visualization', '1', {
    'packages':['corechart']
    });
   
$(document).ready(function(){
    
    
    $(".menu2 a:first").addClass("current");
    $("#total_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
    $("#total_visits").css("cursor","default");
    var getTimePeriod = 'week';
    var chartType = 'total';
    var getModule = $("#module").val();
    getCount(getTimePeriod,getModule);
    viewChart(chartType,getTimePeriod);
      
    function viewChart(chartType,getTimePeriod){
       
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1', {
            'packages':['corechart']
            });
        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart());
           
        function drawChart() {
            //$("#tt").src("../../../images/progress-bar.gif");
            $("#tt").attr("src","../../../images/progress-bar.gif");
            var jsonData = $.ajax({
                url: "../../../controller/modules/analytic/analyticController.php?action=viewChartModule&chartType="+chartType+"&timePeriod="+getTimePeriod+"&module="+getModule,
                dataType:"json",
                async: false
            }).responseText;
            // Create our data table out of JSON data loaded from server.
            
            var data = new google.visualization.DataTable(jsonData);
                           

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.AreaChart(document.getElementById('chart_div_visits'));
            chart.draw(data, {
                width: 750, 
                height: 300, 
                backgroundColor:'#e9e9e9'
            });
        }
    }

    function getCount(getTimePeriod,getModule){
        $.post("../../../controller/modules/analytic/analyticController.php?action=getCountsModule&timePeriod="+getTimePeriod+"&module="+getModule,
            function(data){
                
                var response = JSON.parse(data);    
                    
                $('.total').html(response.total);
                $('.unique').html(response.uniqe);
                $('.new').html(response.newvisite);
                    
            },'json');
    }

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
        
        var getTimePeriod= $(this).attr('id');
        
        $("#timePeriod").val(getTimePeriod);
        $(".menu2 a").css("background-color","#757d7f");
        $(".menu2 a").css("color","White");
        activeModule = getTimePeriod;
        $(this).css("background-color","#e8e8e8");
        $(this).css("color","#434343");
             
            
        $("#total_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
        $("#total_visits").css("cursor","default");
        $("#absolute_unique_visits").attr("src","../../../images/show_graph_btn.png"); 
        $("#absolute_unique_visits").css("cursor","pointer");
        $("#new_visits").attr("src","../../../images/show_graph_btn.png"); 
        $("#new_visits").css("cursor","pointer");
        getCount(getTimePeriod,getModule);
        viewChart(chartType,getTimePeriod);
            
    });
    
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
            viewChart(selectedDiv,selectedTimePeriod);
        }else if(selectedDiv =='uniqe'){
        
            //$(".menu2 a:second").addClass("current");
            $("#absolute_unique_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
            $("#absolute_unique_visits").css("cursor","default");
            $("#total_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#total_visits").css("cursor","pointer");
            $("#new_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#new_visits").css("cursor","pointer");
                
            viewChart(selectedDiv,selectedTimePeriod);
        }else if(selectedDiv =='new'){
            //$(".menu2 a:second").addClass("current");
            $("#new_visits").attr("src","../../../images/show_graph_inactive_btn.png"); 
            $("#new_visits").css("cursor","default");
            $("#total_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#total_visits").css("cursor","pointer");
            $("#absolute_unique_visits").attr("src","../../../images/show_graph_btn.png"); 
            $("#absolute_unique_visits").css("cursor","pointer");

            viewChart(selectedDiv,selectedTimePeriod);
        }
            
    });
    
    
        function reloadDrowChartAllOther(getTimePeriod){
 
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1', {'packages':['corechart']});
            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart());
           
            function drawChart() {
                
                var jsonData = $.ajax({
                    url: "../../../controller/modules/analytic/analyticController.php?action=reloadChartAllOtherModule&timePeriodAllOther="+getTimePeriod+"&module="+getModule,
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