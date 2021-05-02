<?php

function safeRequestKonnective($strGet)
{

    $strGet = preg_replace("/[^\-a-zA-Z0-9\_]*/m", "", $strGet);

    $strGet = str_ireplace("javascript", "", $strGet);

    $strGet = str_ireplace("encode", "", $strGet);

    $strGet = str_ireplace("decode", "", $strGet);

    return trim($strGet);
}


function NewLeadKonnective($api_key_user, $api_key_pass, $campaign_id, $fields_fname, $fields_lname, $fields_address1, $fields_city, $fields_state, $fields_zip, $country_2_digit, $fields_phone, $fields_email, $billingaddress = '', $billingcity = '', $billingstate = '', $billingzip = '', $billingcountry = '', $billingfanme = '', $billinglanme = '', $coupon = '')
{
    global $limelight_api_username, $limelight_api_password, $limelight_crm_instance;

    $_SESSION['fname'] = $fields_fname;

    $_SESSION['lname'] = $fields_lname;

    $_SESSION['address'] = $fields_address1;

    $_SESSION['city'] = $fields_city;

    $_SESSION['state'] = $fields_state;

    $_SESSION['phone'] = $fields_phone;

    $_SESSION['zip'] = $fields_zip;

    $_SESSION['email'] = $fields_email;


    if ($campaign_id == "5") {

        $AFFID = "";

        $SID = "";

        $C1 = "";

        $C2 = "";

        $C3 = "";

        $click_id = "";

    }

    $fields = array('loginId' => $api_key_user,

        'password' => $api_key_pass,

        'campaignId' => $campaign_id,

        'firstName' => trim($fields_fname),

        'lastName' => trim($fields_lname),

        'address1' => trim($fields_address1),

        'address2' => trim($fields_address2),

        'city' => trim($fields_city),

        'state' => trim($fields_state),

        'postalCode' => trim($fields_zip),

        'country' => $country_2_digit,

        'phoneNumber' => trim($fields_phone),

        'emailAddress' => trim($fields_email),

        'shipFirstName' => $billingfanme,

        'shipLastName' => $billinglanme,

        'shipAddress1' => $billingaddress,

        'shipCity' => $billingcity,

        'shipState' => $billingstate,

        'shipPostalCode' => $billingzip,

        'shipCountry' => $billingcountry,

        'affId' => (isset($_SESSION['affId']) && !empty($_SESSION['affId'])) ? $_SESSION['affId'] : '8568CD94',
        'sourceValue1' => (isset($_SESSION['c1']) && !empty($_SESSION['c1'])) ? $_SESSION['c1'] : '[c1]',
        'sourceValue2' => (isset($_SESSION['c2']) && !empty($_SESSION['c2'])) ? $_SESSION['c2'] : '[c2]',
        'sourceValue3' => (isset($_SESSION['c3']) && !empty($_SESSION['c3'])) ? $_SESSION['c3'] : '[c3]',

        'couponCode' =>  $coupon ,

        'ipAddress' => $_SERVER['REMOTE_ADDR']);


    $Curl_Session = curl_init();

    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/leads/import/');

    curl_setopt($Curl_Session, CURLOPT_POST, 1);

    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);

    return $content = curl_exec($Curl_Session);


    //return $content = curl_exec($Curl_Session);

    //$header = curl_getinfo($Curl_Session);

}

