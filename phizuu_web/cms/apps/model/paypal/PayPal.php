<?php
class PayPal {
    public function takePayment ($amount, $details, $description='',$email='',$environment = "Live", $onlyValidate=false) {
        $description = urlencode($description);
        $$email = urlencode($email);

        $API_Endpoint = API_ENDPOINT_LIVE;
        $API_UserName = urlencode(API_USERNAME_LIVE);
        $API_Password = urlencode(API_PASSWORD_LIVE);
        $API_Signature = urlencode(API_SIGNATURE_LIVE);

        if("Sandbox" === $environment || "beta-sandbox" === $environment) {
            $API_Endpoint = API_ENDPOINT_SANDBOX;
            $API_UserName = urlencode(API_USERNAME_SANDBOX);
            $API_Password = urlencode(API_PASSWORD_SANDBOX);
            $API_Signature = urlencode(API_SIGNATURE_SANDBOX);
        }

        $cardNumber = $details['cardNo'];
        $cardType = $details['cardType'];
        $firstName = $details['firstName'];
        $lastName = $details['lastName'];
        $cvvNo = $details['cardCVV'];
        $address1 = $details['address'];
        $city = $details['city'];
        $state = $details['state'];
        $zip = $details['zip'];
        $country = $details['country'];

        $expM = $details['cardExpM'];
        $expY = $details['cardExpY'];

        $card = checkCreditCard($cardNumber, $cardType, $errornumber, $errortext);

        $msg = '';

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
        } elseif ($cvvNo == '') {
            $msg = 'Invalid CVV';
        } elseif ($address1 == '') {
            $msg = 'Invalid address';
        } elseif ($city == '') {
            $msg = 'Invalid city';
        } elseif ($state == '') {
            $msg = 'Invalid state';
        } elseif ($zip == '') {
            $msg = 'Invalid zip code';
        } elseif ($country == '') {
            $msg = 'Invalid country';
        } else {
            if($onlyValidate)
                return TRUE;
            $cardNumber = urlencode($cardNumber);
            $cardType = urlencode($cardType);
            $firstName = urlencode($firstName);
            $lastName = urlencode($lastName);
            $cvvNo = urlencode($cvvNo);
            $address1 = urlencode($address1);
            $city = urlencode($city);
            $state = urlencode($state);
            $zip = urlencode($zip);
            $country = urlencode($country);

            $expDate = $expM.$expY;

            $nextPaymentDate = strtotime('+1 day');
            $PROFILESTARTDATE = urlencode(date('Y-m-d',$nextPaymentDate).'T'. date('H:i:s') .'.0000000Z');
            $BILLINGPERIOD = 'Month';
            $BILLINGFREQUENCY = '1';
           
            //echo $nvpstr;
            $paymentType=urlencode('Authorization');

            $nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$cardType&ACCT=$cardNumber&EXPDATE=".$expM.$expY."&CVV2=$cvvNo&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
                    "&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=USD&DESC=$description&EMAIL=$email";

            $resArray=hash_call("doDirectPayment",$nvpstr, $API_Endpoint,VERSION, $API_UserName, $API_Password, $API_Signature);

            $ack = strtoupper($resArray["ACK"]);

            if($ack!="SUCCESS") {
                $msg = $resArray['L_LONGMESSAGE0'];
            }
        }

        if ($msg != '') {
            throw new Exception($msg);
        } else {
            return TRUE;
        }
    }
}
?>
