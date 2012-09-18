<?php
@session_start();
require_once 'cms/apps/config/config.php';
require_once 'cms/apps/database/Dao.php';
require_once 'cms/apps/model/app_builder/Navigator.php';
require_once 'admin/common/common.php';

/*$_SESSION['cc'] = 'Visa';
$_SESSION['cc_number']='4665693177330669';
$_SESSION['cvvNumber']='124';
$_SESSION['expiry_y']='2010';
$_SESSION['expiry_m']='12';
$_SESSION['recurrent']= true;
$_SESSION['recurrent_amt']= 20;

$_SESSION['user_id'] = 127;
$_SESSION["pid"]=12;*/

$dao = new Dao();

$environment = "Live";//paypal_settingsDetails("paypal_transaction");

$API_Endpoint = API_ENDPOINT_LIVE;
$API_UserName = urlencode(API_USERNAME_LIVE);//urlencode(paypal_settingsDetails("api_uername"));;
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

$paymentType = urlencode('Sale');//Authorization or 'Sale'
$firstName = urlencode($fname);
$lastName = urlencode($lname);
$creditCardType = urlencode($cc);//?????????????????????????????????????????????????????
$creditCardNumber = urlencode($cc_number);
$expDateMonth = $expiry_m;
$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
$expDateYear = urlencode($expiry_y);
$expDate = $padDateMonth.$expDateYear;
$cvv2Number = urlencode($cvvNumber);
$address1 = urlencode($address);
$address2 = urlencode('');
$city = urlencode($city);
$state = urlencode($state);
$zip = urlencode($zip);
$country = urlencode($country); // US or other valid country code
$amount = urlencode($price);
$currencyCode="USD";
$paymentType=urlencode('Authorization');
$emailEnc = urlencode($email);


/****************************************************
 ****************************************************
 Doing direct payment
 ****************************************************
 ****************************************************/


$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=$expDate&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
"&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyCode";


$resArray=hash_call("doDirectPayment",$nvpstr);
/*echo $nvpstr;
echo "<pre>";
print_r($resArray);
echo "</pre>";
exit;*/
/****************************************************
 ****************************************************
 Creating recurret profile 
 ****************************************************
 ****************************************************/



$ack = strtoupper($resArray["ACK"]);

if($ack=="SUCCESS") {
    if (!$setRecurrent){
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
        $AMT = $recurrentPrice;

        $DESC = urlencode( $package . " package's monthly payment rate has been used");

        $nvpstr="&CREDITCARDTYPE=$CREDITCARDTYPE&ACCT=$ACCT&EXPDATE=$EXPDATE&CVV2=$cvv2Number&FIRSTNAME=$FIRSTNAME&LASTNAME=$LASTNAME&PROFILESTARTDATE=$PROFILESTARTDATE&BILLINGPERIOD=$BILLINGPERIOD&BILLINGFREQUENCY=$BILLINGFREQUENCY&AMT=$AMT&DESC=$DESC&EMAIL=$emailEnc";
        //echo $nvpstr;

        $resArray=hash_call("CreateRecurringPaymentsProfile",$nvpstr);

        $ack = strtoupper($resArray["ACK"]);

        if($ack=="SUCCESS") {
            paymentSuccessful(false, $resArray['PROFILEID']);
        } else {
            $_SESSION['reshash']=$resArray;
            paymentSuccessful(true);
        }
    }
} else {	//Success
    paymentFailed($resArray);
}

function paymentFailed($resArray) {
    $_SESSION['reshash']=$resArray;
    $location = "APIError.php";
    
    $_SESSION['payment_error'] = 'yes';
    $_SESSION['error_msg'] = 'Payment failed';


    header("Location: ../success.php");
}


function paymentSuccessful($recurrentFaild = false, $recurrentPayementProfId=0) {
    global $fname,$lname,$address,$city,$state,$zip,$country,$email,$username,$password,$package,$price,$dao, $setRecurrent, $recurrentPrice;

    $artist_name = '';
    $gender = '';
    $label_name = '';
    $application_name = '';

    $_SESSION['recurrent'] = $setRecurrent;
    $recurrentTbl = $setRecurrent && !$recurrentFaild?'1':'0';

    if($setRecurrent && !$recurrentFaild) {
        $recurrentAmt = "'".$recurrentPrice."'";
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
    ", \"***\"".
    ", \"".$application_name."\"".
    ", \"".$package."\"".
    ", \"".$price."\"".
    ", NOW()".
    ",$recurrentTbl".
    ",$recurrentAmt".
    ",$recurrentPayementProfIdTbl".
    ",'Authorization Recieved'".
    ")";

    $dao->query($sql);
    $id = mysql_insert_id();
    sendOrderConfirm($id);

    if($recurrentFaild || $setRecurrent==false)
        sendRecurrentFailedNotification($id, $recurrentFaild);

    $_SESSION['payment_error'] = 'no';
    $_SESSION['package_name'] = ucfirst($package);

    $_SESSION['recurrent_failed'] = 'no';
    if($recurrentFaild == true ) {
        $_SESSION['recurrent_failed'] = 'yes';
    }

    signUpUser();

    header("Location: ../success.php");
}
?>
