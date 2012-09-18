<?php
$menu_item="music";

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

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');


$bmusic= new Music();
$bank_music = $bmusic->listBankMusic($_SESSION['user_id']);
$count=1;
$imusic= new Music();
$iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
$icount=1;

$coverImage = $bmusic->getCoverImage($_SESSION['user_id']);

$soundCloud = new SoundCloudMusic();
$userInfo = $soundCloud->getUserInfo($_SESSION['user_id']);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
<link href="../../../common/tooltip/bubble.css" rel="stylesheet" type="text/css" media="all" />
<style type="text/css">

ul {
    list-style-type:none;
    padding:0px;
    margin:0px;
    width:462px;
    float: left;
     min-height: 200px;
}

li {
    width:460px;
    padding-left:0px;
    cursor: pointer;
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

</style>
<link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
</head>


<body>

<div id="mainWideDiv">
    <div id="header">
        <div style="width: 800px;height: 90px;margin: auto">
                        <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logoInner.png" width="350" height="35" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
  <div id="middleDiv2">
                    
    <div id="body">
                        <br/>

                         <div class="wizardTitle" >
                            
                            <div class="middle" style="width: 910px;height: 25px;padding-top: 5px">Please click Upload to add music to your music module</div>
                           
                        </div>

    <div class="coda_bubble wizardSecondTitle" style="width: 900px">
        <div>
            <p><div style="float:left">Please upload your tracks<?php if($userInfo['package_id']==1) echo " from SoundCloud. After login to your SoundCloud account you can upload the tracks to SoundCloud or you can directly add tracks from SoundCloud"; ?>. You can re-order them by dragging and dropping them into place.</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <?php if($userInfo['package_id']==1) { ?>
                <div style="height: 20px">Quick Video Tutorial (2:02 mins) - to hide move mouse away</div>
                <object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/FlsRtJi3s_A?fs=1&amp;hl=en_US&amp;rel=0&amp;color1=0x006699&amp;color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/FlsRtJi3s_A?fs=1&amp;hl=en_US&amp;rel=0&amp;color1=0x006699&amp;color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
               <?php } else { ?>
                <div style="height: 20px">Quick Video Tutorial (1:37 mins) - to hide move mouse away</div>
                <object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/Fw66KudCV4c?fs=1&amp;hl=en_US&amp;rel=0&amp;color1=0x006699&amp;color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/Fw66KudCV4c?fs=1&amp;hl=en_US&amp;rel=0&amp;color1=0x006699&amp;color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
               <?php } ?>
           </div>
        </div>

    </div>
      <div id="indexBodyLeft">

        <div id="bodyLeft">
          <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">SoundCloud</div>
         
          </div>

             <div style="padding-top: 40px; display: none" id="divSoundCloudConnectButton" class="tahoma_12_blue">
                 <a href="#" style="border: 0; background: transparent url('../../../images/sc-connect.png') top left no-repeat;display: block; text-indent: -9999px; width: 270px; height: 47px; margin-bottom: 10px;margin: auto;" id="sc-connect">Connect with SoundCloud</a>
             </div>
            <div style="float:left;" id="divSoundCloudConnectWaiting" >
                 <img src='../../../images/bigrotation2.gif' align="top" style="float: left;"></img>
                 <div id="divSoundCloudDetailsLoadingMsg" class="tahoma_12_blue" style="float: left; font-size: 16px; margin: 4px">Connecting SoundCloud...</div>
             </div>

        </div>
          <div id="bodyLeft" style="margin-bottom: 10px" >&nbsp;</div>

        <div id="bodyLeft" class="uploadSectionDiv" style="<?php echo ($userInfo['package_id']==1)?"display: none":'' ?>">
          <div id="lightBlueHeader">
          
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Upload Section</div>
         
          </div>
        </div>
        <div id="bodyLeft" class="uploadSectionDiv" style="<?php echo ($userInfo['package_id']==1)?"display: none":'' ?>">
                    <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
                        <div class="fieldset flash" id="fsUploadProgress">
                            <span class="legend">Upload Queue</span>

                        </div>
                        <span id="spanButtonPlaceHolder"></span>
                        <img id="btnCancel" type="image" src="../../../images/cancel.png" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 25px; width: 79px; padding: 0; border: 0; cursor: pointer" />

                        <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px">Please choose MP3 files with bit rate less than 128kbps</div>
                        <div id="errorLog" style="margin: 0; display: none">
                            <div class="fieldset flash" style="overflow:hidden; margin: 0; padding: 5px; width: 452px">
                                <ul id="errorLogList">
                                </ul>
                            </div>
                        </div>

                    </form>

        </div>

<div id="bodyLeft"  style="margin-bottom: 10px">&nbsp;</div>




         <div id="bodyLeft">
          <div id="lightBlueHeader">
      
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Cover Image</div>
    
          </div>

             <div style="text-align: center;padding: 60px 0 60px 0;">
                 <img alt="Image is loading.." id="coverImage" src='' height="191" width="320" style="border: 10px solid #ffffff" ></img>
             </div>

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

        <ul class="connected" id="list_1">
         <?php

                           if(sizeof($iphone_music) >0) {
                               foreach($iphone_music as $bmusic) {
                                 include '../../../view/user/music/bank_list_new.php';
                               }
                               $count++;
                           }
          ?>
      </ul>

        </div>
      </div>
    </div>
          <div id="bodyLeftWizard" >
                            <div class="nextButton" style="width: 927px; border: 0">
                                <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="35" />
                                <img id="buttonNext" class="<?php echo count($iphone_music)==0?'':'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo count($iphone_music)==0?'btn_next_disabled.png':'btn_next.png' ?>" width="89" height="35" />

                            </div>
                        </div>
    <div id="buttonContainer">&nbsp;</div>
  </div>
</div>

<!--	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>-->
<br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>


          <div id="editMusicDiv"  style="background-color: #FFFFFF; border: #043f53 2px solid; padding: 8px; z-index: 15; position:absolute; display: none">
                  <form action="" id="editMusic">
        	<input type="hidden" value="" name="imageURI" id='imageURIEdit'>
            <input type="hidden" value="" name="id" id='idEdit'>
            <div  >
              <table width="759" border="0" cellspacing="4" cellpadding="0" class="tahoma_12_blue">
                <tr>
                  <td width="74" valign="top">Title:</td>
                  <td width="331" valign="top"><input type="text" value="" id="titleEdit" name="title" style="width: 300px;" class="textfield editInput"/></td>
                  <td width="320" valign="top" >Music Image (Click Image to Edit):</td>
                </tr>
                <tr>
                  <td valign="top">Duration:</td>
                  <td valign="top"><input type="text" value="" id="durationEdit" name="duration" style="width: 300px;" class="textfield editInput"/></td>
                  <td width="320" rowspan="5" valign="top">
                      <div  style="border: 1px solid #CCCCCC; height:320px; width:320px" align="center">
                          <img src="" alt="" id="musicCoverEdit"/>
                      </div>
                  </td>
                </tr>
                <tr>
                  <td valign="top">iTuneURI:</td>
                  <td valign="top"><input type="text" value="" id="iTuneURIEdit" name="iTunesURI" style="width: 285px;" class="textfield editInput" onkeyup="javascript: updateLinkURI()"/> <a href="#" id="ituneLink" target="_blank"><img border='0' src="../../../images/NewWindowIcon.png"  /></a></td>
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
                  <td height="184" valign="top">Note:</td>
                  <td valign="top"><textarea type="text" value="" id="noteEdit" name="note" style="width: 300px; height: 177px" class="textfield editInput"></textarea></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><div id="editMessage"></div></td>
                  <td width="320" align="left"><div align="right"><img width="89" height="35" border="0" align="top" src="../../../images/save2.png" style="cursor:pointer" onclick="javascript: saveEditedSong();"/> <img style="cursor:pointer" onclick="javascript: hideEdit();" width="89" height="35" border="0" align="top" src="../../../images/cancel.png"/></div></td>
                </tr>
              </table>
            </div>
</form>
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
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>",'app_wizard':'true'},
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
        var limit=<?php echo $limit_count ->music_limit;?>;
</script>


<!--multi drag-->
<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>
<script type="text/javascript" src="../../../common/tooltip/jquery.codabubble.js"></script>
<script type="text/javascript" src="../../../common/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="../../../common/jquery.imagechooser.js"></script>
<script type="text/JavaScript">
        var limit=<?php echo $limit_count ->music_limit;?>;
</script>

<script type="text/javascript">
        jQuery.fn.center = function () {
            this.css("position","absolute");
            this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
            this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
            return this;
        }


        $(function() {
            
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
            
            $("#list_1").sortable({
                placeholder: 'highlight'
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

            $('#musicCoverEdit').imagechooser({
                method:'upload',
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
                tooltip   : 'Click to edit...'
            });

            $("#list_1").bind('sortupdate', function(event, ui) {
                $("#list_1").sortable( 'disable' );
                $("#list_1").css('cursor', 'wait');

                var ordered = $('#list_1').sortable('serialize');;
                //alert(ordered);
                $.post('../../../controller/music_all_controller.php?action=order_wizard&'+ordered, function(data) {
                    //alert(data);
                    $("#list_1").sortable( 'enable' );
                    $("#list_1").css('cursor', 'move');
                });

            });

         $("#noContent").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });

        $("#skipWarning").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Cancel: function() {
                    $(this).dialog('close');
                },
                Accept: function() {
                    window.location = "AppWizardControllerNew.php?action=music_module_skip";
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

                $('#musicCoverEdit').tooltip({
                delay: 0,
                showURL: false,
                bodyHandler: function() {

                            return "Click to choose an image from the image bank";
                    }
                });

                initLogin(false);

           opts = {
              distances : [-153],
              leftShifts : [400],
              bubbleTimes : [400],
              hideDelays : [500],
              bubbleWidths : [640],
              msieFix : true
           };
           $('.coda_bubble').codaBubble(opts);

           $('#coverImage').imagechooserLoadImage("<?php echo $coverImage==''?'http://phizuu.com/images/tour_images/itunes_default.png': $coverImage;?>");     
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
function deleteTrack(id) {
    $("#id_" + id).children().css("background-color",'#FFAEC0');
    $.post("../../../controller/music_all_controller.php?action=delete_track",{'track_id':id},
        function(data){
            if (data=='ok') {
                this.id = id;
                $("#id_" + this.id).slideUp('slow').remove();
                numMusic--;
                updateNextButton();
                if (tracksListed)
                    soundcloudListTracks('Refreshing Track List..');
            } else {
                alert("Failed to delete the track!");
            }
        }
    );

    return false;
}

var scConnectHTML = "";

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

    <?php if ($userInfo['package_id']==1 ) { ?>
        $(".uploadSectionDiv").hide();
    <?php } ?>

    return false;
}


var updateNextButton = function () {
    if (numMusic>0) {
        $('#buttonNext').attr('src','../../../images/btn_next.png');
        $('#buttonNext').css('cursor','pointer');
    } else {
        $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
        $('#buttonNext').css('cursor','');
    }
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
    $("#editMusicDiv").center();
    $('#editMusicDiv').fadeIn('normal');
    $(".editInput").attr('disabled','disabled');
    $("#editMessage").html('Loading data.. Please wait..');

    $("#titleEdit").val('');
    $("#durationEdit").val('');
    $("#iTuneURIEdit").val('');
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

    $.post("../../../controller/music_all_controller.php?action=ajax_get_data", { "id": id },
        function(data){
            $("#titleEdit").val(data.title);
            $("#durationEdit").val(data.duration);
            $("#iTuneURIEdit").val(data.itunes_uri);
            $("#ituneLink").attr('href',data.itunes_uri);
            $("#albumEdit").val(data.album);
            $("#yearEdit").val(data.year);
            $("#noteEdit").val(data.note);
            $("#imageURIEdit").val(data.image_uri);
            $("#idEdit").val(data.id);
            
            if(data.image_uri == '' || data.image_uri==null) {
                $('#musicCoverEdit').imagechooserLoadImage('../../../images/default-cover.png');
            } else {
                $('#musicCoverEdit').imagechooserLoadImage(data.image_uri);
            }
            
            $("#editMessage").html('');
            $(".editInput").attr('disabled','');
            
    }, "json");


    $('#cover2').fadeIn('normal');
    $('#cover2').css('height',$(document).height());
    $('#cover2').css('width',$(document).width());
}

function soundcloudListTracks(msg) {
    tracksListed = true;
    $('#trackListDiv').html("<img src='../../../images/bigrotation2.gif' align='top' style='float: left;'></img><div id='divSoundCloudDetailsLoadingMsg' class='tahoma_12_blue' style='float: left; font-size: 16px; margin: 4px'>"+msg+"</div>");

    $.post("../../../controller/music_all_controller.php?action=get_soundcloud_tracks",
        function(data){
            //alert(data);
            $('#trackListDiv').html(data);
            inlinePlayer.init();
    });

    return false;
}

function soundcloudAddTrack(trackId, img) {
    if (numMusic >= limit) {
        alert("Manximum number of tracks of "+limit+ " has exeeded");
        return;
    }
    
    img.src = '../../../images/bigrotation2.gif';
    img.onclick = function() {};
    var callback = function(data){
            this.prevId = trackId;
            $('#list_1').append(data).slideDown();
            //alert(this.prevId);
            numMusic++;
            updateNextButton();
            $('#id_' + this.prevId).slideUp().remove();
    };

    $.post("../../../controller/music_all_controller.php?action=soundcloud_add_track&wizard=yes",{'track_id':trackId},callback);
}

function hideEdit() {
    $('#editMusicDiv').fadeOut('normal');
    $('#cover2').fadeOut('normal');
}

function saveEditedSong() {
    //$("#editMusic").attr('disabled','');
    var id = $("#idEdit").val();
    $('#title_'+id).html($("#titleEdit").val());

    var duration = Math.ceil($("#durationEdit").val());
    var seconds = duration%60;
    var minutes = (duration - seconds)/60;
    $('#duration_'+id).html(minutes + "m "+seconds+"s ");

    $('#note_'+id).html($("#noteEdit").val());
    var iTuneURI = $("#iTuneURIEdit").val();
    var imageURI = $("#imageURIEdit").val();
    $("#iTuneURIEdit").val(escape(iTuneURI));
    $("#imageURIEdit").val(escape(imageURI));
    var data = $("#editMusic").serializeArray();
    $("#iTuneURIEdit").val(iTuneURI);
    $("#imageURIEdit").val(imageURI);

    $("#editMessage").html('Saving.. Please wait..');
    $.post("../../../controller/music_all_controller.php?action=edit_music", data,
    function(data){
        if (data!='ok') {
            if (data=='iTunesURLError') {
                alert('iTunes URL is invalid! Could not save details!')
            } else {
                alert (data);
                alert("Error occured while saving! Please try again..");
            }
        } else {
            hideEdit();
        }
    });
}

function updateLinkURI(){
    $("#ituneLink").attr('href',$("#iTuneURIEdit").val());
}


var numMusic = <?php echo count($iphone_music); ?>;
function takeAction(action) {
    if (action=='skip') {
         $("#skipWarning").dialog( "open" );
    } else if (action=='save') {
        if (numMusic==0) {
            $("#noContent").dialog( "open" );
        } else {
            window.location = "AppWizardControllerNew.php?action=music_module_save";
        }
    }
}
</script>
<div id="cover"></div>
<div id="cover2"></div>
<div id="noContent" title="Error!" style="text-align:center">
	<p>To use the music module you need to upload at least one Music. If you don't need this module please click skip this module button.</p>
</div>
<div id="skipWarning" title="Warning!" style="text-align:center">
	<p>If you skip this module you cannot enter Music into your app at a later time. Your application will be submitted to Apple without a Music Module. Please acknowledge by pressing Accept or press Cancel to enter Music.</p>
</div>
</body>
</html>
