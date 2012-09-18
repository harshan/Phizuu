var xmlHttp

function pagination(starting,pagename)
{
xmlHttp=GetXmlHttpObject();
if (xmlHttp==null)
  {
  alert ("Your browser does not support AJAX!");
  return;
  }
var url=pagename+".php";
url = url+"?starting="+starting;
/*//url = url+"&search_text="+document.form1.search_text.value;
url=url+"&sid="+Math.random();
xmlHttp.onreadystatechange=stateChanged;
xmlHttp.open("GET",url,true);
xmlHttp.send(null);*/

make_httpRequest();
  request_url(url);
document.getElementById("page_contents").innerHTML=xmlhttp.responseText;

make_httpRequest();
var page_arr=pagename.split("_");
var url1="add_"+ page_arr[1] +".php?id="; 
  request_url(url1);
document.getElementById(""+ page_arr[1] +"_add").innerHTML=xmlhttp.responseText;
} 

function stateChanged() 
{ 
if (xmlHttp.readyState==4)
{ 
document.getElementById("page_contents").innerHTML=xmlHttp.responseText;
}
}

function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  // Firefox, Opera 8.0+, Safari
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  // Internet Explorer
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}