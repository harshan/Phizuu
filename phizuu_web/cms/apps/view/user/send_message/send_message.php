<?php
require_once "../../../config/app_key_values.php";
require_once "../../../controller/ab_module_controller.php";
session_start();

include("../../../config/config.php");
include("../../../database/Dao.php");
include("../../../controller/session_controller.php");
require_once '../../../model/PushNotifications.php';

require_once '../../../config/database.php';
include('../../../controller/db_connect.php');
include('../../../controller/helper.php');
include('../../../config/error_config.php');

$menu_item="send_message";

$pushNotification = new PushNotifications();
$allowedMessages = $pushNotification->getRemainingPushMessages($_SESSION['user_id']);

if ($allowedMessages>0) {
    $heading = "Send Message to Fans (Remaining $allowedMessages messages)";
} else {
    $heading = "Your message limit is over please try tommorow!";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Phizuu Application</title>
<link href="../../../css/styles.css" rel="stylesheet" type="text/css" />

<?php 
if($_SERVER["SERVER_NAME"]==app_key_values::$LIVE_SERVER_DOMAIN || $_SERVER["SERVER_NAME"]=='localhost') {
?>
 <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=ABQIAAAAVqOqr2StKxM49q-GDo3y3xQOY0W_dtlPxhBcvZR1QIs6CBcSvRQZXZwbUnh_VHq781oSUooAMlV_Kw" type="text/javascript"></script> 
<?php
}else{
 ?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=AIzaSyCbkQyNlC1ZPyrbGhqBKxW9M9borsp1UbI" type="text/javascript"></script> 
<?php
}
?>


 <script type="text/javascript" src="../../../common/popup.js" ></script>
<script type="text/javascript">

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

    var map;
    var geocoder;
    var address;
    function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      map.setCenter(new GLatLng(40.730885,-73.997383), 1);
      map.setUIToDefault();
      GEvent.addListener(map, "click", getAddress);
      geocoder = new GClientGeocoder();
    }
    
    function getAddress(overlay, latlng) {
      if (latlng != null) {
        address = latlng;
        geocoder.getLocations(latlng, showAddress);
      }
    }

    function showAddress(response) {
      map.clearOverlays();
      if (!response || response.Status.code != 200) {
        alert("Status Code:" + response.Status.code);
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
                            place.Point.coordinates[0]);
        marker = new GMarker(point);
        map.addOverlay(marker);
		setLocation (point.lat(), point.lng());
		document.getElementById('txtLocation').value = place.address ;
        /*marker.openInfoWindowHtml(
        '<b>orig latlng:</b>' + response.name + '<br/>' + 
        '<b>latlng:</b>' + place.Point.coordinates[1] + "," + place.Point.coordinates[0] + '<br>' +
        '<b>Status Code:</b>' + response.Status.code + '<br>' +
        '<b>Status Request:</b>' + response.Status.request + '<br>' +
        '<b>Address:</b>' + place.address + '<br>' +
        '<b>Accuracy:</b>' + place.AddressDetails.Accuracy + '<br>' +
        '<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode);*/
      }
    }

    function searchAddress() {
		address = document.getElementById('txtLocation').value;
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found please try again!");
            } else {
				setLocation (point.lat(), point.lng());
              map.setCenter(point, 13);
			  marker = new GMarker(point);
        		map.addOverlay(marker);
              //var marker = new GMarker(point);
              //map.addOverlay(marker);
              //marker.openInfoWindowHtml(address);
            }
          }
        );
      }
    }
	var latG = '';
	var lngG = '';
	function setLocation(lat, lng) {
		document.getElementById('divLatLan').innerHTML = "Latitude: " +  lat + " | Longitude: " + lng;
		latG = lat;
		lngG = lng;
	}

	function showHideLocation() {
		if(document.getElementById('radioAudiance').checked) {
			document.getElementById('locTr1').style['display'] = 'none';
			document.getElementById('locTr2').style['display'] = 'none';
		} else {
			document.getElementById('locTr1').style['display'] = '';
			document.getElementById('locTr2').style['display'] = '';
			document.getElementById('txtRange').style['display'] = 'none';
		}
	}
	
	function rangeOnChange() {
		if (document.getElementById('cmbRange').value == '-1')
			document.getElementById('txtRange').style['display'] = '';
		else 
			document.getElementById('txtRange').style['display'] = 'none';
	}

	function onSubmit() {
		if (document.getElementById('txtMessage').value == '') {
			alert ("Error: Please enter the message");
			return false;
		} else if (document.getElementById('txtMessage').value.length > 150) {
                        alert ("Error: Length of the message should not be longer than 150 characters");
			return false;
                } else if (!document.getElementById('radioAudiance').checked) {
			if (document.getElementById('latLan').value == '') {
				alert("Error: Select a location, by clicking on 'Choose Location'!");
				return false;
			}
			if (document.getElementById('cmbRange').value == '-1' && document.getElementById('txtRange').value == '') {
				alert("Error: Select a range to send the message!");
				return false;
			}
		}
	}

        function onChangeMessage() {
            var text = document.getElementById('txtMessage').value;
            var divElem = document.getElementById('charCountDiv');
            if (text.length <= 150) {
                divElem.innerHTML = 150 - text.length + " chars left";
            } else {
                divElem.innerHTML = "Message exeeds the limit!";
            }
			window.setTimeout("onChangeMessage()",500);
        }

        function showChooseLoaction() {
            Popup.showModal('mapView');
            initialize();
        }

        function locationChoosen() {
			if (latG != '') {
				document.getElementById('idChoosenLocation').innerHTML = document.getElementById('txtLocation').value + " - (" + latG + "|" + lngG + ") ";
				document.getElementById('latLan').value = latG + ";" + lngG;
				Popup.hide('mapView');
			} else {
				alert("Error: Select a location, either by searching or selecting location on the map!");
			}
        }
    </script>
