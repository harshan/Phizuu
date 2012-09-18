<?php
session_start();
require_once 'cms/apps/config/config.php';
require_once 'cms/apps/database/Dao.php';
require_once 'cms/apps/model/app_builder/Navigator.php';

//include("admin/common/common.php");
require_once 'CallerService.php';
require_once 'constants_new.php';


$environment = paypal_settingsDetails("paypal_transaction");
 
$API_UserName = urlencode(paypal_settingsDetails("api_uername"));;
$API_Password = urlencode(paypal_settingsDetails("api_password"));
$API_Signature = urlencode(paypal_settingsDetails("api_signature"));


$API_Endpoint = API_ENDPOINT_LIVE;
if("Sandbox" === $environment || "beta-sandbox" === $environment) {
	$API_Endpoint = API_ENDPOINT_SANDBOX;
}

$subject = SUBJECT;

//$AuthenticationMode = API_AUTHENTICATION_MODE;

if(isset($_REQUEST["a"]) && isset($_SESSION["pid"])){
    $sql = "select * from tbl_members where autoid=".$_SESSION["pid"];
    $rs  = mysql_query($sql) or die (mysql_error());
    $row = mysql_fetch_assoc($rs);

    $fname = $row["fname"];
    $lname = $row["lname"];
    $address = $row["address"];
    $city = $row["city"];
    $state = $row["state"];
    $zip = $row["zip"];
    $country = $row["country"];
    $artist_name = $row["artist_name"];
    $gender = $row["gender"];
    $label_name = $row["label_name"];
    $email = $row["email"];
    $username = $row["username"];
    $password = $row["password"];
    $application_name = $row["application_name"];
    $package = $row["package"];
    $price = $row["price"];
	
}else{
    header( "Location: ".$webUrl."pricing.html" );
}



$paymentType = urlencode('Sale');//Authorization or 'Sale'
$firstName = urlencode($fname);
$lastName = urlencode($lname);
$creditCardType = urlencode('visa');
$creditCardNumber = urlencode(se_decrypt($_SESSION['cc_number']));
$expDateMonth = $_SESSION['expiry_m'];
// Month must be padded with leading zero
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));

$expDateYear = urlencode($_SESSION['expiry_y']);
$cvv2Number = urlencode(se_decrypt($_SESSION['cvvNumber']));
$address1 = urlencode($address);
$address2 = urlencode('');
$city = urlencode($city);
$state = urlencode($state);
$zip = urlencode($zip);
$country = urlencode($country); // US or other valid country code
$amount = urlencode($price);
$currencyCode="USD";
$paymentType=urlencode('Authorization');


/* Construct the request string that will be sent to PayPal.
   The variable $nvpstr contains all the variables and is a
   name value pair string with & as a delimiter */
$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
"&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyCode";

$getAuthModeFromConstantFile = true;
//$getAuthModeFromConstantFile = false;
$nvpHeader = "";

if(!$getAuthModeFromConstantFile) {
	//$AuthMode = "3TOKEN"; //Merchant's API 3-TOKEN Credential is required to make API Call.
	//$AuthMode = "FIRSTPARTY"; //Only merchant Email is required to make EC Calls.
	$AuthMode = "THIRDPARTY"; //Partner's API Credential and Merchant Email as Subject are required.
} else {
	if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature) && !empty($subject)) {
		$AuthMode = "THIRDPARTY";
	}else if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature)) {
		$AuthMode = "3TOKEN";
	}else if(!empty($subject)) {
		$AuthMode = "FIRSTPARTY";
	}
}

switch($AuthMode) {
	
	case "3TOKEN" : 
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature);
			break;
	case "FIRSTPARTY" :
			$nvpHeader = "&SUBJECT=".urlencode($subject);
			break;
	case "THIRDPARTY" :
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature)."&SUBJECT=".urlencode($subject);
			break;		
	
}

