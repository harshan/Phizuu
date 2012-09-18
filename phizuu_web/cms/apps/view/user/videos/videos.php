<?php
require_once "../../../config/app_key_values.php";
$menu_item = "videos";
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/video_controller.php');
include('../../../model/video_model.php');
include('../../../config/error_config.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');
include('../../../model/login_model.php');
require_once('../../../controller/user_settings_controller.php');

$limitFiles = new LimitFiles();
$limit_count = $limitFiles->getLimit($_SESSION['user_id'], 'video');


$bvideo = new Video();
$bank_video = $bvideo->listBankVideos($_SESSION['user_id']);
$count = 1;
$ivideo = new Video();
$iphone_video = $ivideo->listIphoneVideos($_SESSION['user_id']);
$icount = 1;

$user_type = $_ENV['setting_youtube'];
$userSet = new UserSettings();
$get_user = $userSet->getUserSettings($user_type);

$defaultUser = "";
foreach ($get_user as $user) {
    if ($user->preferred == "1") {
        $defaultUser = $user->value;
    }
}

//get videos module item analytic data

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/video/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$dataList = array();
if(isset($allItems)){
foreach ($allItems->{"video"} as $value) {
    $dataList[$value->{"item_id"}] = array($value->{"like_count"}, $value->{"share_count"}, $value->{"comment_count"}, $value->{"view_count"});
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Phizuu Application</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <script type="text/JavaScript">
            <!--
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
                //-->
        </script>

        <style type="text/css">

            ul {
                list-style-type:none;
                background:#fff;
                padding:0px;
                margin:0px;
                min-height:150px;
                width:464px;
            }

            li {

                width:400px;
                padding-left:0px;

                list-style-type:none;
                list-style-image:none;
            }

            .highlight {
                width: 464px;
                height: 80px;
                background-image: url('../../../images/drop.png');

            }

        </style>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>




        <script type="text/JavaScript">
                var limit=<?php echo $limit_count->video_limit; ?>;

                $(function() {
                    $("#list_1, #list_2").sortable({
                        connectWith: '.connected',
                        placeholder: 'highlight',
                        revert: true
                    }).disableSelection();

                    $('.edit').editable('../../../controller/video_all_controller.php?action=edit',{
                        indicator : 'Saving...',
                        tooltip   : 'Click to edit...'
                    });

                    $("#list_2").bind('sortreceive', function(event, ui) {
                        //window.console.log(event);
                        if (videoInList>=limit) {
                            $("#list_1").sortable('cancel');
                            alert('You have reached the maximum number of videos of '+limit+'.');
                        } else {
                            videoInList++;
                        }

                    });

                    $("#list_1").bind('sortreceive', function(event, ui) {
                        videoInList--;
                    });

                    $("#list_2").bind('sortupdate', function(event, ui) {
                        $("#list_1, #list_2").sortable( 'disable' );
                        $("#list_1, #list_2").css('cursor', 'wait');
                        var list1 = $('#list_1').sortable('serialize');
                        list1 = list1.replaceAll('id','list1');

                        var list2 = $('#list_2').sortable('serialize');
                        list2 = list2.replaceAll('id','list2');

                        var ordered = list1 +'&'+ list2;
                        //alert(ordered);
                        
                        $.post('../../../controller/video_all_controller.php?action=order&'+ordered, function(data) {
                            //alert(data);
                            $("#list_1, #list_2").sortable( 'enable' );
                            $("#list_1, #list_2").css('cursor', 'move');
                        });

                    });
                });

                String.prototype.replaceAll=function(s1, s2) {
                    var str = this;
                    var pos = str.indexOf(s1);
                    while (pos > -1){
                        str = str.replace(s1, s2);
                        pos = str.indexOf(s1);
                    }
                    return (str);
                }

        </script>


    </head>


    <body>
        <div id="header">
            <div id="headerContent">
                <?php include("../common/header.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">

                <?php include("../common/navigator.php"); ?>
                <div id="body">
                    <div id="comments_view_full" ></div>
                    <div id="div_error"></div>
                    <div id="bodyLeft">
                        <div id="lightBlueHeader"  >

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Bank Video List</div>

                        </div>
                        <div id="titleBox">
                            <div class="tahoma_14_white" id="title">Title</div>
                            <div class="tahoma_14_white" id="duration">Duration</div>
                            <div class="tahoma_14_white" id="note_thubmnail">Thumbnail</div>
                        </div>

                        <div id="textBarMusic1"><ul id="list_1" class="connected" style="background-color: #e5e5e5;margin-bottom: 10px">

                                <?php
                                if (sizeof($bank_video) > 0) {
                                    foreach ($bank_video as $bVideo) {

                                        $duration = ceil($bVideo->duration);
                                        $seconds = $duration % 60;
                                        $minutes = ($duration - $seconds) / 60;
                                        ?>

                                        <li id="id_<?php echo $bVideo->id; ?>"  style="cursor: pointer;">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div id="textBarMusic">
                                                            <div class="move"><img src="../../../images/move.png"/></div>
                                                            <div class="tahoma_12_blue edit titleMusic" id="1_<?php echo $bVideo->id; ?>"><?php echo $bVideo->title; ?></div>
                                                            <div class="tahoma_14_white" id="durationMusic"><span id="2_<?php echo $bVideo->id; ?>" class="tahoma_12_blue"><?php echo $minutes . "m " . $seconds . 's' ?></span></div>
                                                            <div class="tahoma_12_blue" id="noteMusicThumb">
                                                                <div class="thmbImg"><img src="<?php echo $bVideo->thum_uri; ?>" width="50" height="44" /></div>
                                                            </div>
                                                            <div class="tahoma_12_blue" id="iconBoxMusic">
                                                                <div id="iconVideo" class="edit_<?php echo $bVideo->id; ?>"><a href="javascript: showEdit(<?php echo $bVideo->id; ?>)"><img src="../../../images/file.png" border="0" /></a></div>
                                                                <div id="iconVideo" class="delete_<?php echo $bVideo->id; ?>"><a onclick="javascript: return deleteVideo(<?php echo $bVideo->id; ?>);" href="#"><img src="../../../images/cross.png" border="0" /></a></div>
                                
                                                            </div>
                                                            <div id="div_tooltip_common_<?php echo $bVideo->id;?>" class="div_tooltip_common">Edit</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </li>

                                        <?php
                                        $count++;
                                    }
                                }
                                ?>
                            </ul></div>
                        <div style="float:left; width: 100%;"></div>
                        <div id="lightBlueHeader">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Add Videos from YouTube</div>

                        </div>
                        <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%;padding: 5px 0 0 5px" >
                            Username: <input name="youtubeUserName" id="youtubeUserName" type="text" class="textFeildBoarder" style="width:200px; height:20px;margin-left: 4px" value="<?php echo $defaultUser; ?>"/>
                            <img id="youtubeButton" onclick="javascript: youtubeGetCollections()" style="cursor:pointer" src="../../../images/btn_submit.png" align="top" ></img>
                            <img align="top" id="youtubeButtonWait" src="../../../images/bigrotation2.gif" style="display:none"/>
                        </div>
                        <div class="tahoma_12_blue" style="height: 35px; float: left; width: 100%;padding: 0px 0 0 5px" >
                            <div style="float: left; width: 66px;">Collections:</div>
                            <select onchange="javascript: youtubeGetVideos(this.value)"  name="youtubeCollection" id="youtubeCollection" type="text" class="textFeildBoarder" style="width:209px; height:27px; float: left;" disabled>
                                <option>-- Enter Username --</option>
                            </select>
                            <div id="listingWaiting" class="tahoma_12_blue" style="float: left;display: none ">
                                <img align="middle" id="youtubeButtonWait" src="../../../images/bigrotation2.gif" /> Listing videos...
                            </div>
                        </div>

                        <div id="titleBox">
                            <div class="tahoma_14_white" id="title">Title</div>
                            <div class="tahoma_14_white" id="duration">Duration</div>
                            <div class="tahoma_14_white" id="note_thubmnail">Thumbnail</div>
                        </div>

                        <div id="textBarMusic1"><ul id="list_3"  style="background-color: #e5e5e5">

                            </ul></div>



                    </div>
                    <div id="bodyRgt" style="height:auto">
                        <div id="lightBlueHeader">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">iPhone Video List</div>

                        </div>

                        <div id="titleBox">
                            <div class="tahoma_14_white" id="title">Title</div>
                            <div class="tahoma_14_white" id="duration">Duration</div>
                            <div class="tahoma_14_white" id="note_thubmnail">Thumbnail</div>
                        </div>

                        <div id="textBarMusic1"><ul id="list_2" class="connected"  style="background-color: #e5e5e5">
                                <?php
                                if (sizeof($iphone_video) > 0) {
                                    foreach ($iphone_video as $iVideo) {

                                        $duration = ceil($iVideo->duration);
                                        $seconds = $duration % 60;
                                        $minutes = ($duration - $seconds) / 60;
                                        ?>

                                        <li id="id_<?php echo $iVideo->id; ?>"  >
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div id="textBarMusic">
                                                            <div class="move" style="cursor: move"><img src="../../../images/move.png"/></div>
                                                            <div class="items" style="height: 55px">
                                                                
                                                                <div class="tahoma_12_blue edit titleMusic" style="height: 30px;overflow: hidden" id="1_<?php echo $iVideo->id; ?>"><?php echo $iVideo->title; ?></div>
                                                                <div class="tahoma_14_white" id="durationMusic"><span id="2_<?php echo $iVideo->id; ?>" class="tahoma_12_blue"><?php echo $minutes . "m " . $seconds . 's' ?></span></div>
                                                                <div class="tahoma_12_blue" id="noteMusicThumb">
                                                                    <div class="thmbImg"><img src="<?php echo $iVideo->thum_uri; ?>" width="50" height="44" border="0" /></div>
                                                                </div>
                                                                <div class="tahoma_12_blue" id="iconBoxMusic">
                                                                    <div id="iconVideo" class="edit_<?php echo $iVideo->id; ?>"><a href="javascript: showEdit(<?php echo $iVideo->id; ?>)"><img src="../../../images/file.png" border="0" /></a></div>

                                                                    <div id="iconVideo" class="delete_<?php echo $iVideo->id; ?>"><a onclick="javascript: return deleteVideo(<?php echo $iVideo->id; ?>, true);" href="#"><img src="../../../images/cross.png" border="0" /></a></div>
                                                                </div>
                                                                 <div id="div_tooltip_common_<?php echo $iVideo->id;?>" class="div_tooltip_common">Edit</div>
                                                            </div>
                                                            <?php
                                                if (array_key_exists($iVideo->id, $dataList)) {
                                                    $itemExist = TRUE;
                                                }
                                                ?>
                                                <div style="float: left;padding-left: 20px;width: 300px;vertical-align: bottom;margin-top: -10px;font-family: arial;font-size: 12px;color: #777f81" id="div_source">
                                                    <span class="showViews" id="showViews_<?php echo $iVideo->id; ?>"><img src="../../../images/icon_views.png" /><span style="padding: 0px 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$iVideo->id][3];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showLikes_<?php echo $iVideo->id; ?>"><img src="../../../images/icon_like.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$iVideo->id][0];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showShare_<?php echo $iVideo->id; ?>"><img src="../../../images/icon_share.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$iVideo->id][1];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showComments_<?php echo $iVideo->id; ?>"><img src="../../../images/icon_comment.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$iVideo->id][2];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span><input type="hidden" id="commentsCount_<?php echo $iVideo->id; ?>" value="<?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $dataList[$iVideo->id][2];
                                                    else: echo '0';
                                                    endif;
                                                ?>"/>
                                                
                                                </div>
                                                <div id="viewToolTip<?php echo $iVideo->id ?>" class="div_tooltip"></div>
                                                <div id="tooltip_comment<?php echo $iVideo->id ?>" class="div_tooltip_comment" style="margin: 15px 0 0 135px; "></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </li>


                                      <?php
                                        $icount++;
                                        $itemExist = FALSE;
                                    }
                                }
                                ?>
                            </ul></div>

                    </div>
                </div>

                <br class="clear"/>
                <div id="footerInner" >
                    <div class="lineBottomInner"></div>
                    <!--	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>-->
                </div><br class="clear"/><br class="clear"/>
            </div><br class="clear"/>	
        </div>
        
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>

        <div id="editVideoDiv"  style="background-color: #FFFFFF; border: #043f53 2px solid; padding: 8px; z-index: 25; position:absolute; display: none">
            <form action="" id="editVideo">
                <input type="hidden" value="" name="imageURI" id='imageURIEdit'/>
                <input type="hidden" value="" name="id" id='idEdit'/>
                <div  >
                    <table width="365" border="0" cellspacing="4" cellpadding="0" class="tahoma_12_blue">
                        <tr>
                            <td width="100" valign="top">Title:</td>
                            <td valign="top"><input type="text" value="" id="titleEdit" name="title" style="width: 300px;" class="textfield editInput"></td>
                        </tr>
                        <tr>
                            <td valign="top">Duration:</td>
                            <td valign="top"><input type="text" value="" id="durationEdit" name="duration" style="width: 300px;" class="textfield editInput"></td>

                        </tr>
                        <tr>
                            <td valign="top">Year:</td>
                            <td valign="top"><input type="text" value="" id="yearEdit" name="year" style="width: 300px;" class="textfield editInput"></td>
                        </tr>
                        <tr>
                            <td valign="top">Note:</td>
                            <td valign="top"><textarea type="text" value="" id="noteEdit" name="note" style="width: 300px; height: 120px" class="textfield editInput"></textarea></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div id="editMessage"></div></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td width="320" align="left"><div align="right"><img width="89" height="35" border="0" align="top" src="../../../images/save2.png" style="cursor:pointer" onclick="javascript: saveEditedVideo();"> <img style="cursor:pointer" onclick="javascript: hideEdit();" width="89" height="35" border="0" align="top" src="../../../images/cancel.png"></div></td>
                                            </tr>
                                            </table>
                                            </div>
                                            </form>
                                            </div>

                                            </body>
       <script type="text/javascript">
    
            $(document).ready(function(){
               
            $("#iconBoxMusic div").mouseover(function(){
                    var arr = $(this).attr('class').split("_");;
                    var id = arr[1];
                    
                   
                    if("edit"==arr[0]){
                        $(".edit_"+id).mouseover(function(){
                    
                            $("#div_tooltip_common_"+id).text("Edit");
                            $("#div_tooltip_common_"+id).css({
                                "margin":"45px 0 0 355px",
                                "padding":"10px 0 0 18px"
                            });
                            $("#div_tooltip_common_"+id).show();
                            $(".edit_"+id).mouseout(function(){
                                $("#div_tooltip_common_"+id).hide();
                            });
                    
                        });
                    }
                    else if("delete"==arr[0]){
                        $(".delete_"+id).mouseover(function(){
                            $("#div_tooltip_common_"+id).text("Delete");
                            $("#div_tooltip_common_"+id).show();
                            $("#div_tooltip_common_"+id).css({
                                "margin":"45px 0 0 395px",
                                "padding":"10px 0 0 12px"
                            });
                            $(".delete_"+id).mouseout(function(){
                                $("#div_tooltip_common_"+id).hide();
                            });
                        });
                    }
                            
                });
 
                $(".showViews").mouseover(function(){
                   
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    if('showViews'==arr[0])
                    {
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 15px">Views</div>');
                        $(".div_tooltip").css({"margin":"20px 0 0 30px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});
                       
                    }
                    else if('showLikes'==arr[0]){
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 17px">Likes</div>');
                        $(".div_tooltip").css({"margin":"20px 0 0 70px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                    }
                    else if('showShare'==arr[0]){
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 12px">Shares</div>');
                        $(".div_tooltip").css({"margin":"20px 0 0 105px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                    }
                    else if('showComments'==arr[0]){
                        var comment_count = $("#commentsCount_"+id).val();
                        
                        if(comment_count != 0){
                            
                            $("#tooltip_comment"+id).show();
                            $("#tooltip_comment"+id).html('<div style="padding:10px;padding-top:20px;color: #fff;background:url(../../../images/tooltip_msg.png) no-repeat  top left;height:70px"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                            $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'video', 'itemId':id}, 
                            function(data){
                                $("#tooltip_comment"+id).html(data);
                            });
                    
                        }else{
                            $("#viewToolTip"+id).show();
                            $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 8px;">no comments</div>');
                            $(".div_tooltip").css({'margin':'20px 0 0 155px','background':"url('../../../images/small_tooltip2.png') no-repeat  top left","width":"91px"});
                        }
                    }
                    
                    
                   
                })
                $(".showViews").mouseout(function(){
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    $("#viewToolTip"+id).hide();
                    

                })
                $(".showViews").mouseout(function(){
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    $("#tooltip_comment"+id).mouseover(function(){
                        $("#tooltip_comment"+id).show();
                        $(".viewAllComments").click(function(){
                            //alert("hi");
                            $("#comments_view_full").fadeIn(1000)
                            var title = $("#1_"+id).text();
                            var noOfComments = $("#commentsCount_"+id).val();
                            
                            $("#comments_view_full").html('<div style="float: left"><iframe src="../../common/view_comments.php?title='+title+'&itemId='+id+'&module=video&noOfComments='+noOfComments+'" width="520" height="500" frameborder="0" scrolling="no" ></iframe></div><div style="cursor: pointer;float: left;margin: -10px -10px 0 0 "><img src="../../../images/close.png" id="comment_closs"/></div>');
                            $("#comment_closs").click(function(){
                                $("#comments_view_full").hide();
                            });
                        })
                    })
                    $("#tooltip_comment"+id).mouseout(function(){
                        $("#tooltip_comment"+id).hide();
                    })
                    $("#tooltip_comment"+id).hide();
                })
               
                
                
//                $(".items div").click(function(){
//                    var arr = $(this).attr('id').split("_");;
//                    var id = arr[1];
//                    
//                    var test = $("#title_"+id).text();
//                    alert(test);
//                })

               
                
                       
                
        
            });
        </script>
                                            <script type="text/javascript">
                                                    
                                                    
                                                    
                                                    jQuery.fn.center = function () {
                                                        this.css("position","absolute");
                                                        this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
                                                        this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
                                                        return this;
                                                    }

                                                    function showEdit(id) {
                                                        $("#editVideoDiv").center();
                                                        $('#editVideoDiv').fadeIn('normal');
                                                        $(".editInput").attr('disabled','disabled');
                                                        $("#editMessage").html('Loading data.. Please wait..');

                                                        $("#titleEdit").val('');
                                                        $("#durationEdit").val('');
                                                        $("#yearEdit").val('');
                                                        $("#noteEdit").val('');
                                                        $("#idEdit").val('');

                                                        $.post("../../../controller/video_all_controller.php?action=ajax_get_data", { "id": id },
                                                        function(data){
                                                            $("#titleEdit").val(data.title);
                                                            $("#durationEdit").val(data.duration);
                                                            $("#yearEdit").val(data.year);
                                                            $("#noteEdit").val(data.note);
                                                            $("#idEdit").val(data.id);
                                                            $("#editMessage").html('');
                                                            $(".editInput").attr('disabled','');

                                                        }, "json");


                                                        $('#cover').fadeIn('normal');
                                                        $('#cover').css('height',$(document).height());
                                                        $('#cover').css('width',$(document).width());
                                                    }

                                                    function hideEdit() {
                                                        $('#editVideoDiv').fadeOut('normal');
                                                        $('#cover').fadeOut('normal');
                                                    }

                                                    function saveEditedVideo() {
                                                        //$("#editMusic").attr('disabled','');

                                                        var data = $("#editVideo").serializeArray();

                                                        $("#editMessage").html('Saving.. Please wait..');
                                                        $.post("../../../controller/video_all_controller.php?action=edit_video_ajax", data,
                                                        function(data){
                                                            if (data!='ok') {
                                                                
                                                                alert("Error occured while saving! Please try again..");
                                                            } else {
                                                                var id = $("#idEdit").val();
                                                                $('#1_'+id).html($("#titleEdit").val());
                                                                var duration = Math.ceil($("#durationEdit").val());
                                                                var seconds = duration%60;
                                                                var minutes = (duration - seconds)/60;
                                                                $('#2_'+id).html(minutes + "m "+seconds+"s ");
                                                                hideEdit();
                                                            }
                                                        });
                                                    }

                                                    function youtubeGetCollections() {
                                                        var username = $('#youtubeUserName').val();
                                                        $('#youtubeButton').hide();
                                                        $('#youtubeButtonWait').show();
                                                        $('#youtubeUserName').attr('disabled','disabled');
                                                        $('#youtubeCollection').attr('disabled','disabled');
                                                        $.post('../../../controller/video_all_controller.php?action=list_youtube_videos', {'username':username}, function(data) {
                                                            $('#youtubeButton').show();
                                                            $('#youtubeButtonWait').hide();
                                                            $('#youtubeUserName').attr('disabled','');

                                                            if (data.error) {
                                                                alert("YouTube username is invalid! Please enter valid YouTube username");
                                                            } else {
                                                                $('#youtubeCollection').attr('disabled','');
                                                                $('#youtubeCollection').empty()
                                                                $('#youtubeCollection').append($("<option></option>").attr("value","").text("All"));
                                                                $('#youtubeCollection').append($("<option></option>").attr("value","Favorites").text("Favorites"));
                                                                data = data.data;
                                                                for (i=0; i<data.length; i++) {
                                                                    $('#youtubeCollection').append($("<option></option>").attr("value",data[i].id).text(data[i].title + " ("+data[i].count+")"));
                                                                }
                                                                youtubeGetVideos('');
                                                            }
                                                        },'json');
                                                    }



                                                    function youtubeGetVideos(id) {
                                                        var username = $('#youtubeUserName').val();
                                                        $('#youtubeButton').hide();
                                                        $('#listingWaiting').show();
                                                        $('#youtubeUserName').attr('disabled','disabled');
                                                        $('#youtubeCollection').attr('disabled','disabled');
                                                        $('#addAllButtonLnk').hide();

                                                        $('#list_3').empty();
                                                        $.post('../../../controller/video_all_controller.php?action=get_videos', {'id':id,'username':username}, function(data) {
                                                            $('#youtubeButton').show();
                                                            $('#listingWaiting').hide();
                                                            $('#youtubeUserName').attr('disabled','');
                                                            $('#youtubeCollection').attr('disabled','');
                                                            $('#addAllButtonLnk').show();
                                                            if (data.error) {
                                                                alert('Error occured while listing. The list might not be public or dosn not exist');
                                                                return;
                                                            }
                                                            $('#list_3').append(data.html);
                                                            listedVideos = data.videos;
                                                            window.console.log(listedVideos);
                                                            videosListed = true;
                                                        },'json');
                                                    }

                                                    function addVideo(id,vid) {
                                                        var lastElem = document.getElementById('id_'+vid);
                                                        $(lastElem).find(".add_video").html("<img src='../../../images/bigrotation2.gif' width=27 height=27 />");
                                                        $.post('../../../controller/video_all_controller.php?action=add_video_cms', listedVideos[id], function(data) {
                                                            $(data).appendTo('#list_1').hide().fadeIn(300);
                                                            $("#list_1").sortable( "refresh" );
                                                            $(lastElem).fadeOut(300);
                                                        });
                                                        return false;
                                                    }
                                                    //

                                                    function deleteVideo(id, iphone) {
                                                        var li = $('#id_'+id);
                                                        li.find('div').css('background-color', 'pink');

                                                        var parent = li.parents('ul').attr('id');
                                                        $.post('../../../controller/video_add_iphone_controller.php?id='+id+'&status=delete', function(data) {
                                                            li.fadeOut(300);
                                                            if (videosListed)
                                                                youtubeGetVideos($('#youtubeCollection').val());
                                                            if(parent=='list_2')
                                                                videoInList--;
                                                        });

                                                        return false;
                                                    }

                                                    var videoLimit = <?php echo $limit_count->video_limit ?>;
                                                    var videoInList = <?php echo count($iphone_video) ?>;
                                                    var videosListed = false;
                                            </script>
                                            </html>