<?php
require "../../../config/app_key_values.php";

$menu_item="photos";
?>
<?php



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
<title>phizuu - Application Wizard</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<link href="../../../css/wizard.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../../../common/jquery/css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link href="../../../common/tooltip/bubble.css" rel="stylesheet" type="text/css" media="all" />
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
    width:94px;
    
}


#list_2 .dragHandlePhoto {
    cursor: move;
}

#list_1 .dragHandlePhoto {
    cursor: default;
    padding: 5px;
}

.highlight {
    width: 85px;
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
</style>

<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.jeditable.mini.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.tooltip.min.js"></script>
<script type="text/javascript" src="../../../common/tooltip/jquery.codabubble.js"></script>
<script type="text/javascript">
    $(function(){
    if (picCountInBank<=0) {
        $('#addAllButtonLnk').hide();
    }

  $('.edit').editable('../../../controller/photo_all_controller.php?action=editcms',{
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
        var id = lastElem.id.replace('id_','');
        var uri = document.getElementById('uri'+id).value;
        var thumt_uri = document.getElementById('thumb_uri'+id).value;
        startBusy();
       
        $.post('../../../controller/photo_all_controller.php?action=add_pic',{'uri':uri,'thumb_uri':thumt_uri}, function(data) {
           
            stopBusy();
            lastElem.prevId = lastElem.id;
            lastElem.id = "id_"+data;
            $("#picCover1").hide();
            
            $(lastElem).children(".edit").attr('id',"1_"+data);
            $("#editBox_"+lastElem.prevId).attr('id',"1_"+data);
            
            document.getElementById("list_1").removeChild(lastElem);
            $("#list_2").append(lastElem);
            $(lastElem).unbind();
            $(lastElem).mouseover(list2Over);
            $("#list_2").sortable( "refresh" );
            picCountInList++;
            picCountInBank--;
            $("#imageCount").html(picCountInList);
            
               $("#list_2 .edit").editable('../../../controller/photo_all_controller.php?action=editcms',{
                  indicator : 'Saving...',
                  tooltip   : 'Click to edit...',
                  rows : 3
              });

            if (picCountInList>0) {
                $('#buttonNext').attr('src','../../../images/btn_next.png');
            } else {
                $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
            }

            if (picCountInBank<=0) {
                $('#addAllButtonLnk').hide();
            }

            updateList();
        });
    });

   $("#picCover2").click(
    function(){
        startBusy();
        var id = lastElem.id.replace('id_','');
        $.post('../../../controller/pic_add_iphone_controller.php?status=delete&id='+id, function(data) {
            stopBusy();
            document.getElementById("list_2").removeChild(lastElem);
            $("#picCover2").hide();
            $(lastElem).unbind();
            picCountInList--;
            $("#imageCount").html(picCountInList);
            if (picCountInList>0) {
                $('#buttonNext').attr('src','../../../images/btn_next.png');
            } else {
                $('#buttonNext').attr('src','../../../images/btn_next_disabled.png');
            }

            if (lastElem.prevId) {
                lastElem.id = lastElem.prevId;
                $("#picCover2").hide();
                $(lastElem).unbind();
                $(lastElem).mouseover(list1Over);
                $("#list_1").append(lastElem);

                picCountInBank++;

                if (picCountInBank>0) {
                    $('#addAllButtonLnk').show();
                }
            }
        });

        updateList();
    });
      $('#list_2').bind('sortupdate', updateList);

         $("#invalidFlickrUser").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });

          $("#noContent").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Ok: function() {
                    $(this).dialog('close');
                }
            }
        });

        $("#skipWarning").dialog({
            modal: true,
            autoOpen: false,
            width: 400,
            resizable: false,
            buttons: {
                Cancel: function() {
                    $(this).dialog('close');
                },
                Accept: function() {
                    window.location = "AppWizardControllerNew.php?action=photo_module_skip";
                    $(this).dialog('close');
                }
            }
        });

    opts = {
      distances : [-153],
      leftShifts : [400],
      bubbleTimes : [400],
      hideDelays : [500],
      bubbleWidths : [640],
      msieFix : true
   };
   $('.coda_bubble').codaBubble(opts);
   
     opts = {
      distances : [-127],
      leftShifts : [0],
      bubbleTimes : [100],
      hideDelays : [100],
      bubbleWidths : [500],
      msieFix : true
   };
   $('.flickrToolTip').codaBubble(opts);
   
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

      function flickrGetCollections() {
           var username = $('#flickrUserName').val();
           $('#flickrButton').hide();
           $('#flickrButtonWait').show();
           $('#flickrUserName').attr('disabled','disabled');
           $('#flickrCollection').attr('disabled','disabled');
           $.post('../../../controller/photo_all_controller.php?action=list_flickr_pics', {'username':username}, function(data) {
              $('#flickrButton').show();
              $('#flickrButtonWait').hide();
              $('#flickrUserName').attr('disabled','');

              if (data.all == null) {
                  $("#invalidFlickrUser").dialog("open");
              } else {
                  $('#flickrCollection').attr('disabled','');
                  var sets = data.sets;
                  $('#flickrCollection').empty()
                  $('#flickrCollection').append($("<option></option>").attr("value","").text("All (" + data.all + ")"));
                  if(data.sets!='') {
                      for (i=0; i<sets.length; i++) {
                          //alert(sets[i].title);
                          $('#flickrCollection').append($("<option></option>").attr("value",sets[i].id ).text(sets[i].title + " ("+sets[i].photos+")"));
                      }
                  }
                  flickrGetImages('');
              }
          },'json');
      }
      
      function facebookGetCollections() {
   $('#flickrButtonWait').show();

   $('#flickrCollection').attr('disabled','disabled');
   $.post('../../../controller/photo_all_controller.php?action=list_facebook_albums', function(data) {
      
     alert(data);
      
      
  },'json');
}