function NewOrderWithLeadKonnective($api_key_user, $api_key_pass, $campaign_id, $orderTempId, $creditCardType, $creditCardNumber, $cardMonth, $cardYear, $cvv, $productId, $shippingId, $upsellCount, $billingSameAsShipping, $product_qty, $custom_product_price, $AFID, $SID, $AFFID, $C1, $C2, $C3, $AID, $OPT, $notes = '', $billingaddress = '', $billingcity = '', $billingstate = '', $billingzip = '', $billingcountry = '', $billingfanme = '', $billinglanme = '', $sessionId = '', $insure_campaign_id, $insure_custom_product, $insure_shipping_id, $shipping_price, $guaranteeship)
{

    $billing_fields = array();

    if (!empty($billingSameAsShipping) && $billingSameAsShipping == 'NO') {

        $billing_fields = array('shipFirstName' => $billingfanme,

            'shipLastName' => $billinglanme,

            'shipAddress1' => $billingaddress,

            'shipCity' => $billingcity,

            'shipState' => $billingstate,

            'shipPostalCode' => $billingzip,

            'shipCountry' => $billingcountry

        );

    }


    $fields1 = array('loginId' => $api_key_user,

        'password' => $api_key_pass,

        'orderId' => $orderTempId,

        'paySource' => 'CREDITCARD',

        'firstName' => $_SESSION['fname'],

        'lastName' => $_SESSION['lname'],

        'address1' => $_SESSION['address'],

        'city' => $_SESSION['city'],

        'state' => $_SESSION['state'],

        'postalCode' => $_SESSION['zip'],

        'country' => $_SESSION['country'],

        'phoneNumber' => $_SESSION['phone'],

        'emailAddress' => $_SESSION['email'],

        'cardNumber' => $creditCardNumber,

        'cardMonth' => $cardMonth, //mmyy

        'cardYear' => $cardYear, //mmyy

        'cardSecurityCode' => $cvv,

        'tranType' => 'Sale',

        'product1_id' => $productId,

        'campaignId' => $campaign_id,

        'product1_shipPrice' => '4.99',

        'billShipSame' => $billingSameAsShipping,

        'affId' => 'BSD',

        'sourceValue1' => trim($SID),

        'sourceValue2' => trim($C1),

        'sourceValue3' => trim($C2),

        'sourceValue4' => trim($C3),

        'sourceValue5' => trim($click_id),

        'ipAddress' => $_SERVER['REMOTE_ADDR']);


    //echo "<pre>".print_r($fields1,true)."</pre>";


    if (!empty($billing_fields)) {

        $fields = array_merge($fields1, $billing_fields);

    } else {

        $fields = $fields1;

    }

    //echo "<pre>";

    //print_r($fields);


    $Curl_Session = curl_init();

    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/order/import/');

    curl_setopt($Curl_Session, CURLOPT_POST, 1);

    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($Curl_Session);

    $ret = json_decode($content);


    return $content;

}


