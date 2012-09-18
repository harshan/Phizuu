<?php
require_once "../../../config/app_key_values.php";
require_once ("../../../controller/session_controller.php");
require_once ("../../../config/config.php");
require_once ('../../../config/database.php');
require_once ('../../../database/Dao.php');
require_once ('../../../controller/db_connect.php');
require_once ('../../../controller/helper.php');
require_once ('../../../controller/tours_controller.php');
require_once ('../../../model/tours_model.php');
require_once ('../../../config/error_config.php');
require_once ('../../../model/settings_model.php');
require_once ('../../../model/cms_config/CMSConfig.php');

$toursHidden = CMSConfig::getConfig($_SESSION['user_id'], 'old_tour_hidden');

$hideOld = TRUE;
if ($toursHidden != 'hidden') {
    $hideOld = FALSE;
}

$menu_item = "tours";

$settingModel = new SettingsModel();
$settings = $settingModel->listSettings($_ENV['myspace_url']);
if (count($settings) > 0)
    $url = $settings[count($settings) - 1]->value;
else
    $url = '';

$tour = new ToursModel();
$defaultImageArr = $tour->getDefaultImage($_SESSION['user_id']);
$defaultImage = $defaultImageArr[1];
$defaultImage2 = $defaultImageArr[0];

//get tours module item analytic data

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/event/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$dataList = array();
if (isset($allItems)) {
    foreach ($allItems->{"event"} as $value) {
        $dataList[$value->{"item_id"}] = array($value->{"like_count"}, $value->{"share_count"}, $value->{"comment_count"}, $value->{"view_count"});
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Phizuu Application</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <script type="text/JavaScript">

            function mySpaceTours() {
                if ($("#mySpaceURL").val() == '') {
                    alert ("Please enter the URL!");
                    return false;
                } else {
                    if (confirm("All the existing tours in the module will be removed!\n\nDo you want to continue?")) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }

            function editPic(id){
                var target = $('#6_' + id);

                $('#imageEdit').show(500);
                $('#imageEdit').css('top', target.offset().top + 55);
                $('#imageEdit').css('left', target.offset().left - 208);
                document.getElementById('pidEditForm').reset();
                $('#pidEditForm').attr('action', '../../../controller/tours_all_controller.php?action=change_images&id=' + id);
            }

            function hidePic() {
                $('#imageEdit').hide();
            }

            function deleteItem(id) {
                //$("#newsSortable").
                var itemId = id;
                var item = $("#id_"+id);

                $.post("../../../controller/tours_all_controller.php?action=delete_tour", { 'id': id },
                function(data){
                    if (data!='ok') {
                        alert("Error! while deleting\n\n"+data);
                        $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                    } else{
                        item.hide(500,function(){

                            document.getElementById('tourSortable').removeChild(document.getElementById('id_'+itemId));
                        });
                    }
                });

                $('#id_'+itemId).children().css('background-color', 'pink');

            }


            function selectImage(value, thumb) {
                $("#coverImage").attr('src', value);
                $.post("../../../controller/tours_all_controller.php?action=update_default_image", { 'url': value, 'thumb':thumb },
                function(data){
                    if(data=="ok")
                        $("#showPics").hide(500);
                    else
                        alert("Error occured while changing the image!");
                });
            }


        </script>


        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>

        <script type="text/javascript" src="../../../js/calendar/jscal2.js"></script>
        <script type="text/javascript" src="../../../js/calendar/en.js"></script>

        <script type="text/javascript">


            jQuery.fn.center = function () {
                this.css("position","absolute");
                this.css("top", ( $(window).height() - this.height() ) / 2+$(window).scrollTop() + "px");
                this.css("left", ( $(window).width() - this.width() ) / 2+$(window).scrollLeft() + "px");
                return this;
            }

            var imagesLoaded=false;
            function showMusicChooseWindow () {
                $("#showPics").center();
                $("#showPics").show(500);
                if (!imagesLoaded){
                    $("#showPics #body").html("<img src='../../../images/bigrotation2.gif'></img>");
                    $("#showPics #body").load("../music/select_image.php", function(response, status, xhr) {

                        if (status == "error") {
                            var msg = "Sorry but there was an error: ";
                            $("#error").html(msg + xhr.status + " " + xhr.statusText);
                        } else {
                            imagesLoaded = true;
                        }
                    });


                }

            }
            
        </script>
        <script type="text/javascript" src="../../../js/forms/js_tours.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/steel/steel.css" />

    </head>


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

                <?php if (isset($_GET['error'])) { ?>
                    <div class="tahoma_12_blue_error_bold" style ="padding-top: 4px; float: left">Error! Couldn't retrieve any Tours from the given URL. No existing events were deleted.</div>
                <?php } ?>
                <?php if (isset($_GET['memoryerror'])) { ?>
                    <div class="tahoma_12_blue_error_bold" style ="padding-top: 4px; float: left">Error! We are sorry, the image you selected is too big for us to handle. Please reduce its dimenstions (height and width) and try to re-upload it.</div>
                <?php } ?>
                <div id="body">
                    <div id="comments_view_full" ></div>
                    <div id="bodyNews">
                        <div id="lightBlueHeader2">

                            <div class="tahoma_14_white" id="newsHeader" style="width: 926px">Tours Lists Section</div>

                        </div>
                        <div id="titleBoxNewsBox">
                            <div class="tahoma_14_white" id="titleTours">Name</div>
                            <div class="tahoma_14_white" id="dateTours">Date</div>
                            <div class="tahoma_14_white" id="locationTours">Location</div>
                            <div class="tahoma_14_white" id="descriptionTours">Description</div>
                            <div class="tahoma_14_white" id="titleTickeURL" style="width: 160px">Ticket URL</div>
                            <div class="tahoma_14_white" id="titleThumbImg">Thumb</div>

                        </div>
                        <ul id="tourSortable" class="tahoma_12_blue">
                            <?php include('list_tours1.php'); ?>
                        </ul>




                    </div>
                    <div id="buttonContainer1">
                        <div id="addMusicBttn2_hide">
                            <div id="addTourButton" style="width: 200px; "><img src="../../../images/addNewTours.png" width="147" height="33" onclick="show_div();" style="cursor: pointer" /></div>
                            <a title="No effect to the iPhone List" id="addTourButton" href="../../../controller/tours_all_controller.php?action=<?php echo ($toursHidden != 'hidden') ? 'hide' : 'show'; ?>_old_tours" style="width: 148px;  ">
                                <img src="../../../images/<?php echo ($toursHidden != 'hidden') ? 'hide' : 'show'; ?>OldTours.png" width="148" height="33" border="0" style="cursor: pointer"/>
                            </a>

                        </div>
                    </div>
                    <div id="buttonContainer" style="display:none">

                        <form id="form" name="form" method="post" enctype="multipart/form-data" action="../../../controller/tours_newline_controller.php">
                            <div id="addMusicBttn2">

                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Name</div>
                                    <div id="formSinFeild">
                                        <input name="name" id="name" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Date</div>
                                    <div id="formSinFeild">
                                        <input type="Text" id="date" name="date" maxlength="25" size="25" class="textFeildBoarder" style="width:227px; height:21px;" readonly="readonly"/><img src="../../../images/cal.gif" id="f_btn1"  onclick="calendar_add();" onMouseOver="calendar_add();" />
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Location</div>
                                    <div id="formSinFeild">
                                        <input  name="location1" id="location1" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRowMulti">
                                    <div class="tahoma_12_blue" id="formName">Description</div>
                                    <div id="formMultiFeild">
                                        <textarea name="notes" id="notes" class="textFeildBoarder" style="width:227px; height:100px;"></textarea>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Ticket URL</div>
                                    <div id="formSinFeild">
                                        <input  name="ticketURL" id="ticketURL" type="text" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Flyer</div>
                                    <div id="formSinFeild">
                                        <input  name="flyerImage" id="flyerImage" type="file" class="textFeildBoarder" style="width:227px; height:21px;"/>
                                    </div>
                                </div>

                                <div id="formRowButtons">
                                    <div class="tahoma_12_blue" id="formName"></div>
                                    <div id="formSinFeild">
                                        <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
                                        <input type="image" src="../../../images/save.png" name="Login" id="Login"width="69" height="33"/>
                                        <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="99" height="33" onclick="form.reset(); return false;"/>
                                    </div>
                                </div>		
                            </div>
                        </form>


                    </div>
                    <div id="lightBlueHeader2">

                        <div id="lightBlueHeaderMiddle2" class="tahoma_14_white">Add Tours From SongKick</div>

                    </div>

                    <div>&nbsp;</div>
                    <!--      
                    <div id="buttonContainer" >
                          <div class="tahoma_12_blue">MySpace Automatic Feed is Disabled For Maintenence</div>
                    </div>
                    -->
                    <form action="../../../controller/tours_all_controller.php?action=fetch_myspace_tours" method="post" onsubmit="javascript: return mySpaceTours();">
                        <div style="width: 800px;height: 100px">
                            <div class="tahoma_12_blue" id="formName" style="width:200px">Artist ID in SongKick</div>
                            <div id="formSinFeild" style="width:300px;">
                                <input value="<?php echo $url ?>" name="mySpaceURL" id="mySpaceURL" type="text" class="textFeildBoarder" style="width:300px; height:21px;"/> <input name="btnRss" type="image" src ="../../../images/btn_update.png" style="width:87px; height:33px; align="middle"/>
                        </div>
                    </div>

                </form> 


                <div id="bodyLeft">
                    <div id="lightBlueHeader">

                        <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Tours Default Image</div>

                    </div>

                    <div>
                        <img alt="No Image" id="coverImage" src="<?php echo $defaultImage2 == '' ? '' : $defaultImage2; ?>" height="200"></img>

                    </div>
                    <div style="padding-top:20px;">

                        <img src="../../../images/change.png" style="cursor: pointer;width: 90px;height: 33px" onclick="showMusicChooseWindow(); "></img>
                    </div>




                </div></div>
            <br class="clear"/>

        </div><br class="clear"/> 
    </div>
    <br class="clear"/> 
    <div id="footerInner" >
        <div class="lineBottomInner"></div>
        <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
    </div>

    <div id="imageEdit" style="padding: 8px; border: #043F53 2px solid ; position: absolute; z-index: 100; display: none; background-color: white; width: 300px; height: 60px">
        <form id="pidEditForm" method="post" enctype="multipart/form-data">
            <div id="formRow">
                <div class="tahoma_12_blue" id="formName">Flyer</div>
                <div id="formSinFeild">
                    <input  name="flyerImage" id="flyerImage" type="file" class="textFeildBoarder" style="width:227px; height:21px;"/>
                </div>
            </div>
            <div id="formRowButtons">
                <div class="tahoma_12_blue" id="formName"></div>
                <div id="formSinFeild">
                    <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
                    <input type="image" src="../../../images/save.png" name="Login" id="Login"width="69" height="33"/>
                    <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="99" height="33" onclick="hidePic(); return false;"/>
                </div>
            </div>	
        </form>
    </div>

    <div id="showPics" style="display: none">
        <div id="header" class="tahoma_14_white">Choose images from your flicker account
            <div style="float: right; cursor: pointer;" onclick="javascript: $('#showPics').hide(500);" ><img src="../../../images/item_delete.png"/></div>
        </div>

        <div id="body" >Body</div>
    </div>
</body>
</html>
<script type="text/javascript">
    
    $(document).ready(function(){
               
        
        $(".ttip div").mouseover(function(){
               
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            $("#delete_"+id).mouseover(function(){
                   
                $("#div_tooltip_common_"+id).css({
                    "margin":"45px 0 0 876px",
                    "padding":"10px 0 0 12px"
                });
                $("#div_tooltip_common_"+id).show();
                $("#delete_"+id).mouseout(function(){
                    $("#div_tooltip_common_"+id).hide();
                });
                    
            });
                
                
                            
        });
 
        $(".showViews").mouseover(function(){
                   
            var arr = $(this).attr('id').split("_");;
            var id = arr[1];
                    
            if('showViews'==arr[0])
            {
                $("#viewToolTip"+id).show();
                $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 15px">Views</div>');
                $(".div_tooltip").css({"margin":"60px 0 0 10px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});
                       
            }
            else if('showLikes'==arr[0]){
                $("#viewToolTip"+id).show();
                $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 17px">Likes</div>');
                $(".div_tooltip").css({"margin":"60px 0 0 50px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

            }
            else if('showShare'==arr[0]){
                $("#viewToolTip"+id).show();
                $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 12px">Shares</div>');
                $(".div_tooltip").css({"margin":"60px 0 0 95px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

            }
            else if('showComments'==arr[0]){
                var comment_count = $("#commentsCount_"+id).val();
                if(comment_count != 0){
                    $("#tooltip_comment"+id).show();
                    $("#tooltip_comment"+id).html('<div style="padding:10px;padding-top:20px;color: #fff;background:url(../../../images/tooltip_msg.png) no-repeat  top left;height:70px"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                    $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'event', 'itemId':id}, 
                    function(data){
                        $("#tooltip_comment"+id).html(data);
                    });
                    
                }else{
                    $("#viewToolTip"+id).show();
                    $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 8px;">no comments</div>');
                    $(".div_tooltip").css({'margin':'60px 0 0 135px','background':"url('../../../images/small_tooltip2.png') no-repeat  top left","width":"91px"});
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
                            
                    $("#comments_view_full").html('<div style="float: left"><iframe src="../../common/view_comments.php?title='+title+'&itemId='+id+'&module=event&noOfComments='+noOfComments+'" width="520" height="500" frameborder="0" scrolling="no" ></iframe></div><div style="cursor: pointer;float: left;margin: -10px -10px 0 0 "><img src="../../../images/close.png" id="comment_closs"/></div>');
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
                    
            var test = $("#title_"+id).text();
            alert(test);
        })

               
                
                       
                
        
    });
</script>