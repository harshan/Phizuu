<?php
require_once "../../../config/app_key_values.php";
$menu_item = "music";
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/music_controller.php');
require_once('../../../model/music_model.php');
require_once('../../../config/error_config.php');
require_once('../../../controller/limit_files_controller.php');
require_once('../../../model/limit_files_model.php');
require_once('../../../database/Dao.php');
require_once('../../../model/soundcloud/soundcloud.php');
require_once('../../../model/soundcloud/SoundCloudMusic.php');
require_once('../../../common/oauth.php');

$limitFiles = new LimitFiles();
$limit_count = $limitFiles->getLimit($_SESSION['user_id'], 'music');

$bmusic = new Music();
$bank_music = $bmusic->listBankMusic($_SESSION['user_id']);
$count = 1;
$imusic = new Music();
$iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
$icount = 1;

$coverImage = $bmusic->getCoverImage($_SESSION['user_id']);

$soundCloud = new SoundCloudMusic();
$userInfo = $soundCloud->getUserInfo($_SESSION['user_id']);

$categories = $soundCloud->listCategories($_SESSION['user_id']);
array_push($categories, $soundCloud->getDefaultCategory($_SESSION['user_id']));

$domain = $_SERVER["SERVER_NAME"];
if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
    $callbackURL = "http://$domain/" . app_key_values::$LIVE_SERVER_URL . "images/tour_images/itunes_default.png";
} elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
    $callbackURL = "http://$domain/" . app_key_values::$TEST_SERVER_URL . "images/tour_images/itunes_default.png";
} else {
    $callbackURL = "http://$domain/" . app_key_values::$LOCALHOST_SERVER_URL . "images/tour_images/itunes_default.png";
}

//get music module item analytic data

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/music/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$musicList = array();
if (isset($allItems)) {
    foreach ($allItems->{"music"} as $value) {
        $musicList[$value->{"item_id"}] = array($value->{"like_count"}, $value->{"share_count"}, $value->{"comment_count"}, $value->{"view_count"});
    }
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Phizuu Application</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

        <style type="text/css">

            ul {
                list-style-type:none;
                padding:0px;
                margin:0px;
                width:464px;
                float: left;
                min-height: 200px;
            }

            li {
                width:460px;
                padding-left:0px;
                /*                cursor: pointer;*/
                height: 80px;
            }

            .dds_selected {
                background:#fff;/*#ffc*/
            }
            .dds_ghost {
                opacity:0.5;
            }
            .dds_move {
                background:#fff;/*cfc*/
            }
            .dds_hover {
                background:#F4F4F4;/*orange -#fc9*/
                border:0px dashed #c96;
            }

            .holder {
                border:0px dashed #333;
                background:#fff;
            }



            .highlight {
                width: 464px;
                height: 80px;
                background-image: url('../../../images/drop.png');

            }

            #cover2 {
                position: absolute;
                top: 0px;
                left: 0px;
                opacity: 0.5;
                background-color: black;
                z-index: 14;
                display: none;
            }

            #divSoundCloudConnectButton a:link, #divSoundCloudConnectButton a:visited {
                color: #043F53;
            }

            #divSoundCloudConnectButton a:hover {
                color: #08A6E0;
            }

            a.sm2_link, a.sm2_paused {
                width: 20px;
                height: 20px;
                display: block;
                background: url(../../../images/controls.png) no-repeat 0 0;
                background-position: -0px 0;
                text-indent: -9999px;
            }

            a.sm2_playing {
                background-position: -20px 0;
            }


            #editMusicDiv a:link, #editMusicDiv a:visited {
                color: #043F53;
                text-decoration: none;
            }


            #editMusicDiv a:hover {
                color: #08A6E0;
            }

            #catCont {
                display: block;
                width: 155px
            }

            .catRow {
                float: left;
                width: 155px;
                height: 30px;
            }

            .catName {
                float: left;
                margin-right: 3px;
                width: 130px;
            }

            .catDel {
                float: left;
                width: 20px;
                height: 25px;
            }

            .catRow img{
                height:15px;
                margin-top:3px;
                width:15px;
                cursor: pointer;
            }
        </style>
        <link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

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
                    <div id="div_error">

                    </div>
                    <div id="indexBodyLeft">
                        <div id="bodyLeft">
                            <div id="lightBlueHeader">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Uploaded Songs Section</div>

                            </div>
                            <div id="titleBox">
                                <div class="tahoma_14_white" id="title">Title</div>
                                <div class="tahoma_14_white" id="duration">Duration</div>
                                <div class="tahoma_14_white" id="note">Notes</div>
                            </div>

                            <ul class="connected" id="list_1">
                                <?php
                                if (sizeof($bank_music) > 0) {
                                    foreach ($bank_music as $bmusic) {
                                        include 'bank_list_new.php';
                                    }
                                    $count++;
                                }
                                ?>
                            </ul>


                        </div>
                        <div id="bodyLeft"  style="margin-bottom: 10px;">&nbsp;</div>
                        <div id="bodyLeft">
                            <div id="lightBlueHeader">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle">SoundCloud</div>

                            </div>

                            <div style="float:left; display: none;padding-top: 10px;padding-left: 0px" id="divSoundCloudConnectButton" class="tahoma_12_blue">
                                <a href="#" style="border: 0; background: transparent url('../../../images/sc-connect.png') top left no-repeat;display: block; text-indent: -9999px; width: 270px; height: 47px; margin-bottom: 10px;" id="sc-connect">Connect with SoundCloud</a>
                            </div>
                            <div style="float:left;" id="divSoundCloudConnectWaiting" >
                                <img src='../../../images/bigrotation2.gif' align="top" style="float: left;"></img>
                                <div id="divSoundCloudDetailsLoadingMsg" class="tahoma_12_blue" style="float: left; font-size: 16px; margin: 4px">Connecting SoundCloud...</div>
                            </div>

                        </div>
                        <div id="bodyLeft"  style="margin-bottom: 10px;">&nbsp;</div>

                        <div id="bodyLeft" class="uploadSectionDiv" style="<?php echo ($userInfo['package_id'] == 1) ? "display: none" : '' ?>">
                            <div id="lightBlueHeader">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Upload Section</div>

                            </div>
                        </div>
                        <div id="bodyLeft" class="uploadSectionDiv" style="<?php echo ($userInfo['package_id'] == 1) ? "display: none" : '' ?>; background-color: #e4e4e4">
                            <div class="legend" style="background-color: #788083;color: #ffffff;margin: 0;padding: 4px 0 4px 15px;font-family: Tahoma;font-size: 14px;height: 20px">Upload Queue</div>
                            <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
                                <div class="fieldset flash" id="fsUploadProgress">

