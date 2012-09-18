<?php 
include("../../../config/config.php");
include("../../../controller/session_controller.php");

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
//function showHint(str)
function showHint(page,id)
{
  make_httpRequest();
 var url=""+page+".php?id="+id; 
  request_url(url);
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

}

function showHint2(page,name,file_id,folder_id)
{
  make_httpRequest();
    var url=""+page+".php?name="+name+"&file_id="+file_id+"&folder_id="+folder_id;

  request_url(url);

 document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

}


</script>
    <!--pagination-->
	<script language="JavaScript" src="../../../js/pagination/pagination_pics.js"></script>
    <link rel="stylesheet" type="text/css" href="../../../css/pagination/style.css" />
</head>

<body>

<table width="200" border="1">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;
       </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">
     <p>list categories</p>
    <div id='categories'>
    </div>
      <p>&nbsp;</p></td>
    <td valign="top">
    <div id='result'>Hi</div>
     <span id="txtHint">
     <?php include("list_music_by_cat_a_tbl.php");?>
     </span>
        <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
