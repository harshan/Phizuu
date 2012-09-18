<?php
session_start();

require_once '../../../common/phpcreditcard.php';
//require_once '../../../../../constants_new.php';
//require_once '../../../../../CallerService.php';
require_once '../../../model/UserInfo.php';
require_once ('../../../config/config.php');
require_once ('../../../database/Dao.php');
require_once '../../../config/database.php';
require_once('../../../model/login_model.php');
require_once('../../../controller/helper.php');

require '../../../../../stripe-php-latest/stripe-php-1.7.0/lib/Stripe.php';
//$environment = "Live";//paypal_settingsDetails("paypal_transaction");
//
//$API_Endpoint = API_ENDPOINT_LIVE;
//$API_UserName = urlencode(API_USERNAME_LIVE);//urlencode(paypal_settingsDetails("api_uername"));;
//$API_Password = urlencode(API_PASSWORD_LIVE); //urlencode(paypal_settingsDetails("api_password"));
//$API_Signature = urlencode(API_SIGNATURE_LIVE);//urlencode(paypal_settingsDetails("api_signature"));
//
////echo $API_Endpoint;
//if("Sandbox" === $environment || "beta-sandbox" === $environment) {
//    $API_Endpoint = API_ENDPOINT_SANDBOX;
//    $API_UserName = urlencode(API_USERNAME_SANDBOX);//urlencode(paypal_settingsDetails("api_uername"));;
//    $API_Password = urlencode(API_PASSWORD_SANDBOX); //urlencode(paypal_settingsDetails("api_password"));
//    $API_Signature = urlencode(API_SIGNATURE_SANDBOX);//urlencode(paypal_settingsDetails("api_signature"));
//}

