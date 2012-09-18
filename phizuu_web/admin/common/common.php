<?php
@session_start();
set_time_limit(900);

require("db.php");

$tblAdmin ="tbl_admin";
$tblSettings ="tbl_settings";
$tblGender ="tbl_gender";
$tblPackage ="tbl_package";
$tblCountries ="tbl_countries";
$tblCreditCards ="tbl_credit_cards";
$tblMembers ="tbl_members";
$tbl_payment_information ="tbl_payment_information";

function tohtml($strValue)
{
  return htmlspecialchars($strValue);
}

function tourl($strValue)
{
  return urlencode($strValue);
}

function is_number($string_value)
{
  if(is_numeric($string_value) || !strlen($string_value))
    return true;
  else 
    return false;
}

function get_param($ParamName)
{
  global $_POST;
  global $_GET;

  $ParamValue = "";
  if(isset($_POST[$ParamName]))
    $ParamValue = $_POST[$ParamName];
  else if(isset($_GET[$ParamName]))
    $ParamValue = $_GET[$ParamName];

  return $ParamValue;
}
 
function is_param($param_value)
{
  if($param_value)
    return 1;
  else
    return 0;
}



function tosql($value, $type="")
{
  if($value == "")
    return "NULL";
  else
    if($type == "Number")
      return doubleval($value);
    else
    {
     
	  if(get_magic_quotes_gpc() == 0)
      {
        $value = str_replace("'","''",$value);
        $value = str_replace("\\","\\\\",$value);
		
		//$value = str_replace("&","",$value);
		$value = str_replace("|","",$value);
      }
      else
      {
        $value = str_replace("\\'","''",$value);
        $value = str_replace("\\\"","\"",$value);
		
		//$value = str_replace("&","",$value);
		$value = str_replace("|","",$value);
      }
		
      return "'" .$value. "'";
    }
}

//=================================



//=================================

function toSqlHtml($value)
{	  
	/**/$value = str_replace("&","&amp;",$value);
	$value = str_replace('"',"&quot;",$value);
	$value = str_replace("'","&#039",$value);
	$value = str_replace("’","&rsquo;",$value);
	//$value = str_replace("<","&lt;",$value);
	//$value = str_replace(">","&gt;",$value);
	//$value = htmlspecialchars($value, ENT_QUOTES);
	//$value = htmlspecialchars($value, ENT_NOQUOTES);
	return $value;
}

function forTexEditor($value)
{
	$value = str_replace("'","&#039",$value);
	$value = str_replace("’","&rsquo;",$value);
	return $value;
}

function forflash($value)
{	  
	$value = str_replace("&amp;","&",$value);
	$value = str_replace("&quot;",'"',$value);
	$value = str_replace("&#039","'",$value);
	return $value;
}

function forMessages($message)
{	  
	$message = htmlspecialchars($message, ENT_QUOTES);
	$message = htmlspecialchars($message, ENT_NOQUOTES);
	$message = strip_tags($message);
	$message = stripslashes($message);
	return $message;
}

function fromSqlHtml($value)
{
	$value = str_replace("#and#","&",$value);
	$value = str_replace("#*#","'",$value);
	return $value;
}


function fromSqlPara($value)// Obedit text load
{
	    $value = str_replace("#and#","&",$value);
        $value = str_replace("#*#","'",$value);
		$value = str_replace("#**#",'"',$value);	
		$value = str_replace("<B>Â¥</B>","Â¥",$value);
		$value = str_replace("<B>Â£</B>","Â£",$value);	
return $value;
}

function parentWindowRefresh()
{
	echo('<script type="text/javascript">window.opener.location.reload();self.close();</script>');
}

function get_checkbox_value($sVal, $CheckedValue, $UnCheckedValue)
{
  if(!strlen($sVal))
    return tosql($UnCheckedValue,"Text");
  else
    return tosql($CheckedValue,"Text");
}


 //get_options("select swfType,swfType from tbl_store_swftype",false,true,$swf)