$nvpstr = $nvpHeader.$nvpstr;

/* Make the API call to PayPal, using API signature.
   The API response is stored in an associative array called $resArray */
$resArray=hash_call("doDirectPayment",$nvpstr);

/* Display the API response back to the browser.
   If the response from PayPal was a success, display the response parameters'
   If the response was an error, display the errors received using APIError.php.
   */
$ack = strtoupper($resArray["ACK"]);

//$ack ="SUCCESS";

if($ack!="SUCCESS") { //Error
        $_SESSION['reshash']=$resArray;
	$location = "APIError.php";
        $navigator = new Navigator($_SESSION['user_id']);
        $navigator->setCurrentStep(1);
        $_SESSION['payment_error'] = 'yes';

        $sql = "UPDATE user SET package_id = 1 WHERE id = {$_SESSION['user_id']}";
        $dao = new Dao();
        $dao->query($sql);


        if (isset($_SESSION['upgrade'])) {
            header("Location: upgrade.php");
        } else {
            header("Location: success.php");
        }
        
}else {	//Success
	$sql="update ".$tblMembers." set".	
	" status = 'true' where autoid=".$_SESSION["pid"];
	mysql_query($sql) or die (mysql_error());
	
	$date = Now();			
	
	$sql = "select * from tbl_members where autoid=".$_SESSION["pid"];
	$rs  = mysql_query($sql) or die (mysql_error());
	$row = mysql_fetch_assoc($rs);
	
	$fname = $row["fname"];
	$lname = $row["lname"];
	$address = $row["address"];
	$city = $row["city"];
	$state = $row["state"];
	$zip = $row["zip"];
	$country = $row["country"];	
	$artist_name = $row["artist_name"];
	$gender = $row["gender"];
	$label_name = $row["label_name"];
	$email = $row["email"];
	$username = $row["username"];
	$password = $row["password"];
	$application_name = $row["application_name"];
	$package = $row["package"];
	$price = $row["price"];
	
	$sql = "INSERT INTO ".$tbl_payment_information." (".
	"fname".
	",lname".
	",address".
	",city".
	",state".
	",zip".
	",country".			
	",artist_name".
	",gender".
	",label_name".
	",email".
	",username".
	",password".
	",application_name".
	",package".
	",price".
	",date".
	") VALUES (".
	"'$fname'".
	", \"".$lname."\"".
	", \"".$address."\"".
	", \"".$city."\"".
	", \"".$state."\"".
	", \"".$zip."\"".
	", \"".$country."\"".
	", \"".$artist_name."\"".
	", \"".$gender."\"".
	", \"".$label_name."\"".
	", \"".$email."\"".
	", \"".$username."\"".
	", \"".$password."\"".
	", \"".$application_name."\"".
	", \"".$package."\"".
	", \"".$price."\"".
	", \"".$date."\"".	
	")";

	mysql_query($sql) or die(mysql_error());	
	$id = mysql_insert_id();
	sendOrderConfirm($id);
	
	unset($_SESSION['pid']);
	unset($_SESSION['cc']);
	unset($_SESSION['cc_number']);
	unset($_SESSION['expiry_m']);
	unset($_SESSION['expiry_y']);
	unset($_SESSION['cvvNumber']);
	$_SESSION['payment_error'] = 'no';
        $_SESSION['package_name'] = ucfirst($package);

        $sql = "UPDATE user SET paid = 1 WHERE id = {$_SESSION['user_id']}";

        $dao = new Dao();
        $dao->query($sql);

        $navigator = new Navigator($_SESSION['user_id']);
        $navigator->setCurrentStep(1);
        if(isset($_SESSION['upgrade'])) {
            $sql = "DELETE FROM `ab_modules` WHERE `user_id` = {$_SESSION['user_id']}";
            $dao->query($sql);

            header("Location: upgrade.php");
        } else {
            header("Location: success.php");
        }
	
}
?>
