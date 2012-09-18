<?php
$menu_item="settings"; 
include("../../../config/config.php");
include("../../../controller/session_controller.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/settings_controller.php');
include('../../../model/settings_model.php');
include('../../../config/error_config.php');

require_once '../../../database/Dao.php';
require_once "../../../model/UserInfo.php";
include "y_add_new.php";


$userInfoObj = new UserInfo();
$userInfo = $userInfoObj->getUserInfo();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
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
<script type="text/javascript">
function make_httpRequest(){
		if (window.XMLHttpRequest)
		  {
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else if (window.ActiveXObject)
		  {
		  // code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		else
		  {
		  alert("Your browser does not support XMLHTTP!");
		  return;
		  }
}

function request_url(url){

url=url+"&sid="+Math.random();
xmlhttp.open("GET",url,false);

xmlhttp.send(null);

}

var xmlhttp=null;

function test(val)
{
	//form= document.getElementsByTagName('form');
	//alert(val);
}
function showHint(user,type)
{
    make_httpRequest();
	 
 
  var url="../../../controller/settings_add_controller.php?name="+user+"&type="+type;


  request_url(url);
document.getElementById("div_error").innerHTML=xmlhttp.responseText;

div_id=chect_type(type);

//=============

make_httpRequest();
var url1="y_add_new.php?id=ajx_response&type="+type; 

  request_url(url1);
document.getElementById(div_id).innerHTML=xmlhttp.responseText;


}

function chect_type(type){
var page_type;
var div_id;
if(type == 'y_user'){
div_id="y_data";
}
else if(type == 'r_user'){
div_id="r_data";
}
else if(type == 'f_user'){
div_id="f_data";
}
else if(type == 't_user'){
//page_type="t_add";
div_id="t_data";
}

return div_id;
}

function showEdit(page,id,type,prefered,status)
{

make_httpRequest();
var url=""+page+".php?id="+id+"&prefered="+prefered+"&status="+status+"&type="+type; 

  request_url(url);
 
 
 make_httpRequest();

var url1="y_add_new.php?id=ajx_response&type="+type; 

  request_url(url1);
div_id=chect_type(type); 

document.getElementById(div_id).innerHTML=xmlhttp.responseText;


}

function checkPassword() {
    var password = document.getElementById('password').value;
    var rePassword = document.getElementById('re_password').value;

    if (password != rePassword) {
        alert("Password confirmation not match. Please check the passwords again!");
        return false;
    } else if (confirm('Your about to change the password of the CMS\n\nAre you sure?')) {
        return true;
    }
    return false;
}

$("#yesno").easyconfirm({locale: { title: 'Select Yes or No', button: ['No','Yes']}});
	$("#yesno").click(function() {
		alert("You clicked yes");
	});
        
</script>
<script language="JavaScript">
function submitform()
{
    document.addtours.submit();
}

function show_confirm()
{



var r=confirm("Are you sure you would like to deactivate your account with phizuu?");
if (r==true)
  {
      document.deactivateForm.submit();
  }

}

function updateFacebookLink(){
    var facebooklink = document.getElementById('facebooklink').value;
    
    var url_match = /https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w/_\.]*(\?\S+)?)?)?/;
    var result = url_match.test(facebooklink);

    if(result == true){
    $.post("../../../controller/settings_facebook_link_controller.php?action=updateFBLink",{'value': facebooklink},
        
        function(data){
          if (data!='ok') {
              alert("Error! while Updateing or inserting\n\n"+data);
          }else{
              //document.getElementById('comMessage').style.display = 'block';
              $('#comMessage').slideDown('slow');
              setTimeout(function() {
                  $('#comMessage').slideUp('slow');
      
                     }, 4000);

          }
            
        });
    }else{
        alert('Please enter valied URL');
    }
}
</script>

