<?php
$menu_item="photos";
?>
<?php
include("../../../config/config.php");
require_once ('../../../database/Dao.php');
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/pic_controller.php');
include('../../../model/pic_model.php');
include('../../../model/UserInfo.php');
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

$userInfo = new UserInfo();
$freeUser = $userInfo->isFreeUser();

$_SESSION['redirect_page_name']='pictures/upload_pics_swf.php';
$_SESSION['request_page_name']='pictures/photos.php';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photos - phizuu CMS</title>

<style type="text/css">
ul {
    list-style-type:none;
    margin:0;
    padding:0;
}

li {
    float:left;
    height:122px;
    margin:3px 3px 3px 0;
    padding:1px;
    text-align:center;
    width:85px;
}


#list_2 .dragHandlePhoto {
    cursor: move;
}

#list_1 .dragHandlePhoto {
    cursor: default;
}

.highlight {
    width: 90px;
    height: 122px;
    background-image: url('../../../images/photoDrop.png');
    background-repeat: no-repeat;

}

.photoAddOver{
    position: absolute;
    width: 27px;
    height: 27px;
    background-repeat: no-repeat;
    z-index: 100;
}

#picCover1 {
    background-image: url('../../../images/photoAddOver2.png');
}

#picCover2 {
    background-image: url('../../../images/photoDeleteIcon.png');
}

#list_2 #icon {
    display: none;
}

#list_2 .photoName {
    width: 75px;
}

#albumSwitchAdd {
    float: left;
    width: 948px;
    padding-bottom: 10px;
}

#albumSwitchAdd a:link, #albumSwitchAdd a:visited{
    width: 250px;
    height: 65px;
    background-image: url('../../../images/switch_to_album.png');
    background-position: 0px 0px;
    float: right;
    margin-right: 27px;
}

#albumSwitchAdd a:hover{
    background-position: 0px -65px;
}
</style>

<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
   

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>

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


function delete_confirm(id) {
    if( confirm("Are you sure to delete this picture?")){
        $('#id_'+id+' div').css('background-color', 'red');
        $("#picCover1").fadeOut(300);
        $.post('../../../controller/pic_add_iphone_controller.php?status=delete&id='+id, function(data) {
            $('#id_'+id).hide(300);
        });
    }

    return false;
}

$(function(){
    if (picCountInBank<=0) {
        $('#addAllButtonLnk').hide();
    }

  $('.edit').editable('../../../controller/photo_all_controller.php?action=edit',{
      indicator : 'Saving...',
      tooltip   : 'Click to edit...',
      rows : 3
  });

  $("#list_2").sortable({
      placeholder: 'highlight',
      handle : '.dragHandlePhoto'
  });

$( "#list_2" ).bind( "sortstart", function(event, ui) {
  $("#picCover2").fadeOut(300);
});


  $("#list_1 li").mouseover(list1Over);

  $("#list_2 li").mouseover(list2Over);


  $("#list_2").mouseover(
    function(){
        $("#picCover1").fadeOut(300);
    });

  $("#list_1").mouseover(
    function(){
        $("#picCover2").fadeOut(300);
    });

   $("#picCover1").click(
    function(){
        if (picCountInList>=picLimit) {
            alert("Sorry! You have reached maximum limit of " + picLimit + " picures. Please remove picures in iPhone list if you want to add new.")
            return false;
        }
        $("#picCover1").hide();
        document.getElementById("list_1").removeChild(lastElem);
        $("#list_2").append(lastElem);
        $(lastElem).unbind();
        $(lastElem).mouseover(list2Over);
        $("#list_2").sortable( "refresh" );
        picCountInList++;
        picCountInBank--;

        if (picCountInBank<=0) {
            $('#addAllButtonLnk').hide();
        }
        updateList();
    });

   $("#picCover2").click(
    function(){
        $("#picCover2").hide();
        document.getElementById("list_2").removeChild(lastElem);
        $(lastElem).unbind();
        $(lastElem).mouseover(list1Over);
        $("#list_1").append(lastElem);
        picCountInList--;
        picCountInBank++;

        if (picCountInBank>0) {
            $('#addAllButtonLnk').show();
        }

        updateList();
    });
      $('#list_2').bind('sortupdate', updateList);
});

    var lastElem;

    function list1Over(){
        $("#picCover1").css('top',$(this).offset().top+5);
        $("#picCover1").css('left',$(this).offset().left+5);
        $("#picCover1").fadeIn(100);
        lastElem = this;
    }

    function list2Over(){
        $("#picCover2").css('top',$(this).offset().top+5);
        $("#picCover2").css('left',$(this).offset().left+5);
        $("#picCover2").fadeIn(100);
        lastElem = this;
    }

    var updateList = function(event, ui) {
          $("#list_2").sortable( 'disable' );
          $("#list_2").css('cursor', 'wait');
          $("#list_2 .dragHandlePhoto").css('cursor', 'wait');
          var order = $('#list_2').sortable('serialize');
          $.post('../../../controller/photo_all_controller.php?action=order&'+order, function(data) {
              $("#list_2").sortable( 'enable' );
              $("#list_2 .dragHandlePhoto").css('cursor', 'move');
              $("#list_2").css('cursor', '');
          });
      }


      var addAllPhotos = function () {
    
        $( "#list_1 li" ).each(
            function() {
                if (picCountInList>=picLimit) {
                    alert("Sorry! You have reached maximum limit of " + picLimit + " picures. Please remove picures in iPhone list if you want to add new.")
                    return false;
                }

                document.getElementById("list_1").removeChild(this);
                $("#list_2").append(this);
                $(this).unbind();
                $(this).mouseover(list2Over);
                picCountInList++;
                picCountInBank--;
            });
            $("#picCover1").hide();
            $("#list_2").sortable( "refresh" );

            if (picCountInBank<=0) {
                $('#addAllButtonLnk').hide();
            }
            updateList();
      }