<!--                            <span class="legend">Upload Queue</span>  -->

                                </div>
                                <span id="spanButtonPlaceHolder"></span>
                                <img id="btnCancel" type="image" src="../../../images/cancel.png" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 25px; width: 83px; padding: 0; border: 0; cursor: pointer" />

                                <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px">Please choose MP3 files with bit rate less than 128kbps</div>
                                <div id="errorLog" style="margin: 0; display: none">
                                    <div class="fieldset flash" style="overflow:hidden; margin: 0; padding: 5px; width: 452px">
                                        <ul id="errorLogList">
                                        </ul>
                                    </div>
                                </div>

                            </form>

                        </div>

                        <div id="bodyLeft"  style="margin-bottom: 10px;">&nbsp;</div>




                        <div id="bodyLeft">
                            <div id="lightBlueHeader">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Cover Image</div>

                            </div>

                            <div style="; background-color: #e4e4e4;padding: 40px 0 20px 0;text-align: center">
                                <img alt="Image is loading.." id="coverImage" src='' height="191" width="320" style="border: #ffffff solid 10px"></img>
                            </div>
                            <div id="coverImageButton" style="cursor: pointer;text-align: center"><img src="../../../images/change_cover_image.png"/></div>
                        </div>

                    </div>
                    <div id="indexBodyRight">
                        <div id="bodyRgt">
                            <div id="lightBlueHeader">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle">iPhone</div>

                            </div>
                            <div id="titleBox">
                                <div class="tahoma_14_white" id="title">Title</div>
                                <div class="tahoma_14_white" id="duration">Duration</div>
                                <div class="tahoma_14_white" id="note">Notes</div>
                            </div>

                            <ul class="connected" id="list_2" style="background-color: #E4E4E4;">
                                <?php
                                if (sizeof($iphone_music) > 0) {
                                    foreach ($iphone_music as $imusic) {

                                        $duration = ceil($imusic->duration);
                                        $seconds = $duration % 60;
                                        $minutes = ($duration - $seconds) / 60;
                                        ?>



                                        <li id="id_<?php echo $imusic->id; ?>">
                                            <div id="textBar" class="tahoma_12_blue">
                                                <div class="move" style="cursor: move"><img src="../../../images/move.png"/></div>
                                                <div class="items">

                                                    <div class="tahoma_12_blue title_note" style="line-height: 14px" id="title_<?php echo $imusic->id; ?>"><?php echo $imusic->title; ?></div>
                                                    <div class="tahoma_12_blue duration2" id="duration_<?php echo $imusic->id; ?>"><?php echo $minutes . "m " . $seconds . 's' ?></div>
                                                    <div class="tahoma_12_blue note_area note2new" style="line-height: 14px" id="note_<?php echo $imusic->id; ?>"><?php echo $imusic->note; ?></div>

                                                    <div class="tahoma_12_blue" id="iconBox" >
                                                        <div class="tooltip" id="edit_<?php echo $imusic->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a onclick="showEdit(<?php echo $imusic->id; ?>)"  ><img src="../../../images/file.png" border="0" /></a></div>
                                                        <div class="tooltip" id="delete_<?php echo $imusic->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"> <a href="#" onclick="return deleteTrack(<?php echo $imusic->id; ?>);"><img src="../../../images/cross.png" border="0" /></a></div>

                                                    </div>
                                                    <div id="div_tooltip_common_<?php echo $imusic->id; ?>" class="div_tooltip_common">Edit</div>
                                                </div>

                                                <?php
                                                if (array_key_exists($imusic->id, $musicList)) {
                                                    $itemExist = TRUE;
                                                }
                                                ?>
                                                <div style="float: left;padding-left: 4px;width: 300px;vertical-align: bottom;padding-top:5px" id="div_source">
                                                    <span class="showViews" id="showViews_<?php echo $imusic->id; ?>"> <img src="../../../images/icon_views.png" /><span style="padding: 0px 5px 2px 5px"><?php
                                        if (isset($itemExist) && $itemExist == TRUE):echo $musicList[$imusic->id][3];
                                        else: echo '0';
                                        endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showLikes_<?php echo $imusic->id; ?>"><img src="../../../images/icon_like.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $musicList[$imusic->id][0];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showShare_<?php echo $imusic->id; ?>"><img src="../../../images/icon_share.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $musicList[$imusic->id][1];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span>
                                                    <span class="showViews" id="showComments_<?php echo $imusic->id; ?>"><img src="../../../images/icon_comment.png"/><span style="padding: 0 5px 2px 5px"><?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $musicList[$imusic->id][2];
                                                    else: echo '0';
                                                    endif;
                                                ?></span></span><input type="hidden" id="commentsCount_<?php echo $imusic->id; ?>" value="<?php
                                                    if (isset($itemExist) && $itemExist == TRUE):echo $musicList[$imusic->id][2];
                                                    else: echo '0';
                                                    endif;
                                                ?>"/>

                                                </div>
                                                <div id="viewToolTip<?php echo $imusic->id ?>" class="div_tooltip"></div>
                                                <div id="tooltip_comment<?php echo $imusic->id ?>" class="div_tooltip_comment"></div>

                                            </div>
                                        </li>
                                        <?php
                                        $icount++;
                                        $itemExist = FALSE;
                                    }
                                }
                                ?>
                            </ul>

                        </div>
                    </div><br class="clear"/>
                </div>
                <div id="buttonContainer">&nbsp;</div>
            </div>

            <br class="clear"/><br class="clear"/>
        </div>

        <!--	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>-->
        <br class="clear"/>
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>

        <div id="editMusicDiv"  style="background-color: #FFFFFF; padding: 8px;display: none" >
            <form action="" id="editMusic">
                <input type="hidden" value="" name="imageURI" id='imageURIEdit'/>
                <input type="hidden" value="" name="id" id='idEdit'/>
                <div>
                    <table width="759" border="0" cellspacing="4" cellpadding="0" class="tahoma_12_blue">
                        <tr>
                            <td width="74" valign="top">Title:</td>
                            <td width="331" valign="top"><input type="text" value="" id="titleEdit" name="title" style="width: 300px;" class="textfield editInput"/></td>
                            <td width="320" valign="top" >Music Image (Click Image to Edit):</td>
                        </tr>
                        <tr>
                            <td valign="top">Duration:</td>
                            <td valign="top"><input type="text" value="" id="durationEdit" name="duration" style="width: 300px;" class="textfield editInput"/></td>
                            <td width="320" rowspan="7" valign="top">
                                <div  style="border: 1px solid #CCCCCC; height:320px; width:320px" align="center">
                                    <img src="" alt="" id="musicCoverEdit"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">Category:</td>
                            <td valign="top">
                                <select id="categoryEdit" name="categoryEdit" style="width: 192px;" class="textfield editInput" onchange="return categoryChanged(this.value)">
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                    <?php } ?>
                                </select>
                                <a href="#" onclick="javascript: return addCategory()">Add New</a> |
                                <a href="#" onclick="javascript: return manageCategories()">Manage</a>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top">iTuneURI:</td>
                            <td valign="top"><input type="text" value="" id="iTuneURIEdit" name="iTunesURI" style="width: 200px;" class="textfield editInput"/> <a href="#" id="ituneLink" target="_blank"><img border='0' src="../../../images/NewWindowIcon.png"  /></a><img src="../../../images/find_itune.png" onclick="javascript: return findItunes()" width="90" height="25" style="cursor: pointer"/></td>
                        </tr>
                        <tr>
                            <td valign="top">AndroidURL:</td>
                            <td valign="top"><input type="text" value="" id="androidURIEdit" name="androidURI" style="width: 285px;" class="textfield editInput" /> <a href="#" id="androidLink" target="_blank"><img border='0' src="../../../images/NewWindowIcon.png"  /></a></td>
                        </tr>
                        <tr>
                            <td valign="top">Album:</td>
                            <td valign="top"><input type="text" value="" id="albumEdit" name="album" style="width: 300px;" class="textfield editInput"/></td>
                        </tr>
                        <tr>
                            <td valign="top">Year:</td>
                            <td valign="top"><input type="text" value="" id="yearEdit" name="year" style="width: 300px;" class="textfield editInput"/></td>
                        </tr>
                        <tr>
                            <td height="150" valign="top">Note:</td>
                            <td valign="top"><textarea type="text" value="" id="noteEdit" name="note" style="width: 300px; height: 120px" class="textfield editInput"></textarea></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><div id="editMessage"></div></td>
                            <td width="320" align="left">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>

        <div id="addCat" title="Add Music Category" style="display: none">
            Category Name: <input type="text" id="newCatName" maxlength="15" class="textfield editInput" style="width:138px"/>
        </div>
        <div id="manCat" title="Categories">
            <div id="catCont">
            </div>
        </div>
        <div id="findiTunes" title="Find iTunes" style="display: none">
            <div> <input type="text" id="txtiTunes"/><input type="button" value="Find" id="btnFindiTunes" style="background: #2a2b2b;color:#fff"/></div>
            <div>Note: Enter your keyword without spaces.</div>
            <div style="width: 600px;">
                
                <div id="SearchResult">
                   
                </div>
            </div>
        </div>
        <script type="text/javascript" src="../../../js/swf_up/swfupload.js"></script>
        <script type="text/javascript" src="../../../js/swf_up/swfupload.queue.js"></script>
        <script type="text/javascript" src="../../../js/swf_up/fileprogress.js"></script>
        <script type="text/javascript" src="../../../js/swf_up/handlers.js"></script>

        <script type="text/javascript" src="../../../js/sc-connect.js"></script>

        <script type="text/javascript">
            window.onload = function() {

                var settings = {
                    flash_url : "../../../common/swfupload.swf",
                    upload_url: "../../../controller/music_uploader.php",
                    post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
                    file_size_limit : "50 MB",
                    file_types : "*.mp3",
                    file_types_description : "MP3 (Maximum BitRate 128kbps)",
                    file_upload_limit : 100,
                    file_queue_limit : 10,
                    custom_settings : {
                        progressTarget : "fsUploadProgress",
                        cancelButtonId : "btnCancel"
                    },
                    debug: false,
                    prevent_swf_caching: false,

                    // Button settings
                    button_image_url: "../../../images/upload_4btn.png",
                    button_width: "95",
                    button_height: "25",
                    button_placeholder_id: "spanButtonPlaceHolder",
                    button_text: '<span class="theFont"></span>',
                    button_text_style: ".theFont { font-size: 16; }",
                    button_text_left_padding: 12,
                    button_text_top_padding: 3,


                    // The event handler functions are defined in handlers.js
                    file_queued_handler : fileQueued,
                    file_queue_error_handler : fileQueueError,
                    file_dialog_complete_handler : fileDialogComplete,
                    upload_start_handler : uploadStart,
                    upload_progress_handler : uploadProgress,
                    upload_error_handler : uploadError,
                    upload_success_handler : uploadSuccess,
                    upload_complete_handler : uploadComplete,
                    queue_complete_handler : queueComplete	// Queue plugin event
                };

                swfu = new SWFUpload(settings);


            }



        </script>


        <script type="text/JavaScript">
            var limit=<?php echo $limit_count->music_limit; ?>;
            var bankMusicCount=<?php echo count($iphone_music); ?>;
        </script>


        <!--multi drag-->
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>

        <script type="text/javascript">
    
            function findItunes(){
                $('#findiTunes').dialog('open');
               
                 
            }
            function selectItune(iTune){
                $("#iTuneURIEdit").val(iTune);
                $("#findiTunes").dialog('close');
            }
            
            $(document).ready(function(){
              
             
                $("#btnFindiTunes").click(function(){
                    var keyWord = $("#txtiTunes").val();
                    $("#SearchResult").html('<div style="padding:30px;padding-top:20px;color: #000;height:70px"><img src="../../../images/bigrotation2.gif"/>&nbsp;Loading...</div>');
                    $.post("../../../controller/music_all_controller.php?action=find_iTunes",{'keyWord':keyWord},
                    function(data){
                        $("#SearchResult").html(data);
                    });
                })
                
                $("#findiTunes").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 'auto',
                    height: '300',
                    minHeight: 0,
                    resizable: true,
                    buttons: {
                        "CLOSE": function() {
                            $(this).dialog('close');
                        }
//                        "SELECT": function(){
//                            
//                            $(this).dialog('close');
//                        }
                    }
                });
                $(".tooltip").mouseover(function(){
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    if("edit"==arr[0]){
                        $("#edit_"+id).mouseover(function(){
                    
                            $("#div_tooltip_common_"+id).text("Edit");
                            $("#div_tooltip_common_"+id).css({
                                "margin":"40px 0 0 363px",
                                "padding":"10px 0 0 18px"
                            });
                            $("#div_tooltip_common_"+id).show();
                            $("#edit_"+id).mouseout(function(){
                                $("#div_tooltip_common_"+id).hide();
                            });
                    
                        });
                    }
                    else if("delete"==arr[0]){
                        $("#delete_"+id).mouseover(function(){
                            $("#div_tooltip_common_"+id).text("Delete");
                            $("#div_tooltip_common_"+id).show();
                            $("#div_tooltip_common_"+id).css({
                                "margin":"40px 0 0 408px",
                                "padding":"10px 0 0 12px"
                            });
                            $("#delete_"+id).mouseout(function(){
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
                        $(".div_tooltip").css({"margin":"75px 0 0 30px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});
                       
                    }
                    else if('showLikes'==arr[0]){
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 17px">Likes</div>');
                        $(".div_tooltip").css({"margin":"75px 0 0 70px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                    }
                    else if('showShare'==arr[0]){
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 12px">Shares</div>');
                        $(".div_tooltip").css({"margin":"75px 0 0 115px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                    }
                    else if('showComments'==arr[0]){
                        var comment_count = $("#commentsCount_"+id).val();
                        if(comment_count != 0){
                            $("#tooltip_comment"+id).show();
                            $("#tooltip_comment"+id).html('<div style="padding:30px;padding-top:20px;color: #fff;background:url(../../../images/tooltip_msg.png) no-repeat  top left;height:70px"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                            $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'music', 'itemId':id}, 
                            function(data){
                                $("#tooltip_comment"+id).html(data);
                            });
                    
                        }else{
                            $("#viewToolTip"+id).show();
                            $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 8px;">no comments</div>');
                            $(".div_tooltip").css({'margin':'75px 0 0 155px','background':"url('../../../images/small_tooltip2.png') no-repeat  top left","width":"91px"});
                        }
                    }
                    
                    
                   
                })
                $(".showViews").mouseout(function(){
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                    $("#viewToolTip"+id).hide();
                    

                })
                $(".showViews").mouseout(function(){
                    var arr = $(this).attr('id').split("_");
                    var id = arr[1];
                    $("#tooltip_comment"+id).mouseover(function(){
                        //alert("hi");
                        $("#tooltip_comment"+id).show();
                        $(".viewAllComments").click(function(){
                            
                            $("#comments_view_full").fadeIn(1000)
                            var title = $("#title_"+id).text();
                            var noOfComments = $("#commentsCount_"+id).val();
                            $("#comments_view_full").html('<div style="float: left"><iframe src="../../common/view_comments.php?title='+title+'&itemId='+id+'&module=music&noOfComments='+noOfComments+'" width="520" height="500" frameborder="0" scrolling="no" ></iframe></div><div style="cursor: pointer;float: left;margin: -10px -10px 0 0 "><img src="../../../images/close.png" id="comment_closs"/></div>');
                            
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
               
                
                
                $(".items div").click(function(){
                    var arr = $(this).attr('id').split("_");;
                    var id = arr[1];
                    
                   
                })

               
                
                       
                
        
            });
        </script>
        <script type="text/javascript">
            
            
            $(function() {
                $("#list_1, #list_2").sortable({
                    connectWith: '.connected',
                    placeholder: 'highlight',
                    revert: true
                }).disableSelection();

                $('#coverImage').imagechooser({
                    method:'upload',
                    callback: function(image, thumb) {
                        $('#coverImage').imagechooserLoadImage(image);

                        $.post("../../../controller/music_all_controller.php?action=update_cover", {'url': image},
                        function(data){
                            if(data!="ok"){
                                alert("Error occured while changing the image!");
                            }  
                        });
                    },
                    image_size:{width:320, height: 191},
                    container_size:{width:320, height: 191},
                    create_thumb: false,
                    image_catagory_name: 'music_cover_images',
                    image_base_name:'<?php echo $userInfo['app_id'] ?>',
                    empty_image: '../../../images/icon_blank.png',
                    hint_text: 'Click here to edit'
                });
                
                $('#coverImageButton').imagechooser({
                    method:'upload',
                    callback: function(image, thumb) {
                        $('#coverImage').imagechooserLoadImage(image);

                        $.post("../../../controller/music_all_controller.php?action=update_cover", {'url': image},
                        function(data){
                            if(data!="ok"){
                                alert("Error occured while changing the image!");
                            }  
                        });
                    },
                    image_size:{width:320, height: 191},
                    container_size:{width:320, height: 191},
                    create_thumb: false,
                    image_catagory_name: 'music_cover_images',
                    image_base_name:'<?php echo $userInfo['app_id'] ?>',
                    empty_image: '../../../images/icon_blank.png',
                    hint_text: 'Click here to edit'
                });

                $('#musicCoverEdit').imagechooser({
                    method:'ask',
                    callback: function(image, thumb) {
                        $('#musicCoverEdit').imagechooserLoadImage(image);
                        $("#imageURIEdit").val(image);
                    },
                    image_size:{width:320, height: 320},
                    container_size:{width:320, height: 320},
                    create_thumb: false,
                    image_catagory_name: 'music_images',
                    image_base_name:'<?php echo $userInfo['app_id'] ?>',
                    empty_image: '../../../images/icon_blank.png',
                    hint_text: 'Click here to edit'
                });

                $('.edit').editable('../../../controller/music_all_controller.php?action=edit',{
                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...',
                    callback: function() {
                        $.jGrowl("Successfully saved track information");
                    }
                });
                $("#list_2").bind('sortreceive', function(event, ui) {
                    //window.console.log(event);
                    if (bankMusicCount>=limit) {
                        $("#list_1").sortable('cancel');
                        alert('You have reached the maximum number of music of '+limit+'.');
                    } else {
                        bankMusicCount++;
                    }

                });

                $("#list_1").bind('sortreceive', function(event, ui) {
                    bankMusicCount--;
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
                    $.post('../../../controller/music_all_controller.php?action=order&'+ordered, function(data) {
                        $("#list_1, #list_2").sortable( 'enable' );
                        $("#list_1, #list_2").css('cursor', 'default');
                    });

                });

                $("#editMusicDiv").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 'auto',
                    height: 'auto',
                    resizable: false,
                    show: "blind",
                    hide: "blind",
                    buttons: {
                        "Cancel": function() {
                            $(this).dialog('close');
                        },
                        "Save": function() {
                            saveEditedSong();
                        }

                    }
                });

                $("#addCat").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 'auto',
                    height: 'auto',
                    minHeight: 0,
                    resizable: false,
                    buttons: {
                        "Cancel": function() {
                            $(this).dialog('close');
                        },
                        "Save": function() {
                            var name = $('#newCatName').val();
                            var addButton = $("#addCat").parents('.ui-dialog').find('button:eq(1)');
                            
                            var catName = $("#newCatName").val().trim();
                            if(catName == ''){
                                alert("Category Name can't be blank!");
                                return false;
                            }
                            changeDialogButtonStatus(true,addButton, 'Adding..');
                            
                            
                            $.post("../../../controller/music_all_controller.php?action=add_category",{'name':name},
                            function(data){
                                changeDialogButtonStatus(false,addButton,'Add');
                                $("#addCat").dialog('close');
                                if(data.error) {
                                    alert(data.msg);
                                    $.jGrowl(data.msg);
                                } else {
                                    $.jGrowl('Successfully added the category "'+name+"'");
                                    //$('#categoryEdit')
                                    $('<option></option>').html(name).val(data.id).appendTo('#categoryEdit');
                                    $('#categoryEdit').val(name);
                                }
                            },'json');
                        }

                    }
                });

                $("#manCat").dialog({
                    modal: true,
                    autoOpen: false,
                    width: 'auto',
                    height: 'auto',
                    minHeight: 0,
                    resizable: false,
                    buttons: {
                        "OK": function() {
                            $(this).dialog('close');
                        }
                    }
                });
                
              

                $('.note_area').tooltip({
                    delay: 0,
                    showURL: true,
                    showBody: " - ", 
                    track: true,
                    fade: 250,
                    opacity: 0.85,
                    bodyHandler: function() {
                   
                        if (this.innerHTML.substring(0,5)!='<form' && this.innerHTML != ''){
                            return this.innerHTML.replace(/\n/g,"<br/>");
                        }
                    }
                });

                initLogin(false);

                $('#coverImage').imagechooserLoadImage("<?php echo $coverImage == '' ? $callbackURL : $coverImage; ?>");
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
            var tracksListed = false;
            var scConnectHTML = "";

            var changeDialogButtonStatus = function(disabled, button, text) {
                if(text)
                    button.html(text);

                if (disabled) {
                    button.attr('disabled', true);
                    button.removeClass('ui-state-focus');
                    button.removeClass('ui-state-hover');
                    button.addClass('ui-state-disabled');
                } else {
                    button.removeClass('ui-state-disabled');
                    button.attr('disabled', false);
                }
            }

            function addCategory() {
                $('#newCatName').val('');
                $('#addCat').dialog('open');

                return false;
            }

            function manageCategories() {
                $('#catCont').html('');
                $('#categoryEdit option').each(function() {
                    var id= $(this).val();
        
                    var catRow = $('<div class="catRow"><div class="catName">Category</div><div class="catDel"><img src="../../../images/delete_conference_icon.png"/></div></div>');

                    catRow.find('.catName').html($(this).text()).editable('../../../controller/music_all_controller.php?action=edit_category',{
                        indicator : 'Saving...',
                        tooltip   : 'Click to edit...',
                        submitdata : {'cat_id': id},
                        callback: function(value) {
                            $('#categoryEdit option[value="'+id+'"]').text(value);
                            $.jGrowl("Successfully edited category name!");
                        }
                    }).attr('id','catname_'+id);

        
                    catRow.find('.catDel').click(function(data){
                        if (id=='0') {
                            $.jGrowl('Error! Default category cannot be deleted!');
                            return;
                        }
                        var row = $(this).parents('.catRow');
                        row.find('.catDel img').attr('src','../../../images/bigrotation2.gif');
                        $.post("../../../controller/music_all_controller.php?action=delete_category",{'id':id},function(data){
                            if(data.error == true) {
                                alert('Error occured while deleting category!')
                            } else {
                                row.remove();
                                $('#categoryEdit option[value="'+id+'"]').remove();
                                $.jGrowl('Category deleted successfully!');
                            }
                        },'json');
                    });

                    $('#catCont').append(catRow);
                });
    
                $('#manCat').dialog('open');

                return false;
            }

            function initLogin(logout) {
                var logoutText = 'false';
                if (logout) {
                    logoutText = 'true';
                }

                $.post("../../../controller/music_all_controller.php?action=get_sound_cloud_request_token&logout=" + logoutText, "",
                function(data){
                    if (logout) {
                        showSoundCloudWaitEnd(scConnectHTML);
                    } else {
                        scConnectHTML = $("#divSoundCloudConnectButton").html();
                    }

                    if (data.auth == 'no') {
                        SC.Connect.myOptions = {
                            'request_token_endpoint': data.url,
                            'access_token_endpoint': 'http://api.soundcloud.com/oauth/access_token',
                            'callback': function(query_obj){
                                showSoundCloudWait("Getting Authorization from SoundCloud..");

                                $.post("../../../controller/music_all_controller.php?action=get_access_token",query_obj,
                                function(data){
                                    showSoundCloudWaitEnd(data);
                                    $(".uploadSectionDiv").show();
                                });
                            }
                        };

                        var elem = document.getElementById('sc-connect');
                        SC.Connect.prepareButton(elem,SC.Connect.myOptions);

                        showSoundCloudWaitEnd();
                    } else {
                        $(".uploadSectionDiv").show();
                        showSoundCloudWaitEnd(data.userData);
                    }
                },'json');
            }

            function scLogout() {
                showSoundCloudWait("Logging out, please wait..");
                initLogin(true);

<?php if ($userInfo['package_id'] == 1) { ?>
            $(".uploadSectionDiv").hide();
<?php } ?>

        return false;
    }

    function categoryChanged(value) {
    }

    function deleteTrack(id) {
        $("#id_" + id).children().css("background-color",'#FFAEC0');

        var parent = $("#id_" + id).parents('ul').attr('id');
        $.post("../../../controller/music_all_controller.php?action=delete_track",{'track_id':id},
        function(data){
            if (data=='ok') {
                this.id = id;
                $("#id_" + this.id).slideUp('slow').remove();
                $.jGrowl("Successfully deleted the track!");
                if (tracksListed)
                    soundcloudListTracks('Refreshing Track List..');

                if (parent=='list_2') {
                    bankMusicCount--;
                }

            } else {
                alert("Failed to delete the track!");
            }
        }
    );

        return false;
    }

    function showSoundCloudWait(msg) {
        $("#divSoundCloudConnectWaiting").show();
        $("#divSoundCloudConnectButton").hide();
        $("#divSoundCloudDetailsLoadingMsg").html(msg);
    }

    function showSoundCloudWaitEnd(data) {
        $("#divSoundCloudConnectWaiting").hide();
        $("#divSoundCloudConnectButton").show();
        if (data)
            $("#divSoundCloudConnectButton").html(data);
    }
    
    function showEdit(id) {
        
        
                
        $("#editMusicDiv").dialog('open');

        $(".editInput").attr('disabled','disabled');
        $("#editMessage").html('Loading data.. Please wait..');

        $("#titleEdit").val('');
        $("#durationEdit").val('');
        $("#iTuneURIEdit").val('');
        $("#androidURIEdit").val('');
        $("#ituneLink").attr('href','');
        $("#albumEdit").val('');
        $("#yearEdit").val('');
        $("#noteEdit").val('');
        $("#imageURIEdit").val('');
        $("#idEdit").val('');
        $("#musicCoverEdit").attr('src','../../../images/bigrotation2.gif');
        $("#musicCoverEdit").attr('height',32);
        $("#musicCoverEdit").attr('width',32);
        $('#musicCoverEdit').imagechooserChangeBaseName(id);
        $("#categoryEdit").val(0);

        $.post("../../../controller/music_all_controller.php?action=ajax_get_data", { "id": id },
        function(data){
            $("#titleEdit").val(data.title);
            $("#durationEdit").val(data.duration);
            $("#iTuneURIEdit").val(data.itunes_uri);
            $("#androidURIEdit").val(data.android_url);
            
            if(data.itunes_uri==null || data.itunes_uri==null){
                $("#ituneLink").removeAttr('href');
            }else{
                $("#ituneLink").attr('href',data.itunes_uri);
            }
            
            if(data.android_url==null || data.android_url==""){
                $("#androidLink").removeAttr('href');
            }else{
                $("#androidLink").attr('href',data.android_url);
            }
            $("#albumEdit").val(data.album);
            $("#yearEdit").val(data.year);
            $("#noteEdit").val(data.note);
            $("#imageURIEdit").val(data.image_uri);
            $("#idEdit").val(data.id);
            $("#categoryEdit").val(data.category_id);
            
            if(data.image_uri == '' || data.image_uri==null) {
                $('#musicCoverEdit').imagechooserLoadImage('../../../images/default-cover.png');
            } else {
                $('#musicCoverEdit').imagechooserLoadImage(data.image_uri);
            }
            
            $("#editMessage").html('');
            $(".editInput").attr('disabled','');
            
            
            
        }, "json");
    }
    $("#iTuneURIEdit").change(function(){
        if($("#iTuneURIEdit").val().trim()=="" || $("#iTuneURIEdit").val().trim()==null){
            $("#ituneLink").removeAttr('href');
        }else{
            $("#ituneLink").attr('href',$("#iTuneURIEdit").val());
        }
    });
    $("#androidURIEdit").change(function(){
        if($("#androidURIEdit").val().trim()=="" || $("#androidURIEdit").val().trim()==null){
            $("#androidLink").removeAttr('href');
        }else{
            $("#androidLink").attr('href',$("#androidURIEdit").val());
        }
    });
    function soundcloudListTracks(msg) {
        tracksListed = true;
        $('#trackListDiv').html("<img src='../../../images/bigrotation2.gif' align='top' style='float: left;'></img><div id='divSoundCloudDetailsLoadingMsg' class='tahoma_12_blue' style='float: left; font-size: 16px; margin: 4px'>"+msg+"</div>");

        $.post("../../../controller/music_all_controller.php?action=get_soundcloud_tracks",
        function(data){
            //alert(data);
            $('#trackListDiv').html(data);
        });

        return false;
    }

    function soundcloudAddTrack(trackId, img) {
        img.src = '../../../images/bigrotation2.gif';
        img.onclick = function() {};
        var callback = function(data){
            this.prevId = trackId;
            $('#list_1').append(data).slideDown();
            //alert(this.prevId);
            $('#id_' + this.prevId).slideUp().remove();
        };
   
    
        $.post("../../../controller/music_all_controller.php?action=soundcloud_add_track",{'track_id':trackId},callback);
    }

    function saveEditedSong() {
        //$("#editMusic").attr('disabled','');
        var id = $("#idEdit").val();
        if($("#titleEdit").val().trim()==""){
            alert("Title can't be blank!")
            return false;
        }
        $('#title_'+id).html($("#titleEdit").val());

        var duration = Math.ceil($("#durationEdit").val());
        var seconds = duration%60;
        var minutes = (duration - seconds)/60;
        $('#duration_'+id).html(minutes + "m "+seconds+"s ");
    
        $('#note_'+id).html($("#noteEdit").val());
        var iTuneURI = $("#iTuneURIEdit").val();
        var androidURI = $("#androidURIEdit").val();
        var imageURI = $("#imageURIEdit").val();
        $("#iTuneURIEdit").val(escape(iTuneURI));
        $("#androidURIEdit").val(escape(androidURI));
        $("#imageURIEdit").val(escape(imageURI));
        var data = $("#editMusic").serializeArray();
        $("#iTuneURIEdit").val(iTuneURI);
        $("#imageURIEdit").val(imageURI);
        $("#androidURIEdit").val(androidURI);
        
        $("#editMessage").html('Saving.. Please wait..');
        $.post("../../../controller/music_all_controller.php?action=edit_music", data,
        function(data){
            //alert(data);
            if (data!='ok') {
                if (data=='iTunesURLError') {
                    alert('iTunes URL is invalid! Could not save details!')
                } else if (data=='androidURLError') {
                    alert('Android URL is invalid! Could not save details!')
                } else {
                    //alert (data);
                    alert("Error occured while saving! Please try again..");
                }
            } else {
                $.jGrowl("Successfully saved track information!");
                $("#editMusicDiv").dialog('close');
            }
        });
    }

    function updateLinkURI(){
        $("#ituneLink").attr('href',$("#iTuneURIEdit").val());
        $("#androidLink").attr('href',$("#androidURIEdit").val());
    }


        </script>
        <div id="cover"></div>
        <div id="cover2"></div>
    </body>
</html>