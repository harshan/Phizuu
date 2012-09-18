<?php
$menu_item="photos";
?>
<?php
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../config/error_config.php');
require_once('../../../controller/flickr_controller.php');
include('../../../controller/limit_files_controller.php');
include('../../../model/limit_files_model.php');
$limitFiles= new LimitFiles();
$limit_count=$limitFiles->getLimit($_SESSION['user_id'],'picture');

$bpic= new Picture();
$bank_pic = $bpic->listBankPics($_SESSION['user_id']);
$count=1;
$ipic= new Picture();
$iphone_pic = $ipic->listIphonePics($_SESSION['user_id']);
$icount=1;


$_SESSION['redirect_page_name']='pictures/upload_pics_swf.php';
$_SESSION['request_page_name']='pictures/photos.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>

<style type="text/css">

.panel {float:left;width:500px;margin:0px;}

ul {
    list-style-type:none;
    background:#fff;
    padding:0px;
    min-height:150px;
    width:500px;
}

li {
   display:inline-block;
	padding:0px;
    position:relative;
}

.dds_selected {
    background:#fff;
	opacity:0.5;
	display:inline-block;

	}
	
.dds_ghost {
    opacity:0.5;
	display:inline-block;
	
}
.dds_move {
	float:left;
	background:#ccc;
	width:75px;
	height:75px;
	display:inline;
	
	
}
.dds_hover {
    background:#ccc;
    border:0px dashed #c96;
	display:inline-block;
	

}

.holder {
    border:0px dashed #333;
    background:#fff;
	display:inline;
}

.highlight {
    width: 464px;
    height: 64px;
    background-image: url('../../../images/drop.png');
}
</style>

    <script type="text/javascript">
        var GB_ROOT_DIR = "../../../js/greybox/";
    </script>
	<script type="text/javascript" src="../../../js/mootools.js"></script>
    <script type="text/javascript" src="../../../js/AJS.js"></script>
    <script type="text/javascript" src="../../../js/AJS_fx.js"></script>
    <script type="text/javascript" src="../../../js/gb_scripts.js"></script>
    <link href="../../../css/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
    
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
   
    <!--multi drag-->
    <script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery.js"></script>
    <script type="text/javascript" src="../../../js/Select%20and%20drag_files/jquery-ui.js"></script>
    <script type="text/JavaScript">
    var limit=<?php echo $limit_count ->photo_limit;?>;
    </script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>

    <script type="text/javascript" src="../../../js/forms/js_photos.js"></script>
    <script charset="utf-8" id="injection_graph_func" src="../../../js/Select%20and%20drag_files/injection_graph_func.js"></script>
<script type="text/JavaScript">

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



</script>
</head>
	

<body>
<div id="mainWideDiv">
  <div id="middleDiv">
  	<?php include("../common/header.php");?>
	<?php include("../common/navigator.php");?>
	<div id="bodyPhotos">
            <div id="div_error"></div>
    <?php if(isset($_REQUEST['msg'])){?>
      <div id="photoHolderBox"  class="tahoma_12_ash"><span style="color:#FF0066">Please insert a default accout for Flickr</span><br /><a href="../settings/settings_new.php">Click here</a></div>
    <?php }?>
	  <div id="bodyLeftPhotos">
                                                            <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Bank Photos</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>


		<?php  include("pic_bank_list.php");?>
        <div id="photoHolderBox">
			<div id="addMusicBttn"><a href="photos_add.php"><img src="../../../images/addPhotos.png" width="183" height="25" border="0" /></a></div>
		</div>
	  </div>
	  <div id="bodyMusicRgt">

                                                            <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">iPhone Photos</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>
	  	<?php  include("pic_iphone_list.php");?>
	  </div>
	</div>
	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>	
</div>
<script type="text/javascript">
GB_myShow = function(caption, url, /* optional */ height, width, callback_fn) {

    var options = {
        caption: caption,
        height: height || 500,
        width: width || 500,
        fullscreen: false,
        show_loading: false,
        callback_fn: callback_fn
    }
    var win = new GB_Window(options);
    return win.show(url);
}
</script>
</body>
</html>