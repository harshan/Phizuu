<?php
require_once "../../../config/app_key_values.php";
require_once "../../../../../facebook-php-sdk-6c82b3f/src/facebook.php";
$menu_item = "photos";
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
$limitFiles = new LimitFiles();
$limit_count = $limitFiles->getLimit($_SESSION['user_id'], 'picture');

$bpic = new Picture();
$bank_pic = $bpic->listBankPics($_SESSION['user_id']);
$count = 1;
$ipic = new Picture();
$iphone_pic = $ipic->listIphonePics($_SESSION['user_id']);
$icount = 1;

$userInfo = new UserInfo();
$freeUser = $userInfo->isFreeUser();
$limits = $userInfo->getLimits();
$_SESSION['redirect_page_name'] = 'pictures/upload_pics_swf.php';
$_SESSION['request_page_name'] = 'pictures/photos.php';


if ($_SERVER["SERVER_NAME"] == app_key_values::$LIVE_SERVER_DOMAIN) {
    $appId = app_key_values::$APP_ID_LIVE;
    $secretKey = app_key_values::$SECRET_KEY_LIVE;
} elseif ($_SERVER["SERVER_NAME"] == app_key_values::$TEST_SERVER_DOMAIN) {
    $appId = app_key_values::$APP_ID_TEST;
    $secretKey = app_key_values::$SECRET_KEY_TEST;
} else {
    $appId = app_key_values::$APP_ID_LOCALHOST;
    $secretKey = app_key_values::$SECRET_KEY_LOCALHOST;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Photos - phizuu CMS</title>
        <link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
        <link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />


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
                width: 452px;
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
                /*                margin-left: 18px;*/
                background: url('../../../images/icon_add.png');
                width: 17px;
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

<!--        <style type="text/css">

            #upload1 {
                margin:20px 0px;
                padding:15px;
                font-weight:bold;
                font-size:1.3em;
                font-family:Arial, Helvetica, sans-serif;
                text-align:center;

                color:#3366cc;

                width:99px;
                height: 33px;
                cursor:pointer !important;
                -moz-border-radius:5px;
                -webkit-border-radius:5px;
            }
            .darkbg {
                background:#ddd !important;
            }
            #status {
                font-family:Arial;
                padding:5px;
            }
            ul#files {
                list-style:none;
                padding:0;
                margin:0;
            }
            ul#files li {
                padding:10px;
                margin-bottom:2px;
                width:200px;
                float:left;
                margin-right:10px;
            }
            ul#files li img {
                max-width:180px;
                max-height:150px;
            }
            .success {
                background:#99f099;
                border:1px solid #339933;
            }
            .error {
                background:#f0c6c3;
                border:1px solid #cc6622;
            }


        </style>-->

<!--        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>-->
        <script type="text/javascript" src="../../../js/jquery-1.8.0.min.js" ></script> 


        <script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/JavaScript" src="../../../js/albumnew.js"></script>

        <script type="text/javascript" src="../../../js/ui/ui.core.js"></script>
        <script type="text/javascript" src="../../../js/ui/ui.sortable.js"></script>
        <script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>






        <script type="text/JavaScript">
            var picLimit = <?php echo $limit_count->photo_limit ?>;
            var picCountInList = <?php echo count($iphone_pic) ?>;
            var picCountInBank = <?php echo count($bank_pic) ?>;
            var phpSessionId = "<?php echo session_id(); ?>";
            var albumLimit = <?php echo $limits['album_limit'] ?>;

        </script>






    </head>


    <body style="background-image: url(../../../images/main_backgrnd.png);background-repeat: repeat;">

        <div id="header">
            <div id="headerContent">
                <?php include("../common/header.php"); ?>
            </div>
            <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
        </div>
        <div id="mainWideDiv">
            <div id="middleDiv2">

                <?php include("../common/navigator.php"); ?>
                <div id="bodyPhotos">
                    <?php if (!$freeUser) { ?>
                        <div id="albumSwitchAdd"><a href="switch_mode.php" ></a></div>
                    <?php } ?>
                    <div id="bodyLeftPhotos">
                        <div id="lightBlueHeader">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Bank Photos</div>

                        </div>

                        <ul  id="list_1" class="connected">

                            <?php
                            $pics = $bank_pic;
                            $bankList = true;

                            include("album/pic_list_1.php");
                            ?>
                        </ul>

                        <div id="photoHolderBox" style="height: 15px"></div>

                        <div id="lightBlueHeader" style="height: 200px">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Upload Image</div>
                            <div style="margin-top: 35px">
                                <form method="post" enctype="multipart/form-data"  action="upload.php">
                                    <div style="float: left"><img src="../../../images/upload_btn.png" id="upload_btn"/></div>
                                    <div style="float: left;font-family: arial;font-size: 11px;margin: 17px 0 0 10px ">Maximum file size is 1MB</div>
                                    <input type="file" name="images" id="images" multiple style="visibility: hidden"/>
                                    <button type="submit" id="btn" style="visibility: hidden">Upload Files!</button>
                                </form>
                                <div id="response" style="font-size: 11px;line-height: 14px;clear: both;margin-top: 5px" ></div>
                            </div>

                        </div>
                        <div id="lightBlueHeader" style="clear: both">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">Add pictures from your facebook account</div>
                         
                         </div>
                        
                            <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%">


                                <script>
       
                                    window.fbAsyncInit = function() {
                                        FB.init({
                                            appId      : <?php echo $appId; ?>,
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
                                            'appId' => $appId,
                                            'secret' => $secretKey,
                                        ));





                                $accessTocken = $facebook->getAccessToken();
                                $userId = $facebook->getUser();





                                $defaultKey = $appId . '|' . $secretKey;
                                if ($defaultKey != $accessTocken) {
                                    $url = "https://graph.facebook.com/$userId/albums?access_token=$accessTocken";
                                    $responce = file_get_contents($url);


                                    $arrCount = json_decode($responce);
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
                                    if (isset($arrCount->{'data'})) {
                                        foreach ($arrCount->{'data'} as $value) {
                                            ?>
                                            <option value="<?php echo $value->{'id'} ?>"><?php echo $value->{'name'} ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
<br class="clear"/>
                            </div>
                            <div id="listingWaiting" class="tahoma_12_blue" style="float:left; width: 100%; display: none; padding-bottom: 20px ">
                                <img align="middle" id="flickrButtonWait" src="../../../images/bigrotation2.gif" /> <span id="spanListingText">Listing pictures...</span>
                            </div>
                            <ul  id="list_4">

                            </ul>

                        

                    </div>
                    <div id="bodyMusicRgt">
                        <div id="lightBlueHeader">

                            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 450px">iPhone Photos</div>

                        </div>
                        <ul  id="list_2" >
                            <?php
                            $pics = $iphone_pic;
                            $bankList = true;

                            include("album/pic_list_1.php");
                            ?>
                        </ul>
                    </div>





                </div>

            </div>	
        </div><br class="clear"/>
        <div id="footerInner" >
            <div class="lineBottomInner"></div>
            <div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
        </div>



    </body>
</html>

<div id="zoomImage" title="Image Preview" style="text-align: center;"><img id="imgZoomImage" src="../../../images/bigrotation2.gif" height=32 width=32/></div>
<div id="js"></div>
<script src="../../../js/file_uploader/upload.js"></script>

<script type="text/javascript">
 

$(document).ready(function(){
    $("#upload_btn").click(function(){
        $("#images").click(); 

                
                
                    
    });
    
  
});
</script>