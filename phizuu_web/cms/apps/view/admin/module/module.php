<?php
include ('../../../config/config.php');
include("../../../controller/admin_session_controller.php");

	require_once '../../../config/database.php';
	include('../../../controller/db_connect.php');
	include('../../../controller/helper.php');
	require_once('../../../controller/admin_module_controller.php');
	include('../../../model/admin_module_model.php');
	include('../../../config/error_config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />
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

function showHint()
{

  make_httpRequest();
 

  app_id=document.addModule.app_id.value;
  music=document.addModule.music.checked;
  video=document.addModule.video.checked;
  photo=document.addModule.photo.checked;
  flyer=document.addModule.flyer.checked;
  news=document.addModule.news.checked;
  tour=document.addModule.tour.checked;
  link=document.addModule.link.checked;
  setting=document.addModule.setting.checked;
  status=document.addModule.status.value;
  starting=document.addModule.starting.value;
  
 
  var url="../../../controller/admin_module_add_controller.php?app_id="+app_id+"&music="+music+"&videos="+video+"&photos="+photo+"&flyers="+flyer+"&news="+news+"&tours="+tour+"&links="+link+"&settings="+setting+"&starting="+starting+"&status="+status;
  var url_add="";
  if(document.addModule.status.value == "edit"){
  url_add="&id="+document.addModule.id.value; 
 }else{
 url_add=""; 
 }

if((document.addModule.status.value == "edit") && (app_id == "")){
 url="edit_module.php?id="+document.addModule.id.value+"&starting="+starting+"&msg_error"; 
 url_add="";
}

  request_url(url+url_add);
  
  document.getElementById("module_add").innerHTML=xmlhttp.responseText;


//=============

make_httpRequest();
var url1="list_module.php?starting="+starting; 
  request_url(url1);
document.getElementById("module_list").innerHTML=xmlhttp.responseText;

}

function showEdit(page,id,starting)
{

make_httpRequest();
var url1=""+page+".php?id="+id+"&starting="+starting+""; 
  request_url(url1);
document.getElementById("module_add").innerHTML=xmlhttp.responseText;

}

function showDelete(page,id,status,starting)
{
  make_httpRequest();
 
  var url=""+page+".php?id="+id+"&status="+status+"&starting="+starting;
  request_url(url);

//=============

make_httpRequest();
var url1="list_module.php?starting="+starting; 
  request_url(url1);
document.getElementById("module_list").innerHTML=xmlhttp.responseText;

}
</script>

<SCRIPT language="JavaScript">
function submitform()
{
    document.addModule.submit();
}
</SCRIPT> 
    <!--pagination-->
	<script language="JavaScript" src="../../../js/pagination/pagination.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/pagination/style.css" />
</head>
	

<body>
<div id="mainWideDiv">
  <div id="middleDiv2">
  	<?php include("../../admin/common/header.php");?>
	<?php include("../../admin/common/navigator.php");?>
  	<div id="buttonContainer">
  	  <div id="addMusicBttnAdmin">
	  <div id="lightBlueHeader">
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_L.png" /></div>
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle">Module Management</div>
	  	<div id="lightBlueHeaderSide"><img src="../../../images/light_blue_R.png" /></div>
	  </div>
    	<div id='module_add'>
    	<?php include('add_module.php');?>
    	</div>
	
  	  </div>
	  <div id="addMusicBttnAdmin2">
	  	<div id="bodyRgt">
            <div id='module_list'>
			<?php include('list_module.php');?>
            </div>

        
        
		</div>
	  </div>
    </div>
  </div>
</div>
<div id="footerMain">
	<div class="tahoma_11_blue" id="footer2">&copy; 2012 phizuu. All Rights Reserved.</div>
</div>
</body>
</html>
