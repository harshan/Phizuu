<?php
require_once ("../../../controller/session_controller.php");
require_once("../../../config/config.php");
require_once '../../../config/database.php';
require_once('../../../controller/db_connect.php');
require_once('../../../controller/helper.php');
require_once('../../../controller/tours_controller.php');
require_once('../../../model/tours_model.php');
require_once('../../../config/error_config.php');
require_once '../../../model/settings_model.php';

$menu_item="tours";

$settingModel = new SettingsModel();
$settings = $settingModel->listSettings($_ENV['myspace_url']);
if (count($settings)>0)
    $url = $settings[count($settings)-1]->value;
else
    $url = '';

$tour = new ToursModel();
$defaultImageArr = $tour->getDefaultImage($_SESSION['user_id']);
$defaultImage = $defaultImageArr[1];
$defaultImage2 = $defaultImageArr[0]
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

//-->
</script>


<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>

 	<!--calendar-->
	<script src="../../../js/calendar/jscal2.js"></script>
    <script src="../../../js/calendar/en.js"></script>

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
	

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
<div id="mainWideDiv">
  <div id="middleDiv">
  	  	<?php include("../common/header.php");?>
	<?php include("../common/navigator.php");?>

<?php if(isset ($_GET['error'])) { ?>
      <div class="tahoma_12_blue_error_bold" style ="padding-top: 4px; float: left">Error! Couldn't retrieve any Tours from the given URL. No existing events were deleted.</div>
<?php } ?>
	<div id="bodyNews">
	  
	  	<div id="titleBoxNewsBox">
		  <div class="tahoma_14_white" id="titleTours">Name</div>
		  <div class="tahoma_14_white" id="dateTours">Date</div>
		  <div class="tahoma_14_white" id="locationTours">Location</div>
		  <div class="tahoma_14_white" id="descriptionTours">Description</div>
                  <div class="tahoma_14_white" id="titleTickeURL">Ticket URL</div>
                  <div class="tahoma_14_white" id="titleThumbImg">Thumb</div>

		</div>
            <ul id="tourSortable" class="tahoma_12_blue">
            <?php include('list_tours1.php'); ?>
            </ul>



	 
    </div>
    <div id="buttonContainer1">
     <div id="addMusicBttn2_hide">
	  	<div id="addTourButton"><img src="../../../images/addNewTours.png" width="183" height="25" onclick="show_div();" /></div>
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
          <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
          <input type="image" src="../../../images/save.png" name="Login" id="Login"width="83" height="25"/>
          <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="83" height="25" onclick="form.reset();"/>
          </div>
		</div>		
	  </div>
       </form>


    </div>
            <div id="lightBlueHeader2">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png"></div>
	  	<div id="lightBlueHeaderMiddle2" class="tahoma_14_white">Add Tours From MySpace</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png"></div>
	  </div>

      <div class="tahoma_12_blue">MySpace Automatic Feed is Disabled For Maintenence</div>
<!--
      <form action="../../../controller/tours_all_controller.php?action=fetch_myspace_tours" method="post" onsubmit="javascript: return mySpaceTours();">
		<div style="width: 800px">
		  <div class="tahoma_12_blue" id="formName" style="width:200px">Link to My Space 'All Shows' Page</div>
		  <div id="formSinFeild" style="width:400px;">
                      <input value="<?php echo $url ?>" name="mySpaceURL" id="mySpaceURL" type="text" class="textFeildBoarder" style="width:300px; height:21px;"/> <input name="btnRss" type="image" src ="../../../images/btn_update.png" align="middle"/>
		  </div>
		</div>

            </form> -->


 <div id="bodyLeft">&nbsp;</div>
         <div id="bodyLeft">
          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle">Tours Default Image</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>

             <div>
                 <img alt="No Image" id="coverImage" src="<?php echo $defaultImage2==''?'': $defaultImage2;?>" height="200"></img>

             </div>
             <div style="padding-top:20px;">

                     <img src="../../../images/change.png" style="cursor: pointer" onclick="showMusicChooseWindow(); "></img>
             </div>




        </div>

	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>	
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
          <input type="hidden" name="count" id="count" value="<?php echo $count;?>" />
          <input type="image" src="../../../images/save.png" name="Login" id="Login"width="83" height="25"/>
          <input type="image" src="../../../images/cancel.png" name="Cancel" id="Cancel"width="83" height="25" onclick="hidePic(); return false;"/>
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