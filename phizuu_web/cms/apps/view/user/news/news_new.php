<?php
require_once "../../../config/app_key_values.php";
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once('../../../controller/settings_controller.php');
require_once('../../../model/settings_model.php');
require_once('../../../model/news_model.php');
require_once('../../../controller/news_controller.php');
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/news_controller.php');
require_once('../../../controller/pagination_controller.php');
require_once('../../../model/news_model.php');
require_once('../../../config/error_config.php');

$menu_item = "news";

$news = new News();
$lid = '2';
$rss_list = $news->getRssFeed($lid);
if (sizeof($rss_list) > 0) {
    $rss_stat = "1";
} else {
    $rss_stat = "0";
}


foreach ($rss_list as $rss_one) {
    $rss_val = $rss_one->value;
    $rss_id = $rss_one->id;
}

$ch = curl_init(app_key_values::$API_URL . "analytic/" . $_SESSION['app_id'] . "/$menu_item/getall");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$allItems = curl_exec($ch);
$allItems = json_decode($allItems);
$dataList = array();
if (isset($allItems)) {
    foreach ($allItems->{"news"} as $value) {
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

                function deleteItem(id) {
                    //$("#newsSortable").
                    var itemId = id;
                    var item = $("#id_"+id);

                    $.post("../../../controller/news_all_controller.php?action=delete_news", { 'id': id },
                    function(data){
                        if (data!='ok') {
                            alert("Error! while deleting\n\n"+data);
                            $('#id_'+itemId).children().css('background-color', '#F3F3F3');
                        } else{
                            item.hide(500,function(){

                                document.getElementById('newsSortable').removeChild(document.getElementById('id_'+itemId));
                            });
                        }
                    });
    
                    $('#id_'+itemId).children().css('background-color', 'pink');

                }

                var submitRSSForm = false;

                function submitRSS() {
                    var val = document.getElementById('txtRss').value;

                    if(val==''){
                        document.getElementById('rssForm').submit();
                    }

                    if(val.substring(0, 7)!='http://' && val.substring(0, 7)!='') {
                        if (confirm("News feed URL should start with http://. Are you sure want to use this URL?")){
                            submitRSSForm = true;
                            validateRSS();
                        }
                    } else {
                        submitRSSForm = true;
                        validateRSS();
                    }

                    return false;
                }

                function validateRSS() {
                    var url = $("#txtRss").val();
                    if(url!='') {
                        $("#rssResDiv").html("Checking..");
                        $.post("../../../controller/news_all_controller.php?action=validate_rss", { 'url': url },
                        function(data){
                            if (data=='RSS') {
                                var image = "../../../images/wizard_dot_grn.png";
                                var title = "Valid RSS feed"
                                if (submitRSSForm) {
                                    document.getElementById('rssForm').submit();
                                }
                            } else if (data=='ATOM'){
                                var image = "../../../images/wizard_dot_grn.png";
                                var title = "Valid ATOM feed"
                                if (submitRSSForm) {
                                    document.getElementById('rssForm').submit();
                                }
                                //alert (title);
                            } else if (data=='INV'){
                                var image = "../../../images/wizard_dot_red.png";
                                var title = "Invalid RSS feed URL!"
                                alert (title);
                            } else {
                                alert("Error! while checking feed\n\n"+data);
                            }

                            $("#rssResDiv").html("<img src='" + image + "' alt='"+title+"' title='"+title+"'/>");
                            submitRSSForm = false;
                        });
                    }
                }
                //-->
        </script>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
        <script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>

        <script type="text/javascript" src="../../../js/forms/js_news.js"></script>
        <!--calendar-->
        <script src="../../../js/calendar/jscal2.js"></script>
        <script src="../../../js/calendar/en.js"></script>
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/jscal2.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/border-radius.css" />
        <link rel="stylesheet" type="text/css" href="../../../css/calendar/steel/steel.css" />
        <style type="text/css">
            <!--
            #queue > div {


                float: left;
                width: 945px;
                display: none;
                margin-bottom: 1px;
                background-color: #f3f3f3;
            }


            -->
        </style>
    </head>


    <body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png'); validateRSS();">
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
                    <div id="bodyNews">

                        <div id="lightBlueHeader2">

                            <div class="tahoma_14_white" id="newsHeader" style="width: 926px">News Lists Section</div>

                        </div>

                        <div id="titleBoxNewsBox">
                            <div class="tahoma_14_white" id="titleNews">Name</div>
                            <div class="tahoma_14_white" id="dateNews">Date</div>
                            <div class="tahoma_14_white" id="descriptionNews">Description</div>
                        </div>
                        <ul id="newsSortable" class="tahoma_12_blue">
<?php
if (isset($rss_val) && $rss_val != '') {
    echo "<li>News stories section is disabled since you have entered RSS feed URL. Clear RSS url to enable News stories</li>";
} else {
    include('list_news_cms.php');
}
?>
                        </ul>

                    </div>
                    <div id="buttonContainer1">
                        <div id="addMusicBttn2_hide">
                            <div id="addTourButton"><img style="<?php echo isset($rss_val) && $rss_val != '' ? 'display:none' : ''; ?>;cursor: pointer " src="../../../images/addNews1.png" width="141" height="33"  onclick="show_div();" /></div>
                        </div>
                    </div>
                    <div id="buttonContainer"  style="display:none">
                        <form id="form" name="form" method="post">
                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Add News Section</div>

                            </div>
                            <div id="addMusicBttn2">
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Name</div>
                                    <div id="formSinFeild">
                                        <input type="text" class="textFeildBoarder" name="title" id="title" style="padding-left: 1px;width: 225px"   /><?php if (isset($_REQUEST['msg_error'])) {
                                echo $msg_error;
                            } ?>
                                    </div>
                                </div>
                                <div id="formRow">
                                    <div class="tahoma_12_blue" id="formName">Date</div>
                                    <div id="formSinFeild">
                                        <input type="Text" id="date" maxlength="25" size="25" class="textFeildBoarder" style="width:227px; height:21px;" readonly="readonly"><img src="../../../images/cal.gif" id="f_btn1"  onclick="calendar_add();" onMouseOver="calendar_add();" />
                                    </div>
                                </div>
                                <div id="formRowMulti">
                                    <div class="tahoma_12_blue" id="formName">Description</div>
                                    <div id="formMultiFeild">
                                        <textarea class="textFeildBoarder" style="width:227px; height:100px;" name="notes" rows="5" id="notes"></textarea>
                                    </div>
                                </div>
                                <div id="formRowButtons">
                                    <div class="tahoma_12_blue" id="formName"></div>
                                    <div id="formSinFeild">
                                        <input type="hidden" name="count" id="count" value="<?php echo $count; ?>" />
                                        <input type="image" src="../../../images/save.png" name="Login" id="Login"width="69" height="33"/>
                                        <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel" width="99" height="33" onclick="form.reset();"/>
                                    </div>
                                </div>		
                            </div>
                        </form>
                    </div>
                    <div id="buttonContainer">
                        <form  action="../../../controller/news_add_iphone_controller.php"  name="form2" id="rssForm" method="post">

                            <div id="lightBlueHeader2">

                                <div class="tahoma_14_white" style="width: 921px" id="lightBlueHeaderMiddle4">Add News RSS Feed</div>

                            </div>
                            <div style="width:700px">

                                <div class="tahoma_12_blue" id="formName" style="width:200px">Alternative News RSS Feed</div>
                                <div id="formSinFeild"  style="width:400px">
                                    <input name="txtRss" id="txtRss" type="text" class="textFeildBoarder" style="width:227px; height:21px;float: left" value="<?php if (sizeof($rss_list) > 0) {
                                echo $rss_val;
                            } ?>" /><div class="tahoma_12_blue" id="rssResDiv" style="float: left; width: 100px; padding-left: 4px"></div>             <?php //if (sizeof($rss_list)>0){ echo 'readonly="readonly"';} ?>
                                    <input name="txtRssId" type="hidden" style="width:227px; height:21px;" value="<?php if (sizeof($rss_list) > 0) {
                                echo $rss_id;
                            } ?>"  />
                                    <input name="status" type="hidden" style="width:227px; height:21px;" value="RssStatus"/>
                                    <input name="stat" type="hidden" style="width:227px; height:21px;" value="<?php echo $rss_stat; ?>"/>

                                </div>

                                <div id="formRowButtons">

                                    <div id="formSinFeild">

                                        <img src="../../../images/save.png" style="cursor: pointer" name="Login" id="Login" width="69" height="33" onclick="javascript: return submitRSS();"/>

                                    </div>
                                </div>		
                            </div>
                        </form>
                    </div>
                </div>
                <br class="clear"/><br class="clear"/>
            </div>
        </div>
        <br class="clear"/>
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
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
                            "margin":"40px 0 0 887px",
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
                    $(".div_tooltip").css({"margin":"0px 0 0 10px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});
                       
                }
                else if('showLikes'==arr[0]){
                    $("#viewToolTip"+id).show();
                    $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 17px">Likes</div>');
                    $(".div_tooltip").css({"margin":"0px 0 0 50px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                }
                else if('showShare'==arr[0]){
                    $("#viewToolTip"+id).show();
                    $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 12px">Shares</div>');
                    $(".div_tooltip").css({"margin":"0px 0 0 85px",'background':"url('../../../images/small_tooltip.png') no-repeat  top left","width":"59px"});

                }
                else if('showComments'==arr[0]){
                    var comment_count = $("#commentsCount_"+id).val();
                    if(comment_count != 0){
                        $("#tooltip_comment"+id).show();
                        $("#tooltip_comment"+id).html('<div style="padding:30px;padding-top:20px;color: #fff;background:url(../../../images/tooltip_msg.png) no-repeat  top left;height:70px"><img src="../../../images/ajax-loader.gif"/>&nbsp;Loading...</div>');
                        $.post("../../../controller/music_all_controller.php?action=get_comment_summery",{ 'module':'news', 'itemId':id}, 
                        function(data){
                            $("#tooltip_comment"+id).html(data);
                        });
                    
                    }else{
                        $("#viewToolTip"+id).show();
                        $("#viewToolTip"+id).html('<div style="padding: 10px 0 0 8px;">no comments</div>');
                        $(".div_tooltip").css({'margin':'0px 0 0 135px','background':"url('../../../images/small_tooltip2.png') no-repeat  top left","width":"91px"});
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
                        var title = $("#title_"+id).text();
                        var noOfComments = $("#commentsCount_"+id).val();
                        $("#comments_view_full").html('<div style="float: left"><iframe src="../../common/view_comments.php?title='+title+'&itemId='+id+'&module=news&noOfComments='+noOfComments+'" width="520" height="500" frameborder="0" scrolling="no" ></iframe></div><div style="cursor: pointer;float: left;margin: -10px -10px 0 0 "><img src="../../../images/close.png" id="comment_closs"/></div>');
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