<?php
require_once '../../../model/UserInfo.php';

$userInfo = UserInfo::getUserInfoDirect();

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');


$bmusic= new Music();
$bank_music = $bmusic->listBankMusic($_SESSION['user_id']);
$count=1;
$imusic= new Music();
$iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
$icount=1;

//$box_music_user = $imusic->getBoxAccount($_SESSION['user_id']);
//if(sizeof($box_music_user) >0){
//$_SESSION['box_user']=$box_music_user->user;
//$_SESSION['box_pwd']=$box_music_user->password;
//}
//
$coverImage = $bmusic->getCoverImage($_SESSION['user_id']);
//
//include('../../../controller/boxnet/box_config.php');
//
// //Get Ticket to Proceed
//
//$ticket_return = $box->getTicket ();
//
//if ($box->isError()) {
//     $box->getErrorMsg();
//} else {
//
//	$ticket = $ticket_return['ticket'];
//
//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>phizuu - Application Wizard</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
<link href="../../../common/tooltip/bubble.css" rel="stylesheet" type="text/css" media="all" />
<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />

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
    width:400px;
    padding-left:0px;
    cursor: pointer;
    height: 52px;
}


.holder {
    border:0px dashed #333;
    background:#fff;
}



.highlight {
    width: 464px;
    height: 64px;
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
</style>
<link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />


</head>


<body>

<div id="mainWideDiv">
  <div id="middleDiv2">
                <div id="header">
                    <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="120" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
                </div>
    <div id="body">
                        <br/>

                         <div class="wizardTitle" >
                            <div class="left"><img src="../../../images/wizTitleLeft.png" width="10" height="34"/></div>
                            <div class="middle" style="width: 870px">Please click Upload to add music to your music module</div>
                            <div class="right"><img src="../../../images/wizTitleRight.png" width="10" height="34"/></div>
                        </div>

    <div class="coda_bubble wizardSecondTitle" style="width: 900px">
        <div>
            <p><div style="float:left">Please upload your tracks. You can re-order them by draging and dropping them into place.</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <div style="height: 20px">Quick Video Tutorial (54 seconds) - to hide move mouse away</div>
<object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/RwbxMiV61L8&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/RwbxMiV61L8&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>           </div>
        </div>

    </div>

    <div id="lightBlueHeader" class="wizardItemList">
        <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
        <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Maximum <?php echo $limit_count->music_limit ?> track<?php echo $limit_count->music_limit==1?'':'s'?></div>
        <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
    </div>
    <div id="lightBlueHeader" class="wizardItemList">
        <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
        <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Maximum bitrate 128kbps</div>
        <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
    </div>
                        
    <div id="div_error"></div>
      <div id="indexBodyLeft">
        <div id="bodyLeft" style="height: 180px">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Upload Section</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>

          <div id="titleBox">
            <div class="tahoma_14_white" id="title">Upload Queue</div>
          </div>

          <div id="bodyLeft" class="uploadSectionDiv" style="">
                    <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
                        <div class="fieldset flash" id="fsUploadProgress">
                            <span class="legend">Upload Queue</span>

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

        </div>

        <?php if(isset($_SESSION['modules'])) { ?>
<div id="bodyLeft">&nbsp;</div>


         <div id="bodyLeft">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Cover Image</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>

             <div>
                 <img alt="failed to load the image" id="coverImage" src='' height="200" ></img>

             </div>
             <div style="padding-top:20px;">

                     <img src="../../../images/change.png" style="cursor: pointer" onclick="javascript: showMusicChooseWindow(1);"></img>
             </div>




        </div>

<?php } ?>
      </div>
      <div id="indexBodyRight">
        <div id="bodyRgt">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Uploaded Songs</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>
	  	<div id="titleBox">
		  <div class="tahoma_14_white" id="title">Title</div>
		  <div class="tahoma_14_white" id="duration">Duration</div>
		  <div class="tahoma_14_white" id="note">Notes</div>
		</div>

          <ul class="connected" id="list_2">
        <?php
	   if(sizeof($iphone_music) >0){
	   foreach($iphone_music as $imusic){

               $duration = ceil($imusic->duration);
$seconds = $duration%60;
$minutes = ($duration - $seconds)/60;

               ?>



        <li id="id_<?php echo $imusic->id;?>">
        <div id="textBar" class="tahoma_12_blue">
        <div class="tahoma_12_blue title_note" id="title_<?php echo $imusic->id;?>"><?php echo $imusic->title;?></div>
        <div class="tahoma_12_blue duration2" id="duration_<?php echo $imusic->id;?>"><?php echo $minutes."m ". $seconds . 's'?></div>
        <div class="tahoma_12_blue note_area note2new" id="note_<?php echo $imusic->id;?>"><?php echo $imusic->note;?></div>

       <div class="tahoma_12_blue" id="iconBox">
			  <div id="icon" style="cursor: pointer"><a onclick="showEdit(<?php echo $imusic->id;?>)"  ><img src="../../../images/file.png" border="0" /></a></div>
              	  <div id="icon"> <a href="../../../controller/music_add_iphone_controller.php?id=<?php echo $imusic->id;?>&status=remove" onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
			</div>
		</div>
        </li>
		<?php
	  $icount++;
	  }
	  }
	  ?>
      </ul>

        </div>
      </div>

    </div>
      <div id="bodyLeftWizard" >
                            <div class="nextButton" style="width: 927px; border: 0">
                                <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="143" height="25" />
                                <img class="<?php echo count($iphone_music)==0?'':'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo count($iphone_music)==0?'btn_next_disabled.png':'btn_next.png' ?>" width="83" height="25" />

                            </div>
                        </div>
    <div id="buttonContainer">&nbsp;</div>
  </div>
</div>
                        
	        <div id="footerMain">
            <div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>




        <div id="showPics" style="display: none">
            <div id="header" class="tahoma_14_white">Choose images from your flicker account
                <div style="float: right; cursor: pointer;" onclick="javascript: closePicWindow();" ><img src="../../../images/item_delete.png"/></div>
            </div>

            <div id="bodyx" >Body</div>

        </div>

          <div id="editMusicDiv"  style="background-color: #FFFFFF; border: #043f53 2px solid; padding: 8px; z-index: 15; position:absolute; display: none">
                  <form action="" id="editMusic">
        	<input type="hidden" value="" name="imageURI" id='imageURIEdit'>
            <input type="hidden" value="" name="id" id='idEdit'>
            <div  >
              <table width="759" border="0" cellspacing="4" cellpadding="0" class="tahoma_12_blue">
                <tr>
                  <td width="74" valign="top">Title:</td>
                  <td width="331" valign="top"><input type="text" value="" id="titleEdit" name="title" style="width: 300px;" class="textfield editInput"></td>
                  <td width="320" valign="top" >Music Image (Click Image to Edit):</td>
                </tr>
                <tr>
                  <td valign="top">Duration:</td>
                  <td valign="top"><input type="text" value="" id="durationEdit" name="duration" style="width: 300px;" class="textfield editInput"></td>
                  <td width="320" rowspan="5" valign="top"><div  style="border: 1px solid #CCCCCC; height:320px; width:320px" align="center"><img src="" alt="" name="musicCoverEdit" width="2" height="2"  id="musicCoverEdit" onclick="javascript: showMusicChooseWindow(2);" style="cursor: pointer"/></div></td>
                </tr>
                <tr>
                  <td valign="top">iTuneURI:</td>
                  <td valign="top"><input type="text" value="" id="iTuneURIEdit" name="iTunesURI" style="width: 285px;" class="textfield editInput" onkeyup="javascript: updateLinkURI()"/> <a href="#" id="ituneLink" target="_blank"><img border='0' src="../../../images/NewWindowIcon.png"  /></a></td>
                </tr>
                <tr>
                  <td valign="top">Album:</td>
                  <td valign="top"><input type="text" value="" id="albumEdit" name="album" style="width: 300px;" class="textfield editInput"></td>
                </tr>
                <tr>
                  <td valign="top">Year:</td>
                  <td valign="top"><input type="text" value="" id="yearEdit" name="year" style="width: 300px;" class="textfield editInput"></td>
                </tr>
                <tr>
                  <td height="184" valign="top">Note:</td>
                  <td valign="top"><textarea type="text" value="" id="noteEdit" name="note" style="width: 300px; height: 177px" class="textfield editInput"></textarea></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><div id="editMessage"></div></td>
                  <td width="320" align="left"><div align="right"><img width="83" height="25" border="0" align="top" src="../../../images/save2.png" style="cursor:pointer" onclick="javascript: saveEditedSong();"> <img style="cursor:pointer" onclick="javascript: hideEdit();" width="83" height="25" border="0" align="top" src="../../../images/cancel.png"></div></td>
                </tr>
              </table>
            </div>
</form>
        </div>

<script type="text/javascript" src="../../../js/swf_up/swfupload.js"></script>
<script type="text/javascript" src="../../../js/swf_up/swfupload.queue.js"></script>
<script type="text/javascript" src="../../../js/swf_up/fileprogress.js"></script>
<script type="text/javascript" src="../../../js/swf_up/handlers_old.js"></script>

<script type="text/javascript">
		window.onload = function() {

			var settings = {
				flash_url : "../../../common/swfupload.swf",
				upload_url: "../../../controller/music_uploader.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>", "app_wizard":"true"},
				file_size_limit : "50 MB",
				file_types : "*.mp3",
				file_types_description : "MP3 (Maximum BitRate 128kbps)",
				file_upload_limit : 1,
				file_queue_limit : 1,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,
                                prevent_swf_caching: false,

				// Button settings
				button_image_url: "../../../images/upload_music.png",
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

            var imagesLoaded=false;
            function showMusicChooseWindow (callbackId) {
                $("#showPics").center();
                $('#showPics').fadeIn('normal');
                $('#cover').fadeIn('normal');
                $('#cover').css('height',$(document).height());
                $('#cover').css('width',$(document).width());
                if (!imagesLoaded){
                    $("#showPics #bodyx").html("<img src='../../../images/bigrotation2.gif'></img>");
                    $("#showPics #bodyx").load("../../../view/user/music/select_image.php?callbackId="+callbackId, function(response, status, xhr) {

                        if (status == "error") {
                            var msg = "Sorry but there was an error: ";
                            $("#error").html(msg + xhr.status + " " + xhr.statusText);
                        } else {
                            imagesLoaded = true;
                        }
                    });


                }

            }
            function selectImage(value, thumb, callbackId) {
                if(callbackId=='1'){
                    $.post("../../../controller/music_all_controller.php?action=update_cover", { 'url': value },
                    function(data){
                        if(data=="ok"){
                            //$("#showPics").hide(500);
                            closePicWindow();
                        } else
                            alert("Error occured while changing the image!");
                    });

                    loadImage('coverImage',value, 320, 250);
                } else if(callbackId=='2') {
                    $("#imageURIEdit").val(value);
                    closePicWindow();
                    loadImage('musicCoverEdit',value, 325, 325);
                }
            }

            function closePicWindow(value) {
                $('#showPics').fadeOut('normal');
                $('#cover').fadeOut('normal');
            }


        $(function() {
            $("#list_2").sortable({
                connectWith: '.connected',
                placeholder: 'highlight'
            }).disableSelection();

            $('.edit').editable('../../../controller/music_all_controller.php?action=edit',{
                indicator : 'Saving...',
                tooltip   : 'Click to edit...'
            });

            $("#list_2").bind('sortupdate', function(event, ui) {
                $("#list_2").sortable( 'disable' );
                $("#list_2").css('cursor', 'wait');

                var list2 = $('#list_2').sortable('serialize');
                list2 = list2.replaceAll('id','list2');

                var ordered =  list2;
                //alert(ordered);
                $.post('../../../controller/music_all_controller.php?action=order&'+ordered, function(data) {
                    //alert(data);
                    $("#list_2").sortable( 'enable' );
                    $("#list_2").css('cursor', 'move');
                });

            });


                $('.note_area').tooltip({
                delay: 0,
                showURL: false,
                bodyHandler: function() {

                    if (this.innerHTML.substring(0,5)!='<form'){

                        if(this.innerHTML != '')
                            return this.innerHTML.replace(/\n/g,"<br>");
                        else{

                            return "Empty";
                        }
                    } else {
                        return '';
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

                <?php
                if (isset($_SESSION['last_music'])) {
                    $lastItemID = $_SESSION['last_music'];
                    unset($_SESSION['last_music']);
                    echo "showEdit($lastItemID);\n";
                }
                ?>
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
            opts = {
              distances : [-153],
              leftShifts : [400],
              bubbleTimes : [400],
              hideDelays : [500],
              bubbleWidths : [640],
              msieFix : true
           };
           $('.coda_bubble').codaBubble(opts);
           
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


            if(data.image_uri == '') {
                loadImage('musicCoverEdit','../../../images/default-cover.png', 320, 320);
            } else {
                loadImage('musicCoverEdit',data.image_uri, 320, 320);
            }
            $("#editMessage").html('');
            $(".editInput").attr('disabled','');

    }, "json");


    $('#cover2').fadeIn('normal');
    $('#cover2').css('height',$(document).height());
    $('#cover2').css('width',$(document).width());
}

function hideEdit() {
    $('#editMusicDiv').fadeOut('normal');
    $('#cover2').fadeOut('normal');
}

function loadImage(imgId, src, w, h) {
    $("#"+imgId).attr('src','../../../images/bigrotation2.gif');
    $("#"+imgId).attr('height',32);
    $("#"+imgId).attr('width',32);
    var newImg = new Image();
    newImg.src = src;

    newImg.onload = function() {
        var height = this.height;
        var width = this.width;

        var tH = (height/width)*w;
        var tW = w;

        if (tH>h){
            var tW = (width/height)*h;
            var tH = h;
        }

        $("#"+imgId).attr('height',tH);
        $("#"+imgId).attr('width',tW);
        $("#"+imgId).attr('src', this.src);
    }
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

loadImage('coverImage',"<?php echo $coverImage==''?'http://phizuu.com/images/tour_images/itunes_default.png': $coverImage;?>", 320, 250);
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