<?php
require_once "../../../config/app_key_values.php";
require "../../../../../facebook-php-sdk-6c82b3f/src/facebook.php";

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
include('../../../model/Album.php');
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

$albumObj = new Album($_SESSION['user_id']);
$albums = $albumObj->listAlbums();

$limits = $userInfo->getLimits();

if($_SERVER["SERVER_NAME"]==app_key_values::$LIVE_SERVER_DOMAIN){
    $appId = app_key_values::$APP_ID_LIVE ;
    $secretKey = app_key_values::$SECRET_KEY_LIVE;
}elseif($_SERVER["SERVER_NAME"]==app_key_values::$TEST_SERVER_DOMAIN){
    $appId = app_key_values::$APP_ID_TEST ;
    $secretKey = app_key_values::$SECRET_KEY_TEST;
}else{
    $appId = app_key_values::$APP_ID_LOCALHOST ;
    $secretKey = app_key_values::$SECRET_KEY_LOCALHOST;
}
    

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Photos - phizuu CMS</title>

<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link href="../../../css/swf_up/default_new.css" rel="stylesheet" type="text/css" />
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

<style type="text/css">

.albumImageWrapper {
    width: 115px;
    height: 160px;
    background-image: url('../../../images/album.png');
    border: 1px solid #f4f3f1;
}

.albumImageWrapper .image{
    width: 94px;
    height: 94px;
    border: 0;
    float: left;
    padding-left: 10px;
    padding-top: 10px;
}

.albumImageWrapper .description{
    width: 82px;
    height: 36px;
    float: left;
    padding-left: 5px;
    padding-top: 6.albumImageWrapper .image.albumImageWrapper .imagepx;
    font-family: Tahoma;
    font-size: 11px;
    color: #01f1f1f;
    overflow: hidden;
}

.albumImageWrapper .icons{
    width: 60px;
    height: 16px;
    float: left;
    padding-top: 0px;
    padding-left: 0px;
}

.albumImageWrapper .clickable{
    width: 16px;
    height: 16px;
    float: left;
    cursor: pointer;
}

.albumImageWrapper .iconLeft{
    background: url('../../../images/img_icon_delete.png');
    margin-left: 2px;
    width: 16px;
}

.albumImageWrapper .iconMiddle{
    background: url('../../../images/img_icon_add.png');
    margin-left: 3px;
}

.albumImageWrapper .iconRight {
    margin-left: 3px;
}

#list_1 .albumImageWrapper .iconRight{
    background: url('../../../images/album_pick_move_right.png');
}

#list_2 .albumImageWrapper .iconRight{
    background: url('../../../images/album_pick_move_left.png');
}


#list_1, #list_2, #list_3, #list_4, #list_5 {
    list-style-type:none;
    margin:0;
    padding:0;
    width: 450px;
    float: left;
    padding-top: 1px;
}

#list_1 li, #list_2 li, #list_4 li, #list_5 li {
    float:left;
    margin:3px 3px 3px 0;
    padding-bottom:4px;
    padding-right: 18px;
    text-align:center;
    width:91px;
    height: 153px;
}

#list_3 li{
    float:left;
    padding-bottom:12px;
    padding-right: 12px;
    text-align:center;
    width:99px;
    height: 104px;
}

.albumWrapper {
    width: 99px;
    height: 104px;
    background-image: url('../../../images/album_view.png');
}

.albumWrapper .image{
    width: 95px;
    height: 50px;
    border: 0;
    float: left;
    padding-left: 2px;
    padding-top: 4px;
}

.albumWrapper .description{
    width: 95px;
    height: 18px;
    float: left;
    padding-left: 2px;
    padding-top: 2px;
    font-family: Tahoma;
    font-size: 11px;
    color: #333333;
    word-wrap: break-word;
}

.albumWrapper .icons{
    width: 99px;
    height: 16px;
    float: left;
    padding-top: 8px;
}

.albumWrapper .clickable{
    width: 16px;
    height: 16px;
    float: left;
    cursor: pointer;
}

.albumWrapper .iconLeft{
    background: url('../../../images/album_del_icon.png');
    margin-left: 5px;
    width: 22px;
}

.albumWrapper .iconLeftNone{
    margin-left: 5px;
}

.albumWrapper .iconMiddle{
    background: url('../../../images/album_open.png');
    margin-left: 10px;
    width: 20px;
}