function NewOrder($api_key_user, $api_key_pass, $inputs)
{
    $billing_fields = array();
    $paypal_fields = array();
    $state_val = '';
    $billingstate_val = '';

    if (!empty($inputs['paySource_paypal']) && $inputs['paySource_paypal'] == 'PAYPAL') {
        $paypal_fields = array(

            'salesUrl' => $inputs['salesUrl_paypal'],

            'paypalBillerId' => $inputs['paypalBillerId_paypal'],

            'sessionId' => $inputs['session_id'],

            'orderId' => $_SESSION['order_id_lead'],

        );

        $_SESSION['paySource'] = $inputs['paySource_paypal'];
    }
    else{

        $_SESSION['paySource'] = 'CREDITCARD';
    }

    if ($inputs['fields_country'] == 'US'){
        $state_val = $inputs['shippingState'];
    }elseif ($inputs['fields_country'] == 'GB') {
        $state_val = $inputs['fields_state_UK'];

    }elseif ($inputs['fields_country'] == 'AU' ||$inputs['fields_country'] == 'CA' ||$inputs['fields_country'] == 'DE' ||$inputs['fields_country'] == 'IE' ||$inputs['fields_country'] == 'NZ'  ){
        $state_val = $inputs['fields_state_other'];
    }


    if ($inputs['billing_country'] == 'US'){
        $billingstate_val = $inputs['billing_state'];
    }elseif ($inputs['billing_country'] == 'GB'){
        $billingstate_val = $inputs['billing_state_stateUK'];
    }elseif ($inputs['billing_country'] == 'AU' ||$inputs['billing_country'] == 'CA' ||$inputs['billing_country'] == 'DE' ||$inputs['billing_country'] == 'IE' ||$inputs['billing_country'] == 'NZ'  ){
        $billingstate_val = $inputs['billing_state_state_other'];
    }

    if (!empty($inputs['billingSameAsShipping']) && $inputs['billingSameAsShipping'] == 'NO') {
        //SHIPPING
//        if($inputs['fields_state'] != '' ){
//            $shipState = $inputs['fields_state'];
//        }else if($inputs['billing_stateUK'] != ''){
//            $shipState =$inputs['billing_stateUK'];
//        }else{
//            $shipState =$inputs['fields_state2'];
//        }
//        var_dump($shipState);
//         exit;

        $billing_fields = array(

            'shipAddress1' => $inputs['fields_address1'],

            'shipAddress2' => $inputs['fields_apt'],

            'shipCity' => $inputs['shippingCity'],

           'shipState' => $state_val,

           // 'shipState' => $shipState,

            'shipPostalCode' => $inputs['shippingZip'],

            'shipCountry' => $inputs['fields_country']

        );


        $_SESSION['address'] = $inputs['billing_street_address'];

        $_SESSION['address2'] = $inputs['billing_apt'];

        $_SESSION['city'] = $inputs['billing_city'];

        $_SESSION['state'] = $billingstate_val;
       // $_SESSION['state'] = $shipState;

        $_SESSION['zip'] = $inputs['billing_postcode'];

        $_SESSION['country'] = $inputs['billing_country'];

    } else {

        $_SESSION['address2'] = $inputs['fields_apt'];

        $_SESSION['address'] = $inputs['fields_address1'];

        $_SESSION['city'] = $inputs['shippingCity'];

        //BILLING
//        if($inputs['fields_state'] != '' ){
//            $state = $inputs['fields_state'] ;
//        }else if($inputs['ukfields_state'] != ''){
//            $state =$inputs['ukfields_state'];
//        }else{
//            $state =$inputs['fields_state2'];
//        }
//       var_dump($state);
//       exit;
        $_SESSION['state'] = $state_val;
       // $_SESSION['state'] = $state;

        $_SESSION['zip'] = $inputs['shippingZip'];

        $_SESSION['country'] = $inputs['fields_country'];

    }

    $search = array('(', ')');
    $phone = str_replace($search, '', $inputs['fields_phone']);

    $fields1 = array('loginId' => $api_key_user,

        'password' => $api_key_pass,

        'paySource' => 'CREDITCARD',

        'firstName' => $inputs['fields_fname'],

        'lastName' => $inputs['fields_lname'],

        'address1' => $_SESSION['address'],

        'address2' => $_SESSION['address2'],

        'city' => $_SESSION['city'],

        'state' => $_SESSION['state'],

        'country' => $_SESSION['country'],

        'postalCode' => $_SESSION['zip'],

        'emailAddress' => trim($inputs['fields_email']),

        'phoneNumber' => $phone,

        'cardNumber' => $inputs['cc_number'],

        'cardMonth' => $inputs['fields_expmonth'], //mmyy

        'cardYear' => $inputs['fields_expyear'], //mmyy

        'cardSecurityCode' => $inputs['cc_cvv'],

        'tranType' => 'Sale',

        'product1_id' => $inputs['custom_product'],

        'campaignId' => $inputs['campaign_id'],

        'product1_shipPrice' => $inputs['shipping_price_pro'],

        'billShipSame' => $inputs['billingSameAsShipping'],
        'affId' => (isset($_SESSION['affId']) && !empty($_SESSION['affId'])) ? $_SESSION['affId'] : '8568CD94',
        'sourceValue1' => (isset($_SESSION['c1']) && !empty($_SESSION['c1'])) ? $_SESSION['c1'] : '[c1]',
        'sourceValue2' => (isset($_SESSION['c2']) && !empty($_SESSION['c2'])) ? $_SESSION['c2'] : '[c2]',
        'sourceValue3' => (isset($_SESSION['c3']) && !empty($_SESSION['c3'])) ? $_SESSION['c3'] : '[c3]',

        'couponCode' =>  $inputs['coupon_discount_code'] ,

        'salesTax' =>  $inputs['salesTax'] ,

        'ipAddress' => $_SERVER['REMOTE_ADDR']);


    if (!empty($billing_fields)) {

        $fields = array_merge($fields1, $billing_fields);

    } else {

        $fields = $fields1;

    }

    if (!empty($paypal_fields)) {

        $fields_all = array_merge($fields, $paypal_fields);

    } else {

        $fields_all = $fields;

    }

/*    echo "<pre>";
    var_dump($fields);
    exit();*/
    $Curl_Session = curl_init();

    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/order/import/');

    curl_setopt($Curl_Session, CURLOPT_POST, 1);

    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields_all));

    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($Curl_Session);

    $ret = json_decode($content);
    /*  echo "<pre>";
      var_dump($ret);
      exit();*/

    return $content;
}

