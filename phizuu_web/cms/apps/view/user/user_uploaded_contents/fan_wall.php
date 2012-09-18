<?php
require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');



$menu_item = 'fan_contents';



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>phizuu - Links</title>
<style type="text/css">
.post_row {
    float: left;
    width: 920px;
    padding: 4px;
    background-color: #F3F3F3;
    margin-bottom: 6px;
    cursor: pointer;
    border: 1px solid #CCCCCC;
}

.reply_row {
    float: left;
    width: 875px;
    padding: 4px;
    background-color: #F3F3F3;
    margin-bottom: 6px;
    cursor: pointer;
    border: 1px solid #CCCCCC;
}

.comment {
    float: left;
    width: 920px;
}

.expand_btn {
    float: right;
    overflow: hidden;
}

.row {
    float: left;
    width: 920px;
}

.comment_div {
    float: left;
    background-color: #f2f0f1;
    display: none;
    width: 882px;
    margin-left: 20px;
    margin-top: -3px;
    margin-bottom: 2px;
    border-top: 0;
    padding: 5px;
    overflow: hidden;
    float: left;
}

.tour_row_expanded {
    border: 1px solid #CCCCCC;
}

.photo_container {
    float: left;
    width: 75px;
    padding: 5px;
}

.photo_container a:visited, .photo_container a:link{
    color: #07738A;
    text-decoration: none;
}

.photo_container a:hover{
    color: #08A6E0;
    text-decoration: underline;
}

.image_container {
    float: left;
    width: 75px;
    height: 75px;
}

.post_info {
    font-size: 10px;
    float: left;
    padding-bottom: 5px;
    width: 890px;
}

#postContainerDiv {
    width: 948px;
    float: left;
    overflow: hidden;
    padding-top: 2px;
}

.load_more {
    font-size: 14px;
    text-align: center;
}

.post_info a:visited, .post_info a:link{
    color: #07738A;
    text-decoration: underline;
}

.post_info a:hover{
    color: #08A6E0;
    text-decoration: none;
}
</style>

<link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>

</head>

    <body class="tahoma_12_blue">
         <div id="header">
        <div id="headerContent">
           <?php include("../../../view/user/common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
<div id="mainWideDiv">
  <div id="middleDiv2">
        
        <?php include("../../../view/user/common/navigator.php");?>
<div id="body">
      <div style="height: 20px; float: left; width: 920px"></div>
      <div style="height: 32px; float: left; width: 920px;margin-bottom: 10px">
          <div style="float: left; width: 182px;height: 33;"><a href="../../../controller/modules/user_uploaded_content/UserUploadedContentsController.php?action=main_view"><img src="../../../images/manage_fan_photos_In.png" border="0"/></a></div>
          <div style="float: left; width: 182px;height: 33;"><img src="../../../images/manage_fan_wall_Ac.png" border="0"/></div>
      </div>
      <div class="bodyRow">
          <div id="lightBlueHeader" style="width: 100%">
              
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 920px">Manage Fan Wall Posts/Replies</div>
           
          </div>
      </div>
      <div id="postContainerDiv">
            <?php include 'fan_wall_post_list.php'; ?>
      </div>
      <div style="height: 20px; float: left; width: 920px" id="scrollDetector"></div>
  </div> <br class="clear"/> <br class="clear"/>
  </div>
</div>
        <br class="clear"/> 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
        


<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>

<script type="text/javascript">
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

function toggle_comments(id) {
    var item = $('#post_'+id);
    var details = item.find('.comment_div');

    if(details.css('display')=='none') {
        details.html("<img src='../../../images/bigrotation2.gif' height=20 width=20 align='top'/> Loading Comments..");
        item.find('.expand_img').attr('src','../../../images/collapse_tour.png');
        $.post('UserUploadedContentsController.php?action=get_replies_ajax', {'comment_id':id}, function(data){
            details.html(data);
        });
        
        
        details.slideDown(300,
            function() {
                item.find('.tour_row').addClass('tour_row_expanded');
            }
        );
        
    } else {
        item.find('.expand_img').attr('src','../../../images/expand_tour.png');
        details.slideUp(300,
            function() {
                item.find('.tour_row').removeClass('tour_row_expanded');
            }
        );
    }
}

function loadMore(nextId, elem) {
    $(elem).html("<img src='../../../images/bigrotation2.gif' height=20 width=20/>");
    var elemToRemove = $(elem).parents('.load_more');
    $.post('UserUploadedContentsController.php?action=get_wall_posts_ajax', {'next_id':nextId}, function(data){
        elemToRemove.remove();
        $('#postContainerDiv').append(data);
        loadingInProgress = false;
    });
}

function deleteImage(tourId, photoId,link) {
    if(confirm('After deleting the file it is unrecoverable.\n\nAre you sure you want to delete this file?')) {
        var imageItem = $(link).parents('.photo_container');
        imageItem.css('background-color','pink');
        $.post('UserUploadedContentsController.php?action=delete_fan_photo_ajax', {'tour_id':tourId, 'photo_id':photoId}, function(data){
            imageItem.hide(300);
        });
    }
    return false;
}

function deleteComment(commentId, link, reply_to) {
    if(confirm('After deleting the post it is unrecoverable.\n\nAre you sure you want to delete this post?')) {
        var postItem = $('#post_' + commentId);
        postItem.children().css('background-color','pink');
        $.post('UserUploadedContentsController.php?action=delete_comment_ajax', {'comment_id':commentId}, function(data){
            if(data.success) {
                postItem.hide(300);
                if(reply_to) {
                    var parentItem = $('#post_' + reply_to);
                    if(parentItem.find('.rpl_count b').html()==1) {
                        parentItem.find('.rpl_count').html('No replies');
                        parentItem.find('.expand_btn').hide(300);
                        parentItem.find('.comment_div').hide();
                    } else {
                        parentItem.find('.rpl_count b').html(parentItem.find('.rpl_count b').html()-1);
                    }
                }
            } else {
                alert('Error occured while deleting post!');
                postItem.css('border','0px pink solid');
            }
        },'json');
    }
    return false;
}

$(window).scroll(function () {
    if ($('.load_more').html()==null) {
        return;
    }
    var viewportHeight = $(window).height();
    var itemFromTop = $(window).scrollTop()+viewportHeight - $('#scrollDetector').offset().top;

    if (itemFromTop>0 && loadingInProgress==false) {
        loadingInProgress = true;
        loadMore($('.load_more .next_id').html(),$('.load_more .post_row'));
    }
});

var loadingInProgress = false;
</script>
</body>

</html>
