<?php
require_once "../../../config/app_key_values.php";
$menu_item="photos";
?>
<?php
require_once ("../../../config/config.php");
require_once ('../../../database/Dao.php');
require_once ("../../../controller/session_controller.php");
require_once ('../../../model/Album.php');
require_once ('../../../model/UserInfo.php');
require_once ('../../../../../facebook-php-sdk-6c82b3f/src/facebook.php');

$albumId = $_GET['album_id'];

$albumObj = new Album($_SESSION['user_id']);
$albumForHere = $albumObj->listAlbums($albumId);

$albumPics = $albumObj->listPicturesOfAlbum($albumId);

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

// Create our Application instance.
$facebook = new Facebook(array(
//  'appId'  => '301675082439',
//  'secret' => '4d4a7d398663bf166488071d46e71642',
 'appId'  => $appId,
  'secret' => $secretKey,
  'cookie' => true,
));


$accessToken = $facebook->getAccessToken();

// We may or may not have this data based on a $_GET or $_COOKIE based session.
//
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getUser();

$me = null;
// Session based API call.

if ($session) {
  try {
    $uid = $facebook->getUser();
    $me = $facebook->api('me/');
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}



// login or logout url will be needed depending on current user state.
if ($me) {
    $loggedIn = TRUE;
} else {
    $loggedIn = FALSE;
}

if ($me) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>phizuu - Links</title>
<style type="text/css">
    .rowBox {
        min-height: 10px;
        float: left;
        width: 100%;
        overflow: hidden;
}
.listThumbs {
    float: left;
    padding: 5px;
}

.progressItem {
    border-top: 1px #CCCCCC solid;
    float: left;
    width: 920px;
    padding: 5px;
}

.descImg {
    padding-left: 10px;
}
</style>

<link rel="stylesheet" type="text/css" href="../../../css/styles.css"/>

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
        
        <?php include("../../../view/user/common/navigator.php");?>
      <div class="rowBox"></div>
      <div class="rowBox">
          <div style="clear: both">&nbsp;</div>
          <div id="lightBlueHeader2">
              
              <div class="tahoma_14_white" id="lightBlueHeaderMiddle2">Share "<?php echo $albumForHere[0]['album_name']; ?>" Album on FaceBook</div>
             
          </div>
      </div>
      <?php if($loggedIn) { ?>
      <div class="rowBox tahoma_12_blue" style="padding: 5px">
          Following pictures of the album will be shared with the name '<?php echo $albumForHere[0]['album_name']; ?>' on your FaceBook Profile (<?php echo $me['name'] ?>). If you need <a href="<?php echo $logoutUrl ?>">click here to logout.</a>
      </div>
      <?php } else { ?>

      <div class="rowBox tahoma_12_blue" style="padding: 5px">
          Please login to FaceBook to share the album: <fb:login-button></fb:login-button>
      </div>
      <?php } ?>
      <div class="rowBox tahoma_12_blue" style="padding: 5px">
          <?php foreach ($albumPics as $pic) {
              ?>
          <div class="listThumbs">
              <img src="<?php echo $pic->thumb_uri ?>" style="width: 75px;height: 75px"/>
          </div>
          <?php
          }
          ?>
      </div>
      <div class="rowBox tahoma_12_blue" style="padding: 5px" id="progressDiv">
          <?php if($loggedIn) { ?>
          <img src="../../../images/share_on_fb_btn.png" style="cursor:pointer" onclick="javascript:createAlbum();"/>
          <?php } ?>
      </div>

  </div>
</div>
 <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
 <div id="fb-root"></div>

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/ui/ui.core.js"></script>


<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=APP_ID";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

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
};

(function(d){
           
           var js, id = 'facebook-jssdk';if (d.getElementById(id)) return; 
           js = d.createElement('script'); js.id = id; js.async = true;
           js.src = "//connect.facebook.net/en_US/all.js";
           
           d.getElementsByTagName('head')[0].appendChild(js);
          
         }(document));


function createAlbum() {
    $('#progressDiv').html('');
    var progressItem = createProgressDiv('Creating new album..');
    progressItem.ajaxError(function(){
        progressItem.html('Error occured while connecting to the server!!! Check your internet connection.');
    });
    progressItem.appendTo('#progressDiv').hide().fadeIn(300);
    $.post('../../../controller/photo_all_controller.php?action=create_album_on_facebook', {'album_name':'<?php echo $albumForHere[0]['album_name']; ?>'}, function(data) {
        if(data!='error') {
            progressItem.html('Album created successfuly!');
            albumId = data;
            uploadNextImage();
        } else {
            progressItem.html('Error occured while creating an album');
        }
    });
}

function uploadNextImage() {
    if(imagesToBeUploaded[nextImage]) {
        uploadImage(imagesToBeUploaded[nextImage]['name'],imagesToBeUploaded[nextImage]['uri'],imagesToBeUploaded[nextImage]['thumb_uri']);
        nextImage++;
    } else {
        var progressItem = createProgressDiv('');
        progressItem.html('Album uploaded successfuly!');
        progressItem.appendTo('#progressDiv').hide().fadeIn(300);
    }
}

function uploadImage(name, url, thumb) {
    var progressItem = createProgressDiv('Adding image '+ name +'..',thumb);
    progressItem.appendTo('#progressDiv').hide().fadeIn(300);
    $.post('../../../controller/photo_all_controller.php?action=upload_image_to_facebook_album', {'album_id':albumId,'url':url, 'name':name}, function(data) {
        if(data!='error') {
            progressItem.find('span').html('Image uploaded successfuly!');
        } else {
            progressItem.html('Error occured while adding image!');
        }
        uploadNextImage();
    });
}

function createProgressDiv(msg, thumb_url) {
    var newDiv = $('<div></div>');
    newDiv.addClass('progressItem');
    var image  = '';
    if(thumb_url) {
        image = "<img src="+ thumb_url +" height=50 width=50 align='top'/>"
    }
    
    newDiv.html(image + "<span class='descImg'><img src='../../../images/bigrotation2.gif' height=16 width=16 align='top'/> "+msg+"</span>");
    return newDiv;
}

var albumId = null;
var imagesToBeUploaded = eval('(' + '<?php echo json_encode($albumPics) ?>' + ')');
var nextImage = 0;
</script>

</body>

</html>