function NewClick($api_key_user, $api_key_pass, $campaign_id, $pageType, $uri)
{
    $affId = (isset($_SESSION['affId']) && !empty($_SESSION['affId'])) ? $_SESSION['affId'] : '8568CD94';
    $c1 = (!empty($_SESSION['c1'])) ? $_SESSION['c1'] : '[c1]';
    $c2 = (!empty($_SESSION['c2'])) ? $_SESSION['c2'] : '[c2]';
    $c3 = (!empty($_SESSION['c3'])) ? $_SESSION['c3'] : '[c3]';
    $requestUri = "http://watch1.folliboosthair.com" . "?affId={$affId}&c1={$c1}&c2={$c2}&c3={$c3}";

    $fields = array(
        'loginId' => $api_key_user,
        'password' => $api_key_pass,
        'pageType' => trim($pageType),
        'userAgent' => trim($_SERVER['HTTP_USER_AGENT']),
        'campaignId' => $campaign_id,
        'requestUri' => $requestUri,
        'ipAddress' => $_SERVER['REMOTE_ADDR']);


    $Curl_Session = curl_init();
    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/landers/clicks/import/');
    curl_setopt($Curl_Session, CURLOPT_POST, 1);
    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($Curl_Session);
    $ret = json_decode($content);
    return $content;
}

function NewOrderIdKonnective($api_key_user, $api_key_pass, $orderId, $product_id, $shipping_price)
{

    $fields = array(
        'loginId' => $api_key_user,

        'password' => $api_key_pass,

        'method' => 'NewOrderCardOnFile',

        'orderId' => $orderId,

        'productId' => $product_id,

        'product1_shipPrice' => $shipping_price,

    );

    $Curl_Session = curl_init();

    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/upsale/import/');

    curl_setopt($Curl_Session, CURLOPT_POST, 1);

    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($Curl_Session);
    /* echo "<pre>";
         var_dump($fields);
         exit();*/
    //echo "<pre>";
    //print_r($content);

    return $content;

}

