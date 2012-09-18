<?php
$limitFiles = new LimitFiles();
$limit_count = $limitFiles->getLimit($_SESSION['user_id'], 'video');


$bvideo = new Video();
$bank_video = $bvideo->listBankVideos($_SESSION['user_id']);
$count = 1;
$ivideo = new Video();
$iphone_video = $ivideo->listIphoneVideos($_SESSION['user_id']);
$icount = 1;
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
                background:#fff;
                padding:0px;
                margin:0px;
                min-height:150px;
                width:464px;
            }

            li {

                width:400px;
                padding-left:0px;
                cursor: move;
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
        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../common/tooltip/jquery.codabubble.js"></script>
        <script type="text/JavaScript">
            var limit=<?php echo $limit_count->video_limit; ?>;

            $(function() {
            
             
                $("#list_1, #list_2").sortable({
                    placeholder: 'highlight'
                }).disableSelection();

                $('.edit').editable('../../../controller/video_all_controller.php?action=edit',{
                    indicator : 'Saving...',
                    tooltip   : 'Click to edit...'
                });

                $("#list_2").bind('sortupdate', function(event, ui) {
                    $("#list_1, #list_2").sortable( 'disable' );
                    $("#list_1, #list_2").css('cursor', 'wait');

                    var list2 = $('#list_2').sortable('serialize');
                    list2 = list2.replaceAll('id','list2');

                    var ordered = list2;
                    //alert(ordered);
                    $.post('../../../controller/video_all_controller.php?action=order&'+ordered, function(data) {
                        //alert(data);
                        $("#list_1, #list_2").sortable( 'enable' );
                        $("#list_1, #list_2").css('cursor', 'move');
                    });

                });

                $("#invalidYouTubeUser").dialog({
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
                            window.location = "AppWizardControllerNew.php?action=video_module_skip";
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
                    $(".editInput").addClass('textfield');

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
                        alert (data);
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
                        $("#invalidYouTubeUser").dialog("open");
                    } else {
                        $('#youtubeCollection').attr('disabled','');
                        $('#youtubeCollection').empty()
                        $('#youtubeCollection').append($("<option></option>").attr("value","").text("All"));
                        data = data.data;
                        for (i=0; i<data.length; i++) {
                            //alert(sets[i].title);
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

                $('#list_1').empty();
                $.post('../../../controller/video_all_controller.php?action=get_videos', {'id':id,'username':username}, function(data) {
                    $('#youtubeButton').show();
                    $('#listingWaiting').hide();
                    $('#youtubeUserName').attr('disabled','');
                    $('#youtubeCollection').attr('disabled','');
                    $('#addAllButtonLnk').show();
                    $('#list_1').append(data.html);
                    listedVideos = data.videos;
                    $("#list_1").sortable( "refresh" );
                },'json');
            }

            function addVideo(id,vid) {
                if(videoInList==videoLimit) {
                    alert('Sorry! your maximum limit for videos has exeeded!');
                    return false;
                }
                lastElem = document.getElementById('id_'+vid);
                startBusy();
                $.post('../../../controller/video_all_controller.php?action=add_video', listedVideos[id], function(data) {
                    stopBusy()
                    $('#list_2').append(data);
                    $("#list_2").sortable( "refresh" );
                    document.getElementById("list_1").removeChild(lastElem);
                    $('#buttonNext').attr('src','../../../images/btn_next.png');
                    $('#buttonNext').addClass('wizardButton');
                    videoInList++;
                    $("#imageCount").html(videoInList);
                });
            }
      
            function startBusy() {
                $('#cover').css('opacity',0.5);
                $('#cover').css('background-color','#000000');
                $('#cover').css('width',$(document).width());
                $('#cover').css('height',$(document).height());
                $('#cover').css('width',$(document).width());
                $('#cover').fadeIn('normal');
            }

            function stopBusy() {
                $('#cover').fadeOut('fast');
            }
            function delete_confirm() {
                return confirm('This will delete the file and refresh the page!\n\nAre you sure you want to continue?')
            }

            function takeAction(action) {
                if (action=='skip') {
                    $("#skipWarning").dialog( "open" );
                } else if (action=='save') {
                    if (videoInList==0) {
                        $("#noContent").dialog( "open" );
                    } else {
                        window.location = "AppWizardControllerNew.php?action=video_module_save";
                    }
                }
            }

            var listedVideos;
            var lastElem;
        </script>

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
                <!--                <div id="header">
                                    <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logo.png" width="334" height="62" border="0" /></a></div>
                                    <div class="tahoma_12_white2" id="loginBox">
                                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                                        </a>
                                        <a href="../../logout_controller.php">
                                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                                        </a>
                                    </div>
                                </div>-->

                <div id="body">

                    <br/>

                    <div class="wizardTitle" >

                        <div class="middle" style="width: 910px;padding-top:5px;height: 27px ">Choose video's from your <img align="top" src="../../../images/youtube_logo.png"></img> account</div>

                    </div>

                    <div class="coda_bubble wizardSecondTitle" style="width: 900px">
                        <div>
                            <p><div style="float:left; width: 850px">You can choose videos that are uploaded to YouTube. Enter your username and then press (+) sign to add the videos. You can order them by dragging and dropping.</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
                        </div>
                        <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
                            <div style="width:480px; height: 405px; ">
                                <div style="height: 20px">Quick Video Tutorial (36 seconds) - to hide move mouse away</div>
                                <object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/Nadzk8ZVvDs&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/Nadzk8ZVvDs&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>           </div>
                        </div>
                    </div>

                    <div id="lightBlueHeader" class="wizardItemList">

                        <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Maximum <?php echo $limit_count->video_limit ?> video<?php echo $limit_count->video_limit == 1 ? '' : 's' ?></div>

                    </div>   

                    <div id="div_error"></div>
                    <div id="bodyLeft">
                        <div id="lightBlueHeader">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Select Videos</div>

                        </div>
                        <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%">
                            Username: <input name="youtubeUserName" id="youtubeUserName" type="text" class="textFeildBoarder" style="width:200px; height:20px;margin-left: 4px"/>
                            <img id="youtubeButton" onclick="javascript: youtubeGetCollections()" style="cursor:pointer" src="../../../images/btn_submit.png" align="top" ></img>
                            <img align="top" id="youtubeButtonWait" src="../../../images/bigrotation2.gif" style="display:none"/>
                        </div>
                        <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%">
                            <div style="float: left; width: 66px;">Collections:</div>
                            <select onchange="javascript: youtubeGetVideos(this.value)"  name="youtubeCollection" id="youtubeCollection" type="text" class="textFeildBoarder" style="width:209px; height:27px; float: left;" disabled>
                                <option>-- Enter Username --</option>
                            </select>
                            <div id="listingWaiting" class="tahoma_12_blue" style="float: left;display: none ">
                                <img align="middle" id="youtubeButtonWait" src="../../../images/bigrotation2.gif" /> Listing videos...
                            </div>
                        </div>
                        <div style="margin-bottom: 5px">&nbsp;</div>
                        <div id="titleBox">
                            <div class="tahoma_14_white" id="title">Title</div>
                            <div class="tahoma_14_white" id="duration">Duration</div>
                            <div class="tahoma_14_white" id="note_thubmnail">Thumbnail</div>
                        </div>

                        <div id="textBarMusic1"><ul id="list_1">

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
                                                                <div class="tooltip" id="edit_<?php echo $bVideo->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a href="javascript: showEdit(<?php echo $bVideo->id; ?>)"  ><img src="../../../images/file.png" border="0" /></a></div>

                                                                <div class="tooltip" id="delete_<?php echo $bVideo->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a href="../../../controller/video_add_iphone_controller.php?id=<?php echo $bVideo->id; ?>&status=delete" onclick="return delete_confirm();"  ><img src="../../../images/cross.png" border="0" /></a></div>
                                                            </div>
                                                            <div id="div_tooltip_common_<?php echo $bVideo->id; ?>" class="div_tooltip_common">Edit</div>
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
                        <div id="buttonContainer">

                        </div>
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

                        <div id="textBarMusic1"><ul id="list_2">
                                <?php
                                if (sizeof($iphone_video) > 0) {
                                    foreach ($iphone_video as $iVideo) {

                                        $duration = ceil($iVideo->duration);
                                        $seconds = $duration % 60;
                                        $minutes = ($duration - $seconds) / 60;
                                        ?>

                                        <li id="id_<?php echo $iVideo->id; ?>"  style="cursor: pointer;">
                                            <table border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td>
                                                        <div id="textBarMusic">
                                                            <div class="move"><img src="../../../images/move.png"/></div>
                                                            <div class="tahoma_12_blue edit titleMusic" id="1_<?php echo $iVideo->id; ?>"><?php echo $iVideo->title; ?></div>
                                                            <div class="tahoma_14_white" id="durationMusic"><span id="2_<?php echo $iVideo->id; ?>" class="tahoma_12_blue"><?php echo $minutes . "m " . $seconds . 's' ?></span></div>
                                                            <div class="tahoma_12_blue" id="noteMusicThumb">
                                                                <div class="thmbImg"><img src="<?php echo $iVideo->thum_uri; ?>" width="50" height="44" border="0" /></div>
                                                            </div>
                                                            <div class="tahoma_12_blue" id="iconBoxMusic">
                                                                <div  class="tooltip" id="edit_<?php echo $iVideo->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a href="javascript: showEdit(<?php echo $iVideo->id; ?>)"><img src="../../../images/file.png" border="0" /></a></div>

                                                                <div  class="tooltip" id="delete_<?php echo $iVideo->id; ?>" style="cursor: pointer;float: left;height: 28px;width: 24px;padding-top: 18px;padding-left: 15px;"><a href="../../../controller/video_add_iphone_controller.php?id=<?php echo $iVideo->id; ?>&status=remove" onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
                                                            </div>
                                                             <div id="div_tooltip_common_<?php echo $iVideo->id; ?>" class="div_tooltip_common">Edit</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </li>


                                        <?php
                                        $icount++;
                                    }
                                }
                                ?>
                            </ul></div>
                        <div class="tahoma_12_blue" style="font-size: 14px; text-align: right" ><span  id="imageCount"><?php echo count($iphone_video) ?></span> out of <?php echo $limit_count->video_limit ?></div>
                    </div>




                </div>
                <div class="nextButton" style="width: 927px; border: 0">
                    <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="35" />
                    <img id="buttonNext" class="<?php echo count($iphone_video) == 0 ? '' : 'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo count($iphone_video) == 0 ? 'btn_next_disabled.png' : 'btn_next.png' ?>" width="89" height="35" />

                </div>
                <!--<div id="indexBodyRight"></div>-->
            </div>
            <div id="buttonContainer">&nbsp;</div>
        </div>

        <br class="clear"/>
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>


        <div id="cover"></div>


        <div id="editVideoDiv"  style="background-color: #FFFFFF; border: #043f53 2px solid; padding: 8px; z-index: 25; position:absolute; display: none">
            <form action="" id="editVideo">
                <input type="hidden" value="" name="imageURI" id='imageURIEdit'/>
                <input type="hidden" value="" name="id" id='idEdit'/>
                <div  >
                    <table width="365" border="0" cellspacing="4" cellpadding="0" class="tahoma_12_blue">
                        <tr>
                            <td width="100" valign="top">Title:</td>
                            <td valign="top"><input type="text" value="" id="titleEdit" name="title" style="width: 300px;" class="textfield editInput"/></td>
                        </tr>
                        <tr>
                            <td valign="top">Duration:</td>
                            <td valign="top"><input type="text" value="" id="durationEdit" name="duration" style="width: 300px;" class="textfield editInput"/></td>

                        </tr>
                        <tr>
                            <td valign="top">Year:</td>
                            <td valign="top"><input type="text" value="" id="yearEdit" name="year" style="width: 300px;" class="textfield editInput"/></td>
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
                            <td width="320" align="left"><div align="right"><img width="89" height="33" border="0" align="top" src="../../../images/save2.png" style="cursor:pointer" onclick="javascript: saveEditedVideo();"/> <img style="cursor:pointer" onclick="javascript: hideEdit();" width="99" height="33" border="0" align="top" src="../../../images/cancel.png"/></div></td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>

        <div id="noContent" title="Error!" style="text-align:center">
            <p>To use the Video module you need to add at least one Video. If you don't need this module please click skip this module button.</p>
        </div>
        <div id="skipWarning" title="Warning!" style="text-align:center">
            <p>If you skip this module you cannot enter Videos into your app at a later time. Your application will be submitted to Apple without a Video Module. Please acknowledge by pressing Accept or press Cancel to add Videos.</p>
        </div>
        <div id="invalidYouTubeUser" title="Error!" style="text-align:left">
            <p>YouTube username is invalid! Please enter valid YouTube username.</p>
        </div>
        <script type="text/javascript">
            var videoLimit = <?php echo $limit_count->video_limit ?>;
            var videoInList = <?php echo count($iphone_video) ?>;
        </script>
    </body>
</html>

<script>
    $(document).ready(function() {
        $(".tooltip").mouseover(function(){
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
         
            if("edit"==arr[0]){
                $("#edit_"+id).mouseover(function(){
                    
                    $("#div_tooltip_common_"+id).text("Edit");
                    $("#div_tooltip_common_"+id).css({
                        "margin":"52px 0 0 338px",
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
                        "margin":"52px 0 0 379px",
                        "padding":"10px 0 0 12px"
                    });
                    $("#delete_"+id).mouseout(function(){
                        $("#div_tooltip_common_"+id).hide();
                    });
                });
            }
                            
        });
    });  
</script>