function get_options($sql,$is_search,$is_required,$selected_value)
{
  global $db2;  //-- connection special for list box

  $options_str="";
  if ($is_search)
    $options_str.="<option value='Select'>Select</option>";
  else
  {
    if (!$is_required)
    {
      $options_str.="<option value=\"\"></option>";
    }
  }
  $rs  = mysql_query($sql) or die (mysql_error());
 // $db2->query($sql);
  while( $row = mysql_fetch_array($rs)) 
  {
   $id=$row[0];
   $value=$row[1];
    $selected="";
    if ($id == $selected_value)
    {
      $selected = "SELECTED";
    }
    $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
  }
  return $options_str;
}

function cmToFtandIn($val) {
	$ft = floor($val / 30);	
	$mCm = $val - $ft * 30;
	return $ft."ft ". floor($mCm * .394).'in';
}

function get_optionsHeight($sql,$is_search,$is_required,$selected_value)
{
  global $db2;  //-- connection special for list box

  $options_str="";
  if ($is_search)
    $options_str.="<option value='Select'>Select</option>";
  else
  {
    if (!$is_required)
    {
      $options_str.="<option value=\"\"></option>";
    }
  }
  $rs  = mysql_query($sql) or die (mysql_error());
 // $db2->query($sql);
  while( $row = mysql_fetch_array($rs)) 
  {
   $id=$row[0];
   $value=$row[1];
    $selected="";
    if ($id == $selected_value)
    {
      $selected = "SELECTED";
    }
    $options_str.= "<option value='".$id."' ".$selected.">".cmToFtandIn($value)." - ".$value."cm</option>";
  }
  return $options_str;
}

function get_optionsRegion($sql,$is_search,$is_required,$selected_value)
{
  global $db2;  //-- connection special for list box

  $options_str="";
  if ($is_search)
    $options_str.="<option value='Select'>Select</option>";
  else
  {
    if (!$is_required)
    {
      $options_str.="<option value=\"\"></option>";
    }
  }
  $rs  = mysql_query($sql) or die (mysql_error());
 // $db2->query($sql);
  while( $row = mysql_fetch_array($rs)) 
  {
   $id=$row[0];
   $value=$row[1];
    $selected="";
    if ($id == $selected_value)
    {
      $selected = "SELECTED";
    }
	if($value == "-")
	{
		$options_str.= '<optgroup label="------------------------------"></optgroup>';
	}else{
		$options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
	}
    
  }
  return $options_str;
}

function get_optionsDC($sql,$is_search,$is_required,$selected_value)
{
	global $db2;  //-- connection special for list box
	
	$options_str="";
	$selected1="";
	$selected2="";
	if($selected_value == "-1")
	{
		$selected1="SELECTED";
		$selected2="";
	}else if($selected_value == "0"){
		$selected1="";
		$selected2="SELECTED";
	}
	$options_str.="<option value='-1' ".$selected1.">New</option>";
	$options_str.="<option value='0' ".$selected2.">All</option>";
  
  $rs  = mysql_query($sql) or die (mysql_error());
 // $db2->query($sql);
  while( $row = mysql_fetch_array($rs)) 
  {
   $id=$row[0];
   $value=$row[1];
    $selected="";
    if ($id == $selected_value)
    {
      $selected = "SELECTED";
    }
    $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
  }
  return $options_str;
}


function get_lov_options($lov_str,$is_search,$is_required,$selected_value)
{
  $options_str="";
  if (!$is_required && !$is_search)
    $options_str.="<option value=\"\"></option>";

  $LOV = split(";", $lov_str);

  if(sizeof($LOV)%2 != 0) 
    $array_length = sizeof($LOV) - 1;
  else
    $array_length = sizeof($LOV);
  reset($LOV);

  for($i = 0; $i < $array_length; $i = $i + 2)
  {
    $id =  $LOV[$i];
    $value = $LOV[$i + 1];
    $selected="";
    if ($id == $selected_value)
      $selected = "SELECTED";

    $options_str.= "<option value='".$id."' ".$selected.">".$value."</option>";
  }
  return $options_str;
}

function get_lov_values($lov_str)
{
  $options_str="";
  $LOV = split(";", $lov_str);

  if(sizeof($LOV)%2 != 0) 
    $array_length = sizeof($LOV) - 1;
  else
    $array_length = sizeof($LOV);
  reset($LOV);

  $values = array();
  for($i = 0; $i < $array_length; $i = $i + 2)
  {
    $id =  $LOV[$i];
    $value = $LOV[$i + 1];
    $values[$id] = $value;
  }
  return $values;
}

