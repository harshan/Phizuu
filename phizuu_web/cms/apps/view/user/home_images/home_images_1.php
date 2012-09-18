<?php 
require_once "../../../controller/home_image_controller.php";
@session_start();
include "ThumbNail.php";

$menu_item="news";



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Phizuu Application</title>

<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />



<!--Image upload support files-->
<?php if(isset($_POST['upload'])){ ?>
<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<?php } ?>
<script type="text/javascript" src="../../../js/jquery-1.4.min.js"></script>
<script type="text/javascript" src="../../../js/crop/jquery-pack.js"></script>
	<script src="../../../js/sort/jquery-1.7.2.js"></script>
<script type="text/javascript" src="../../../js/crop/jquery.imgareaselect-0.3.min.js"></script>


<!--sort order-->
<link rel="stylesheet" href="../../../js/sort/jquery.ui.all.css">

<!--	<script src="../../../js/sort/jquery.ui.core.js"></script>-->
	<script src="../../../js/sort/jquery.ui.widget.js"></script>
	<script src="../../../js/sort/jquery.ui.mouse.js"></script>
       
<!--	<script src="../../../js/sort/jquery.ui.sortable.js"></script>-->
        <?php if(!isset($_POST['upload'])){ ?>
        <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
        <?php } ?>
        <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
<!--<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>-->

	<link rel="stylesheet" href="../../../js/sort/demos.css">

<style>
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
	#sortable li { margin: 0 1px 1px 1px; padding: 0.1em; padding-left: 1.5em; font-size: 1.4em; height: 74px;width: 500px }
	#sortable li span { position: absolute; margin-left: -1.3em; }
	</style>
	<script>
//	$(function() {
//		$( "#sortable" ).sortable();
//		$( "#sortable" ).disableSelection();
//	});
        
       

        </script>

</head>
<?php 

//get no of recoeds
$homeImageController = new home_image_controller();
$homeImageArr = $homeImageController->getAllHomeImagesByAppId($_SESSION['app_id']);
if(isset($homeImageArr)){
    $noofRecodes = count($homeImageArr);         
}
$folderName = $_SESSION['app_id'];
$upload_dir = "../../../../../static_files/$folderName/images/home_images/";
$upload_dir_temp = "../../../temporary_files/home_image/";

$upload_dir_thumb = "../../../../../static_files/$folderName/images/home_thumb_images/";
if (!is_dir($upload_dir)){
    mkdir($upload_dir,0777);
}
if (!is_dir($upload_dir_thumb)){
    mkdir($upload_dir_thumb,0777);
}
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $callbackURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_images/";
    $callbackThumbURL = "http://localhost/phizuu_web/static_files/$folderName/images/home_thumb_images/";
} else {
    $callbackURL = "http://phizuu.com/static_files/$folderName/images/home_images/";
    $callbackThumbURL = "http://phizuu.com/static_files/$folderName/images/home_thumb_images/";
}
if (isset($_POST['upload'])) {
    
    
    
    $showStatus = 1;
    list($width, $height, $type, $attr) = @getimagesize($_FILES['fileUpload']['tmp_name']);

    $error = FALSE;

    echo $noOfRec = $homeImageController->getNoOfRerodes();
    

    if ($_FILES['fileUpload']['size'] > 1048576) {
        $message = ("Your picture size is too large (Max 1MB) ");
    } elseif ($_FILES['fileUpload']['size'] == 0) {
        $message = ("Upload file can't be empty or file size is too large!");
    } elseif
    (!$type || $type == 4 || $type == 5) {
        $message = ('The specified file is not a picture! (Only .jpg, .png, .gif )');
    } elseif ($width >1200 || $height>1000 ){
        $message = ('exceeded image dimension limits (Max 1200 X 1000)');
    } elseif($width <640 || $height<734){
        $message = ('Too small image dimension size (Min 640 X 734)');
    }elseif($noOfRec > 4) {
         $message = ('You can upload only 5 imagers');
    }else{

        $time = date("dmyHis", time());
        $fileName = $time . GetExtension($_FILES["fileUpload"]["name"]);
        move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $upload_dir_temp . $fileName );
        $uploadStatus = true;
    }
}

