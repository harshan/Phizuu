<?php
require_once("../../../controller/session_controller.php");
require_once ("../../../config/config.php");

//for pagination
if(isset($_GET['starting'])&& !isset($_REQUEST['submit'])){
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

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
  name=document.addTours.name.value;
  date=document.addTours.date.value;
  location1=document.addTours.location1.value;
  notes=document.addTours.notes.value;
  status=document.addTours.status.value;
  starting=document.addTours.starting.value;

  var url="../../../controller/tours_add_controller.php?name="+name+"&date="+date+"&notes="+notes+"&location1="+location1+"&status="+status+"&starting="+starting;
  var url_add="";
  if(document.addTours.status.value == "edit"){
   url_add="&id="+document.addTours.id.value; 
  
 }else{
 url_add=""; 
 }
 
 if((document.addTours.status.value == "edit") && (name == "")){
 url="edit_tours.php?id="+document.addTours.id.value+"&starting="+starting+"&msg_error"; 
 url_add="";
}
  request_url(url+url_add);
document.getElementById("tours_add").innerHTML=xmlhttp.responseText;

//=============

make_httpRequest();
var url1="list_tours.php?starting="+starting; 
  request_url(url1);
document.getElementById("tours_list").innerHTML=xmlhttp.responseText;

//=============


}

function showEdit(page,id,starting)
{

make_httpRequest();
var url1=""+page+".php?id="+id+"&starting="+starting+""; 
  request_url(url1);
document.getElementById("tours_add").innerHTML=xmlhttp.responseText;


}

function showDelete(page,id,status,starting)
{

if (delete_confirm()){

  make_httpRequest();
 
  var url=""+page+".php?id="+id+"&status="+status+"&starting="+starting;
  request_url(url);

//=============

make_httpRequest();
var url1="list_tours.php?starting="+starting; 
  request_url(url1);
document.getElementById("tours_list").innerHTML=xmlhttp.responseText;
}
}
function calendar(){
//<![CDATA[

      var cal = Calendar.setup({
          onSelect: function(cal) { cal.hide() }
      });
      cal.manageFields("f_btn1", "date", "%Y-%m-%d");
    //]]>
}

</script>
<SCRIPT language="JavaScript">
function submitform()
{
    document.addtours.submit();
}

function delete_confirm(){
return confirm("Are you sure you want to delete");
 
}
</SCRIPT> 
 	<!--calendar-->
	<script src="../../../js/calendar/jscal2.js"></script>
    <script src="../../../js/calendar/en.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/calendar/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../../../css/calendar/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="../../../css/calendar/steel/steel.css" />
    <!--pagination-->
	<script language="JavaScript" src="../../../js/pagination/pagination.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/pagination/style.css" />
</head>

<body>
<table width="200" border="1">
  <tr>
    <td>&nbsp;</td>

  </tr>
  <tr>
    <td>
    <div id='tours_add'>
    <?php include('add_tours.php');?>
    </div>
    </td>

  </tr>
  <tr>
    <td>
    <div id='tours_list'>
    <?php include('list_tours.php');?>
    
    </div>
    </td>

  </tr>
</table>
</body>
</html>