function se_encrypt($string) {
$skey ="sd!%as^qedgh#54$$@;[+";
    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
    }

    return base64_encode($result);
  }


function se_decrypt($string) {
$skey ="sd!%as^qedgh#54$$@;[+";
    $string = base64_decode($string);

    for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
    }

    return $result;
}
//echo(se_decrypt("×møç"));

function GetCheck($value)
{
	if ($value=="true")
	{
		return "checked=checked";
	}else{
		return "";
	}
}

function GetCheckForPro($value)
{
	if ($value=="" || $value=="0" || $value=="2")
	{
		return "";
	}else{
		return "checked=checked";
	}
}

function check_security()
{
	if (isset($_SESSION['username'])) {
	
	} else {
	
		header( "Location: index.php?login=error" );
		exit;
	}
}

function getCurrencySymbole()
{
	$symbol = "&pound;";
	return $symbol;
}
function getCurrencyCode()
{
	$currencyCode = "GBP";
	return $currencyCode;
}
function getAdminEmail()
{
	global $tblSettings;
	
	$sql = "select email from ".$tblSettings." where autoid='1'";
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
	
	return $row['email'];
}

function format_phone($phone){
  $phone = preg_replace("/[^0-9]/", "", $phone);
  if(strlen($phone) == 7)
  	return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
  elseif(strlen($phone) == 10)
 	 return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
  else
  	return $phone;
}

function getNumOfSubs($subId)
{
	global $tblNavigation;
	
	$sql = "select subId from ".$tblNavigation." where subId=".tosql($subId,"Number")." AND menutype='Sub'";
	$rs  = mysql_query($sql) or die (mysql_error());
	$num = mysql_num_rows($rs);
	
	return $num;
}

