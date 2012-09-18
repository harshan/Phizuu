<?php
include ('../../../config/config.php');
include("../../../controller/admin_session_controller.php");

?>
<?php include('../../../config/error_config.php');?>
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
  box=document.addBox.box.value;
  password=document.addBox.password.value; 
  box_status=document.addBox.box_status.value;
  status=document.addBox.status.value;
  starting=document.addBox.starting.value;
 
  var url="../../../controller/admin_box_add_controller.php?box="+box+"&password="+password+"&box_status="+box_status+"&starting="+starting+"&status="+status;
  var url_add="";
  if(document.addBox.status.value == "edit"){
  url_add="&id="+document.addBox.id.value; 
 }else{
 url_add=""; 
 }

if((document.addBox.status.value == "edit") && (box == "")){
 url="edit_box_mgt.php?id="+document.addBox.id.value+"&starting="+starting+"&msg_error"; 
 url_add="";
}
  request_url(url+url_add);
  
  document.getElementById("box_add").innerHTML=xmlhttp.responseText;


//=============

make_httpRequest();
var url1="list_box_mgt.php?starting="+starting; 
  request_url(url1);
document.getElementById("box_list").innerHTML=xmlhttp.responseText;

}

function showEdit(page,id,starting)
{

make_httpRequest();
var url1=""+page+".php?id="+id+"&starting="+starting+""; 
  request_url(url1);
document.getElementById("box_add").innerHTML=xmlhttp.responseText;

}

function showDelete(page,id,status,starting)
{
  make_httpRequest();
 
  var url=""+page+".php?id="+id+"&status="+status+"&starting="+starting;
  request_url(url);

//=============

make_httpRequest();
var url1="list_box_mgt.php?starting="+starting; 
  request_url(url1);
document.getElementById("box_list").innerHTML=xmlhttp.responseText;

}
</script>

<SCRIPT language="JavaScript">
function submitform()
{
    document.addBox.submit();
}
</SCRIPT> 
    <!--pagination-->
	<script language="JavaScript" src="../../../js/pagination/pagination_users.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/pagination/style.css" />
</head>
	

<body>
<div id="mainWideDiv">
  <div id="middleDiv2">
<?php include("../../admin/common/header.php");?>
	<?php include("../../admin/common/navigator.php");?>
  	<div id="buttonContainer">
  	  <div id="addMusicBttnAdmin">
              <div id='box_add'>
                <?php include('add_box_mgt.php');?>
               </div>
            
            <div id="bodyRgt">
                <div id='box_list'>
                  <?php include('list_box_mgt.php');?>
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