.albumWrapper .iconRight {
    width: 14px;
    background: url('../../../images/file.png');
    margin-left: 16px
}

.albumImageWrapper .addIcon {
/*    margin-left: 18px;*/
    background: url('../../../images/icon_add.png');
    width: 17px;
}

.albumImageWrapper .addIconDis {
    margin-left: 18px;
    background: url('../../../images/album_add_icon.png');
}


#list_2 .dragHandlePhoto, #list_3 .dragHandlePhoto {
    cursor: move;
}

#list_1 .dragHandlePhoto {
    cursor: default;
}

.highlight {
    width: 91px;
    height: 153px;
    background-image: url('../../../images/photoDrop.png');
    background-repeat: no-repeat;

}

.highlight_album {
    width: 99px;
    height: 104px;
    background-image: url('../../../images/album_drop.png');
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

#selectCover {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 99px;
    height: 104px;
    background-color: blue;
    z-index: 20;
    display: none;
}

.editRow {
    float: left;
    width: 375px;
    font-size: 14px;
    padding-bottom: 5px;
}

.editRow .lable {
    float: left;
    width: 75px;
}

.editRow .input {
    float: left;
    width: 300px;
}

.ui-datepicker {
    z-index: 1050;
}

div.flash {
    width: 400px;
}

.progressWrapper {
    width: 400px;
}

.albumSelectImageWrapper {
    float: left;
    padding: 6px;
    cursor: pointer;
}

.albumSelectImageWrapper img{
    border: 2px solid black;
}
</style>

<script type="text/JavaScript">
    var picLimit = <?php echo $limit_count->photo_limit ?>;
    var picCountInList = <?php echo count($iphone_pic) ?>;
    var picCountInBank = <?php echo count($bank_pic) ?>;
    var phpSessionId = "<?php echo session_id(); ?>";
    var albumLimit = <?php echo $limits['album_limit'] ?>;
    var albumCount = <?php echo count($albums) ?>;
</script>

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../../../js/swf_up/album_swf_all.js"></script>
<script type="text/JavaScript" src="../../../js/album.js"></script>


</head>
	
    <script type="text/javascript">
    function reloadPage(){
        $('#flickrButtonWait').show();
        window.location.reload();
        
    }
    
    </script>
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

    <?php if(isset($_REQUEST['msg'])){?>
      <div id="photoHolderBox"  class="tahoma_12_ash"><span style="color:#FF0066">Please insert a default accout for Flickr</span><br /><a href="../settings/settings_new.php">Click here</a></div>
    <?php }?>
	  <div id="bodyLeftPhotos">
              <div id="lightBlueHeader">
                  
                  <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Bank Photos</div>
                 
              </div>


              <ul  id="list_1" class="connected">

                  <?php
                  $pics = $bank_pic;
                  $bankList = true;
                  
                  include("album/pic_list.php");

                  ?>
              </ul>
              <div id="photoHolderBox" style="height: 15px"></div>
          <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Upload Pictures</div>
            
          </div>
              <div id="photoHolderBox" class="uploadSectionDiv" >
                    <form id="form1" action="index.php" method="post" enctype="multipart/form-data">
                        <div class="fieldset flash" id="fsUploadProgress">
                            <span class="legend">Upload Queue</span>

                        </div>
                        <span id="spanButtonPlaceHolder"></span>
                        <input id="btnCancel" type="image" src="../../../images/cancel.png" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 25px; width: 69px; padding: 0; border: 0" />

                        <div id="divStatus" class="tahoma_12_blue" style="padding-bottom: 5px">Select files less than 1MB</div>
                        <div id="errorLog" style="margin: 0; display: none">
                            <div class="fieldset flash" style="overflow:hidden; margin: 0; padding: 5px; width: 452px">
                                <ul id="errorLogList">
                                </ul>
                            </div>
                        </div>

                    </form>

        </div>

              <div id="photoHolderBox" style="height: 15px"></div>
          <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Add pictures from your facebook account</div>
           
          </div>