function VALIDATE_USPHONE($phonenumber,$useareacode=true)
{
/*if ( preg_match("/^[ ]*[(]{0,1}[ ]*[0-9]{3,3}[ ]*[)]{0,1}[-]{0,1}[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[ ]*$/",$phonenumber) || (preg_match("/^[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[ ]*$/",$phonenumber) && !$useareacode)) {
	return true;
}else{
	return false;
}*/

	/*if(ereg("^[0-9]{3}-[0-9]{3}-[0-9]{4}$", $phonenumber))
	{
		return true;
	}else
	{
		return false;
	}*/
	if(is_numeric($phonenumber))
		return true;
	else
		return false;
}
//character limit
function limitchrmid($value,$lenght){
    if (strlen($value) >= $lenght ){
		$lenght_max = ($lenght);
        //$lenght_max = ($lenght/2)-3;
        //$start = strlen($value)- $lenght_max;
        $limited = substr($value,0,$lenght_max);
        $limited.= " ... ";                  
        //$limited.= substr($value,$start,$lenght_max);
    }
    else{
        $limited = $value;
    }
    return $limited;
} 
/*
$stack=Array('apple','banana','pear','apple', 'cherry', 'apple');
array_remval("apple", $stack);

//output: Array('banana','pear', 'cherry')
*/
function array_remval($val,$arr)
{
	  $array_remval = $arr;
	  for($x=0;$x<count($array_remval);$x++)
	  {
		  $i=array_search($val,$array_remval);
		  if (is_numeric($i)) {
			  $array_temp  = array_slice($array_remval, 0, $i );
			  $array_temp2 = array_slice($array_remval, $i+1, count($array_remval)-1 );
			  $array_remval = array_merge($array_temp, $array_temp2);
		  }
	  }
	  return $array_temp;
}
function  validfiles($type)
{
    $file_types  = array(   
    'image/pjpeg'     => 'jpg', 
    'image/jpeg'     => 'jpg',
    'image/jpeg'     => 'jpeg',
    'image/gif'     => 'gif',
    'image/X-PNG'    => 'png', 
    'image/PNG'         => 'png', 
    'image/png'     => 'png', 
    'image/x-png'     => 'png', 
    'image/JPG'     => 'jpg',
    'image/GIF'     => 'gif',
	'application/x-shockwave-flash'     => 'swf',
    );
    
    if(array_key_exists($type, $file_types))
    {
       return $file_types[$type];
    }else
	{
		return "";
	}
}
function doNf($value){
	
	if(strlen($value) == 1){
		$r = "00";
	}else if(strlen($value) == 2){
		$r = "0";
	}
	return $r;
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function StripUrl($title)
{
	$title = trim($title);
	$title = str_replace("#", "sharp", $title);
	
	$title = str_replace("/", "or", $title);
	
	$title = str_replace("$", "", $title);
	
	$title = str_replace("&amp;", "and", $title);
	
	$title = str_replace("&", "and", $title);
	
	$title = str_replace("+", "plus", $title);
	
	$title = str_replace(",", "", $title);
	
	$title = str_replace(":", "", $title);
	
	$title = str_replace(";", "", $title);
	
	$title = str_replace("=", "equals", $title);
	
	$title = str_replace("?", "", $title);
	
	$title = str_replace("@", "at", $title);
	
	$title = str_replace("<", "", $title);
	
	$title = str_replace(">", "", $title);
	
	$title = str_replace("%", "", $title);
	
	$title = str_replace("{", "", $title);
	
	$title = str_replace("}", "", $title);
	
	$title = str_replace("|", "", $title);
	
	$title = str_replace("\\", "", $title);
	
	$title = str_replace("^", "", $title);
	
	$title = str_replace("~", "", $title);
	
	$title = str_replace("[", "", $title);
	
	$title = str_replace("]", "", $title);
	
	$title = str_replace("`", "", $title);
	
	$title = str_replace("'", "", $title);
	
	$title = str_replace("\"", "", $title);
	
	$title = str_replace(" ", "-", $title);
	
	return strtolower($title);
}

function do_paypal_settings()
{

global $tblSettings;

		$sql = "select * from ".$tblSettings." where payment_id=1";
		$rs  = mysql_query($sql) or die (mysql_error());
		while( $row = mysql_fetch_array($rs)) 
		{
			$api_uername=$row['api_uername'];
			$api_password=$row['api_password'];
			$api_signature=$row['api_signature'];
			$paypal_transaction=$row['paypal_transaction'];
			
			if ($paypal_transaction == 'Live') {
				$api_url = 'https://api-3t.paypal.com/nvp';
				$paypal_url = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
			  } else {
				$api_url = 'https://api-3t.sandbox.paypal.com/nvp';
				$paypal_url = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
			  }
			$paypal .="<?php\r\n";
			$paypal .="define('API_USERNAME', '".$api_uername."');\r\n";
			$paypal .="define('API_PASSWORD', '".$api_password."');\r\n";
			$paypal .="define('API_SIGNATURE', '".$api_signature."');\r\n";
			$paypal .="define('API_ENDPOINT', '".$api_url."');\r\n";
			$paypal .="define('USE_PROXY', FALSE);\r\n";
			$paypal .="define('PROXY_HOST', '127.0.0.1');\r\n";
			$paypal .="define('PROXY_PORT', '808');\r\n";
			$paypal .="define('PAYPAL_URL', '".$paypal_url."');\r\n";
			$paypal .="define('VERSION', '60.0');\r\n";
			$paypal .="define('ACK_SUCCESS', 'SUCCESS');\r\n";
			$paypal .="?>\r\n";
		}

  $file= fopen("../constants.php" , "w"); //fopen -- Opens file or URL
  fwrite($file, $paypal); //fwrite -- Binary-safe file write
  fclose($file); //fclose -- Closes an open file pointer
}

function paypal_settingsDetails($field)
{
	global $tblSettings;
	$sql = "select * from ".$tblSettings." where payment_id=1";
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
		
	return $row[$field];
}



function Now(){
    $today = date('Y-m-d H:i:s');
    return $today;
}


function getCreditCard($id){
	global $tblCreditCards;
	
	$sql = "select credit_card from ".$tblCreditCards." where autoid=$id";
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
	
	return $row['credit_card'];
}

function deleteAbandonedCart()
{
	/*global $tbl_payment_information;
	
	$yesterday = date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
	$sql = "DELETE FROM ".$tbl_payment_information." WHERE status ='false' AND date < '$yesterday'";
	mysql_query($sql) or die (mysql_error());*/
}

function getOrderNo($id){
	$orderNo = 1000+$id;
	return 	$orderNo;
}

function sendOrderConfirm($id)
{	
	require_once 'XPertMailer.php';
	
	global $tbl_payment_information;

	$sql = "select * from ".$tbl_payment_information." where autoid=".tosql($id,"Number");
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
	$autoid = $row["autoid"];
	$fname = $row["fname"];
	$lname = $row["lname"];
	$artist_name = $row["artist_name"];
	$label_name = $row["label_name"];
	$email = $row["email"];
	$username = $row["username"];
	$password = $row["password"];
	$application_name = $row["application_name"];
	$package = $row["package"];
	$price = $row["price"];
        $recurrent = $row['recurrent_payments'];
        $recurrentAmt = $row['recurrent_amount'];
        $recurrentProfId = $row['recurrent_profile_id'];
 
	$name = $fname." ".$lname;
	
	// Seller ===========================================================
	$base_email = getAdminEmail();
	
	$subject = "phizuu Order confirm";		
	$mail = new XPertMailer;	
	$mail->from($base_email, 'phizuu');
	
	$text = '';

	$msg = "<html>\n";
	$msg .= "<body>\n";
	$msg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'>\n";
	$msg .= "This email confirms ".$name." has paid  $".$price."<br/><br/>\n";
	$msg .= "Order Number:".getOrderNo($id)."<br>\n";
	$msg .= "Name: ".$name."<br>\n";
	$msg .= "Email: ".$email."<br>\n";
	$msg .= "Username: ".$username."<br>\n";
	$msg .= "Password: *********<br>\n";
	$msg .= "Package: ".$package."<br>\n";
	$msg .= "Price: $".$price."<br>\n";
        if($recurrent==1) {
            $msg .= "Recurrent Amount (Per Month): $".$recurrentAmt."<br>\n";
            $msg .= "Recurrent profile id: ".$recurrentProfId."<br>\n";
        } else {
            $msg .= "Recurrent Payment: Not Set<br>\n";
        }
	$msg .= "</span></body>\n";
	$msg .= "</html>\n";
	
        //echo $msg;

	$send = $mail->send('info.phizuu@gmail.com', $subject, $text, $msg);	//'info.phizuu@gmail.com' is secondary mail that forwads mails to info@phizuu.com [Debugging solution]

	// End Seller ======================================================

	// Buyer ===========================================================
	$subject = "phizuu ( Thank you for Purchasing )";
	$mail = new XPertMailer;	
	$mail->from($base_email, 'phizuu');	
	
	$buyerMsg = "<html>\n";
	$buyerMsg .= "<body>\n";
	$buyerMsg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'>\n";
	$buyerMsg .= "This email confirms you have paid  $".$price."<br/><br/>\n";
	$buyerMsg .= "Order Number:".getOrderNo($id)."<br>\n";
	$buyerMsg .= "Name: ".$name."<br>\n";
	$buyerMsg .= "Email: ".$email."<br>\n";
	$buyerMsg .= "Username: ".$username."<br>\n";
	$buyerMsg .= "Package: ".$package."<br>\n";
	$buyerMsg .= "Price: $".$price."<br>\n";
        if($recurrent==1) {
            $buyerMsg .= "Recurrent Amount (Per Month): $".$recurrentAmt."<br>\n";
        }

	$buyerMsg .= "</span></body>\n";
	$buyerMsg .= "</html>\n";

        //echo $buyerMsg;
	
	$send = $mail->send($email, $subject, $text, $buyerMsg);
	
}

function sendRecurrentFailedNotification($id, $failed = false) {
	require_once 'XPertMailer.php';

	global $tbl_payment_information;

	$sql = "select * from ".$tbl_payment_information." where autoid=".tosql($id,"Number");
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
	$autoid = $row["autoid"];
	$fname = $row["fname"];
	$lname = $row["lname"];
	$artist_name = $row["artist_name"];
	$label_name = $row["label_name"];
	$email = $row["email"];
	$username = $row["username"];
	$password = $row["password"];
	$application_name = $row["application_name"];
	$package = $row["package"];
	$price = $row["price"];
	$name = $fname." ".$lname;

	// Seller ===========================================================
	$base_email = getAdminEmail();

        if($failed)
            $subject = "phizuu - Recurrent Payments Failed";
        else
            $subject = "phizuu - Setup Recurrent Payments";
        
        $mail = new XPertMailer;
	$mail->from($base_email, 'phizuu');

	$text = '';

	$msg = "<html>\n";
	$msg .= "<body>\n";
	$msg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'><br/>\n";
        $msg .= "Dear ".$name.",<br/><br/>\n";
        if($failed)
            $msg .= "Unfortunately, setting up your recurrent payments failed.";
        else
            $msg .= "You did not create a recurrent payment profile.";
	$msg .= " In order to continue your application, you should setup monthly payments as recurrent payments for phizuu LLC.<br><br>\n";
	$msg .= "Please log in to the CMS and setup recurrent payments once you complete the application. ";
	$msg .= "If you have any problems, please feel free to contact us by replying to this.\n";
        $msg .= "<br/><br/>-Team phizuu\n";
	$msg .= "</span></body>\n";
	$msg .= "</html>\n";

        //echo $msg;

	$send = $mail->send($email, $subject, $text, $msg);	//'info.phizuu@gmail.com' is secondary mail that forwads mails to info@phizuu.com [Debugging solution]
								// Password is phizuupayment
}

function check_HTTPS()
{
	/**/
	$page = "https://";	
	if ($_SERVER["HTTPS"] != "on")
	{		
		if ($_SERVER["SERVER_PORT"] != "80") {
			$page .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$page .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		header("Location:".$page);
		exit;		
	}
}
function check_HTTP()
{
	/**/
	if ($_SERVER["HTTPS"] == "on")
	{
		$page = "http://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$page .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$page .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		header("Location:".$page);
		exit;	
	}	
}

function PageURL() {
 /**/
 $pageURL = "https://";
 
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/new/";
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"]."/new/";
 }
 
 return $pageURL;
 
}

function deleteAbandonedMembers()
{
	$yesterday = date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - 1, date('Y')));
	$sql = "DELETE FROM tbl_members
	        WHERE date < '$yesterday' AND status='false'";
	mysql_query($sql) or die (mysql_error());
}

