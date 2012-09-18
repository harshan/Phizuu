<?php
error_reporting(1);
require_once "../../../config/app_key_values.php";
require_once "../../../controller/home_image_controller.php";
@session_start();
include "ThumbNail.php";
$menu_item = "home_images";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Phizuu Application</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
        <!--Slide show start-->
        <script type="text/javascript" src="../../../js/slide_show/jquery-1.2.6.min.js"></script>

        <script type="text/javascript">

            /*** 
            Simple jQuery Slideshow Script
            Released by Jon Raasch (jonraasch.com) under FreeBSD license: free to use or modify, not responsible for anything, etc.  Please link out to me if you like it :)
             ***/

            function slideSwitch() {
                var $active = $('#slideshow DIV.active');

                if ( $active.length == 0 ) $active = $('#slideshow DIV:last');

                // use this to pull the divs in the order they appear in the markup
                var $next =  $active.next().length ? $active.next()
                : $('#slideshow DIV:first');

                // uncomment below to pull the divs randomly
                // var $sibs  = $active.siblings();
                // var rndNum = Math.floor(Math.random() * $sibs.length );
                // var $next  = $( $sibs[ rndNum ] );


                $active.addClass('last-active');

                $next.css({opacity: 0.0})
                .addClass('active')
                .animate({opacity: 1.0}, 0, function() {
                    $active.removeClass('active last-active');
                });
            }

            $(function() {
                setInterval( "slideSwitch()", 3000 );
            });
            function takeAction(action) {
    
                if (action=='skip') {
                    window.location = "AppWizardControllerNew.php?action=loading_image_skip";
                } else if (action=='save') {
                    $.post("../../../controller/modules/home_image/home_image_controller.php?action=noOfRecoeds", function(data){
                        if(data==0){
            
                            alert("Please upload at least one image! ");
                        }else{
                            $.post("../../../controller/modules/home_image/home_image_controller.php?action=checkDetaultImage", function(data){
                
                                if(data>0){
                                    document.getElementById('mainForm').submit();
                                }else{
                                    alert("Please select default image! ");
                                }
                            });
            
                        }
                    });
            
                }
            }

        </script>

        <style type="text/css">

            /*** set the width and height to match your images **/

            #slideshow {
                position:relative;
                height:250px;
                width: 217px;
            }

            #slideshow DIV {
                position:absolute;
                top:0;
                left:0;
                z-index:8;
                opacity:0.0;
                height: 250px;
                background-color: #FFF;
            }

            #slideshow DIV.active {
                z-index:10;
                opacity:1.0;
            }

            #slideshow DIV.last-active {
                z-index:9;
            }

            #slideshow DIV IMG {
                height: 250px;
                display: block;
                border: 0;
                /*    margin-bottom: 10px;*/
            }

        </style>

        <!-- Slide show finish-->
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <!--<script src="../../../js/sort/jquery-1.7.2.js"></script>-->
        <script type="text/javascript" src="../../../js/crop/jquery.imgareaselect-0.3.min.js"></script>
        <!--sort order-->
        <link rel="stylesheet" href="../../../js/sort/jquery.ui.all.css">
            <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
            <script type="text/javascript" src="../../../js/jquery-ui-dialog.js"></script>
            <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
            <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
            <link rel="stylesheet" href="../../../js/sort/demos.css">

                <style>
                    #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
                    #sortable li { margin: 0 1px 1px 1px; padding: 0.1em; padding-left: 1.5em; font-size: 1.4em; height: 75px;width: 500px;background-color: #d3d3d3}
                    #sortable li span { position: absolute; margin-left: -1.3em; }
                </style>
                <script>
                    $(function() {
                        $( "#sortable" ).sortable();
                        $( "#sortable" ).disableSelection();
                    });
        
       

                </script>
                <!--TinyBox start-->
                <link rel="stylesheet" href="../../../js/tinyBox/style.css" type="text/css" />
                <script type="text/javascript" src="../../../js/tinyBox/tinybox.js"></script>
                <!--TinyBox ends-->


                </head>
                <?php
//get no of recoeds
                $homeImageController = new home_image_controller();
                $homeImageArr = $homeImageController->getAllHomeImagesByAppId($_SESSION['user_id']);

                if (isset($homeImageArr)) {
                    $noofRecodes = count($homeImageArr);
                }
                $folderName = $_SESSION['user_id'];
                $upload_dir = "../../../../../static_files/$folderName/images/home_images/";
                $upload_dir_temp = "../../../../../static_files/$folderName/images/temp_images/";
                $upload_dir_thumb = "../../../../../static_files/$folderName/images/home_thumb_images/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, TRUE);
                }
                if (!is_dir($upload_dir_thumb)) {
                    mkdir($upload_dir_thumb, 0777);
                }

