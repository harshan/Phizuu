<?php
$menu_item="news";

require_once("../../../config/config.php");
require_once("../../../controller/session_controller.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/news_controller.php');
require_once('../../../model/news_model.php');
require_once('../../../config/error_config.php');


$bnews= new News();
$bank_news = $bnews->listBankNews($_SESSION['user_id']);
$count=1;
$inews= new News();
$iphone_news = $inews->listIphonenews($_SESSION['user_id']);
$icount=1;


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
	cursor: pointer;
	list-style-type:none;
	list-style-image:none;
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

</style>
    <script type="text/javascript">
	<!--
        var GB_ROOT_DIR = "../../../js/greybox/";
		//-->
    </script>
	<script type="text/javascript" src="../../../js/mootools.js"></script>
    <script type="text/javascript" src="../../../js/AJS.js"></script>
    <script type="text/javascript" src="../../../js/AJS_fx.js"></script>
    <script type="text/javascript" src="../../../js/gb_scripts.js"></script>
    <link href="../../../css/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
    
    <!--multi drag-->
    <script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery.js"></script>
<script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery-ui.js"></script>

    <script type="text/javascript" src="../../../js/forms/js_news_list.js"></script>
<script charset="utf-8" id="injection_graph_func" src="../../../js/Select%20and%20drag_files/injection_graph_func.js"></script>

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
          <div id="titleBox">
            <div class="tahoma_14_white" id="title">Name</div>
            <div class="tahoma_14_white" id="duration">Date</div>
            <div class="tahoma_14_white" id="noteNew">Description</div>
          </div>
      
      <div id="textBarMusic1">    
          <ul class="ui-droppable" id="list_1">
          <?php 
	  if(sizeof($bank_news) >0){
	  foreach($bank_news as $bNews){?>
       <li class="ui-draggable" style="cursor: pointer;"  id="list_1_item_<?php echo $bNews->id;?>">
       <table border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td>
          <div id="textBarNew">
            <div class="tahoma_12_blue" id="title_note"><?php echo $bNews->title;?></div>
            <div class="tahoma_14_white" id="duration2"><span class="tahoma_12_blue"><?php echo $bNews->date;?></span></div>
            <div class="tahoma_12_blue" id="note2new"><?php echo $bNews->description;?></div>
            <div class="tahoma_12_blue" id="iconBox">
              
			  <div id="icon"><a href="#"  onclick="return arrow_icon('<?php echo 'list_1_item_'.$bNews->id;?>','list_1','list_2');" id="<?php echo 'list_1_lnk_'.$bNews->id;?>"><img src="../../../images/arrow.png" border="0"  id="<?php echo 'list_1_img_'.$bNews->id;?>" /></a></div>
              <div id="icon"><a href="../../../controller/news_add_iphone_controller.php?id=<?php echo $bNews->id; ?>&status=delete" onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
            </div>
          </div>
              </td>
      </tr>
      </table>
          </li>
<?php 
	  $count++;
	  }
	  }?>
       </ul>
       </div>
        </div>
        <div id="bodyLeft">&nbsp;</div>
        <div id="bodyLeft"><a href="news_new.php"><img src="../../../images/addnewmusic.png" border="0" /></a></div>
      </div>
      <div id="indexBodyRight">
        <div id="bodyRgt">
          <div id="titleBox">
            <div class="tahoma_14_white" id="title">Title</div>
            <div class="tahoma_14_white" id="durationLft">Duration</div>
            <div class="tahoma_14_white" id="noteLft">Notes</div>
          </div>
          
          <div id="textBarMusic1">
          <ul class="ui-droppable" id="list_2">
          <?php 
	   if(sizeof($iphone_news) >0){
	   foreach($iphone_news as $iNews){?>
        <li class="ui-draggable" style="cursor: pointer;" id="list_2_item_<?php echo $iNews->id;?>">
        <table border="0" cellpadding="0" cellspacing="0">
      <tr>
      <td>
          <div id="textBar">
            <div class="tahoma_12_blue" id="title_note"><?php echo $iNews->title;?></div>
            <div class="tahoma_14_white" id="duration2"><span class="tahoma_12_blue"><?php echo $iNews->date;?></span></div>
            <div class="tahoma_12_blue" id="note2new"><?php echo $iNews->description;?></div>
            
            <div class="tahoma_12_blue" id="iconBox">
              
			  <div id="icon"><a href="#"  onclick="return arrow_icon('<?php echo 'list_2_item_'.$iNews->id;?>','list_2','list_1');" id="<?php echo 'list_2_lnk_'.$iNews->id;?>"><img src="../../../images/arrow2.png" border="0"  id="<?php echo 'list_2_img_'.$iNews->id;?>" /></a></div>
              <div id="icon"><a href="../../../controller/news_add_iphone_controller.php?id=<?php echo $iNews->id;?>&status=remove" onclick="return delete_confirm();"><img src="../../../images/cross.png" border="0" /></a></div>
            </div>
            
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
      </ul>
      </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
</body>
</html>