$userInfoObj = new UserInfo();
$userInfo = $userInfoObj->getUserInfo();
list($price, $recurrentPrice, $packageName) = getPackageData($userInfo['package_id']);
//Stripe secret key
$apikey = "IEnAJiI5MEMsMDbcsavOJUxwF1AqcOhw";
if($_GET['action']=='view') {
    include '../../../view/user/payments/recurrent_profile.php';
} if($_GET['action']=='create') {
    $cardNumber = $_POST['cardNo'];
    $cardType = $_POST['cardType'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $cvvNo = $_POST['cardCVV'];

    $expM = $_POST['cardExpM'];
    $expY = $_POST['cardExpY'];
    
    $card = checkCreditCard($cardNumber, $cardType, $errornumber, $errortext);
    if($firstName == '') {
        $msg = "First Name cannot be empty";
    }elseif($lastName == '') {
        $msg = "Last Name cannot be empty";
    }elseif (!$card) {
        $msg = $errortext;
    }elseif ($expY < date('Y')) {
        $msg = 'Card is expired';
    } elseif($expY == date('Y') && $expM < date('m')) {
        $msg = 'Card is expired';
    } elseif (strlen($cvvNo)!==3 || !is_numeric($cvvNo)) {
        $msg = 'Invalid CVV no';
    } else {
        $cardNumber = urlencode($cardNumber);
        $cardType = urlencode($cardType);
        $firstName = urlencode($firstName);
        $lastName = urlencode($lastName);
        $cvvNo = urlencode($cvvNo);

        $expDate = $expM.$expY;

        $nextPaymentDate = strtotime('+1 day');
        $PROFILESTARTDATE = urlencode(date('Y-m-d',$nextPaymentDate).'T'. date('H:i:s') .'.0000000Z');
        $BILLINGPERIOD = 'Month';
        $BILLINGFREQUENCY = '1';

        $email = $userInfo['email'];

        $DESC = urlencode( $packageName . " package's monthly payment rate has been used");

        //$nvpstr="&CREDITCARDTYPE=$cardType&ACCT=$cardNumber&EXPDATE=$expDate&CVV2=$cvvNo&FIRSTNAME=$firstName&LASTNAME=$lastName&PROFILESTARTDATE=$PROFILESTARTDATE&BILLINGPERIOD=$BILLINGPERIOD&BILLINGFREQUENCY=$BILLINGFREQUENCY&AMT=$recurrentPrice&DESC=$DESC&EMAIL=$email";
        //echo $nvpstr;

        //$resArray=hash_call("CreateRecurringPaymentsProfile",$nvpstr);
        
        //Strpe settings starts
          Stripe::setApiKey($apikey);
          $error = '';
          $success = '';
          //CMS Pachege ID and Strip plan id's matching

          $plan = $userInfo['package_id'];
          
          //$obj = Stripe_Customer::retrieve($userInfo['app_id'] );
            $curl = curl_init('https://api.stripe.com/v1/customers/'.$userInfo['app_id']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);                         
            curl_setopt($curl, CURLOPT_USERPWD, $apikey);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                    
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);                          
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);                           
            curl_setopt($curl, CURLOPT_USERAGENT, 'Sample Code');

            $result = curl_exec($curl);                                          
            $resultStatus = curl_getinfo($curl);
            $obj = json_decode($result);
            //print_r($response);
          if(isset($obj->{'id'})){
             
             $token = $_POST['stripeToken']; 
             $cu = Stripe_Customer::retrieve($userInfo['app_id']); 
             $cu->email = $email; 
             $cu->card = $token;
             $cu->save();
             
             $c = Stripe_Customer::retrieve($userInfo['app_id']); 
             $c->updateSubscription(array("plan" => $plan, "prorate" => true));
             $success ="SUCCESS";
                    
          }else{
          
          try {
              //if stripe token not generated this message is provided
            if (!isset($_POST['stripeToken'])){
                throw new Exception("The Stripe Token was not generated correctly");
            }
              
            
            $token = $_POST['stripeToken'];
            
            
            
            //Pass all details to stripe 
            $result = Stripe_Customer::create(array( "description" => $DESC, 
                                                     "card" => $token,
                                                     "plan" => $plan,
                                                     "email" => $email,
                                                     "id" => $userInfo['app_id'] ));
            $success ="SUCCESS";
            $obj = json_decode($result);
            
                
            
          }
          catch (Exception $e) {
            $msg = $e->getMessage();
          }
          }
          

          //Stripe settings end
          
        //$ack = strtoupper($resArray["ACK"]);

        if($success=="SUCCESS") {
            //$msgSuccess = "Thank you! We have successfuly created a recurrent profile with the id '".$resArray['PROFILEID']."' in PayPal. This payments module will be hidden here after, until we enable it again when a new setup is required.";
            $msgSuccess = "Thank you! We have successfuly created a recurrent profile for you.";
        } else {
            //$msg = $resArray['L_LONGMESSAGE0'];
            $msg = $msg;
        }
    }
    
    if(isset($msgSuccess)) {
        $sql = "UPDATE module SET recurrent_payments = 0 WHERE app_id = {$userInfo['app_id']}";
        $dao = new Dao();
        $dao->query($sql);

        $sql = "INSERT INTO `recurrent_payment_history` (".
                    "`user_id` ,".
                    "`amount` ,".
                    "`profile_id` ,".
                    "`time`".
                ") VALUES (".
                    "'{$userInfo['id']}', '$recurrentPrice', '".$obj->{'id'}."',CURRENT_TIMESTAMP".
                ");";

        $dao->query($sql);

        $login = new LoginModel();
        $chk_nav=$login -> checkNavModules();
        $navi_val[0] = array('music' => $chk_nav ->music,'videos' =>$chk_nav ->videos,'photos' => $chk_nav ->photos,'flyers' => $chk_nav ->flyers,'news' => $chk_nav ->news,'tours' => $chk_nav ->tours,'links' => $chk_nav ->links,'settings' => $chk_nav ->settings,'send_message' => $chk_nav ->send_message,'analytics' => $chk_nav ->analytics, 'payments'=>$chk_nav->recurrent_payments);

        $_SESSION['modules']=$navi_val;
    }
    
    include '../../../view/user/payments/recurrent_profile.php';
}

if($_REQUEST['action']=='deactivate'){
    //Update module table 
    $sql = "UPDATE module SET recurrent_payments = 1 WHERE app_id = {$userInfo['app_id']}";
    $dao = new Dao();
    $dao->query($sql);
    //cancel Subscription
    Stripe::setApiKey($apikey); 
    $cu = Stripe_Customer::retrieve($userInfo['app_id']); 
    $cu->cancelSubscription();
    header('location:../../../../apps/controller/modules/login/?action=logout');
    
}

function getPackageData($package_id) {
    if( $package_id == "1"){
        $price = "0";
        $recurrentPrice = "0";
        $package = "Garage Band";
    }else if($package_id == "2"){
        $price = "199";
        $package = "Idol";
        $recurrentPrice = "9.99";
    }else if($package_id == "3"){
        $price = "499";
        $package = "Rockstar";
        $recurrentPrice = "29.99";
    } else if( $package_id == "4"){
        $price = "99";
        $package = "Android+bb: Garage Band";
        $recurrentPrice = "4.99";
    }else if($package_id == "5"){
        $price = "149";
        $recurrentPrice = "6.99";
        $package = "Android+bb: Idol";
    }else if($package_id == "6"){
        $price = "199";
        $package = "Android+bb: Rockstar";
        $recurrentPrice = "9.99";
    }

    return array($price, $recurrentPrice, $package);
}

?>