//      function flickrGetImages(id) {
//           var username = $('#flickrUserName').val();
//           $('#flickrButton').hide();
//           $('#listingWaiting').show();
//           $('#flickrUserName').attr('disabled','disabled');
//           $('#flickrCollection').attr('disabled','disabled');
//           $('#addAllButtonLnk').hide();
//
//           $('#list_1').empty();
//           $.post('../../../controller/photo_all_controller.php?action=get_photos', {'id':id,'username':username}, function(data) {
//              $('#flickrButton').show();
//              $('#listingWaiting').hide();
//              $('#flickrUserName').attr('disabled','');
//              $('#flickrCollection').attr('disabled','');
//              $('#addAllButtonLnk').show();
//              $('#list_1').append(data.html);
//              bankImages = data.pics;
//              $("#list_1 li").mouseover(list1Over);
//              listed = true;
//          },'json');
//      }
function flickrGetImages() {
    
    var id = $('#flickrCollection').val();
    
    var selectedAlbum = document.getElementById('flickrCollection').value;
    
    if(selectedAlbum != 0){
    $('#flickrButton').hide();
    $('#listingWaiting').show();
    $('#flickrUserName').attr('disabled','disabled');
    $('#flickrCollection').attr('disabled','disabled');
    $('#addAllButtonLnk').hide();

    $('#list_1').empty();
     
    $.post('../../../controller/photo_all_controller.php?action=get_photos_album_facebook', {'id':id}, function(data) {
   
 

        $('#flickrButton').show();
        $('#listingWaiting').hide();
        $('#flickrUserName').attr('disabled','');
        $('#flickrCollection').attr('disabled','');
        $('#addAllButtonLnk').show();
        $('#list_1').append(data.html);
        bankImages = data.pics;
        $("#list_1 li").mouseover(list1Over);
        listed = true;
    },'json');
    }
}
function startBusy() {
    $('#cover').css('opacity',0.5);
    $('#cover').css('background-color','#000000');
    $('#cover').css('width',$(document).width());
    $('#cover').css('height',$(document).height());
    $('#cover').css('width',$(document).width());
    $('#cover').fadeIn('normal');
}

function stopBusy() {
    $('#cover').fadeOut('fast');
}

function takeAction(action) {
    if (action=='skip') {
         $("#skipWarning").dialog( "open" );
    } else if (action=='save') {
        if (picCountInList==0) {
            $("#noContent").dialog( "open" );
        } else {
            window.location = "AppWizardControllerNew.php?action=photo_module_save";
        }
    }
}

    function reloadPage(){
        $('#flickrButtonWait').show();
        window.location.reload();
        
    }
    
var bankImages;
var picLimit = <?php echo $limit_count->photo_limit ?>;
var picCountInList = <?php echo count($iphone_pic) ?>;
var picCountInBank = <?php echo count($bank_pic) ?>;
var listed = false;
</script>