var picLimit = <?php echo $limit_count->photo_limit ?>;
var picCountInList = <?php echo count($iphone_pic) ?>;
var picCountInBank = <?php echo count($bank_pic) ?>;


</script>
</head>
	

<body>
    <div id="header">
        <div id="headerContent">
            <?php include("../common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
<div id="mainWideDiv">
  <div id="middleDiv2">
  	
	<?php include("../common/navigator.php");?>
	<div id="bodyPhotos">
            <?php if (!$freeUser) { ?>
            <div id="albumSwitchAdd"><a href="switch_mode.php" ></a></div>
            <?php } ?>
    <?php if(isset($_REQUEST['msg'])){?>
      <div id="photoHolderBox"  class="tahoma_12_ash"><span style="color:#FF0066">Please insert a default accout for Flickr</span><br /><a href="../settings/settings_new.php">Click here</a></div>
    <?php }?>
	  <div id="bodyLeftPhotos">
                                                            <div id="lightBlueHeader">
           
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 425px">Bank Photos</div>
           
          </div>


              <ul  id="list_1" class="connected">

                  <?php
                  $pics = $bank_pic;
                  $bankList = true;
                  
                  include("pic_list.php");

                  ?>
              </ul>

              <div id="photoHolderBox" style="padding-bottom: 20px">
                  <div id="addMusicBttn"><a href="#" onclick="javascript: return addAllPhotos();" id="addAllButtonLnk"><img src="../../../images/btn_add_all.png" width="99" height="33" border="0" /></a></div>
		</div>
              <br/><br/>
        <div id="photoHolderBox">
			<div id="addMusicBttn"><a href="photos_add.php"><img src="../../../images/addPhotos.png" width="157" height="33" border="0" /></a></div>
		</div>
	  </div>
	  <div id="bodyMusicRgt">

                                                            <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 425px">iPhone Photos</div>
           
          </div>
              <ul  id="list_2" class="connected">

                                        <?php
                                        $pics = $iphone_pic;
                                        $bankList = false;

                                        include("pic_list.php");
                                        ?>
              </ul>
	  </div>
	</div><br class="clear"/>
	
  </div>	<br class="clear"/>
</div><br class="clear"/>
      <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    <div id="picCover1" class="photoAddOver" style="display: none"></div>
    <div id="picCover2" class="photoAddOver" style="display: none"></div>
    <div id="cover"></div>
</body>
</html>