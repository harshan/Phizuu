<?php
@session_start();
require_once 'cms/apps/config/config.php';
require_once 'cms/apps/database/Dao.php';
require_once 'cms/apps/model/app_builder/Navigator.php';
require_once 'admin/common/common.php';

$_SESSION['cc'] = 'Visa';
$_SESSION['cc_number']='4665693177330669';
$_SESSION['cvvNumber']='124';
$_SESSION['expiry_y']='2010';
$_SESSION['expiry_m']='12';
$_SESSION['recurrent']= true;
$_SESSION['recurrent_amt']= 20;
//$_SESSION['upgrade'] = false;
unset($_SESSION['upgrade'] );

$_SESSION['user_id'] = 127;
$_SESSION["pid"]=12;

$dao = new Dao();

$environment = "Sandbox";//paypal_settingsDetails("paypal_transaction");

$API_Endpoint = API_ENDPOINT_LIVE;
$API_UserName = urlencode(API_PASSWORD_LIVE);//urlencode(paypal_settingsDetails("api_uername"));;
$API_Password = urlencode(API_PASSWORD_LIVE); //urlencode(paypal_settingsDetails("api_password"));
$API_Signature = urlencode(API_SIGNATURE_LIVE);//urlencode(paypal_settingsDetails("api_signature"));

//echo $API_Endpoint;
if("Sandbox" === $environment || "beta-sandbox" === $environment) {
    $API_Endpoint = API_ENDPOINT_SANDBOX;
    $API_UserName = urlencode(API_USERNAME_SANDBOX);//urlencode(paypal_settingsDetails("api_uername"));;
    $API_Password = urlencode(API_PASSWORD_SANDBOX); //urlencode(paypal_settingsDetails("api_password"));
    $API_Signature = urlencode(API_SIGNATURE_SANDBOX);//urlencode(paypal_settingsDetails("api_signature"));
}

/****************************************************
 ****************************************************
 Retrieve Data From the DataBase and Session
 ****************************************************
 ****************************************************/

if(isset($_SESSION["pid"])){
    $sql = "SELECT * FROM tbl_members WHERE autoid=".$_SESSION["pid"];
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

    $paymentType = urlencode('Sale');//Authorization or 'Sale'
    $firstName = urlencode($fname);
    $lastName = urlencode($lname);
    $creditCardType = urlencode($_SESSION['cc']);//?????????????????????????????????????????????????????
    $creditCardNumber = urlencode($_SESSION['cc_number']);
    $expDateMonth = $_SESSION['expiry_m'];
    $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
    $expDateYear = urlencode($_SESSION['expiry_y']);
    $expDate = $padDateMonth.$expDateYear;
    $cvv2Number = urlencode($_SESSION['cvvNumber']);
    $address1 = urlencode($address);
    $address2 = urlencode('');
    $city = urlencode($city);
    $state = urlencode($state);
    $zip = urlencode($zip);
    $country = urlencode($country); // US or other valid country code
    $amount = urlencode($price);
    $currencyCode="USD";
    $paymentType=urlencode('Authorization');
    $email = urlencode($email);
}else{
    header( "Location: free/pricing.html" );
    exit;
}

/****************************************************
 ****************************************************
 Doing direct payment
 ****************************************************
 ****************************************************/


$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDate&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
"&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyCode";


//echo $nvpstr;

$resArray=hash_call("doDirectPayment",$nvpstr);
//echo "<pre>";
//print_r($resArray);
//echo "</pre>";

/****************************************************
 ****************************************************
 Creating recurret profile 
 ****************************************************
 ****************************************************/



$ack = strtoupper($resArray["ACK"]);