<link href="../../../css/swf_up/default.css" rel="stylesheet" type="text/css" />
<?php 

?>

</head>


<body>

<div id="mainWideDiv">
     <div id="header">
        <div style="width: 800px;height: 90px;margin: auto">
                        <div id="logoContainer"><a href="http://phizuu.com"><img src="../../../images/logoInner.png" width="350" height="35" border="0" /></a></div>
                    <div class="tahoma_12_white2" id="loginBox">
                        <a href="AppWizardControllerNew.php?action=package_upgrade">
                            <img border="0" src="../../../images/wizard_btn_upgrade2.png" width="99" height="35" />
                        </a>
                        <a href="../../logout_controller.php">
                            <img border="0" src="../../../images/wizard_btn_logout.png" width="95" height="35" />
                        </a>
                    </div>
        </div>
                </div>
  <div id="middleDiv2">
               
    <div id="body">
                        <br/>

                        <div class="wizardTitle" >
                           
                            <div class="middle" style="width: 910px;">
                                <span id="mainTitle">Choose images from your </span><span id="mainTitle1"><img align="top" src="../../../images/flickr_logo.png"></img></span> <span id="mainTitle">account</span></div>
                        
                        </div>

    <div class="coda_bubble wizardSecondTitle" style="width: 900px">
        <div>
            <p><div style="float:left; width: 837px">You can choose images that you have uploaded to the facebook account. Enter your username and then add the images using the small (+) sign at the corner of the image.</div><img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html" style="width:640px; height: 385px; margin-left: 100px">
           <div style="width:480px; height: 405px; ">
               <div style="height: 20px">Quick Video Tutorial (45 seconds) - to hide move mouse away</div>
<object width="480" height="385"><param name="movie" value="http://www.youtube-nocookie.com/v/OFGiJw1diok&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/OFGiJw1diok&hl=en_US&fs=1&rel=0&color1=0x006699&color2=0x54abd6" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
           </div>
        </div>
    </div>

    <div id="lightBlueHeader" class="wizardItemList">
       
        <div class="tahoma_12_white" id="lightBlueHeaderMiddle" style="width:180px">Maximum <?php echo $limit_count->photo_limit ?> image<?php echo $limit_count->photo_limit==1?'':'s'?></div>
      
    </div>                      
                        <div style="padding-top: 0;clear: both;width:1000px">
            <div id="div_error"></div>

	  <div id="bodyLeftPhotos">
                                                            <div id="lightBlueHeader">
           
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 425px">Photo Selecting Section</div>
           
          </div>
<!--               <div class="tahoma_12_blue flickrToolTip" style="height: 33px; float: left; width: 100%; position: relative">

        <div>
            <p style="font-size: 18px; color: #555555; margin: 0; width: 450px">What is my <img align="top" src="../../../images/flickr_logo.png"></img>account? <img style="float:right" class="trigger" src="../../../images/tool_tip.png"/></p>
        </div>
       <div class="bubble_html">
           <div style="width:450px; height: 120px; ">
               <img src="../../../images/wizFlickrTip.png" alt="tip is loading.."/>
               <div>The login name you use to sign-in isn't always your flickr username. Please login and look at the top right hand side of the screen to locate your Flickr username. If youd don't have a flickr account please visit <a href="http://www.flickr.com" target="_blank">www.flickr.com</a> and create one</div>
           </div>
        </div>

              </div>  -->
              <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%">