</head>
	

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
  <div id="header">
        <div id="headerContent">
            <?php include("../common/header.php");?>
        </div>
        <div style="position: absolute; background-image: url(../../../images/menu_bg.png);height: 80px;width: 100%"></div>
    </div>
    <div id="mainWideDiv">
  <div id="middleDiv2">
  	 
    <?php include("../common/navigator.php");?>
	<div id="body">
    <div id="div_error"></div>
	  <div id="bodyLeftSettings">
	  	<div id="titleBoxSettings">
		  <div class="tahoma_14_white" id="title">Youtube Accounts</div>
		  <div class="tahoma_14_white" id="duration"></div>
		  <div class="tahoma_14_white" id="note"></div>
		</div>
		<div id="y_data">
      <?php user_content('y_user');?>
        </div>
              <div id="titleBoxSettings">
		  <div class="tahoma_14_white" id="title">Facebook Link</div>
		  <div class="tahoma_14_white" id="duration"></div>
		  <div class="tahoma_14_white" id="note"></div>
		</div>
              
        <div id="y_data">
            <div style="height: 50px">&nbsp;</div>
            <div style="height: 70px;padding: 0  0 0 15px;margin-top: 50px">
                
                <div class="fbLisk"  style="margin-top: 160px;height: 50px;">
                    <div style="float: left;padding-top: 5px;color: #616262">Facebook Link</div>
                    <?php 
                    $settingController = new Settings();
                    $settingsArray = $settingController->getFacebookLink($_SESSION['user_id']);
                    ?>
                    <div style="float: left;padding-top: 5px;padding-left: 5px"><input type="text" name="facebooklink" id="facebooklink" value="<?php if(isset($settingsArray) && $settingsArray!=null){echo $settingsArray->value;} ?>"/></div>
                    <div style="float: left;padding-left: 78px"><input type="image" src="../../../images/save.png" onclick="updateFacebookLink()"/></div>
                     
                </div>
                
            </div>
            <div style="display: none;clear: both;padding-left: 15px" class="tahoma_12_blue" id="comMessage">Recored Updated Successfully</div>
        </div>
<!--		<div id="titleBoxSettings">
		  <div class="tahoma_14_white" id="title">Flickr Accounts</div>
		  <div class="tahoma_14_white" id="duration"></div>
		  <div class="tahoma_14_white" id="note"></div>
		</div>
		<div id="f_data">
      <?php // user_content('f_user');?>
         </div>-->
              <div id="titleBoxSettings">
                  <div class="tahoma_14_white" id="title">Twitter</div>
                  <div class="tahoma_14_white" id="duration"></div>
                  <div class="tahoma_14_white" id="note"></div>
              </div>
              <div id="t_data">
                                         <?php user_content('t_user');?>
              </div>

              <form action="../../../controller/modules/login/?action=change_password_settings" method="post" onsubmit="javascript: return checkPassword();">
                  <div id="titleBoxSettings">
                      <div class="tahoma_14_white" id="title">Change Password</div>
                      <div class="tahoma_14_white" id="duration"></div>
                      <div class="tahoma_14_white" id="note"></div>
                  </div>
                  <div id="t_data">

                      <div id="textSettings">
                          <div class="tahoma_12_blue" id="titleSettings" >New Password</div>
                          <div class="tahoma_14_white" id="removeSettings">
                              <input name="password" id="password" type="password"/>
                          </div>
                      </div>
                        <div id="textSettings">
                          <div class="tahoma_12_blue" id="titleSettings" >Confirm New Password</div>
                          <div class="tahoma_14_white" id="removeSettings">
                              <input name="re_password" id="re_password" type="password"/>
                          </div>
                      </div>

                  </div>
                  <div style="text-align: left; padding: 5px; padding-left: 400px; float: left">
                    <input type="image" src="../../../images/save2.png" name="button" id="button" />
                  </div>
              </form>