<!--              <div class="tahoma_12_blue flickrToolTip" style="height: 33px; float: left; width: 100%; position: relative">

                  <div>
                      <p style="font-size: 18px; color: #555555; margin: 0; width: 450px; cursor: pointer" onclick="javascript: $('#flickrToolTip').dialog('open')">What is my <img align="top" src="../../../images/flickr_logo.png" style="margin-top: -3px"/>account?</p>
                  </div>
                  <div id="flickrToolTip" style="display: none" title="Help!">
                      <div style="width:450px; height: 120px; " >
                          <img src="../../../images/wizFlickrTip.png" alt="tip is loading.." />
                          <div>The login name you use to sign-in isn't always your flickr username. Please login and look at the top right hand side of the screen to locate your Flickr username. If youd don't have a flickr account please visit <a href="http://www.flickr.com" target="_blank">www.flickr.com</a> and create one</div>
                      </div>
                  </div>

              </div>-->
              <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%">
<!--                Username: <input name="flickrUserName" id="flickrUserName" type="text" class="textFeildBoarder" style="width:200px; height:20px;margin-left: 4px"/>
                <img id="flickrButton" onclick="javascript: flickrGetCollections()" style="cursor:pointer" src="../../../images/btn_submit.png" align="top" ></img>-->
                  
<!--                  facebook connection start-->

     <script>
       
        window.fbAsyncInit = function() {
          FB.init({
            appId      : <?php echo $appId;?>,
            status     : true, 
            scope      : true,
            cookie     : true,
            xfbml      : true,
            oauth      : true
           
 
          });
          // whenever the user logs in, we refresh the page
          FB.Event.subscribe('auth.login', function() {
            window.location.reload();
            });
            FB.Event.subscribe("auth.logout", function() {window.location.reload();});
        };
        
         
        (function(d){
           
           var js, id = 'facebook-jssdk';if (d.getElementById(id)) return; 
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           
           d.getElementsByTagName('head')[0].appendChild(js);
          
         }(document));
         
      </script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=APP_ID";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!--<div class="fb-login-button" data-show-faces="true" data-width="200" data-max-rows="1"></div>-->
<fb:login-button scope="user_photos" autologoutlink="true" data-show-faces="true"   data-width="200"  data-max-rows="1"></fb:login-button>
   <?php 

      $facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $secretKey,
     
));
     
      
      
      
      
      $accessTocken =  $facebook->getAccessToken();
      $userId = $facebook->getUser();

   
      
      

      $defaultKey = $appId.'|'.$secretKey;
      if($accessTocken != $defaultKey){
      if($defaultKey != $accessTocken){
      $url = "https://graph.facebook.com/$userId/albums?access_token=$accessTocken";
      $responce=file_get_contents($url) ;     


    $arrCount = json_decode($responce);
      }
      }
      ?>

<!--                  facebook connection finish-->
                <img align="top" id="flickrButtonWait" src="../../../images/bigrotation2.gif" style="display:none"/>
              </div>
              <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%;margin-top: 50px">
                  Collections:
                  <select onchange="javascript: loadFlickrImages()"  name="flickrCollection" id="flickrCollection" type="text" class="textFeildBoarder" style="width:209px; height:27px;" >
                      <option value="0">-------Please select album-------</option>
                      <?php 
                      if(isset($arrCount->{'data'})){
                        foreach ($arrCount->{'data'} as $value){ ?>
                      <option value="<?php  echo $value->{'id'} ?>"><?php  echo $value->{'name'} ?></option>
                      <?php }} ?>
                  </select>
<!--                  <input type="button"  id="flickrButton" value="List Albums" onclick="javascript: reloadPage()"/>-->
              </div>

              <div id="listingWaiting" class="tahoma_12_blue" style="float:left; width: 100%; display: none; padding-bottom: 20px ">
                  <img align="middle" id="flickrButtonWait" src="../../../images/bigrotation2.gif" /> <span id="spanListingText">Listing pictures...</span>
              </div>
              <ul  id="list_4">

              </ul>

          <div id="photoHolderBox" style="height: 15px"></div>
          <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Add pictures uploaded by your Fans</div>
           
          </div>
          <div id="fanPhotoListingButton" style="float:left; width: 100%; padding-bottom: 20px; padding-top: 5px; color: #3a3a3a;"><img onclick="javascript: listFanPhotos();" style="cursor: pointer" src="../../../images/listFanPhotos_Btn.png" /></div>
          <ul  id="list_5">

          </ul>

	  </div>
	  <div id="bodyMusicRgt">

                                                            <div id="lightBlueHeader">
            
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">iPhone Photos</div>
           
          </div>
              <div style="width: 470px; float: left; height: 30px;padding: 5px 0 5px 0;">
                  <img id="newAlbumBtn" style="cursor: pointer" src="../../../images/new_album.png" onclick="javascrip: showCreateAlbum();"/>
                  <img id="listAlbumBtn" style="cursor: pointer" src="../../../images/list_album_dis.png" onclick="javascript: listAlbums();"/>
                  <img id="listAlbumBtn" style="cursor: pointer" src="../../../images/facebook_share_button.png" title="Share on FaceBook" onclick="javascript: shareAlbumFaceBook();"/>
                  <div id="albumTitleDiv" style="font-family: Tahoma; font-size: 16px; color: #03455A; padding-right: 18px; float: right"></div>
              </div>
              <ul  id="list_3">
                    <?php
                    include("album/list_album.php");
                    ?>
              </ul>
              
              <ul  id="list_2" style="display: none">

              </ul>
	  </div>
	</div> 