</head>

<body onload="MM_preloadImages('../../../images/musicAc.png','../../../images/VideoAc.png','../../../images/photosAc.png','../../../images/flyersAc.png','../../../images/newsAc.png','../../../images/ToursAc.png','../../../images/linksAc.png','../../../images/settingsAc.png'); onChangeMessage()">
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
                    <?php
                    if(isset ($_GET['success']) && $_GET['success']=='ok') {
                    ?>
                    <div class="tahoma_12_blue" style="font-weight: bold; height: 30px">Message sent Successfully!</div>
                    <?php
                    } else if ((isset ($_GET['success']) && $_GET['success']=='error') ) {
                    ?>
                    <div class="tahoma_12_blue" style="font-weight: bold; height: 30px ">Error in sending the message! <span style="font-size: 10px">(<?php echo $_GET['resp'] ?>)</span></div>

                    <?php
                    }
                    ?>
			<form name="sndFrm" action="../../../controller/push_notifications_controller.php?action=send_message" method="post" onsubmit="return onSubmit();">
				<input type="hidden" name="latLan" id='latLan' />
                                <div id="lightBlueHeader2">
	  	
	  	<div class="tahoma_14_white" id="lightBlueHeaderMiddle2"  style="border-radius: 3px 3px 0 0;-moz-border-radius: 3px 3px 0 0"><?php echo $heading ?></div>
	  	
	  </div>
          <?php if($allowedMessages>0) { ?>
				<table width="922" style="float:left;" class="tahoma_12_blue" cellpadding="4">
					<tr>
                                            <td width="111" valign="top" ><strong>Message</strong><br/> (maximum 150 characters)</td>
                                            <td colspan="2"><textarea  class="textFeildBoarder" name="txtMessage" id="txtMessage" style="width:300px; height: 100px"></textarea>
                                                    <div style="width:300px; text-align: right" id ="charCountDiv"></div>                                                </td>
					</tr>
                                        <tr>
                                            <td width="111" valign="top"><strong>Module</strong></td>
                                            <td><select name="module">
                                                    <option value=""></option>
                                                    <?php 
                                                    $abModuleController = new ab_module_controller();
                                                    
                                                    $abModules = $abModuleController->getAllModulesByUser($_SESSION['user_id']);
                                                    foreach($abModules as $value){ 
                                                        ?>
                                                   <option value="<?php echo $value['module_name'] ;?>"><?php echo $value['module_name'] ;?></option>
                                                   <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
					<tr>
						<td width="111" valign="top"><strong>Audience</strong></td>
                                                <td width="81"><input class="textFeildBoarder" style="border: 0" name="radioAudiance" id="radioAudiance" type="radio" value="all" checked="checked" onclick="showHideLocation();" />
				      All Fans </td>
						
					    <td width="489"><input class="textFeildBoarder" style="border: 0" name="radioAudiance" type="radio" value="location" onclick="showHideLocation();" />
			          Fans Near Me </td>
					</tr>
					<tr id='locTr1' style="display:none">
					  <td valign="top"><strong>Range:</strong></td>
					  <td><select class="textFeildBoarder" name="cmbRange" id="cmbRange" onchange="rangeOnChange();">
					    <option value="1" selected="selected">1 mile</option>
					    <option value="5">5 miles</option>
					    <option value="10">10 miles</option>
					    <option value="100">100 miles</option>
					    <option value="-1">Specify (Miles)</option>
					    </select>					  </td>
					  <td><input name="txtRange" type="text" id="txtRange"  style="width: 50px; text-align: right; display:none"/> </td>
				  </tr>
					<tr id='locTr2' style="display:none">
					  <td valign="top"><strong>Select Location: </strong></td>
				      <td colspan="2" valign="top">

<span id="idChoosenLocation"></span><a href="#" onclick="showChooseLoaction(); return false;">Choose Location </a>
</td>
			      </tr >
					<tr>
					  <td valign="top">&nbsp;</td>
				      <td colspan="2" valign="top"><input type="submit" value='Send Message' /></td>
			      </tr>
					
			  </table>
                                <?php } ?>
			  <br />

			  
		  </form>
		</div>
	</div>
	
</div>
<div id='mapView'  class="tahoma_12_blue" style="background-color:#FFFFFF; padding: 10px; border: #07738a 2px solid; display: none" >
    <input class="textFeildBoarder" name="txtLocation" type="text" id="txtLocation" style="width: 200px; " />  <input name="Button" type="button" id="Button" value="Search" onclick="searchAddress();" /> | <input type="button" value="Choose This Location" onclick="locationChoosen();" />
        <img border="0" src="../../../images/x_close.png" style="float:right; cursor: pointer" onclick="javascript: Popup.hide('mapView');"/>
        <br/><span style="color:#CCCCCC" id='divLatLan'></span>
	<div id="map_canvas" style="width: 500px; height: 400px"></div>
</div>
    <br class="clear"/>
     <div id="footerInner" >
    <div class="lineBottomInner"></div>
	<div class="tahoma_11_gray" >&copy; 2012 phizuu. All Rights Reserved.</div>
  </div>
</body>
</html>