//if ($_SERVER['SERVER_NAME'] == 'localhost') {
//    $callbackURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_images/";
//    $callbackThumbURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_thumb_images/";
//} else {
//    $callbackURL = "http://phizuu.com/static_files/$folderName/images/home_images/";
//    $callbackThumbURL = "http://phizuu.com/static_files/$folderName/images/home_thumb_images/";
//}

                $domain = $_SERVER["SERVER_NAME"];
                if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
                    $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/home_images/";
                    $callbackThumbURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
                } elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
                    $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/home_images/";
                    $callbackThumbURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
                } else {
                    $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/home_images/";
                    $callbackThumbURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "static_files/$folderName/images/home_thumb_images/";
                }



//Delete files in temp folder
                foreach (glob($upload_dir_temp . '*.*') as $v) {
                    unlink($v);
                }

                function GetFileName($path) {
                    $path_parts = pathinfo($path);

                    $exe = $path_parts['extension'];
                    $file = $path_parts['filename'];
                    return $imageName = $file . '.' . $exe;
                }
                ?>    


                <body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
                    <div id="header">
                        <div id="headerContent">
                            <?php include("../common/header.php"); ?>
                        </div>
                        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
                    </div>
                    <div id="mainWideDiv">
                        <div id="middleDiv2">

                            <?php include("../common/navigator.php"); ?>
                            <div id="bodyPhotos">
                                <div style="width: 150px;padding-bottom: 10px;cursor: pointer">
                                    <div id="lightbox" onclick="TINY.box.show({iframe:'../../../common/image_uploader/image_uploader.php',width:1000,height:600,fixed:false,maskid:'bluemask',maskopacity:10,closejs:function(){closeJS()}})"><img src="../../../images/upload_img.png"/></div>
                                </div>
                                <div id="lightBlueHeader">

                                    <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 503.5px;padding-top: 8px;height: 20px;margin-left: 1px">Home Images</div>

                                </div>

                                <div id="photoHolderBox" class="uploadSectionDiv" style="clear: left">



                                    <ul id="sortable">
                                        <?php
                                        foreach ($homeImageArr as $value) {
                                            $filename1 = GetFileName($value['image_url'])
                                            ?>               
                                            <li class="ui-state-default" id='<?php echo 'id_' . $value['id']; ?>'>
                                                <div class="dragHandle" style="cursor: move;float: left;background-color: #d3d3d3"></div>
                                                <div class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left"></div>
                                                <span><img src="<?php echo $value['image_url_thumb'] ?>"/>

                                                </span>
                                    <!--            <span style="float: right;padding-left: 300px;padding-top: 25px;"><input type="submit" value="Set as Default" onclick="setDefault(<?php echo $value['id']; ?>)"/></span>-->
                                                <div><input type="hidden" id="<?php echo 'imagePath' . $value['id']; ?>" value="<?php echo $upload_dir . $filename1; ?>"/>
                                                    <input type="hidden" id="<?php echo 'imagePathThumb' . $value['id']; ?>" value="<?php echo $upload_dir_thumb . $filename1; ?>"/></div>
                                                <div class='ttip' style='float:right'>
                                                    <div style="float: right;padding-top: 25px;cursor: pointer;padding-right: 5px" id="delete_<?php echo $value['id'] ?>"><img src="../../../images/cross.png" onclick="deleteItem(<?php echo $value['id']; ?>)" /></div>
                                                </div>  
                                                <div style="float: right;padding-right: 20px;padding-top: 25px;cursor: pointer"><input type="button" id="<?php echo 'default' . $value['id']; ?>" value="Set as Default" onclick="setDefault(<?php echo $value['id']; ?>)"/></div>
                                                <div id="div_tooltip_common_<?php echo $value['id']; ?>" class="div_tooltip_common">Delete</div>
                                            </li>
                                        <?php } ?>
                                    </ul>   

                                </div>


                                <div style="width: 260px;height: 500px;float: right;background-image: url('../../../images/home_preview_new.png');background-repeat:no-repeat;">
                                    <div id="slideshow" style="margin: 130px 0 0 21px;background-image: url('../../../images/empty_bg.png');">

                                        <?php
                                        $homeImageArr = $homeImageController->getAllHomeImagesByAppId($_SESSION['user_id']);
                                        if (isset($homeImageArr)) {
                                            $i = 0;
                                            foreach ($homeImageArr as $value) {
                                                if ($i == 0) {
                                                    ?>
                                                    <div class="active">
                                                        <img src="<?php echo $value['image_url'] ?>" />
                                                    </div>
                                                <?php } else { ?>
                                                    <div>
                                                        <img src="<?php echo $value['image_url'] ?>" />
                                                    </div>
                                                    <?php
                                                }
                                            }
                                            $i++;
                                        }
                                        ?>


                                    </div>
                                </div>
                            </div>

                        </div><br class="clear"/>  <br class="clear"/> 

                    </div> 

                    <div id="result" style="display: none"></div>
                    <br class="clear"/>  <br class="clear"/> 
                    <div id="footerInner" >
                        <div class="lineBottomInner"></div>
                        <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
                    </div>

                </body>

                </html>


                <script type="text/javascript">
                    $( "#dialog" ).dialog({
                        autoOpen: false,
                        show: "blind",
                        hide: "explode"
                    });
                    function MM_swapImgRestore() { //v3.0
                        var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
                    }

                    function MM_preloadImages() { //v3.0
                        var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                            var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
                                if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                        }

                        function MM_findObj(n, d) { //v4.01
                            var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
                                d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
                            if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
                            for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
                            if(!x && d.getElementById) x=d.getElementById(n); return x;
                        }

                        function MM_swapImage() { //v3.0
                            var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
                            if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
                        }
                        //oder set using this code
                        $(document).ready(function() {
                            
                            $(".ttip div").mouseover(function(){
                               
                                var arr = $(this).attr('id').split("_");;
                                var id = arr[1];
                               
                                $("#delete_"+id).mouseover(function(){
                   
                                    $("#div_tooltip_common_"+id).css({
                                        "margin":"52px 0 0 468px",
                                        "padding":"10px 0 0 12px"
                                    });
                                    $("#div_tooltip_common_"+id).show();
                                    $("#delete_"+id).mouseout(function(){
                                        $("#div_tooltip_common_"+id).hide();
                                    });
                    
                                });
                
                
                            
                            });
                            $("#sortable").sortable({handle : '.dragHandle'});
                           
                            $('#sortable').bind('sortupdate', function(event, ui) {
                            
                                $("#sortable").sortable( 'disable' );
                                $("#sortable").css('cursor', 'wait');
                                $("#sortable .dragHandle").css('cursor', 'wait');
                                var order = $('#sortable').sortable('serialize');
                                $.post('../../../controller/modules/home_image/home_image_controller.php?action=order&'+order, function(data) {
                                    $("#sortable").sortable( 'enable' );
                                    $("#sortable").css('cursor', '');
                                    $("#sortable .dragHandle").css('cursor', 'move');
                                });
                            });
                            
                           
                            //refreshEdits();
                        });
                        showUploader();
                        function showUploader(){
                            $.post("../../../controller/modules/home_image/home_image_controller.php?action=noOfRecoeds", function(data){
                                if(data=='2'){
           
                                }
                                //alert(data);
                            });
                        } 
                        function setDefault(id){
    
                            var itemId = id;
                            var item = $("#id_"+id);
                            var imagePath = document.getElementById('imagePath'+id).value;
                            //var imagePathThumb = document.getElementById('imagePathThumb'+id ).value;
                            
                            $.post("../../../controller/modules/home_image/home_image_controller.php?action=set_default", { 'id': id,'imagePath':imagePath },
                            function(data){
                               
                              
                                if (data!='ok') {
                                    alert("Error! while set as default\n\n"+data);
                                } else{
                                    alert("Selected image set as default successfully");
                                    window.location.reload(true);
                                }
                            });
        
                        }
                        
                        function deleteItem(id) {
                            //$("#newsSortable").
                            var itemId = id;
                            var item = $("#id_"+id);
                            var imagePath = document.getElementById('imagePath'+id).value;
                            var imagePathThumb = document.getElementById('imagePathThumb'+id ).value;
                            
                            if($('#default'+id).val()=="Default Image"){
                                alert("Can not delete default image.");
                                
                                
                                
                               
                            }else{
                                $.post("../../../controller/modules/home_image/home_image_controller.php?action=delete_image", { 'id': id,'imagePath':imagePath,'imagePathThumb':imagePathThumb },
        
                                function(data){
          
                                    if (data!='ok') {
                                        alert("Error! while deleting\n\n"+data);
                                        $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                                    } else{
                                        item.hide(500,function(){
                                            document.getElementById('sortable').removeChild(document.getElementById('id_'+itemId));
                                            window.location.reload(true);

                                        });
                                    }
                                });
                                $('#id_'+itemId).children().css('background-color', 'pink');
                            }
                            

                        }
                        $('#result').load('../../../controller/modules/home_image/home_image_controller.php?action=get_default_image', function(data) {
                            $('#default'+data).css({'background-color': '#437C17','color': '#ffffff'});
                            $('#default'+data).val("Default Image");              
                        });
                </script>
                <script type="text/javascript">
                        //function openJS(){alert('loaded')}
                        function closeJS(){
    
                            window.location.reload();
                        }
                </script>