function Leads($api_key_user, $api_key_pass, $campaign_id, $inputs){

    $errors = array();

    $folder = '';

    $AFID = '';

    $AFFID = '';

    $SID = '';

    $C1 = '';

    $C2 = '';

    $C3 = '';

    $AID = '';

    $OPT = '';

    $click_id = '';

    $CID = '';

    $notes = '';


    if (!empty($inputs['AFID'])) {

        $AFID = $inputs['AFID'];

    }

    if (!empty($inputs['SID'])) {

        $SID = $inputs['SID'];

    }

    if (!empty($inputs['AFFID'])) {

        $AFFID = $inputs['AFFID'];

    }

    if (!empty($inputs['C1'])) {

        $C1 = $inputs['C1'];

    }

    if (!empty($inputs['C2'])) {

        $C2 = $inputs['C2'];

    }

    if (!empty($inputs['C3'])) {

        $C3 = $inputs['C3'];

    }

    if (!empty($inputs['AID'])) {

        $AID = $inputs['AID'];

    }

    if (!empty($inputs['OPT'])) {

        $OPT = $inputs['OPT'];

    }

    if (!empty($inputs['click_id'])) {

        $click_id = $inputs['click_id'];

    }

    if (!empty($inputs['CID'])) {

        $CID = $inputs['CID'];

    }

    if (!empty($inputs['notes'])) {

        $notes = $inputs['notes'];

    }


    $order_campain_id = $inputs['campaign_id'];

    $productId = $inputs['custom_product'];

    $page = $inputs['page'];

    $custom_product_price_ll = $inputs['custom_product_price'];

    $_SESSION['pr_price'] = $custom_product_price_ll;

    $_SESSION['product_name'] = $inputs['product_name'];

    $shippingId = $inputs['shippingId'];

    $cc_type = $inputs['cc_type'];

    $cc_number = $inputs['cc_number'];

    $_SESSION['cc_number'] = $inputs['cc_number'];

    $fields_expmonth = $inputs['fields_expmonth'];

    $fields_expyear = $inputs['fields_expyear'];

    $cc_cvv = $inputs['cc_cvv'];

    $_SESSION['cc_cvv'] = $inputs['cc_cvv'];

    $_SESSION['fields_expmonth'] = $inputs['fields_expmonth'];

    $_SESSION['fields_expyear'] = $inputs['fields_expyear'];

    $billing_same_as_shipping = $inputs['billingSameAsShipping'];


    $expirationDate = $fields_expmonth . $fields_expyear;

    $upsellCount = 0;

    $product_qty = 1;

    $amount = $inputs['custom_product_price'];


    if ($billing_same_as_shipping == 'NO') {

        $billingfanme = $inputs['billing_fname'];

        $billinglanme = $inputs['billing_lname'];

        $billingaddress = $inputs['billing_street_address'];

        $billingcity = $inputs['billing_city'];

        $billingstate = $inputs['billing_state'];

        $billingzip = $inputs['billing_postcode'];

        $billingcountry = $inputs['billing_country'];

    } else {

        $billingfanme = $inputs['fields_fname'];

        $billinglanme = $inputs['fields_lname'];

        $billingaddress = $inputs['fields_address1'];

        $billingcity = $inputs['shippingCity'];

        $billingstate = $inputs['shippingState'];

        $billingzip = $inputs['shippingZip'];

        $billingcountry = $inputs['fields_country'];

    }

    $_SESSION['billing_fname'] = $billingfanme;

    $_SESSION['billing_lname'] = $billinglanme;

    $_SESSION['billing_street_address'] = $billingaddress;

    $_SESSION['billing_city'] = $billingcity;

    $_SESSION['billing_state'] = $billingstate;

    $_SESSION['billing_postcode'] = $billingzip;



    $orderTempId = isset($inputs['orderId']) ? $inputs['orderId'] : '';


    $sessionId = !empty($inputs['sessionId']) ? $inputs['sessionId'] : '';

    $_SESSION['country'] = $billingcountry;

    $insure_campaign_id = '';

    $fields_address2 = isset($inputs['fields_address2']) ? $inputs['fields_address2'] : '';

    $insure_custom_product = '';

    $insure_shipping_id = '';

    $shipping_price = '';

    $guaranteeship = '';


    $_SESSION['fields_fname'] = $inputs['fields_fname'];

    $_SESSION['fields_lname'] = $inputs['fields_lname'];

    $_SESSION['fields_address1'] = $inputs['fields_address1'];

    $_SESSION['fields_address2'] = $fields_address2;

    $_SESSION['shippingCity'] = $inputs['shippingCity'];

    $_SESSION['shippingState'] = $inputs['shippingState'];

    $_SESSION['shippingZip'] = $inputs['shippingZip'];

    $_SESSION['fields_phone'] = $inputs['fields_phone'];

    $_SESSION['fields_email'] = $inputs['fields_email'];


    $content = NewLeadKonnective($api_key_user, $api_key_pass, $campaign_id, $billingfanme, $billinglanme, $billingaddress, $billingcity, $billingstate, $billingzip, $billingcountry, $inputs['fields_phone'], $inputs['fields_email'], $inputs['fields_address1'], $inputs['shippingCity'], $inputs['shippingState'], $inputs['shippingZip'], $inputs['fields_country'], $inputs['fields_fname'], $inputs['fields_lname'], $inputs['coupon_discount_code']);


    $ret = json_decode($content);
    return $ret;



}

function confirmPaypal($api_key_user, $api_key_pass, $campaign_id, $token, $PayerID, $sessionId, $orderId, $product1_id, $coupon_code)
{
    $fields = array(
        'loginId' => $api_key_user,

        'password' => $api_key_pass,

        'payerId' => $PayerID,

        'token' => $token,

        'sessionId' => $sessionId,

        'orderId' =>  $orderId,

        'product1_id' => $product1_id,

        'campaignId' => $campaign_id,

        'couponCode' => $coupon_code,

        'paypalBillerId' => '3',

    );

    $Curl_Session = curl_init();

    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/transactions/confirmPaypal/');

    curl_setopt($Curl_Session, CURLOPT_POST, 1);

    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));

    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($Curl_Session);

    $ret = json_decode($content);

    return $ret;
}

?>