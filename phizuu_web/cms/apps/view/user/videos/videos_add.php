<?php
$menu_item="videos";
include("../../../controller/youTube_controller.php");

include("../../../config/config.php");
require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
require_once('../../../controller/user_settings_controller.php');
include('../../../model/login_model.php');
include('../../../config/error_config.php');

@session_start();
//YouTube
$user_type=$_ENV['setting_youtube'];

$userSet= new UserSettings();
$get_user = $userSet->getUserSettings($user_type);
$count=1;

//get prefered user
$userSet_prefered= new UserSettings();
$userSet_prefered->getPreferedUser_settings($user_type);

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
//function showHint(str)
function showHint(page,id)
{
    document.getElementById("txtHint").innerHTML="<img src='../../../images/bigrotation2.gif'></img>";
  make_httpRequest();
 var url=""+page+".php?id="+id; 
  request_url(url);
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

}

function showHint2(page,str,note,year,stream_uri,duration,play_id,vid,thumb,vid_gp3)
{
    document.getElementById("txtHint").innerHTML="<img src='../../../images/bigrotation2.gif'></img>";
  make_httpRequest();
    var url=""+page+".php?title=" + str+"&note="+ note+"&year="+ year+"&stream_uri="+ escape(stream_uri)+"&duration="+ duration+"&play_id="+ play_id+"&vid="+ vid+"&thumb="+ escape(thumb)+"&vid_gp3="+ escape(vid_gp3);

  request_url(url);
 document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

}

function showAccount(accid)
{
    document.getElementById("categories").innerHTML="<img src='../../../images/bigrotation2.gif'></img>";

            if (accid.length!=0)
            {

                make_httpRequest();
                var url="list_videos_cat_a1.php?youUser=" + accid;
                request_url(url);
                document.getElementById("categories").innerHTML=xmlhttp.responseText;
                document.getElementById("txtHint").innerHTML = '';
            }
            else{
                document.getElementById("categories").innerHTML='';
                document.getElementById("txtHint").innerHTML = '';

            }

    }

    function onChangeUsername(accid) {
        if (accid!='-1') {
            showAccount(accid);
            document.getElementById("txtUserName").style['display']='none';
        } else {
            document.getElementById("txtUserName").style['display']='';
        }
    }

function clearText(field){

    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;

}

    function searchVideos(e){ //e is event object passed from function invocation
        var characterCode //literal character code will be stored in this variable

        if(e && e.which){ //if which property of event object is supported (NN4)
            e = e;
            characterCode = e.which; //character code is contained in NN4's which property
        }
        else{
            e = event;
            characterCode = e.keyCode ;//character code is contained in IE's keyCode property
        }

        if(characterCode == 13){ //if generated character code is equal to ascii 13 (if enter key)
            showAccount(document.getElementById("txtUserName").value); //submit the form
            return false;
        } else {
            return true;
        }
    }

</script>
    <!--pagination-->
	<script language="JavaScript" src="../../../js/pagination/pagination_pics.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/pagination/style.css" />
</head>
	

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png')">
<div id="mainWideDiv">
  <div id="middleDiv2">
  	<?php include("../common/header.php");?>
	<?php include("../common/navigator.php");?>
	<div id="bodyPhotos" style="width:450px; ">
	  <div id="bodyLeftPhotos">
	  	<div class="tahoma_12_ash" id="videoYoutubeLeft">Youtube User:&nbsp;&nbsp;
                    <select name="youtube" id="youtube" onchange="onChangeUsername(this.value);" class="textfield" style="width:120px">
	  	    <?php foreach ($get_user as $user){?>
     <option value="<?php echo $user ->value; ?>" <?php if($user ->preferred == "1"){echo "selected";} ?> ><?php echo $user ->value; ?></option>
      <?php }?>
                    </select> &nbsp;&nbsp;&nbsp; (<a href="../settings/settings_new.php" class="tahoma_12_ash">Sign with a different account</a>) </div><!-- <input id="txtUserName" type="text" style="width:120px; display: none" class="textfield" onfocus="clearText(this)" onblur="clearText(this)" value="Enter username" onkeypress="return searchVideos(event)"/> -->
  	    </div>
       		<div id='categories'>
         		<?php include("list_videos_cat_a1.php");?>
			</div>
	  </div>
      <div id="bodyMusicRgt" style="padding-top: 80px;">
            <div id='result'></div>
      <span id="txtHint"></span>

	  </div>
	</div>
	<div class="tahoma_11_blue" id="footer">&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>	
</div>

</body>
</html>