<br class="clear"/>
  </div> 
  
</div>
    <br class="clear"/>
 
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
    
    <div id="cover"></div>
    <div id="selectCover"></div>
    <div id="thumbWaitDiv" title="Album Thumbnail">
        <div ><img style="padding-bottom: 4px; vertical-align: middle;" src='../../../images/bigrotation2.gif' height=32 width=32/> <span id="waitDivInsideTitle" >Creating thumb</span></div>
        <br/>
        <div style="font-size: 12px; text-align: justify;">The album thumbnail is created/refreshed when the first two images added to or removed from the album. This may take 10s-20s. Please don't close the browser until this is completed.</div>
    </div>
    <div id="createEditAlbumDiv" style="width: 300px;" title="Create New Album">
        <div class="editRow">
            <div class="lable">Title</div>
            <div class="input">
                <input id="albumName" class="textFeildBoarder" style="height: 18px" maxlength="14"></input>
                <span style="font-size: 12px">(Maximum 14 chars)</span>
            </div>
        </div>
        <div class="editRow">
            <div class="lable">Date</div>
            <div class="input">
                <input id="albumDate" class="textFeildBoarder"  style="height: 18px"></input>
            </div>
        </div>
        <div class="editRow">
            <div class="lable">Location</div>
            <div class="input">
                <input id="albumLocation" class="textFeildBoarder"  style="height: 18px"></input>
            </div>
        </div>
        <div class="editRow">
            <div class="lable">Description</div>
            <div class="input">
                <textarea id="albumDesc" class="textFeildBoarder"  style="height: 75px; width: 250px;"></textarea>
            </div>
        </div>
        <div class="editRow" id="divCoverImage">
            <div class="lable" >Cover Image</div>
            <div class="input" style="font-size: 12px">
                <img id="editImage" src=""></img>
                <br/>

                <a href="#" onclick="javascript: showImageUploader()">Upload Image</a> | <a href="#" onclick="javascript: showSelectImageList()">Select Image from Album or Bank List</a>
            </div>
        </div>
        <div class="editRow" id="divCoverImageEditLink">
            <div class="lable">Cover Image</div>
            <div class="input" style="font-size: 12px" >
               <a href="#" onclick="javascript: showImageUploader()">Upload Image</a> | <a href="#" onclick="javascript: showSelectImageList()">Select Image from Bank List</a>
             </div>
        </div>
    </div>
    <div id="zoomImage" title="Image Preview" style="text-align: center"><img id="imgZoomImage" src="../../../images/bigrotation2.gif" height=32 width=32/></div>
    <div id="invalidFlickrUser" title="Error!" style="text-align:left">
	<p>flickr username is invalid! Please enter valid flickr username.</p>
    </div>
    <div id="showErrorDialog" title="Error!" style="text-align:left">
	<p>Please select or open an album before add images to iPhone list.<br/><br/>You can select an album by clicking or open an album by clicking <img src='../../../images/album_open.png' align='top'/> icon of the album.</p>
    </div>    
    <div id="alreadyAddedPic" title="Warning!" style="text-align:left">
	<p>This image is already in your albums or in bank list</p>
    </div>
    <div id="uploadImage" title="Upload Image" style="text-align:left">
        <iframe style="width: 370px; height: 90px; border: 0px" id="imageUploadIFrame"></iframe>
    </div>
    <div id="selectImage" title="Select Image" style="text-align:left; ">

    </div>
</body>
 