function GetExtension($extFile) 
{
$extention = substr($extFile, strrpos($extFile, '.'));
return $extention;
}
function GetFileName($path){
    $path_parts = pathinfo($path);

    $exe =  $path_parts['extension'];
    $file =  $path_parts['filename']; 
    return $imageName = $file.'.'.$exe;
}
if(isset($_POST['upload_thumbnail'])){
    $fileName = $_REQUEST['fileName'];
}
if(isset($uploadStatus) || isset($_POST['upload_thumbnail'])){
//**********************Crop***************************************************
//$fileName = $_FILES["fileUpload"]["name"];
//$upload_dir = "upload_pic"; 				// The directory for the images to be saved in
$upload_path = $upload_dir_temp."";				// The path to where the image will be saved
$large_image_name = $fileName; 		// New name of the large image
$thumb_image_name = $fileName; 	// New name of the thumbnail image
$max_file = "1048576"; 						// Approx 1MB
$max_width = "100";						// Max width allowed for the large image
$thumb_width = "640";
$thumb_height ="734";					// Height of thumbnail image
//Small thumb details
$small_thumb_name = $fileName;
$small_thumb_width = "50";
$small_thumb_height = "100";
//$small_thumb_image_name = $upload_dir_thumb.$fileName;

$exe = "";
//Image functions
//You do not need to alter these functions
function resizeImage($image,$width,$height,$scale) {
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	$source = imagecreatefromjpeg($image);
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	imagejpeg($newImage,$image,90);
	chmod($image, 0777);
	return $image;
}

$exe = GetExtension($fileName);


function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        $exe = GetExtension($image);
        if($exe == ".png")
        {
            $source = imagecreatefrompng($image);
        }else if($exe == ".gif")
        {
            $source = imagecreatefromgif($image);
        }else if($exe == ".jpeg" || $exe == ".jpg")
        {
            
            $source = imagecreatefromjpeg($image);
        }

	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	imagejpeg($newImage,$thumb_image_name,90);
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$sizes = getimagesize($image);
	$height = $sizes[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$sizes = getimagesize($image);
	$width = $sizes[0];
	return $width;
}

//Image Locations
$large_image_location = $upload_path.$large_image_name;
$thumb_image_location = $upload_path.$thumb_image_name;

//Create the upload directory with the right permissions if it doesn't exist
if(!is_dir($upload_dir)){
	mkdir($upload_dir, 0777);
	chmod($upload_dir, 0777);
}

//Check to see if any images with the same names already exist
$large_photo_exists = "<img src=\"".$upload_path.$large_image_name."\" alt=\"Large Image\"/>";


}
//Save thumb image

if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	//Scale the image to the thumb_width set above a
	$scale = $thumb_width/$w;
        $time=date("dmyHis", time());
        $imageName= "thumb".$time.$exe;
        if(isset ($_SESSION['$fileName']))
        {
            $thumb_image_location = $upload_dir.$imageName;
        }
       
        $cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
       
       

        
        if(!@copy($upload_path.$fileName,$upload_dir.$fileName))
        {
            
        } else {
            copy($upload_dir.$fileName,$upload_dir_thumb.$fileName);
            $thumbNail = new ThumbNail();
            $thumbNail->create_abs_image($fileName,$fileName, 64,73.4, $upload_dir_thumb);
            unlink($upload_path.$fileName);
            
        }
        //unlink($imageName);
        $homeImageController = new home_image_controller();
        //assing  data to array
        $home_image_arr = array(
            "app_id" => $_SESSION['app_id'],
            "image_url" => $callbackURL.$fileName,
            "image_url_thumb" => $callbackThumbURL.$fileName,
            "hot_spot_type" => 0,
            "module_name" => "",
            "link_url" => "",
            "top" => 0,
            "left" => 0,
            "width" => 0,
            "height" => 0,
            "order_no" => $noofRecodes+1
            
        );
        
        $homeImageController->addHomeImage($home_image_arr);
	header('location:home_images.php');
	
        
	
}
?>    
<script language="javascript" type="text/javascript">


</script>

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png'); validateRSS();">
<div id="mainWideDiv">
  <div id="middleDiv2">
  	  	<?php include("../common/header.php");?>
		<?php  include("../common/navigator.php");?>
      <div id="bodyPhotos">
           <div id="lightBlueHeader">
                       <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
                       <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 410px">Home Images</div>
                       <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
           </div>
          
          <div id="photoHolderBox" style="height: 15px"></div>
          