function signUpUser() {
    global $username,$password,$email,$package_id,$package, $fname;

    $emailCode = '';
    for ($i=0; $i<64; $i++) {
        $emailCode .= chr(rand(97,122));
    }

    $sql =
    "INSERT INTO user (
        `username`,
        `password`,
        `app_id`,
        `app_name`,
        `email`,
        `parent`,
        `package_id`,
        `status`,
        `is_suspended`,
        `box_id`,
        `paid`,
        `email_code`
    ) VALUES (
        '$username',
        md5('$password'),
        0,
        '',
        '$email',
        NULL,
        '$package_id',
        0,
        1,
        1,
        1,
        '$emailCode'
    )";


    $dao = new Dao();
    $res = $dao->query($sql);
    $userId = mysql_insert_id();

    $sql = "INSERT INTO app_wizard_warnings(user_id,created_date,warning_count) VALUES ('$userId',NOW(),0)";
    $dao->query($sql);

    $_SESSION['user_id'] = $userId;

    $navigator = new Navigator($_SESSION['user_id']);
    $navigator->isFirstTime();
    $navigator->setCurrentStep(1);


    $link = "http://phizuu.com/confirm_email.php?id=$userId&code=$emailCode";
    sendSignUpEmail($username, $email, $password, $link, $package, $fname);
}

function sendSignUpEmail($username, $email, $password, $link, $packageName, $fname)
{
	require_once 'XPertMailer.php';

	$subject = "Welcome to phizuu";
	$mail = new XPertMailer;
	$mail->from('info@phizuu.com', 'phizuu');

	$text = '';

	$msg = "<html>\n";
	$msg .= "<body>\n";
	$msg .= "<span style='font-family:Arial, Helvetica, sans-serif;font-size:12px'>";
        $msg .= "Hey $fname,";
        $msg .= "<br/><br/>Congrats, you now have access to phizuu where you will be able to create your own custom iPhone application.";
        $msg .= "<br/><br/>Please confirm that you own this email address by clicking the link below and you're ready to go!";
        $msg .= "<br/><br/>";
	$msg .= "<a href='$link'>$link</a>";
	$msg .= "<br/>Username: $username";
	$msg .= "<br/>Password: $password";
        $msg .= "<br/><br/>Thank you for joining phizuu!!";
	$msg .= "<br/><br/>";
	$msg .= "-Team phizuu";
	$msg .= "</span></body>\n";
	$msg .= "</html>\n";

	$send = $mail->send($email, $subject, $text, $msg);
}
?>