<!--              <form action="../../../controller/settings_app_config.php" method="post">
              <div id="titleBoxSettings">
                  <div class="tahoma_14_white" id="title">Push Notifications</div>
                  <div class="tahoma_14_white" id="duration"></div>
                  <div class="tahoma_14_white" id="note"></div>
              </div>
              <div id="t_data">
                  <?php
                  $settingsObj = new SettingsModel();
                  $settings = $settingsObj->getSettingsFromAPI($_SESSION['user_id']);
                  ?>
                  <input type="hidden" name="AppVersion" value='<?php echo  $settings->AppVersion ?>'></input>
                  <input type="hidden" name="AppName" value='<?php echo  $settings->AppName ?>'></input>
                  <input type="hidden" name="DevCertificate" value='<?php echo  $settings->DevCertificate ?>'></input>
                  <input type="hidden" name="ProdCertificate" value='<?php echo  $settings->ProdCertificate ?>'></input>
                  <input type="hidden" name="Development" value='<?php echo  $settings->Development ?>'></input>
                  <input type="hidden" name="Status" value='<?php echo  $settings->Status ?>'></input>
                  
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings" >Push Music</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushMusic" <?php echo ($settings!=false && $settings->PushMusic=='enabled')?'checked=checked':''; ?>></input>
                        </div>
                    </div>
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings" >Push Videos</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushVideos" <?php echo $settings && $settings->PushVideos=='enabled'?'checked=checked':''; ?>></input>
                        </div>
                    </div>
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings">Push Photos</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushPhotos" <?php echo $settings && $settings->PushPhotos=='enabled'?'checked=checked':''; ?>></input>
                        </div>
                    </div>
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings">Push Tours</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushTours" <?php  echo $settings && $settings->PushTours=='enabled'?'checked=checked':''; ?>></input>
                        </div>
                    </div>
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings">Push Alerts</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushAlerts" <?php echo $settings && $settings->PushAlerts=='enabled'?'checked=checked':''; ?>></input>
                        </div>
                    </div>
                    <div id="textSettings">
			<div class="tahoma_12_blue" id="titleSettings">Push Sounds</div>
                        <div class="tahoma_14_white" id="removeSettings">
                            <input type="checkbox" name="PushSounds" <?php echo $settings && $settings->PushSounds=='enabled'?'checked=checked':''; ?>></input>
                        </div>
                    </div>
              </div>
                  <div style="text-align: left; padding: 5px; padding-left: 400px; float: left">
                    <input type="image" src="../../../images/save2.png" name="button" id="button" />
                  </div>
              </form>-->
	  </div>
         <?php 
         //Retreve customer information from stripe
        $curl = curl_init('https://api.stripe.com/v1/customers/'.$userInfo['app_id']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
        curl_setopt($curl, CURLOPT_USERPWD, 'IEnAJiI5MEMsMDbcsavOJUxwF1AqcOhw');
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                    
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                          
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                           
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sample Code');

        $result = curl_exec($curl);                                          
        //$resultStatus = curl_getinfo($curl);
        $obj = json_decode($result);
        
        if(isset($obj->{'id'})){
         ?> 
          <div id="titleBoxSettings">
              <div class="tahoma_14_white" style="height: 20px;	width: 200px;padding: 4px;">Deactivate Your Phizuu Account</div>
                  <div class="tahoma_14_white" id="duration"></div>
                  <div class="tahoma_14_white" id="note"></div>
              </div>
    <div style="clear: both"></div>
    <div style="float: left;">
        <div style="padding: 4px;width: 492px;font-family:Tahoma;font-size:12px;color:#616262;">Please click here if you would like to de-activate your account.</div>
        <div style="padding: 4px;width: 492px;font-family:Tahoma;font-size:12px;color:#616262;">Note: If you deactivate your account you will no longer be able to access the CMS
to update your content.</div>
        <form  method="post" name="deactivateForm" action="../../../controller/modules/payments/PaymentController.php?action=deactivate">
        <div><input type="button" name="deactivate" value="Deactivate Account" onclick="show_confirm()"/></div>
        </form>
          </div>
	
        <?php } ?>
        </div>
	
  </div>
        <br class="clear"/><br class="clear"/><br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</div>
</body>
</html>
