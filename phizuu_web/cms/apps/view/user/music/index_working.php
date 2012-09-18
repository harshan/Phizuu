<?php
$menu_item="music";

include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/music_controller.php');
include('../../../model/music_model.php');
include('../../../config/error_config.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');

$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'music');


$bmusic= new Music();
$bank_music = $bmusic->listBankMusic($_SESSION['user_id']);
$count=1;
$imusic= new Music();
$iphone_music = $imusic->listIphoneMusic($_SESSION['user_id']);
$icount=1;

$box_music_user = $imusic->getBoxAccount($_SESSION['user_id']);
if(sizeof($box_music_user) >0){
$_SESSION['box_user']=$box_music_user->user;
$_SESSION['box_pwd']=$box_music_user->password;
}

$coverImage = $bmusic->getCoverImage($_SESSION['user_id']);

include('../../../controller/boxnet/box_config.php');

// Get Ticket to Proceed

$ticket_return = $box->getTicket ();

if ($box->isError()) {
     $box->getErrorMsg();
} else {
	
	$ticket = $ticket_return['ticket'];

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
<link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />


</head>
	

<body>

<div id="mainWideDiv">
  <div id="middleDiv2">
    <?php include("../common/header.php");?>
    <?php include("../common/navigator.php");?>
    <div id="body">
    <div id="div_error"></div>
      <div id="indexBodyLeft">
        <div id="bodyLeft">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Uploaded Songs Section</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>
	  	<div id="titleBox">
		  <div class="tahoma_14_white" id="title">Title</div>
		  <div class="tahoma_14_white" id="duration">Duration</div>
		  <div class="tahoma_14_white" id="note">Notes</div>
		</div>

        <ul class="connected" id="list_1">
         <?php 

                           if(sizeof($bank_music) >0) {
                               foreach($bank_music as $bmusic) {
                                 include 'bank_list_new.php';
                               }
                               $count++;
                           }
          ?>
      </ul>


        </div>
        <div id="bodyLeft">&nbsp;</div>
        <div id="bodyLeft" style="height: 180px">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Upload Section</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>
          <div id="titleBox">
            <div class="tahoma_14_white" id="title">Upload Queue</div>
          </div>
        
          <div id="ashBlock">
             <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
            
           		<div>
						<div class="fieldset flash" id="fsUploadProgress1"  style="width:400px">
							
						</div>
						<div style="padding-left: 5px;" class="tahoma_12_blue">
							<span id="spanButtonPlaceholder1"></span>
                                                        <input type="image" src="../../../images/cancel.png" id="btnCancel1" onclick="cancelQueue(upload1);" disabled="disabled" style="border: 0px; padding: 0"/>

						</div>
				</div>
            </form>
          </div>

            <div id="bodyLeft" class="tahoma_12_blue">Please upload files with bitrate <b>128kbps</b> or <b>less</b></div>
          
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
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">iPhone</div>
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
    <div id="buttonContainer">&nbsp;</div>
  </div>
</div>

	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>


                
        <div id="showPics" style="display: none">
            <div id="header" class="tahoma_14_white">Choose images from your flicker account
                <div style="float: right; cursor: pointer;" onclick="javascript: closePicWindow();" ><img src="../../../images/item_delete.png"/></div>
            </div>

            <div id="body" >Body</div>

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
<script type="text/javascript" src="../../../js/swf_up/handlers.js"></script>

<script type="text/javascript">
		var upload1, upload2;
		window.onload = function() {

			upload1 = new SWFUpload({
				// Backend Settings
				upload_url: "../../../controller/upload_controller.php",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>","auth_token" : "<?php echo $_SESSION['auth_token']; ?>","api_key" : "<?php echo  $_SESSION['api_key']; ?>","user_id" : "<?php echo  $_SESSION['user_id']; ?>"},

				// File Upload Settings
				file_size_limit : "102400",	// 100MB
				file_types : "*.mp3",
				file_types_description : "MP3 Files",
				file_upload_limit : "10",
				file_queue_limit : "1",

				// Event Handler Settings (all my handlers are in the Handler.js file)
				file_dialog_start_handler : fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "../../../images/up3.jpg",
				button_placeholder_id : "spanButtonPlaceholder1",
				button_width: 96,
				button_height: 25,

				// Flash Settings
				flash_url : "swfupload.swf",


				custom_settings : {
					progressTarget : "fsUploadProgress1",
					cancelButtonId : "btnCancel1"
				}

				// Debug Settings
				//debug: true
			});

	     }



	</script>


<script type="text/JavaScript">
        var limit=<?php echo $limit_count ->music_limit;?>;
</script>


<!--multi drag-->
<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
<script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>

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
                    $("#showPics #body").html("<img src='../../../images/bigrotation2.gif'></img>");
                    $("#showPics #body").load("select_image.php?callbackId="+callbackId, function(response, status, xhr) {

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
            $("#list_1, #list_2").sortable({
                connectWith: '.connected',
                placeholder: 'highlight'
            }).disableSelection();

            $('.edit').editable('../../../controller/music_all_controller.php?action=edit',{
                indicator : 'Saving...',
                tooltip   : 'Click to edit...'
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
                    //alert(data);
                    $("#list_1, #list_2").sortable( 'enable' );
                    $("#list_1, #list_2").css('cursor', 'move');
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

loadImage('coverImage',"<?php echo $coverImage==''?'http://phizuu.com/images/tour_images/itunes_default.png': $coverImage;?>", 320, 250);
</script>
<div id="cover"></div>
<div id="cover2"></div>
</body>
</html>