<!--          <div id="lightBlueHeader">
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 408px">Upload Pictures</div>
            <div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
          </div>-->
          
           <div id="photoHolderBox" class="uploadSectionDiv" >
                    
                   <?php // if(isset($noofRecodes)){
                   //    if($noofRecodes < 5){ 
                   // ?>     
                   <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                    <div id="UploadYourOwnImageInput"><input type="file" name="fileUpload" id="fileUpload"/>
                        <input type="submit"  name="upload" value="Upload" onclick="submitform()" class="button"/></div>
                   
                   
                </form>
               <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px">Select files less than 1MB</div>
               <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px;color: red">
                        <?php // }} ?>

                        
                        <?php if(isset($message)){
                            echo $message;
                        } ?>
                    </div>
                        <div id="errorLog" style="margin: 0; display: none">
                            <div class="fieldset flash" style="overflow:hidden; margin: 0; padding: 5px; width: 452px">
                                <ul id="errorLogList">
                                </ul>
                            </div>
                        </div>

         <ul id="sortable">
          <?php 
          
          foreach ($homeImageArr as $value){
              $filename1 = GetFileName($value['image_url'])
          ?>               
	<li class="ui-state-default" id='<?php echo 'id_'.$value['id'];?>'>
            <div class="dragHandle" style="cursor: move;float: left"></div>
            <div class="ui-icon ui-icon-arrowthick-2-n-s" style="float: left"></div>
            <span><img src="<?php echo $value['image_url_thumb']?>"/>
                
            </span>
            <div><input type="hidden" id="<?php echo 'imagePath'.$value['id']; ?>" value="<?php  echo  $upload_dir.$filename1; ?>"/>
                <input type="hidden" id="<?php echo 'imagePathThumb'.$value['id']; ?>" value="<?php  echo  $upload_dir_thumb.$filename1; ?>"/></div>
            <span style="float: right;padding-left: 425px;padding-top: 25px;cursor: pointer"><img src="../../../images/cross.png" onclick="deleteItem(<?php echo $value['id']; ?>)"/></span>
        </li>
            <?php } ?>
         </ul>   
               
        <?php if(isset($fileName)){?> 
                        <div>
  <?php
//Only display the javacript if an image has been uploaded
if(strlen($large_photo_exists)>0){
	$current_large_image_width = getWidth($large_image_location);
	$current_large_image_height = getHeight($large_image_location);?>
<script type="text/javascript">
function preview(img, selection) { 
        
	var scaleX = <?php echo $thumb_width;?> / selection.width; 
	var scaleY = <?php echo $thumb_height;?> / selection.height; 
	
	$('#thumbnail + div > img').css({ 
		width: Math.round(scaleX * <?php echo $current_large_image_width;?>) + 'px', 
		height: Math.round(scaleY * <?php echo $current_large_image_height;?>) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
} 

$(document).ready(function () { 
        $('#thumbnail').imgAreaSelect({  minWidth: 640, minHeight: 734, handles: true });
        $('#thumbnail').imgAreaSelect({ x1: 0, y1: 0, x2: 640, y2: 734 });
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
		    }else{
                document.getElementById("val").value="true";
            }
	});
}); 


$(window).load(function () { 
	$('#thumbnail').imgAreaSelect({ aspectRatio: '640:734', onSelectChange: preview }); 
});

</script>
<?php 

}?>
          
                            <div style="clear: both; padding-top: 10px">
                   <form name="thumbnail" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" >
				<input type="hidden" name="x1" value="0" id="x1" />
				<input type="hidden" name="y1" value="0" id="y1" />
				<input type="hidden" name="x2" value="640" id="x2" />
				<input type="hidden" name="y2" value="734" id="y2" />
				<input type="hidden" name="w" value="640" id="w" />
				<input type="hidden" name="h" value="734" id="h" />
                                <input type="hidden" name="val" id="val" />
                                <input type="hidden" name="fileName" id="fileName" value="<?php echo $fileName?>" />
                                <input type="submit" name="upload_thumbnail" value="Crop Image" id="save_thumb" class="button" />
			</form>
                        <img src="<?php echo $upload_dir_temp.$fileName;?>" id="thumbnail" alt="Create Thumbnail" style="float: left;"/>
			<div style=" position:relative; overflow:hidden; float: left; width:640px; height:734px;display: none" >
                            <img src="<?php echo $upload_dir_temp.$fileName;?>" style="position: relative; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;"  alt="Thumbnail Preview"/>
			</div>
			
			
</div>     
                        </div>
                        <?php } ?>
        </div>
<div>

</div>
      </div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
</body>
    
</html>

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
//oder set using this code
$(document).ready(function() {
        $("#sortable").sortable({handle : '.dragHandle'});

        $('#sortable').bind('sortupdate', function(event, ui) {
            $("#sortable").sortable( 'disable' );
            $("#sortable").css('cursor', 'wait');
            $("#sortable .dragHandle").css('cursor', 'wait');
            var order = $('#sortable').sortable('serialize');
            $.post('../../../controller/modules/home_image/home_image_controller.php?action=order&'+order, function(data) {
                $("#sortable").sortable( 'enable' );
                $("#sortable").css('cursor', '');
                $("#sortable .dragHandle").css('cursor', 'move');
            });
        });

        //refreshEdits();
    });
    showUploader();
 function showUploader(){
    $.post("../../../controller/modules/home_image/home_image_controller.php?action=noOfRecoeds", function(data){
        if(data=='2'){
            alert(data);
        }
        //alert(data);
    });
 } 
 function deleteItem(id) {
        //$("#newsSortable").
        var itemId = id;
        var item = $("#id_"+id);
        var imagePath = document.getElementById('imagePath'+id).value;
        var imagePathThumb = document.getElementById('imagePathThumb'+id ).value;
        $.post("../../../controller/modules/home_image/home_image_controller.php?action=delete_image&imagePath="+imagePath+"&imagePathThumb="+imagePathThumb, { 'id': id },
        
        function(data){
            document.getElementById('sortable').removeChild(document.getElementById('id_'+itemId));
            if (data!='ok') {
                alert("Error! while deleting\n\n"+data);
                $('#id_'+itemId).children().css('background-color', '#F3F3F3');
            } else{
                item.hide(500,function(){
                    document.getElementById('sortable').removeChild(document.getElementById('id_'+itemId));
                    
                });
            }
        });
        
        $('#id_'+itemId).children().css('background-color', 'pink');

    }

</script>
