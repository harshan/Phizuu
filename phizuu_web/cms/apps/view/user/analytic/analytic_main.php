<?php
$menu_item = 'analytics';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>phizuu - Analytic</title>


        <link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="../../../css/side_munu.css"/>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    </head>

    <script type="text/javascript">
   
        //    jQuery(function($){
        //        $('#side_menu li').click(function(){
        //             $('#side_menu li').css({
        //                background:'#d2d2d2'
        //             })
        //             $(this).css({
        //                backgroundColor: '#3dc5ec'
        //             })
        //                    
        //             
        //         })
        //        
        //       
        //    })
     
        
        var activeModule = "app";
        function selectedMenu(val){
            
            activeModule = val;
            
            if(val == 'app'){
                resetBackgroundColor();
                document.getElementById('app').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_app.php";
                document.getElementById('app').style.color = '#1e1f1f';
                
            
            }
            if(val == 'music'){
                resetBackgroundColor();
                document.getElementById('music').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_music.php";
                document.getElementById('music').style.color = '#1e1f1f';
            }
            if(val == 'photos'){
                resetBackgroundColor();
                document.getElementById('photos').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_photos.php";
                document.getElementById('photos').style.color = '#1e1f1f';
            }
            if(val == 'videos'){
                resetBackgroundColor();
                document.getElementById('videos').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_videos.php";
                document.getElementById('videos').style.color = '#1e1f1f';
            }
            if(val == 'events'){
                resetBackgroundColor();
                document.getElementById('events').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_events.php";
                document.getElementById('events').style.color = '#1e1f1f';
            }
            if(val == 'news'){
                resetBackgroundColor();
                document.getElementById('news').style.background = 'url(../../../images/side_tab_ac.png)';
                document.getElementById('iframe_page').src = "analytic_news.php";
                document.getElementById('news').style.color = '#1e1f1f';
            }
//            if(val == 'links'){
//                resetBackgroundColor();
//                document.getElementById('links').style.backgroundColor ='#3dc5ec';
//                document.getElementById('iframe_page').src = "analytic_links.php";
//         
//            }
    }
    function resetBackgroundColor(){
        document.getElementById('app').style.background = 'url(../../../images/side_tab_in.png)';
        document.getElementById('music').style.background = 'url(../../../images/side_tab_in.png)';
        document.getElementById('photos').style.background = 'url(../../../images/side_tab_in.png)';
        document.getElementById('videos').style.background = 'url(../../../images/side_tab_in.png)';
        document.getElementById('events').style.background = 'url(../../../images/side_tab_in.png)';
        document.getElementById('news').style.background = 'url(../../../images/side_tab_in.png)';
        
        document.getElementById('app').style.color = '#fff';
        document.getElementById('music').style.color = '#fff';
        document.getElementById('photos').style.color = '#fff';
        document.getElementById('videos').style.color = '#fff';
        document.getElementById('events').style.color = '#fff';
        document.getElementById('news').style.color = '#fff';
     
//        document.getElementById('links').style.backgroundColor ='#d2d2d2';
    }
        
    jQuery(function($){
            
        $(function(){
            $('#app').css({
                background:'url(../../../images/side_tab_ac.png)',
                color:'#1e1f1f'
            })
        });
            
        $('#side_menu li').mouseover(function(){
               
            $(this).css({
                background:'url(../../../images/side_tab_ac.png)',
                color:'#1e1f1f'
            })
                    
             
        })
        $('#side_menu li').mouseout(function(){

            if(activeModule != $(this).attr('id')){   
                
                $(this).css({
                    background:'url(../../../images/side_tab_in.png)',
                    color:'#fff'
                })
               
            
            }
             
        })
          
       
    }) 
    </script>   

    <body>
        <div id="header" >
            <div id="headerContent">
                <?php include("../../../view/user/common/header.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">

                <?php include("../../../view/user/common/navigator.php"); ?>

                <div id="body">
                    <div style="width: 150px;height: 100%;float: left;margin-right: 10px">
                        <div class="menu_div">
                            <ul id="side_menu">
                                <li id="app" onclick="selectedMenu('app')"  style="border-radius:3px 3px 0 0" >App</li>
                                <li id="music" onclick="selectedMenu('music')">Music</li>
                                <li id="photos" onclick="selectedMenu('photos')">Photos</li>
                                <li id="videos" onclick="selectedMenu('videos')">Videos</li>
                                <li id="events" onclick="selectedMenu('events')">Tours</li>
                                <li id="news" onclick="selectedMenu('news')">News</li>
<!--                                <li id="links" onclick="selectedMenu('links')">Links</li>-->

                            </ul>
                        </div>
                    </div>
                    <div style="height: 100%" id="page_content">
                        <iframe src="analytic_app.php" frameborder="0" width="775px" height="800px" id="iframe_page"></iframe> 
                    </div>


                </div>

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