<!--                Username: <input name="flickrUserName" id="flickrUserName" type="text" class="textFeildBoarder" style="width:200px; height:20px;margin-left: 4px"/>
                <img id="flickrButton" onclick="javascript: flickrGetCollections()" style="cursor:pointer" src="../../../images/btn_submit.png" align="top" ></img>-->
              <script>
       
        window.fbAsyncInit = function() {
          FB.init({
            appId      : <?php echo $appId ?>,
            user_photos:true,
            status     : true, 
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

<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=APP_ID";
  fjs.parentNode.insertBefore(js, fjs);
  
}(document, 'script', 'facebook-jssdk'));

</script>
<!--<div class="fb-login-button" data-show-faces="true"   data-width="200" data-max-rows="1">Facebook Log In</div>-->
<fb:login-button scope="user_photos" autologoutlink="true" data-show-faces="true"   data-width="400"  data-max-rows="1"></fb:login-button>
   <?php 
require_once "../../../../../facebook-php-sdk-6c82b3f/src/facebook.php";
      $facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $secretKey,
));
      $accessTocken =  $facebook->getAccessToken();
      $userId = $facebook->getUser();

      $defaultKey = $appId.'|'.$secretKey;
      
      if($defaultKey != $accessTocken){
      $url = "https://graph.facebook.com/$userId/albums?access_token=$accessTocken";
      $responce=file_get_contents($url) ;     


    $arrCount = json_decode($responce);

      }

      ?>

                <img align="top" id="flickrButtonWait" src="../../../images/bigrotation2.gif" style="display:none"/>
              </div>
              <div class="tahoma_12_blue" style="height: 33px; float: left; width: 100%; margin-top: 60px">
                  <div style="float: left">
                  Collections:
                  <select onchange="javascript: flickrGetImages()"  name="flickrCollection" id="flickrCollection" type="text" class="textFeildBoarder" style="width:209px; height:27px;" >
                      <option value="0">-------Please select album-------</option>
                      <?php 
                      if(isset($arrCount->{'data'})){
                        foreach ($arrCount->{'data'} as $value){ ?>
                      <option value="<?php  echo $value->{'id'} ?>"><?php  echo $value->{'name'} ?></option>
                      <?php }} ?>
                  </select>
                  </div>
                  <div style="float: left;padding-left: 5px">
                  <input type="button"  id="flickrButton" value="List Albums" onclick="javascript: reloadPage()"/>
                  </div>
                  <div id="listingWaiting" class="tahoma_12_blue" style="float:right; width: 100%;display: none ">
                      <img align="middle" id="flickrButtonWait" src="../../../images/bigrotation2.gif" /> Listing pictures...
                  </div>
              </div>
              
           
              
              
<ul  id="list_1" class="connected" style="margin-top: 180px;">
                  <?php
                  $pics = $bank_pic;
                  $bankList = true;

                  include("supporting/pic_list.php");

                  ?>
              </ul>


              <br/><br/>
        <div id="photoHolderBox">
			
		</div>
	  </div>
	  <div id="bodyMusicRgt">

                                                            <div id="lightBlueHeader">
           
            <div class="tahoma_14_white" id="lightBlueHeaderMiddle" style="width: 425px">Selected Photos</div>
         
          </div>
              <form method="post">
              <ul  id="list_2" class="connected">
                                        <?php
                                        $pics = $iphone_pic;
                                        $bankList = false;

                                        include("supporting/pic_list.php");
                                        ?>
              </ul>
                  </form> 
              <div class="tahoma_12_blue" style="font-size: 14px; text-align: right; float: left; width: 425px" ><span  id="imageCount"><?php echo count($iphone_pic);?></span> out of <?php echo $limit_count->photo_limit ?></div>
	  </div>
	</div>
      
                             <div class="nextButton" style="width: 927px; border: 0">
                                <img class="wizardButton" onclick="javascript: takeAction('skip');" src="../../../images/btn_skip_module.png" width="170" height="35" />
                                <img id="buttonNext" class="<?php echo count($iphone_pic)==0?'':'wizardButton' ?>" onclick="javascript: takeAction('save');" src="../../../images/<?php echo count($iphone_pic)==0?'btn_next_disabled.png':'btn_next.png' ?>" width="89" height="35" />
                                
                             </div>

    </div>


  </div>
    <br class="clear"/>
</div>
<br class="clear"/>    
	       <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>



<div id="cover"></div>
<div id="cover2"></div>
<div id="noContent" title="Error!" style="text-align:center">
	<p>To use the photos module you need add at least one Picture. If you don't need this module please click skip this module button.</p>
</div>
<div id="skipWarning" title="Warning!" style="text-align:center">
	<p>If you skip this module you cannot enter Photos into your app at a later time. Your application will be submitted to Apple without a Photo Module. Please acknowledge by pressing Accept or press Cancel to add Photos.</p>
</div>
<div id="invalidFlickrUser" title="Error!" style="text-align:left">
	<p>flickr username is invalid! Please enter valid flickr username.</p>
</div>
    <div id="picCover1" class="photoAddOver" style="display: none"></div>
    <div id="picCover2" class="photoAddOver" style="display: none"></div>
    
</body>
</html>