<?php
include('./KonConfig.php');
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
if (!empty($_POST['AFID'])) {
    $AFID = $_POST['AFID'];
}
if (!empty($_POST['SID'])) {
    $SID = $_POST['SID'];
}
if (!empty($_POST['AFFID'])) {
    $AFFID = $_POST['AFFID'];
}
if (!empty($_POST['C1'])) {
    $C1 = $_POST['C1'];
}
if (!empty($_POST['C2'])) {
    $C2 = $_POST['C2'];
}
if (!empty($_POST['C3'])) {
    $C3 = $_POST['C3'];
}
if (!empty($_POST['AID'])) {
    $AID = $_POST['AID'];
}
if (!empty($_POST['OPT'])) {
    $OPT = $_POST['OPT'];
}
if (!empty($_POST['click_id'])) {
    $click_id = $_POST['click_id'];
}
if (!empty($_POST['CID'])) {
    $CID = $_POST['CID'];
}
if (!empty($_POST['notes'])) {
    $notes = $_POST['notes'];
}
$order_campain_id = $_REQUEST['campaign_id'];
$productId = $_REQUEST['custom_product'];
$page = $_REQUEST['page'];
$custom_product_price_ll = $_REQUEST['custom_product_price'];
$_SESSION['pr_price'] = $custom_product_price_ll;
$_SESSION['product_name'] = $_REQUEST['product_name'];
$shippingId = $_REQUEST['shippingId'];
$cc_type = $_REQUEST['cc_type'];
$cc_number = $_REQUEST['cc_number'];
$_SESSION['cc_number'] = $_REQUEST['cc_number'];
$fields_expmonth = $_REQUEST['fields_expmonth'];
$fields_expyear = $_REQUEST['fields_expyear'];
$cc_cvv = $_REQUEST['cc_cvv'];
$_SESSION['cc_cvv'] = $_REQUEST['cc_cvv'];
$_SESSION['fields_expmonth'] = $_REQUEST['fields_expmonth'];
$_SESSION['fields_expyear'] = $_REQUEST['fields_expyear'];
$billing_same_as_shipping = $_REQUEST['billingSameAsShipping'];
$expirationDate = $fields_expmonth . $fields_expyear;
$upsellCount = 0;
$product_qty = 1;
$amount = $_REQUEST['custom_product_price'];
if ($billing_same_as_shipping == 'NO') {
    $billingfanme = $_REQUEST['billing_fname'];
    $billinglanme = $_REQUEST['billing_lname'];
    $billingaddress = $_REQUEST['billing_street_address'];
    $billingcity = $_REQUEST['billing_city'];
    $billingstate = $_REQUEST['billing_state'];
    $billingzip = $_REQUEST['billing_postcode'];
    $billingcountry = $_REQUEST['billing_country'];

} else {
    $billingfanme = $_POST['fields_fname'];
    $billinglanme = $_POST['fields_lname'];
    $billingaddress = $_POST['fields_address1'];
    $billingcity = $_POST['shippingCity'];
    $billingstate = $_POST['shippingState'];
    $billingzip = $_POST['shippingZip'];
    $billingcountry = $_POST['fields_country'];

}
$_SESSION['billing_fname'] = $billingfanme;
$_SESSION['billing_lname'] = $billinglanme;
$_SESSION['billing_street_address'] = $billingaddress;
$_SESSION['billing_city'] = $billingcity;
$_SESSION['billing_state'] = $billingstate;
$_SESSION['billing_postcode'] = $billingzip;
$orderTempId = isset($_POST['orderId']) ? $_POST['orderId'] : '';
$sessionId = !empty($_REQUEST['sessionId']) ? $_REQUEST['sessionId'] : '';
$insure_campaign_id = '';
$fields_address2 = isset($_POST['fields_address2']) ? $_POST['fields_address2'] : '';
$insure_custom_product = '';
$insure_shipping_id = '';
$shipping_price = '';
$guaranteeship = '';
$_SESSION['fields_fname'] = $_POST['fields_fname'];
$_SESSION['fields_lname'] = $_POST['fields_lname'];
$_SESSION['fields_address1'] = $_POST['fields_address1'];
$_SESSION['fields_address2'] = $fields_address2;
$_SESSION['shippingCity'] = $_POST['shippingCity'];
$_SESSION['shippingState'] = $_POST['shippingState'];
$_SESSION['shippingZip'] = $_POST['shippingZip'];
$_SESSION['fields_phone'] = $_POST['fields_phone'];
$_SESSION['fields_email'] = $_POST['fields_email'];

$content = NewLeadKonnective($api_key_user, $api_key_pass, $campaign_id, $billingfanme, $billinglanme, $billingaddress, $billingcity, $billingstate, $billingzip, $billingcountry, $_POST['fields_phone'], $_POST['fields_email'], $_POST['fields_address1'], $_POST['shippingCity'], $_POST['shippingState'], $_POST['shippingZip'], $_POST['fields_country'], $_POST['fields_fname'], $_POST['fields_lname']);
$ret = json_decode($content);
?>