if($ack=="SUCCESS") {
    if (!$_SESSION['recurrent']){
        paymentSuccessful(); // We are done
    } else {
        $CREDITCARDTYPE = $creditCardType;
        $ACCT = $creditCardNumber;
        $EXPDATE = $expDate;
        $FIRSTNAME = $firstName;
        $LASTNAME = $lastName;

        $nextPaymentDate = strtotime('+1 month');
        $PROFILESTARTDATE = urlencode(date('Y-m-d',$nextPaymentDate).'T'. date('H:i:s') .'.0000000Z');
        $BILLINGPERIOD = 'Month';
        $BILLINGFREQUENCY = '1';
        $AMT = $_SESSION['recurrent_amt'];

        $DESC = urlencode( $package . " package's monthly payment rate has been used");

        $nvpstr="&CREDITCARDTYPE=$CREDITCARDTYPE&ACCT=$ACCT&EXPDATE=$EXPDATE&CVV2=$cvv2Number&FIRSTNAME=$FIRSTNAME&LASTNAME=$LASTNAME&PROFILESTARTDATE=$PROFILESTARTDATE&BILLINGPERIOD=$BILLINGPERIOD&BILLINGFREQUENCY=$BILLINGFREQUENCY&AMT=$AMT&DESC=$DESC&EMAIL=$email";
        //echo $nvpstr;

        $resArray=hash_call("CreateRecurringPaymentsProfile",$nvpstr);

        $ack = strtoupper($resArray["ACK"]);
//echo "<pre>";
//print_r($resArray);
//echo "</pre>";
        if($ack=="SUCCESS") {
            paymentSuccessful(false, $resArray['PROFILEID']);
        } else {
            $_SESSION['reshash']=$resArray;
            paymentSuccessful(true);
        }
    }
}else {	//Success
    paymentFailed($resArray);
}

function paymentFailed($resArray) {
    $_SESSION['reshash']=$resArray;
    $location = "APIError.php";
    $navigator = new Navigator($_SESSION['user_id']);
    $navigator->setCurrentStep(1);
    $_SESSION['payment_error'] = 'yes';
    
    $_SESSION['error_msg'] = 'Payment failed';

    $sql = "UPDATE user SET package_id = 1 WHERE id = {$_SESSION['user_id']}";
    $dao = new Dao();
    $dao->query($sql);


    if (isset($_SESSION['upgrade'])) {
        header("Location: upgrade.php");
    } else {
        header("Location: success.php");
    }
}


function paymentSuccessful($recurrentFaild = false, $recurrentPayementProfId=0) {
    $sql="update tbl_members set".
    " status = 'true' where autoid=".$_SESSION["pid"];
    mysql_query($sql) or die (mysql_error());

    $date = date('Y-m-d H:i:s');

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
    $recurrentTbl = $_SESSION['recurrent'] && !$recurrentFaild?'1':'0';

    if($_SESSION['recurrent'] && !$recurrentFaild) {
        $recurrentAmt = "'".$_SESSION['recurrent_amt']."'";
        $recurrentPayementProfIdTbl = "'".$recurrentPayementProfId."'";
    } else {
        $recurrentAmt = 'NULL';
        $recurrentPayementProfIdTbl = "NULL";
    }

    $sql = "INSERT INTO tbl_payment_information (".
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
    ",recurrent_payments".
    ",recurrent_amount".
    ",recurrent_profile_id".
    ",status".
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
    ",$recurrentTbl".
    ",$recurrentAmt".
    ",$recurrentPayementProfIdTbl".
    ",'Authorization Recieved'".
    ")";

    mysql_query($sql) or die(mysql_error());
    $id = mysql_insert_id();
    sendOrderConfirm($id);

    if($recurrentFaild || $_SESSION['recurrent']==false)
        sendRecurrentFailedNotification($id, $recurrentFaild);

    $_SESSION['payment_error'] = 'no';
    $_SESSION['package_name'] = ucfirst($package);

    $_SESSION['recurrent_failed'] = 'no';
    if($recurrentFaild == true ) {
        $_SESSION['recurrent_failed'] = 'yes';
    }

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
