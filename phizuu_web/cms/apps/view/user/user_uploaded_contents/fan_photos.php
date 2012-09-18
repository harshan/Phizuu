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
.tour_row {
    float: left;
    width: 920px;
    height: 25px;
    padding: 4px;
    background-color: #F3F3F3;
    margin-bottom: 2px;
    cursor: pointer;
    border: 1px solid #F3F3F3;
}

.title {
    font-weight: bold;
}

.expand_btn {
    float: right;
    overflow: hidden;
}

.row {
    float: left;
    width: 920px;
}

.tour_details {
    float: left;
    background-color: #F3F3F3;
    display: none;
    width: 898px;
    margin-left: 20px;
    margin-top: -3px;
    margin-bottom: 2px;
    border: 1px solid #CCCCCC;
    border-top: 0;
    padding: 5px;
    overflow: hidden;
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
          <div style="float: left;  width: 182px;height: 33;margin-left: -2px"><img src="../../../images/manage_fan_photos_Ac.png" border="0"/></div>
          <div style="float: left;  width: 182px;height: 33"><a href="../../../controller/modules/user_uploaded_content/UserUploadedContentsController.php?action=fan_wall_view"><img src="../../../images/manage_fan_wall_In.png" border="0"/></a></div>
      </div>
      <div class="bodyRow">
          <div id="lightBlueHeader" style="width: 100%">
              
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 920px">Manage Fan Photos in Events module</div>
              
          </div>
      </div>
        <?php
        $tours = $tours->events;

        foreach ($tours as $tour) {  ?>

      <div id="tour_<?php echo $tour->id ?>" class="row" >
          <div class="tour_row" onclick="javascript: toggle_tour(<?php echo $tour->id ?>);">
              <span class="title"><?php echo $tour->name ?></span> at
              <span class="location"><?php echo $tour->location; ?></span>
              <div class="expand_btn">
                  <img class="expand_img" src="../../../images/expand_tour.png"/>
              </div>
          </div>
          <div class="tour_details">
              
          </div>
      </div>
        <?php } ?>
  </div><br class="clear"/>
  </div>
</div>
        <div style="height: 20px; float: left; width: 920px"></div>
    <br class="clear"/> <br class="clear"/>
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

function toggle_tour(id) {
    var item = $('#tour_'+id);
    var details = item.find('.tour_details');

    if(details.css('display')=='none') {
        details.html("<img src='../../../images/bigrotation2.gif' height=32 width=32/>");
        item.find('.expand_img').attr('src','../../../images/collapse_tour.png');
        $.post('UserUploadedContentsController.php?action=get_fan_photos_ajax', {'id':id}, function(data){
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

</script>
</body>

</html>
