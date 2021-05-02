<?php
include('./kon/KonConfig.php');
foreach (array('affId', 'c1', 'c2', 'c3') as $v) {
    if (isset($_GET[$v]) && !empty($_GET[$v]))
        $_SESSION[$v] = $_GET[$v];
}
$productId = $_REQUEST['product_id'];

$_SESSION['billingSameAsShipping2'] = "";

if (!$_SESSION['billingSameAsShipping']) {
    $_SESSION['billingSameAsShipping'] = "YES";
}
if (isset($_REQUEST['product_id']) && !in_array($_REQUEST['product_id'], array(141, 142, 143))) {
    $errors = array();
    $productId = $_REQUEST['product_id'];
    $errordub = '';
    $purchasedItemsArray = array();
    if (isset($_SESSION['purchased_items_ids'])) {
        foreach ($_SESSION['purchased_items_ids'] as $v) {
            $purchasedItemsArray[] = $v['product_id'];
        }
    }
    $refusalArray = array(
        141 => array(141, 142, 143),
        142 => array(141, 142, 143),
        143 => array(141, 142, 143),
        144 => array(144, 145),
        145 => array(144, 145),
        146 => array(146, 147),
        147 => array(146, 147),
        148 => array(148)
    );

    if (isset($refusalArray[$productId])) {
        if (count(array_intersect($refusalArray[$productId], $purchasedItemsArray)) > 0) {
            $errordub = 'duplicate order';
        }
    }

    if (empty($errordub)) {

        $shipping_price = "0.00";
        $content = NewOrderIdKonnective($api_key_user, $api_key_pass, $_SESSION['ord_id'], $productId, $shipping_price);
        $ret = json_decode($content, true);

        if ($ret['result'] == 'SUCCESS') {
            $data2 = $ret['message'];
            $orderId = $data2['orderId'];
            $_SESSION['purchased_items_ids'][] = array('product_id' => $productId);
            $_SESSION['purchased_items'] = array('time' => time(), 'return' => $data2['items'],'total_amount' => $data2['totalAmount'],'order_id' => $data2['orderId'], 'discountPrice' => $_SESSION['discountPrice']);
            $_SESSION['p_price'] = $productArray[$productId]['price'];
            $_SESSION['sku'] = $productArray[$productId]['sku'];
            $_SESSION['p_name'] = $productArray[$productId]['name'];
            $_SESSION['upsell_price'] = $productArray[$productId]['price'];
            $_SESSION['upsell_prices'] += $productArray[$productId]['price'];

            header("Location:{$productArray[$productId]['success_redirect']}");
            die();
        } else {
            if (isset($ret['message']))
                $errorMessage = $ret['message'];
            $ref = $_SERVER['HTTP_REFERER'];
            $refParts = explode('?', $ref);
            $url = $refParts[0] . '?errorMessage=' . $errorMessage;
            header("Location:$url");
        }
    } else {

        $ref = $productArray[$productId]['page'];
        $url = $ref . '?errorMessage=' . $errordub;
        header("Location:$url");
    }
    exit();
} else {
    if (isset($_POST['submitted']) && $_POST['submitted'] == '1') {
        ?>
        <link rel="shortcut icon" href="img/favicon.webp?v=9" type="image/png">
        <?php
        $r = NewClick($api_key_user, $api_key_pass, $campaign_id, 'checkoutPage', $_SERVER['REQUEST_URI']);

        $ret = json_decode($r, true);
        $session_id = $ret['message']['sessionId'];

        $_SESSION['session_id'] = $session_id;
        $_SESSION['session_id_new'] = $session_id;

        $_SESSION['custom_product'] = $_REQUEST['custom_product'];
        $errors = array();
        $requiredFields = array('campaign_id' => 'Campaign Id', 'custom_product' => 'Product Id', 'cc_number' => 'Credit Card number');

        if (!isset($_REQUEST['paySource_paypal'])) {
            foreach ($requiredFields as $k => $v)
                if (empty($_POST[$k])) {
                    $errors[] = $requiredFields[$k] . " can not be empty.";
                }
        }

        foreach ($_REQUEST as $k => $v) {
            $_SESSION[$k] = $v;
            $_SESSION[$k . '_2'] = $v;
        }


        if (count($errors) > 0) {
            $errorMessage = implode("\n ", $errors);
        } else {

            $leads_res = Leads($api_key_user, $api_key_pass, $campaign_id, $_REQUEST);
            if ($leads_res->result == 'SUCCESS') {


                if (isset($_REQUEST['paySource_paypal'])) {
                    $_SESSION['order_id_lead'] = $leads_res->message->orderId;
                    $_SESSION['ord_id'] = $leads_res->message->orderId;
                    $_SESSION['product_id_1'] = $_POST['custom_product'];
                } else {
                    $_SESSION['order_id_lead'] = '';
                }

                $content = NewOrder($api_key_user, $api_key_pass, $_REQUEST);
                $ret = json_decode($content);

                if ($ret->result == 'SUCCESS') {
                    if (!isset($ret->message->paypalUrl)) {
                        $data2 = $ret->message;
                        $_SESSION['fields_email'] = "";
                        $_SESSION['fields_country'] = "";
                        $_SESSION['fields_phone'] = "";
                        $_SESSION['fields_fname'] = "";
                        $_SESSION['fields_lname'] = "";
                        $_SESSION['fields_address1'] = "";
                        $_SESSION['shippingCity'] = "";
                        $_SESSION['shippingState'] = "";
                        $_SESSION['fields_state_UK'] = "";
                        $_SESSION['fields_state_other'] = "";
                        $_SESSION['shippingZip'] = "";
                        $_SESSION['fields_country'] = "";
                        $_SESSION['billing_postcode_ca'] = "";
                        $_SESSION['fields_apt'] = "";
                        $_SESSION['billing_apt'] = "";
                        $_SESSION['billing_country'] = "";
                        $_SESSION['billing_street_address'] = "";
                        $_SESSION['billing_city'] = "";
                        $_SESSION['billing_state'] = "";
                        $_SESSION['billing_state_stateUK'] = "";
                        $_SESSION['billing_state_state_other'] = "";
                        $_SESSION['billing_postcode'] = "";
                        $_SESSION['fields_coupon'] = "";
                        $_SESSION['coupon_discount_code'] = "";
                        $_SESSION['salesTax'] = "";
                        $_SESSION['coupon_discount_price'] = "";
                        $_SESSION['coupon_discount_price_val'] = "";
                        $_SESSION['upsell_price'] = "";
                        $_SESSION['upsell_prices'] = "";

                        $product_Id = $_REQUEST['custom_product'];
                        $_SESSION['main_price'] = $ret->message->totalAmount;
                        $_SESSION['orderValue'] = $ret->message->orderValue;
                        $_SESSION['purchased_items'] = array();
                        $_SESSION['purchased_items_ids'] = array();
                        $_SESSION['purchased_items_ids'][] = array('product_id' => $product_Id);
                        $_SESSION['purchased_items']  = array('time' => time(), 'return' => $data2->items,'total_amount' => $data2->totalAmount,'order_id' => $data2->orderId, 'discountPrice' => $data2->discountPrice);
                        $orderId = $data2->orderId;
                        $_SESSION['discountPrice'] = $data2->discountPrice;
                        $_SESSION['ord_id'] = $orderId;
                        $_SESSION['main_price'] = $data2->totalAmount;
                        $_SESSION['sku'] = $productArray[$product_Id]['sku'];
                        $_SESSION['main_name'] = $productArray[$product_Id]['name'];

                        header("Location:{$productArray[$product_Id]['success_redirect']}");
                    } else {
                        header("Location:{$ret->message->paypalUrl}");
                    }
                } else {
                    if (isset($ret->message) && is_string($ret->message))
                        $errorMessage = "Please fix the following errors: " . $ret->message;

                    if (is_object($ret->message)) {
                        foreach ($ret->message as $key => $value) {
                            $errorMessage .= $key . " " . $value . " ";
                        }
                    }
                }
            } else {
                if (isset($ret->message) && is_string($ret->message))
                    $errorMessage = "Please fix the following errors: " . $ret->message;

                if (is_object($ret->message)) {
                    foreach ($ret->message as $key => $value) {
                        $errorMessage .= $key . " " . $value . " ";
                    }
                }
            }

        }
    } else {
    }
}
$product_id = $_REQUEST['product_id'];
if ($product_id == '142' || $product_id == '143') {
    $shipping = 0.00;
} else {
    $shipping = 4.99;
}
?>
<?php
@session_start();
error_reporting(0);
$validation_function = 'validate_checkout_form()';
if (!isset($_REQUEST['prospectId']) || empty($_REQUEST['prospectId'])) {
    $validation_function = 'validate_one_form()';
}
if ($errorMessage) {
    echo "<table id='error_table_msg' style='width:100%;' align='center'><tr><td style='background-color:#ff0000;color:#ffffff;font-size: 18px;font-family: arial,helvetica,sans-serif; font-weight:bold;height:50px; padding-top: 10px; text-align: center;' align='center'>" . urldecode($errorMessage) . "</td></tr></table>";
}

if ($_REQUEST['errorMessage']) {
    echo "<table style='width:100%;' align='center'><tr><td style='background-color:#ff0000;color:#ffffff;font-size: 18px;font-family: arial,helvetica,sans-serif; font-weight:bold;height:50px; padding-top: 10px; text-align: center;' align='center'>" . urldecode($_REQUEST['errorMessage']) . "</td></tr></table>";
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Get MagLife Bracelet</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <link rel="stylesheet" href="checkout-style.css?v=4.2"/>
    <link rel="shortcut icon" href="img/favicon.webp?v=9" type="image/png">

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '725297681751604');
        fbq('track', 'InitiateCheckout');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=725297681751604&ev=InitiateCheckout&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-102630686-11"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-102630686-11');
</script>


    <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
    <script src="js/html5shiv.min.js"></script>
    <script>
        $(document).ready(function () {
            // tips
            $(".cal_help").hover(function () {
                $(this).find(".cal_tip_area").fadeIn(300);
            });

            $(".cal_help").mouseleave(function () {
                $(this).find(".cal_tip_area").fadeOut(200);
            });

            $(".check_btn").click(function () {
                $(".cs-loader").show();

                setTimeout(function () {
                    $('.result_col').fadeIn();
                    $('.form_col').hide();

                }, 3000);

            });

            //reload page
            $('.recal').click(function () {

                location.reload();
            });

        });
    </script>

    <style>
        /*button.com_pur {
            font-size: 31px;
            background: rgb(255, 159, 0);
            padding: 20px 20px;
            color: #fff;
            display: inline-block;
            text-align: center;
            border-radius: 5px;
            margin: 30px 0 0 0;
            font-family: 'Lato-Regular';
            width: 100%;
            border: 0;
            cursor: pointer;
        }*/
        td.coupon_td_btn{
            width: 40% !important;
        }
        @media (max-width: 361px) {
            button.com_pur {
                font-size: 24px;
            }
        }

        .email {
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 99.7%;
            display: inline-block;
            padding: 12px 20px;
            margin: 8px 0px 8px 0;
            box-sizing: border-box;
        }

        .s_con {
            width: 49%;
        }

        input#autocompleteship[type=text] {
            width: 64%;
            display: inline-block;
            padding: 12px 20px;
            margin: 8px 3px 8px 0;
            box-sizing: border-box;
        }

        input#billing_apt[type=text] {
            width: 34.5%;
            display: inline-block;
            padding: 12px 20px;
            margin: 8px 0px 8px 0;
            box-sizing: border-box;
        }

        input#billing_state_stateCA[type=text] {
            width: 49%;
            padding: 12px 20px;
            margin: 8px 0px 8px 0;
            box-sizing: border-box;
        }

        input#stateCA[type=text] {
            width: 49%;
            padding: 12px 20px;
            margin: 8px 0px 8px 0;
            box-sizing: border-box;
        }

        .s_con_2 {
            width: 37%;
        }

        .credit_card {
            padding: 15px 20px 15px 14px;
        }

        td.dollar5 {
            padding: 5px 5px 10px 261px;
            float: right;
        }

        .checkout_pg td.dollar {
            padding: 10px 10px 30px 93px;
        }

        .checkout_pg td.dollar_total {
            padding: 5px 5px 10px 242px;
        }
        tr#tr-coupon > td.coupon_td {
            width: 70% !important;
        }
        tr#tr-coupon > td.coupon_td_btn {
            width: 30% !important;
        }
        @media screen and (max-width: 830px){
        td.coupon_td {
            width: 70% !important;
        }
            .customer_info {
                padding-top: 0 !important;
            }
        }

        @media (max-width: 500px) {
            td.coupon_td {
                width: 50% !important;
            }
        }
        @media (max-width: 420px) {
            td.dollar5 {
                padding: 5px 5px 10px 119px;
            }
            td.coupon_td {
                width: 50% !important;
            }

            .mobile {
                margin-right: -75px !important;
            }

            .mobile_total {
                padding-left: 30px !important;
            }
            tr#tr-coupon > td.coupon_td {
                width: 55% !important;
            }
            tr#tr-coupon > td.coupon_td_btn {
                width: 45% !important;
            }
        }

        tr.coupon_tr td {
            padding: 5px 0 6px 7px;
        }

        @media screen and (max-width: 900px) {
            .checkout_pg td.dollar {
                padding: 10px 10px 30px 68px;
            }
            .checkout_pg .product_bg tr td:nth-child(2) {
                width: auto !important;
            }
            .checkout_pg td.dollar_total {
                padding: 5px 5px 30px 242px;
            }
        }

        td {
            padding: 5px 10px 10px 10px;
        }
    </style>
    <style>
        [v-cloak] {
            display: none;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .white-overlay {
            background-color: rgba(255, 255, 255, 0.90);
            z-index: 9999;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            position: fixed;
            margin: 0;
        }

        .spinner-wrapper {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: 0 auto;
            min-width: 220px;
        }

        .spinner {
            border: 6px solid #e2e2e2;
            border-top: 6px solid #297FBC;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 2s linear infinite;
            margin: auto;
        }

        .spinner-icon {
            position: relative;
        }

        .spinner-icon .spinner-icon-svg {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -19px;
            margin-top: -70px;
            width: 40px;
            height: 40px;
        }

        .spinner-text {
            text-align: center;
            color: #526473;
            font-size: 16px;
            padding-top: 2rem;
        }
    </style>
    <style>
        @import url('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');

        html {
            min-height: 100%;
        }

        body {
            height: 100%;
            overflow-x: hidden;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        }

        input, label, select {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
            font-size: 14px;
        }

        input {
            font-size: 14px !important;
        }

        :-webkit-autofill {
            color: #fff !important;
        }

        h1 {
            font-size: 22px;
            font-weight: normal;
            margin: 0;
        }

        h2 {
            font-size: 18px;
            font-weight: normal;
            margin: 0;
        }

        h3 {
            font-size: 16px;
            font-weight: normal;
            margin: 0;
        }

        .row {
            padding: 10px 0 10px 0;
        }

        .padding-top-20 {
            padding-top: 20px;
        }

        .padding-left-20 {
            padding-left: 20px;
        }

        .padding-bottom-20 {
            padding-bottom: 20px;
        }

        .padding-right-20 {
            padding-right: 20px;
        }

        section.mobile-switch {
            position: relative;
            border-top: 30px solid white;
            padding: 15px 20px 15px 20px;
            margin-left: -15px;
            margin-right: -15px;
            font-size: 14px;
            z-index: 10;
        }

        section.mobile-switch span p {
            display: inline;
        }

        section.mobile-switch .fa-shopping-cart {
            font-size: 24px;
            padding-right: 10px;
        }

        section.mobile-switch .fa-chevron-down {
            font-size: 12px;
        }

        section.mobile-switch .total-price-without-discount {
            font-size: 0.85714em;
            text-decoration: line-through;
            color: #999999;
            position: absolute;
            right: 20px;
            top: 5px
        }

        section.mobile-switch .ch-placeholder {
            font-size: 18px;
        }

        section.mobile-switch:before {
            content: '';
            position: absolute;
            z-index: -1;
            top: 0px;
            left: 0px;
            display: block;
            left: -300px;
            width: 900%;
            background: #fafafa;
            border-top: 1px solid #e6e6e6;
            border-bottom: 1px solid #e6e6e6;
            height: 100%;
        }

        .input-wrap,
        .select-wrap {
            position: relative;
            width: 100%;
            margin-bottom: 12px;
        }

        .input-wrap.ch-invalid,
        .select-wrap.ch-invalid {
        }

        .text-danger {
            display: none;
            color: #de2a2a;
            padding-top: 4px;
        }

        .ch-invalid .text-danger {
            display: block;
        }

        .input-wrap .ch-input,
        .select-wrap .ch-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            -o-appearance: none;
            appearance: none;
            outline: none;
            width: 100%;
            height: 44px;
            color: black;
            border: none;
            -moz-box-shadow: 0 0 0 1px #d9d9d9;
            -webkit-box-shadow: 0 0 0 1px #d9d9d9;
            box-shadow: 0 0 0 1px #d9d9d9;
        }

        .select-wrap .ch-select {
            padding-right: 32px;
        }

        .input-wrap .ch-input:focus,
        .select-wrap .ch-select:focus {
            -moz-box-shadow: 0 0 0 2px black;
            -webkit-box-shadow: 0 0 0 2px black;
            box-shadow: 0 0 0 2px black;
            -webkit-transition: all 0.4s;
            transition: all 0.4s;
        }

        .input-wrap.ch-invalid .ch-input,
        .select-wrap.ch-invalid .ch-select {
            -moz-box-shadow: 0 0 0 2px #de2a2a;
            -webkit-box-shadow: 0 0 0 2px #de2a2a;
            box-shadow: 0 0 0 2px #de2a2a;
            transition: all 0.4s;
        }

        .input-wrap .input-label,
        .select-wrap .input-label {
            font-weight: normal;
            position: absolute;
            top: 12px;
            left: 0px;
            padding-left: 13px;
            padding-right: 13px;
            white-space: nowrap;
            overflow: hidden;
            font-size: 14px;
            color: #999999;
            pointer-events: none;
            width: -webkit-calc(100% - 13px);
            width: -moz-calc(100% - 13px);
            width: calc(100% - 13px);
        }

        .input-wrap .ch-input.ch-dirty,
        .select-wrap .ch-select.ch-dirty {
            padding-top: 22px;
        }

        .input-wrap .ch-input.ch-dirty + .input-label,
        .select-wrap .ch-select.ch-dirty + .input-label {
            top: 6px;
            font-size: 12px;
            -webkit-transition: top 0.2s, font-size 0.2s;
            transition: top 0.2s, font-size 0.2s;
        }

        .input-wrap i,
        .select-wrap i {
            position: absolute;
            top: 13px;
            right: 10px;
            font-size: 18px;
            color: #d9d9d9;
        }

        .input-wrap i.fa-lock + .pop-up,
        .input-wrap i.fa-question-circle + .pop-up {
            display: none;
        }

        .input-wrap i.fa-lock:hover + .pop-up,
        .input-wrap i.fa-question-circle:hover + .pop-up {
            display: block;
        }

        .input-wrap .pop-up {
            position: absolute;
            bottom: 44px;
            right: 0px;
            min-width: 200px;
        }

        .input-wrap .pop-up:before {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: black;
            opacity: 0.8;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        .input-wrap .pop-up:after {
            content: "";
            display: block;
            position: absolute;
            bottom: -8px;
            right: 8px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 8px solid black;
            opacity: 0.8;
        }

        .input-wrap .pop-up .content {
            padding: 12px;
            color: white;
            position: relative;
            text-align: center;
        }

        .select-wrap .ch-select-arrow {
            position: absolute;
            right: 0px;
            width: 30px;
            height: 20px;
            top: 11px;
            border-left: 1px solid #d9d9d9;
            pointer-events: none;
            text-align: center;
            background-color: white;
            z-index: 1;
        }

        .select-wrap .ch-select-arrow:before {
            content: '';
            position: relative;
            top: -2px;
            display: inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #737373;
        }

        .checkbox-wrap {
            position: relative;
            padding-top: 8px;
            padding-bottom: 40px;
            width: 100%;
            height: 20px;
        }

        .checkbox-wrap .ch-label {
            display: block;
            width: 100%;
            height: 20px;
        }

        .checkbox-wrap .ch-checkbox {
            display: none;
        }

        .checkbox-wrap .ch-checkbox + label .ch-custom-checkbox {
            position: relative;
            display: block;
            float: left;
            margin-right: 10px;
            width: 18px;
            height: 18px;
            -moz-box-shadow: inset 0 0 0 1px #d9d9d9;
            -webkit-box-shadow: inset 0 0 0 1px #d9d9d9;
            box-shadow: inset 0 0 0 1px #d9d9d9;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            -webkit-transition: box-shadow 0.3s;
            transition: box-shadow 0.3s;
            cursor: pointer;
        }

        .checkbox-wrap .ch-checkbox:checked + label .ch-custom-checkbox {
            -webkit-box-shadow: inset 0 0 0 9px black;
            -moz-box-shadow: inset 0 0 0 9px black;
            box-shadow: inset 0 0 0 9px black;
            -webkit-transition: box-shadow 0.3s;
            transition: box-shadow 0.3s;
        }

        .checkbox-wrap .ch-checkbox:checked + label .ch-custom-checkbox:before {
            content: '\2713 ';
            display: block;
            position: absolute;
            top: 0px;
            left: 0px;
            width: 18px;
            height: 18px;
            color: white;
            text-align: center;
            line-height: 18px;
            font-size: 12px;
        }

        .checkbox-wrap .ch-label .ch-custom-label {
            display: block;
            float: left;
            font-weight: normal;
            color: #737373;
            font-size: 14px;
            cursor: pointer;
        }

        ul.bordered-ul {
            width: 100%;
            list-style: none;
            padding: 0;
            margin: 0 0 12px 0;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            border: 1px solid #d9d9d9;
        }

        ul.bordered-ul li:not(.extended-li) {
            padding: 0;
            margin: 0;
            height: 54px;
            border-top: 1px solid #d9d9d9;

            position: relative;
        }

        ul.bordered-ul li:not(.extended-li):first-child {
            border-top: none;
        }

        ul.bordered-ul li:not(.extended-li) label {
            font-weight: normal;
            line-height: normal;
            width: 100%;
            cursor: pointer;
        }

        ul.bordered-ul li:not(.extended-li) label .payments {
            padding-top: 14px;
            padding-right: 20px;
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img {
            display: block;
            float: left;
            margin: 0 2px 0 2px;
            width: 38px;
            height: 24px;
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img.visa {
            background-image: url('assets/images/payment-methods/visa.svg');
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img.master {
            background-image: url('assets/images/payment-methods/master.svg');
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img.american {
            background-image: url('assets/images/payment-methods/american.svg');
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img.jcb {
            background-image: url('assets/images/payment-methods/jcb.svg');
        }

        ul.bordered-ul li:not(.extended-li) label .payments .img.discover {
            background-image: url('assets/images/payment-methods/discover.svg');
        }

        ul.bordered-ul li:not(.extended-li) label .payments .and-more {
            display: block;
            padding-left: 4px;
            float: left;
            height: 24px;
            line-height: 24px;
            color: #999999;
            font-size: 12px;
        }

        ul.bordered-ul li:not(.extended-li) label input {
            display: none;
        }

        ul.bordered-ul li:not(.extended-li) label .ch-custom-label {
            display: block;
            position: absolute;
            max-width: 80%;
            top: 50%;
            left: 54px;
            max-height: 48px;
            overflow: hidden;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        ul.bordered-ul li:not(.extended-li) label .ch-custom-label.absolute-left {
            left: 6px;
            max-width: 100%;
        }

        ul.bordered-ul li:not(.extended-li) label .price {
            display: block;
            position: absolute;
            top: 50%;
            right: 12px;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        ul.bordered-ul li:not(.extended-li) label .ch-custom-label img {
            max-height: 24px;
        }

        ul.bordered-ul li:not(.extended-li) label .ch-custom-radio {
            margin: 18px;
            display: block;
            float: left;
            width: 18px;
            height: 18px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            border: 1px solid #d9d9d9;
            -webkit-transition: border-width 0.3s;
            transition: border-width 0.3s;
        }

        ul.bordered-ul li:not(.extended-li) label input[type='radio']:checked + .ch-custom-radio {
            border: 7px solid #1a1a1a;
            -webkit-transition: border-width 0.3s;
            transition: border-width 0.3s;
        }

        ul.bordered-ul li.extended-li {
            padding: 20px 0 8px 0;
            margin: 0;
            background-color: #fafafa;
            border-top: 1px solid #d9d9d9;
        }

        .ch-btn {
            position: relative;
            width: 100%;
            padding: 20px;
            font-size: 32px;
            background-color: rgb(255, 159, 0);
            color: white;
            white-space: normal;
        }

        .ch-btn.ch-loading:before,
        .ch-btn:after {
            content: '';
            display: block;
            position: absolute;
            bottom: 0px;
            height: 3px;
        }

        .ch-btn.ch-loading:before {
            width: 0%;
            background-color: green;
            right: 0%;
            -webkit-animation: myfirst1 4s infinite;
            animation: myfirst1 4s infinite;
        }

        @keyframes myfirst1 {
            0% {
                width: 0%;
            }
            20% {
                width: 100%;
            }
            80% {
                width: 100%;
            }
            100% {
                width: 0%;
            }
        }

        .ch-btn.ch-loading:after {
            width: 0%;
            background-color: inherit;
            right: 0%;
            -webkit-animation: myfirst2 4s infinite;
            animation: myfirst2 4s infinite;
            -webkit-animation-delay: 0.85s;
            animation-delay: 0.85s;
        }

        @keyframes myfirst2 {
            0% {
                width: 0%;
            }
            30% {
                width: 100%;
            }
            55% {
                width: 0%;
            }
            100% {
                width: 0%;
            }
        }

        .ch-btn .ch-btn-text {
        }

        .ch-btn:hover {
            background-color: rgb(222, 140, 3);
            color: white;
        }

        footer {
            margin-top: 60px;
            border-top: 1px solid #d9d9d9;
            font-size: 12px;
            color: #737373;
            padding-bottom: 30px;
        }

        footer ul.footer-links {
            padding: 14px 0 14px 0;
            list-style: none;
            margin: 0;
        }

        footer ul.footer-links li {
            padding: 0;
            margin: 0;
            float: left;
        }

        ul.cart-line-items {
            list-style: none;
            padding: 0;
            margin: 0;
            padding-bottom: 12px;
        }

        ul.cart-line-items li {
            padding: 0;
            margin: 0;
        }

        ul.cart-line-items li .image-container {
        }

        ul.cart-line-items li .image-container .item-image-holder {
            position: relative;
            width: 64px;
            height: 64px;
            -moz-box-shadow: inset 0 0 0 1px #d9d9d9;
            -webkit-box-shadow: inset 0 0 0 1px #d9d9d9;
            box-shadow: inset 0 0 0 1px #d9d9d9;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center center;
        }

        ul.cart-line-items li .item-image-holder .item-quanitity-indicator {
            position: absolute;
            top: -11px;
            right: -11px;
            width: 22px;
            height: 22px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            background-color: #737373;
            color: white;
            text-align: center;
            line-height: 22px;
        }

        ul.cart-line-items li .title-container {
            height: 64px;
            display: block;
            line-height: 64px;
            color: #4b4b4b;
        }

        ul.cart-line-items li .title-container .title-floater {
            line-height: normal;
            display: inline-block;
            height: 1px;
            width: 100%;
            position: relative;
        }

        ul.cart-line-items li .title-container .title-floater .line-title,
        ul.cart-line-items li .title-container .title-floater .line-variant-title {
            width: 100%;
            display: block;
            position: absolute;
        }

        ul.cart-line-items li .title-container .title-floater .line-title {
            font-weight: bold;
            overflow: hidden;
            line-height: 16px;
            max-height: 32px;
            bottom: 2px;

        }

        ul.cart-line-items li .title-container .title-floater .line-variant-title {
            color: grey;
            font-size: 12px;
            overflow: hidden;
            line-height: 12px;
            max-height: 24px;
            top: 4px;
        }

        ul.cart-line-items li .price-container {
            height: 64px;
            line-height: 64px;
            color: #4b4b4b;
            font-weight: bold;
        }

        ul.cart-line-items li .price-container .line-price {
            display: inline-block;
            font-size: 14px;
        }

        ul.cart-line-items li .price-container .line-price-strike {
            display: block !important;
            margin-bottom: -40px;
            position: relative;
            margin-top: -5px;
            font-size: 12px;
            opacity: 0.8;
            text-decoration: line-through;
        }

        .ch-coupon-form {
            padding-bottom: 8px;
        }

        .ch-coupon-form .ch-coupon-switcher {
            padding-bottom: 20px;
            cursor: pointer;
        }

        .ch-coupon-form .ch-coupon-switcher i {
            position: relative;
            top: -2px;
            font-size: 10px;
        }

        .ch-coupon-form .ch-coupon-switcher .coupon-initial {
        }

        .ch-coupon-form .ch-coupon-switcher .coupon-success {
            color: #4CAF50;
        }

        .ch-coupon-form button.btn.btn-coupon {
            height: 44px;
            color: white;
            transition: all 0.2s;
            background-color: gray;
        }

        .ch-coupon-form button.btn.btn-coupon.ch-active {
            background-color: #1a1a1a;
            -webkit-transition: all 0.2s;
            transition: all 0.2s;
        }

        .separator {
            padding: 10px 0;
        }

        .separator:before {
            content: '';
            display: block;
            height: 1px;
            background-color: #d9d9d9;
        }

        ul.ch-price-breakdown {
            list-style: none;
            padding: 0;
            margin: 0;
            padding-bottom: 14px;
        }

        ul.ch-price-breakdown li {
            padding: 0;
            margin: 0;
            height: 24px;
            line-height: 24px;
            margin-bottom: 8px;
        }

        ul.ch-price-breakdown li .ch-label {
            height: inherit;
            float: left;
            width: 50%;
            text-align: left;
        }

        ul.ch-price-breakdown li .ch-value {
            height: inherit;
            float: left;
            width: 50%;
            text-align: right;
            font-weight: bold;
        }

        .ch-total-price {
            height: 48px;
            padding-top: 20px;
        }

        .ch-total-price .ch-label {
            float: left;
            width: 50%;
            text-align: left;
            font-size: 16px;
            line-height: 30px
        }

        .ch-total-price .ch-value {
            float: left;
            width: 50%;
            text-align: right;
            font-size: 12px;
            line-height: 30px;
        }

        .ch-total-price .ch-value .ch-currency {
            padding-right: 10px;
        }

        .ch-total-price .ch-value .ch-placeholder {
            font-size: 24px;
            font-weight: bold;
        }

        .form-control {
            padding: 4px 12px;
        }

        @media (max-width: 500px) {
            ul.bordered-ul li:

        not(.extended-li

        ) label .payments {
              padding-top: 20px;
          }

            ul.bordered-ul li:not(.extended-li) label .payments .img {
                width: 18px;
                height: 14px;
                background-position: center;
                background-size: cover;
            }
        }

        @media (max-width: 767px) {
            ul.cart-line-items li .image-container .item-image-holder {
                width: 40px;
                height: 40px;
                margin-top: 12px;
            }
        }

        @media (max-width: 991px) {
            .container {
                overflow-x: hidden;
            }

            section.checkout-side {
                z-index: 8;
            }

            section.checkout-side.ch-hide {
                max-height: 0px;
                overflow: hidden;
                -webkit-transition: max-height 1s;
                transition: max-height 1s;
            }

            section.checkout-side:after {
                position: absolute;
                content: '';
                display: block;
                background: #fafafa;
                border-bottom: 1px solid #e6e6e6;
                top: 0px;
                left: -300px;
                width: 900%;
                height: 100%;
                z-index: -1;
            }

            section.checkout-side.ch-hide:after {
                border: none;
            }

            ul.bordered-ul li:not(.extended-li) label .ch-custom-label {
                max-width: 60%;
            }
        }

        @media (min-width: 992px) {
            .container {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
            }

            .row .no-right-padding {
                padding-right: 0px !important;
            }

            .row .no-left-padding {
                padding-left: 0px !important;
            }

            .row .padding-right-7 {
                padding-right: 7px !important;
            }

            .row .padding-left-7 {
                padding-left: 7px !important;
            }

            section.checkout-main,
            section.checkout-side {
            }

            section.checkout-main {
                padding-top: 60px;
                padding-left: 15px;
                padding-right: 60px;
            }

            section.checkout-main:after {
                content: "";
                display: block;
                position: absolute;
                right: 0px;
                top: 0px;
                height: 100%;
                width: 500%;
                z-index: -1;
                background-color: white;
            }

            section.checkout-side {
                padding-top: 60px;
                position: relative;
                padding-left: 30px;
            }

            section.checkout-side:after {
                content: "";
                display: block;
                position: absolute;
                left: 0px;
                top: 0px;
                height: 100%;
                width: 500%;
                z-index: -1;
                background-color: #fafafa;
                box-shadow: 1px 0 0 #e1e1e1 inset;
            }
        }
        @media only screen and (max-width:940px) {

            span.etc_and_more {
                display: none !important;
            }

            img.etc_and_more {
                margin-right: 10px !important;
            }
        }
        @media only screen and (min-width:830px) and (max-width:1015px) {
            .fr{
                margin-top: -18px;
            }
        }
        @media only screen and (min-width:918px) and (max-width:940px) {
            .fr{
                margin-top: 0 !important;
            }
        }
        @media (max-width: 740px){
            #orderTable td#name_product {
                width: auto !important;
                /*display: inline;*/
            }
        }
        @media (max-width: 400px){
            #orderTable td#name_product {
                white-space: unset !important;
            }
        }
        .checkout_pg #orderTable td.dollar {
            /*padding: 0;*/
            padding: 5px 10px 10px 10px;
            text-align: right;
            margin-bottom: 10px;
        }
        #orderTable td#name_product {
            max-width: none;
            /*display: inline;*/
            white-space: nowrap;
            margin-bottom: 10px;
        }
        p.s_add {
            padding-bottom: 8px;
            padding-top: 5px;
        }
        .mt-20 {
            margin-top: 5px;
            margin-bottom: 8px;
        }
/*        #tr-taxes td{
            padding: 5px 10px 10px 10px;
        }*/
        .footer-links{
            text-decoration: none;
            color: #000000 !important;
            opacity: 1;
            transition: opacity 0.2s ease-in-out;
            font-size: 14px;
        }
        .disclamer_text{
            margin: 0 9px;
            line-height: 1.5;
            font-size: 12px;
        }
        a.btn.btn_coupon{
            width: 100%;
        }
        .s_con, .s_con_2{
            -webkit-appearance: none !important;
            background: url("https://cdn1.iconfinder.com/data/icons/pixel-perfect-at-16px-volume-2/16/5001-128.png") 98% 48% !important;
            background-repeat: no-repeat !important;
            background-color: #fff !important;
            background-size: 8px 8px !important;
        }
        #tr-coupon{
            width: 100%;
        }
        #orderTable{
            width: 100%;
        }
        #fields_coupon{
        margin: 7px 0;
        }
    </style>
</head>

<body>
<!--<div class="hdr-top-bar-sec-wrp">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="hdr-top-bar-wrp">
          <span>NEW YEAR FLASH SALE!</span>
          <p>Use code  <span>NY15</span>  for 15% OFF</p>
        </div>
      </div>
    </div>
  </div>
</div>-->
<div class="checkout_pg width">
    <div class="container">
        <div class="row showOnMobile clearfix">
            <div class="col-12">
                <div class="hdr-innr clearfix">
                    <div class="hdr-lft">
                        <img data-src="img/logo.png" src="img/pixel.png" class="logo lazy" alt="">
                    </div>
                    <div class="hdr-rgt">
                        <img class="lazy" data-src="img/secure.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mobileSwitch clearfix">
            <div class="col-5 order-col">
                <div class="product_bg pt-60">
                    <table id="orderTable">
                        <tr class="border">
                            <!--<td>
                                <div class="pro_img">
                                    <img data-src="" src="img/pixel.png" alt="" class="weird_rock lazy"
                                         id="img_product">
                                </div>
                            </td>-->
                            <td id="name_product"></td>
                            <td class="dollar price_product"></td>
                        </tr>

                        <tr id="tr-coupon" class="coupon_tr border">
                            <td class="coupon_td">
                                <input type="text" class="coupon_input" placeholder="Coupon" name="fields_coupon"
                                       id="fields_coupon" value="<?php echo $_SESSION['coupon_discount_code']; ?>">
                            </td>
                            <td class="coupon_td_btn">
                                <a href="javascript:void(0);" onclick="coupon();" class="btn btn_coupon">Apply Coupon</a>
                            </td>
                        </tr>
                        <tr style="display: none" id="coupon_msg">
                            <td style="width: 100% !important;">
                                <p id="applied" style="color:green;font-weight: 600;display: none;padding-bottom: 0;">Discount Applied</p>
                                <p id="apply_error" style="color:red;font-weight: 600;display: none;padding-bottom: 0;">Invalid Code</p>
                                <p id="applied_text_error" style="display: none;color:red;padding-bottom: 0;">You must enter the email
                                    address first to apply coupon code.</p>
                            </td>
                            <td style="width: 0 !important;"></td>
                        </tr>
                        <tr id="tr-subtotal">
                            <td class="sbtotal">Subtotal</td>
                            <td class="dollar5 sbttl_amount price_product"></td>
                        </tr>

                        <tr id="tr-shipping">
                            <td>Shipping</td>
                            <td id="shipping" class="dollar5 mobile">$<span id="shipping"><?php echo $shipping; ?></span></td>
                        </tr>
                        <tr id="discount_tr" style="display: none;">
                            <td>Discount</td>
                            <td class="dollar5 mobile" id="discount_td"></td>
                        </tr>
                        <tr id="tr-taxes">
                            <td>Tax</td>
                            <td class="dollar5 mobile taxes"></td>
                        </tr>
                        <tr id="tr-total" style="border-top: 1px solid #e6e6e6;">
                            <td class="title_total">Total</td>
                            <td class="dollar_total"><span id="total_span"
                                                           class="bigger_font mobile_total total_product_price"
                                                           style="padding-left: 5px;"></span></td>
                        </tr>

                    </table>
                    <div class="review-secure">
                        <div class="review-dsc-wrp">
                            <div class="review-head">
                                <h6><span>Trusted customer reviews</span></h6>
                            </div>
                            <div class="review-dsc-tp-wrp">
                                <div class="review-dsc-tp-inr clearfix">
                                    <div class="review-dsc-tp-img">
                                        <img class="lazy" data-src="img/review-1-2.png">
                                    </div>
                                    <div class="review-dsc-tp-dsc">
                                        <img src="img/review-stars.png">
                                        <strong>Laura B.</strong>
                                        <span> Dallas, TX</span>
                                        <b> <img class="lazy" data-src="img/locker-green.png"> Verified Buyer</b>
                                        <p>“It does work, I got myself and my son some. My son commented the next day the pain in his wrists was almost gone. Mine are working great no pain. My son is a mechanic and sure loves his.”</p>
                                    </div>
                                </div>
                                <div class="review-dsc-tp-inr clearfix">
                                    <div class="review-dsc-tp-img">
                                        <img class="lazy" data-src="img/review-3-2.png">
                                    </div>
                                    <div class="review-dsc-tp-dsc">
                                        <img src="img/review-stars.png">
                                        <strong>Tomas C.</strong>
                                        <span> Salt Lake City, UT</span>
                                        <b> <img class="lazy" data-src="img/locker-green.png"> Verified Buyer</b>
                                        <p>"I have had mine on for five days and they work. Less joint aches. Working for me!"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="review-dsc-wrp">
                            <div class="review-head">
                                <h6><span>Why choose us?</span></h6>
                            </div>
                            <div class="review-dsc-tp-wrp">
                                <div class="review-dsc-tp-inr clearfix">
                                    <div class="review-dsc-tp-img">
                                        <img class="lazy" data-src="img/shipping-bw2.png">
                                    </div>
                                    <div class="review-dsc-tp-dsc">
                                        <p> 30-Day Money Back Guarantee. If you don't like your product for any reason, send it back to us for a refund!</p>
                                    </div>
                                </div>
                                <div class="review-dsc-tp-inr clearfix">
                                    <div class="review-dsc-tp-img">
                                        <img class="lazy" data-src="img/dollar-bw2.png">
                                    </div>
                                    <div class="review-dsc-tp-dsc">
                                        <p> Over 50,000 successfully shipped orders and happy customers.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-extra-small-12 mb-30 for_mob">
<!--                    <a href="privacy.php" class="footer-links" target="_blank">I consent to receive recurring automated marketing by text message through an automatic telephone dialing system. Consent is not a condition to purchase. STOP to cancel, HELP for help. Message and Data rates may apply. View Privacy Policy & ToS.</a><br>-->
                    <a href="privacy.php" class="footer-links" target="_blank">Privacy Policy</a> |
                    <a href="terms.php" class="footer-links" target="_blank">Terms of service</a>
                </p>
            </div>

            <div class="col-7">
                <form action="./step2.php?product_id=<?php echo $_REQUEST['product_id'] ?>" method="post"
                      id="checkout_form" class="clearfix">
                    <div class="customer_info hdr-sec-wrp">
                        <div class="hdr-innr clearfix">
                            <div class="hdr-lft">
                                <img data-src="img/logo.png" src="img/pixel.png" class="logo lazy" alt="">
                            </div>
                            <div class="hdr-rgt">
                                <img class="lazy" data-src="img/secure.png">
                            </div>
                        </div>
                        <p class="f18 s_add pb-10 sec_title">Customer Information</p>

                        <input class="email" type="email" placeholder="Email address" id="email" maxlength="30"
                               onblur='$("#applied_text_error").css("display", "none");$("#apply_error").css("display", "none");'
                               ;
                               onfocusout="partial();"
                               name="fields_email" value="<?php echo $_SESSION['fields_email']; ?>"
                               pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" required>
                        <!--<div class="gap1">&nbsp;</div>-->
                        <p class="f18 s_add sec_title">Shipping Address</p>
                        <div class="shipping_add">
                            <input class="name" type="text" id="firstName" pattern="[\sa-zA-Z]{2,32}"
                                   title="First name should only contain letters." placeholder="First name"
                                   name="fields_fname"
                                   value="<?php echo $_SESSION['fields_fname']; ?>" required maxlength="30"
                                   onfocusout="partial();">

                            <input class="name" type="text" id="lastName" pattern="[\sa-zA-Z]{2,32}"
                                   onfocusout="partial();"
                                   title="Last name should only contain letters." placeholder="Last name"
                                   name="fields_lname"
                                   value="<?php echo $_SESSION['fields_lname']; ?>" required maxlength="30">

                            <input class="input_form" type="text" pattern="^((?!(0))[0-9]{10})$" title="Phone Must be 10 digits, and should not start with 0."
                                   placeholder="Phone number" id="phone" name="fields_phone" minlength="5"
                                   maxlength="10" value="<?php echo $_SESSION['fields_phone']; ?>">

                            <input minlength="3" type="text" id="autocomplete" placeholder="Address" name="fields_address1"
                                   maxlength="50" value="<?php echo $_SESSION['fields_address1']; ?>" required>

                            <input type="text" id="apt" name="fields_apt" placeholder="Apt, suite, etc. (optional)"
                                   value="<?php echo $_SESSION['fields_apt']; ?>">

                            <input minlength="3" maxlength="35" type="text" placeholder="City"
                                   title="City should only contain letters, and spaces but must not start with a space"
                                   id="locality" name="shippingCity" value="<?php echo $_SESSION['shippingCity']; ?>"
                                   required>
                            <?php
                            $countryArray = array();
                            $countryArray["US"] = "United States";
                            $countryArray["CA"] = "Canada";
                            $countryArray["AT"] = "Austria";
                            $countryArray["AU"] = "Australia";
                            $countryArray["BE"] = "Belgium";
                            $countryArray["DE"] = "Germany";
                            $countryArray["ES"] = "Spain";
                            $countryArray["FR"] = "France";
                            $countryArray["GB"] = "United Kingdom";
                            $countryArray["IE"] = "Ireland";
                            $countryArray["IT"] = "Italy";
                            $countryArray["NL"] = "Netherlands";
                            $countryArray["SE"] = "Sweden";
                            ?>
                            <select name="fields_country" id="country" class="s_con" placeholder="Country" required>
                                <?php
                                foreach ($countryArray as $key => $value) {
                                    if ($key == $_SESSION['fields_country']) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php
                            $statesArray = array();
                            $statesArray["AL"] = "Alabama";
                            $statesArray["AK"] = "Alaska";
                            $statesArray["AZ"] = "Arizona";
                            $statesArray["AR"] = "Arkansas";
                            $statesArray["CA"] = "California";
                            $statesArray["CO"] = "Colorado";
                            $statesArray["CT"] = "Connecticut";
                            $statesArray["DE"] = "Delaware";
                            $statesArray["DC"] = "District of Columbia";
                            $statesArray["FL"] = "Florida";
                            $statesArray["GA"] = "Georgia";
                            $statesArray["HI"] = "Hawaii";
                            $statesArray["ID"] = "Idaho";
                            $statesArray["IL"] = "Illinois";
                            $statesArray["IN"] = "Indiana";
                            $statesArray["IA"] = "Iowa";
                            $statesArray["KS"] = "Kansas";
                            $statesArray["KY"] = "Kentucky";
                            $statesArray["LA"] = "Louisiana";
                            $statesArray["ME"] = "Maine";
                            $statesArray["MD"] = "Maryland";
                            $statesArray["MA"] = "Massachusetts";
                            $statesArray["MI"] = "Michigan";
                            $statesArray["MN"] = "Minnesota";
                            $statesArray["MS"] = "Mississippi";
                            $statesArray["MO"] = "Missouri";
                            $statesArray["MT"] = "Montana";
                            $statesArray["NE"] = "Nebraska";
                            $statesArray["NV"] = "Nevada";
                            $statesArray["NH"] = "New Hampshire";
                            $statesArray["NJ"] = "New Jersey";
                            $statesArray["NM"] = "New Mexico";
                            $statesArray["NY"] = "New York";
                            $statesArray["NC"] = "North Carolina";
                            $statesArray["ND"] = "North Dakota";
                            $statesArray["OH"] = "Ohio";
                            $statesArray["OK"] = "Oklahoma";
                            $statesArray["OR"] = "Oregon";
                            $statesArray["PA"] = "Pennsylvania";
                            $statesArray["PR"] = "Puerto Rico";
                            $statesArray["RI"] = "Rhode Island";
                            $statesArray["SC"] = "South Carolina";
                            $statesArray["SD"] = "South Dakota";
                            $statesArray["TN"] = "Tennessee";
                            $statesArray["TX"] = "Texas";
                            $statesArray["UT"] = "Utah";
                            $statesArray["VT"] = "Vermont";
                            $statesArray["VA"] = "Virginia";
                            $statesArray["WA"] = "Washington";
                            $statesArray["WV"] = "West Virginia";
                            $statesArray["WI"] = "Wisconsin";
                            $statesArray["WY"] = "Wyoming";
                            ?>
                            <!--<input type="text" class="input_form two_fields s_con" name="shippingState"
                                   id="administrative_area_level_1"  value="<?php /*echo $_SESSION['shippingState']; */?>" placeholder="State"  required>-->
                            <select class="s_con" name="shippingState"
                                    style="<?php echo $_SESSION['fields_country'] == 'US' || $_SESSION['fields_country'] == '' ? "display:inline;" : "display:none;" ?>"
                                    id="administrative_area_level_22" required>
                                <option value="">Select State</option>
                                <?php
                                foreach ($statesArray as $key => $value) {
                                    if ($key == $_SESSION['shippingState']) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select>

                            <input type="text" pattern="[a-zA-Z0-9\s\-]+" placeholder="Zip Code" id="postal_code"
                                   onfocusout="partial();" name="shippingZip" minlength="3" maxlength="5"
                                   value="<?php echo $_SESSION['shippingZip']; ?>" required>
                        </div>

                        <div id="shipping_time_US" style="display: none;">
                            <p class="f18 mt-20 s_add">Shipping Time:</p>
                            <p class="f18 open_reg pb-0" style="font-size: 15px;">5-7 Business Days</p>
                        </div>
                        <div id="shipping_time" style="display: none;">
                            <p class="f18 mt-20 s_add">Shipping Time:</p>
                            <p class="f18 open_reg pb-0" style="font-size: 15px;">8-12 Business Days</p>
                        </div>

                        <!--<div class="gap1">&nbsp;</div>-->
                        <p class="f18 mt-20 s_add sec_title">Billing Address</p>

                        <label class="b_add" id="rad">Same as shipping address
                            <input type="radio" class="input_check" checked="checked" name="radio"
                                   onclick="valueChanged('YES')">
                            <span class="checkmark"></span>
                        </label>
                        <label class="b_add border_top" id="rad2">Use a different billing address
                            <input type="radio" class="input_check" name="radio" onclick="valueChanged('NO')">
                            <span class="checkmark"></span>
                        </label>

                        <div class="mt-20 mb-20 billing_add" style="display: none">
                            <input minlength="3" maxlength="50" type="text" name="billing_street_address"
                                   id="autocompleteship"
                                   value="<?php echo $_SESSION['billing_street_address']; ?>" placeholder="Address"/>
                            <input type="text" maxlength="35" id="billing_apt" name="billing_apt"
                                   placeholder="Apt, suite, etc. (optional)"
                                   value="<?php echo $_SESSION['billing_apt']; ?>">
                            <input minlength="3" maxlength="35" type="text" name="billing_city" id="locality_ship"
                                   placeholder="City" value="<?php echo $_SESSION['billing_city']; ?>"/>
                            <?php
                            $countryArray = array();
                            $countryArray["US"] = "United States";
                            $countryArray["CA"] = "Canada";
                            $countryArray["AT"] = "Austria";
                            $countryArray["AU"] = "Australia";
                            $countryArray["BE"] = "Belgium";
                            $countryArray["DE"] = "Germany";
                            $countryArray["ES"] = "Spain";
                            $countryArray["FR"] = "France";
                            $countryArray["GB"] = "United Kingdom";
                            $countryArray["IE"] = "Ireland";
                            $countryArray["IT"] = "Italy";
                            $countryArray["NL"] = "Netherlands";
                            $countryArray["SE"] = "Sweden";
                            ?>
                            <select name="billing_country" id="billing_country" placeholder="Country" class="s_con">
                                <?php
                                foreach ($countryArray as $key => $value) {
                                    if ($key == $_SESSION['billing_country']) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select>
                            <select name="billing_state" id="administrative_area_level_22_ship"
                                    style="display:  <?php echo $_SESSION['billing_country'] == 'US' || $_SESSION['billing_country'] == '' ? 'inline' : 'none' ?>"
                                    class="s_con">
                                <option value="">Select State</option>
                                <?php
                                foreach ($statesArray as $key => $value) {
                                    if ($key == $_SESSION['billing_state']) {
                                        echo "<option value='$key' selected>$value</option>";
                                    } else {
                                        echo "<option value='$key'>$value</option>";
                                    }
                                }
                                ?>
                            </select>

                            <!--                            <input class="input_form" type="text" placeholder="State" id="billing_state_stateCA" style="display: -->
                            <?php //echo $_SESSION['billing_country'] != 'US' && $_SESSION['billing_country']!='' ? 'inline' : 'none'  ?><!--" name="billing_state2" minlength="3" maxlength="30" value="-->
                            <?php //echo $_SESSION['billing_state2']; ?><!--">-->
                            <input class="input_form" pattern="[a-zA-Z0-9\s\-]+" type="text" name="billing_postcode"
                                   onfocusout="partial();" id="postal_code_ship" minlength="3" maxlength="5"
                                   value="<?php echo $_SESSION['billing_postcode']; ?>" placeholder="Zip Code"/>

                        </div>

                        <p class="f18 open_reg mt-20 pb-0 sec_title">Payment Information</p>
                        <p class="text-extra-small-12 pb-3">All transactions are secure and encrypted</p>
                        <p class="og_font text-extra-small-12">NOTE: We will ﻿<u>never﻿</u> charge your card
                            without your permission.</p>

                        <label class="credit_card">Credit Card
                            <input value="cards" type="radio" checked="checked" name="pmethod" id="credit_card" onclick="valueChangedPayment('credit_card')">
                            <span class="checkmark"></span>
                            <span class="fr">
<!--                                <img data-src="img/card.png" src="img/pixel.png" alt="" class="card lazy">-->
                                <img data-src="img/ccs.svg" src="img/pixel.png" alt="" class="card lazy etc_and_more" height="20">
                                <span class="etc_and_more" style="float: right;font-size: 12px; color: #919191;">and more...</span>
                            </span>
                        </label>
                        <div class="credit_info payment-method pmactive">
                            <div class="padlock">
                                <input type="text" placeholder="Card Number" id="cardNumber" name="cc_number"
                                       autocomplete="off" maxlength="16"
                                       onKeyDown="return onlyNumbers(event, 'require')" required>
                                <div class=" cal_help"><img data-src="img/padlock.png" src="img/pixel.png" alt=""
                                                            class="lazy">
                                    <div class="cal_tip_area" style="display: none;">
                                        <p> Please enter a valid credit card number </p>
                                    </div>
                                </div>
                            </div>
                            <div class="question">
                                <select class="s_con_2" name="fields_expmonth" id="expirationMonth" required>
                                    <option value="" selected>Select EXP Month</option>
                                    <option value="01">01 (Jan)</option>
                                    <option value='02'>02 (Feb)</option>
                                    <option value='03'>03 (Mar)</option>
                                    <option value='04'>04 (Apr)</option>
                                    <option value='05'>05 (May)</option>
                                    <option value='06'>06 (Jun)</option>
                                    <option value='07'>07 (Jul)</option>
                                    <option value='08'>08 (Aug)</option>
                                    <option value='09'>09 (Sep)</option>
                                    <option value='10'>10 (Oct)</option>
                                    <option value='11'>11 (Nov)</option>
                                    <option value='12'>12 (Dec)</option>
                                </select>
                                <select name="fields_expyear" id="expirationYear" class="s_con_2" required>
                                    <option value="" selected>Select EXP Year</option>
                                    <option value='21'>2021</option>
                                    <option value='22'>2022</option>
                                    <option value='23'>2023</option>
                                    <option value='24'>2024</option>
                                    <option value='25'>2025</option>
                                    <option value='26'>2026</option>
                                    <option value='27'>2027</option>
                                    <option value='28'>2028</option>
                                    <option value='29'>2029</option>
                                    <option value='30'>2030</option>
                                    <option value='31'>2031</option>
                                    <option value='32'>2032</option>
                                    <option value='33'>2033</option>
                                    <option value='34'>2034</option>
                                    <option value='35'>2035</option>
                                    <option value='36'>2036</option>
                                </select>
                                <input class="s_mon" type="text" placeholder="CVV code" id="securityCode" name="cc_cvv"
                                       pattern="^([0-9]*)$" autocomplete="off" maxlength="4" value=""
                                       onKeyDown="return onlyNumbers(event, 'require')" required>
                                <div class=" cal_help"><img data-src="img/question-mark.png" src="img/pixel.png" alt=""
                                                            class="lazy">
                                    <div class="cal_tip_area" style="display: none;">
                                        <p> 3-digit security code usually found on the back of your card. American
                                            Express cards have a 4-digit code located on the front. </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="credit_card paypal-payment">
                            <img src="img/pp-logo-100px.png">
                            <input value="paypal" type="radio" name="pmethod" class="input_check_payment" id="paypal" onclick="valueChangedPayment('paypal')">
                            <span class="checkmark"></span>
                        </label>
                        <div id="inputs_paypal">
                            <input type="hidden" name="session_id" value="<?php echo $_SESSION['session_id_new']; ?>" id="session_id">
<!--                            <input type="hidden" name="session_id" value="--><?php //echo $_SESSION['session_id'];?><!--" id="session_id">-->
                            <input type="hidden" name="paySource_paypal" value="" id="paySource_paypal">
                            <input type="hidden" name="paypalBillerId_paypal" value="" id="paypalBillerId_paypal">
                            <input type="hidden" name="salesUrl_paypal" value="" id="salesUrl_paypal">
                        </div>
                        <div class="paypal-info payment-method">
                            <div class="paypal-layout">
                                <button>
                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAxcHgiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAxMDEgMzIiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiIHhtbG5zPSJodHRwOiYjeDJGOyYjeDJGO3d3dy53My5vcmcmI3gyRjsyMDAwJiN4MkY7c3ZnIj48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDEyLjIzNyAyLjggTCA0LjQzNyAyLjggQyAzLjkzNyAyLjggMy40MzcgMy4yIDMuMzM3IDMuNyBMIDAuMjM3IDIzLjcgQyAwLjEzNyAyNC4xIDAuNDM3IDI0LjQgMC44MzcgMjQuNCBMIDQuNTM3IDI0LjQgQyA1LjAzNyAyNC40IDUuNTM3IDI0IDUuNjM3IDIzLjUgTCA2LjQzNyAxOC4xIEMgNi41MzcgMTcuNiA2LjkzNyAxNy4yIDcuNTM3IDE3LjIgTCAxMC4wMzcgMTcuMiBDIDE1LjEzNyAxNy4yIDE4LjEzNyAxNC43IDE4LjkzNyA5LjggQyAxOS4yMzcgNy43IDE4LjkzNyA2IDE3LjkzNyA0LjggQyAxNi44MzcgMy41IDE0LjgzNyAyLjggMTIuMjM3IDIuOCBaIE0gMTMuMTM3IDEwLjEgQyAxMi43MzcgMTIuOSAxMC41MzcgMTIuOSA4LjUzNyAxMi45IEwgNy4zMzcgMTIuOSBMIDguMTM3IDcuNyBDIDguMTM3IDcuNCA4LjQzNyA3LjIgOC43MzcgNy4yIEwgOS4yMzcgNy4yIEMgMTAuNjM3IDcuMiAxMS45MzcgNy4yIDEyLjYzNyA4IEMgMTMuMTM3IDguNCAxMy4zMzcgOS4xIDEzLjEzNyAxMC4xIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDAzMDg3IiBkPSJNIDM1LjQzNyAxMCBMIDMxLjczNyAxMCBDIDMxLjQzNyAxMCAzMS4xMzcgMTAuMiAzMS4xMzcgMTAuNSBMIDMwLjkzNyAxMS41IEwgMzAuNjM3IDExLjEgQyAyOS44MzcgOS45IDI4LjAzNyA5LjUgMjYuMjM3IDkuNSBDIDIyLjEzNyA5LjUgMTguNjM3IDEyLjYgMTcuOTM3IDE3IEMgMTcuNTM3IDE5LjIgMTguMDM3IDIxLjMgMTkuMzM3IDIyLjcgQyAyMC40MzcgMjQgMjIuMTM3IDI0LjYgMjQuMDM3IDI0LjYgQyAyNy4zMzcgMjQuNiAyOS4yMzcgMjIuNSAyOS4yMzcgMjIuNSBMIDI5LjAzNyAyMy41IEMgMjguOTM3IDIzLjkgMjkuMjM3IDI0LjMgMjkuNjM3IDI0LjMgTCAzMy4wMzcgMjQuMyBDIDMzLjUzNyAyNC4zIDM0LjAzNyAyMy45IDM0LjEzNyAyMy40IEwgMzYuMTM3IDEwLjYgQyAzNi4yMzcgMTAuNCAzNS44MzcgMTAgMzUuNDM3IDEwIFogTSAzMC4zMzcgMTcuMiBDIDI5LjkzNyAxOS4zIDI4LjMzNyAyMC44IDI2LjEzNyAyMC44IEMgMjUuMDM3IDIwLjggMjQuMjM3IDIwLjUgMjMuNjM3IDE5LjggQyAyMy4wMzcgMTkuMSAyMi44MzcgMTguMiAyMy4wMzcgMTcuMiBDIDIzLjMzNyAxNS4xIDI1LjEzNyAxMy42IDI3LjIzNyAxMy42IEMgMjguMzM3IDEzLjYgMjkuMTM3IDE0IDI5LjczNyAxNC42IEMgMzAuMjM3IDE1LjMgMzAuNDM3IDE2LjIgMzAuMzM3IDE3LjIgWiI+PC9wYXRoPjxwYXRoIGZpbGw9IiMwMDMwODciIGQ9Ik0gNTUuMzM3IDEwIEwgNTEuNjM3IDEwIEMgNTEuMjM3IDEwIDUwLjkzNyAxMC4yIDUwLjczNyAxMC41IEwgNDUuNTM3IDE4LjEgTCA0My4zMzcgMTAuOCBDIDQzLjIzNyAxMC4zIDQyLjczNyAxMCA0Mi4zMzcgMTAgTCAzOC42MzcgMTAgQyAzOC4yMzcgMTAgMzcuODM3IDEwLjQgMzguMDM3IDEwLjkgTCA0Mi4xMzcgMjMgTCAzOC4yMzcgMjguNCBDIDM3LjkzNyAyOC44IDM4LjIzNyAyOS40IDM4LjczNyAyOS40IEwgNDIuNDM3IDI5LjQgQyA0Mi44MzcgMjkuNCA0My4xMzcgMjkuMiA0My4zMzcgMjguOSBMIDU1LjgzNyAxMC45IEMgNTYuMTM3IDEwLjYgNTUuODM3IDEwIDU1LjMzNyAxMCBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA2Ny43MzcgMi44IEwgNTkuOTM3IDIuOCBDIDU5LjQzNyAyLjggNTguOTM3IDMuMiA1OC44MzcgMy43IEwgNTUuNzM3IDIzLjYgQyA1NS42MzcgMjQgNTUuOTM3IDI0LjMgNTYuMzM3IDI0LjMgTCA2MC4zMzcgMjQuMyBDIDYwLjczNyAyNC4zIDYxLjAzNyAyNCA2MS4wMzcgMjMuNyBMIDYxLjkzNyAxOCBDIDYyLjAzNyAxNy41IDYyLjQzNyAxNy4xIDYzLjAzNyAxNy4xIEwgNjUuNTM3IDE3LjEgQyA3MC42MzcgMTcuMSA3My42MzcgMTQuNiA3NC40MzcgOS43IEMgNzQuNzM3IDcuNiA3NC40MzcgNS45IDczLjQzNyA0LjcgQyA3Mi4yMzcgMy41IDcwLjMzNyAyLjggNjcuNzM3IDIuOCBaIE0gNjguNjM3IDEwLjEgQyA2OC4yMzcgMTIuOSA2Ni4wMzcgMTIuOSA2NC4wMzcgMTIuOSBMIDYyLjgzNyAxMi45IEwgNjMuNjM3IDcuNyBDIDYzLjYzNyA3LjQgNjMuOTM3IDcuMiA2NC4yMzcgNy4yIEwgNjQuNzM3IDcuMiBDIDY2LjEzNyA3LjIgNjcuNDM3IDcuMiA2OC4xMzcgOCBDIDY4LjYzNyA4LjQgNjguNzM3IDkuMSA2OC42MzcgMTAuMSBaIj48L3BhdGg+PHBhdGggZmlsbD0iIzAwOWNkZSIgZD0iTSA5MC45MzcgMTAgTCA4Ny4yMzcgMTAgQyA4Ni45MzcgMTAgODYuNjM3IDEwLjIgODYuNjM3IDEwLjUgTCA4Ni40MzcgMTEuNSBMIDg2LjEzNyAxMS4xIEMgODUuMzM3IDkuOSA4My41MzcgOS41IDgxLjczNyA5LjUgQyA3Ny42MzcgOS41IDc0LjEzNyAxMi42IDczLjQzNyAxNyBDIDczLjAzNyAxOS4yIDczLjUzNyAyMS4zIDc0LjgzNyAyMi43IEMgNzUuOTM3IDI0IDc3LjYzNyAyNC42IDc5LjUzNyAyNC42IEMgODIuODM3IDI0LjYgODQuNzM3IDIyLjUgODQuNzM3IDIyLjUgTCA4NC41MzcgMjMuNSBDIDg0LjQzNyAyMy45IDg0LjczNyAyNC4zIDg1LjEzNyAyNC4zIEwgODguNTM3IDI0LjMgQyA4OS4wMzcgMjQuMyA4OS41MzcgMjMuOSA4OS42MzcgMjMuNCBMIDkxLjYzNyAxMC42IEMgOTEuNjM3IDEwLjQgOTEuMzM3IDEwIDkwLjkzNyAxMCBaIE0gODUuNzM3IDE3LjIgQyA4NS4zMzcgMTkuMyA4My43MzcgMjAuOCA4MS41MzcgMjAuOCBDIDgwLjQzNyAyMC44IDc5LjYzNyAyMC41IDc5LjAzNyAxOS44IEMgNzguNDM3IDE5LjEgNzguMjM3IDE4LjIgNzguNDM3IDE3LjIgQyA3OC43MzcgMTUuMSA4MC41MzcgMTMuNiA4Mi42MzcgMTMuNiBDIDgzLjczNyAxMy42IDg0LjUzNyAxNCA4NS4xMzcgMTQuNiBDIDg1LjczNyAxNS4zIDg1LjkzNyAxNi4yIDg1LjczNyAxNy4yIFoiPjwvcGF0aD48cGF0aCBmaWxsPSIjMDA5Y2RlIiBkPSJNIDk1LjMzNyAzLjMgTCA5Mi4xMzcgMjMuNiBDIDkyLjAzNyAyNCA5Mi4zMzcgMjQuMyA5Mi43MzcgMjQuMyBMIDk1LjkzNyAyNC4zIEMgOTYuNDM3IDI0LjMgOTYuOTM3IDIzLjkgOTcuMDM3IDIzLjQgTCAxMDAuMjM3IDMuNSBDIDEwMC4zMzcgMy4xIDEwMC4wMzcgMi44IDk5LjYzNyAyLjggTCA5Ni4wMzcgMi44IEMgOTUuNjM3IDIuOCA5NS40MzcgMyA5NS4zMzcgMy4zIFoiPjwvcGF0aD48L3N2Zz4="
                                         data-v-188c8269="" alt="" aria-label="PayPal"
                                         class="paypal-logo paypal-logo-paypal paypal-logo-color-blue">
                                </button>
                                <p>Click the button above to securely complete your checkout with PayPal.</p>
                            </div>
                        </div>
                        <div class="submit-btn-wrp">
                            <button onclick="window.onbeforeunload = null;" type="submit" class="com_pur checkout_btn"
                                    id="submit-checkout" value="complete checkout">
                                <strong><i class="fa fa-lock" aria-hidden="true"></i> Complete Purchase</strong>
                                <span>TRY IT RISK FREE! - 30 DAY MONEY BACK GUARANTEE!</span>
                            </button>
                        </div>
                        <div class="secure-img-wrp">
                            <ul class="clearfix">
                                <li>
                                    <img class="lazy" data-src="img/seal-mcafee.png">
                                </li>
                                <li>
                                    <img class="lazy" data-src="img/seal-norton.png">
                                </li>
                                <li>
                                    <img class="lazy" data-src="img/seal-secure.png">
                                </li>
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" id="custom_product" name="custom_product"
                           value="<?php echo $_REQUEST['product_id']; ?>"/>
                    <input type="hidden" id="custom_product_price" name="custom_product_price"
                           value="<?php echo $pr_price; ?>"/>
                    <input type="hidden" id="product_name" name="product_name"
                           value="<?php echo $product_name; ?>"/>
                    <input type="hidden" name="shippingId" id="shippingId" value="<?php echo $shipping_id; ?>"/>
                    <input type="hidden" name="campaign_id" id="campaign_id"
                           value="<?php echo $campaign_id; ?>"/>
                    <input type="hidden" name="billingSameAsShipping" id="billingSameAsShipping" value="YES"/>
                    <input type="hidden" name="billingSameAsShipping2" id="billingSameAsShipping2"
                           value="<?php echo $_SESSION['billingSameAsShipping']; ?>"/>
                    <input type="hidden" id="submitted" name="submitted" value="1"/>
                    <input type="hidden" id="page" name="page" value="step2"/>
                    <input type="hidden" id="hash_url" name="hash_url" value=""/>
                    <input type="hidden" name="shipping_price_pro" id="shipping_price_pro" value="<?php echo $shipping; ?>">
                    <input type="hidden" id="coupon" name="coupon" value=""/>
                    <input type="hidden" id="coupon_discount_code" name="coupon_discount_code"
                           value="<?php echo $_SESSION['coupon_discount_code']; ?>"/>
                    <input type="hidden" id="salesTax" name="salesTax"
                           value="<?php echo $_SESSION['salesTax']; ?>"/>
                    <input type="hidden" id="coupon_discount_price" name="coupon_discount_price"
                           value="<?php echo $_SESSION['coupon_discount_price']; ?>"/>
                    <input type="hidden" id="coupon_discount_price_val" name="coupon_discount_price_val"
                           value="<?php echo $_SESSION['coupon_discount_price_val']; ?>"/>
                    <input type="hidden" name="test_ty" value="0" id="test_ty">
                </form>

                <div class="under-form review-secure">
                    <div class="review-dsc-wrp customer-reviews">
                        <div class="review-head">
                            <h6><span>Trusted customer reviews</span></h6>
                        </div>
                        <div class="review-dsc-tp-wrp">
                            <div class="review-dsc-tp-inr clearfix">
                                <div class="review-dsc-tp-img">
                                    <img class="lazy" data-src="img/review-1-2.png">
                                </div>
                                <div class="review-dsc-tp-dsc">
                                    <img src="img/review-stars.png">
                                    <strong>Laura B.</strong>
                                    <span> Dallas, TX</span>
                                    <b> <img class="lazy" data-src="img/locker-green.png"> Verified Buyer</b>
                                    <p>“It does work, I got myself and my son some. My son commented the next day the pain in his wrists was almost gone. Mine are working great no pain. My son is a mechanic and sure loves his.”</p>
                                </div>
                            </div>
                            <div class="review-dsc-tp-inr clearfix">
                                <div class="review-dsc-tp-img">
                                    <img class="lazy" data-src="img/review-3-2.png">
                                </div>
                                <div class="review-dsc-tp-dsc">
                                    <img src="img/review-stars.png">
                                    <strong>Tomas C.</strong>
                                    <span> Salt Lake City, UT</span>
                                    <b> <img class="lazy" data-src="img/locker-green.png"> Verified Buyer</b>
                                    <p>"I have had mine on for five days and they work. Less joint aches. Working for me!"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="review-dsc-wrp why-choose-us">
                        <div class="review-head">
                            <h6><span>Why choose us?</span></h6>
                        </div>
                        <div class="review-dsc-tp-wrp">
                            <div class="review-dsc-tp-inr clearfix">
                                <div class="review-dsc-tp-img">
                                    <img class="lazy" data-src="img/shipping-bw2.png">
                                </div>
                                <div class="review-dsc-tp-dsc">
                                    <p> 30-Day Money Back Guarantee. If you don't like your product for any reason, send it back to us for a refund!</p>
                                </div>
                            </div>
                            <div class="review-dsc-tp-inr clearfix">
                                <div class="review-dsc-tp-img">
                                    <img class="lazy" data-src="img/dollar-bw2.png">
                                </div>
                                <div class="review-dsc-tp-dsc">
                                    <p> Over 50,000 successfully shipped orders and happy customers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix" style="text-align: center">
<!--                    <p class="text-small-14 mb-30 for_desk disclamer_text"><a href="privacy.php" class="footer-links" target="_blank">I consent to receive recurring automated marketing by text message through an automatic telephone dialing system. Consent is not a condition to purchase. STOP to cancel, HELP for help. Message and Data rates may apply. View Privacy Policy & ToS.</a><br>-->
                        <a href="privacy.php" class="footer-links" target="_blank">Privacy Policy</a> |
                        <a href="terms.php" class="footer-links" target="_blank">Terms of service</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/checkout.js?v=8.2"></script>
<script>

    //Start Shipping
    $.ajax({
        method: "GET",
        async: false,
        url: "./load_states.php",
        data:{country: $("#country option:selected").val()}
    })
        .done(function (msg) {
            document.getElementById("administrative_area_level_22").options.length = 0;
            var states = JSON.parse(msg);
            states.forEach(myFunction);
        });
    $("#country").change(function(){
        var  country = $(this ).val();
        $.ajax({
            method: "GET",
            async: false,
            url: "./load_states.php",
            data:{country: country}
        })
            .done(function (msg) {
                document.getElementById("administrative_area_level_22").options.length = 0;
                var states = JSON.parse(msg);
                states.forEach(myFunction);
            });
    });

    function myFunction(item, index) {

        var states = $("#administrative_area_level_22");
        if(item.code == '<?php echo $_SESSION['shippingState']; ?>') {
            var elem = $('<option value="">Select State</option><option selected value=' + item.code + '>' + item.name + '</option>');
        }else{
            var elem = $('<option value="">Select State</option><option value=' + item.code + '>' + item.name + '</option>');
        }

        states.append(elem);
    }

    //End Shipping

    //Start Billing
    $.ajax({
        method: "GET",
        async: false,
        url: "./load_states.php",
        data:{country: $("#billing_country option:selected").val()}
    })
        .done(function (msg) {
            document.getElementById("administrative_area_level_22_ship").options.length = 0;
            var states = JSON.parse(msg);
            states.forEach(myFunctionBilling);
        });
    $("#billing_country").change(function(){
        var  country = $(this ).val();
        $.ajax({
            method: "GET",
            async: false,
            url: "./load_states.php",
            data:{country: country}
        })
            .done(function (msg) {
                document.getElementById("administrative_area_level_22_ship").options.length = 0;
                var states = JSON.parse(msg);
                states.forEach(myFunctionBilling);
            });
    });

    function myFunctionBilling(item, index) {

        var states = $("#administrative_area_level_22_ship");
        if(item.code == '<?php echo $_SESSION['billing_state']; ?>') {
            var elem = $('<option value="">Select State</option><option selected value=' + item.code + '>' + item.name + '</option>');
        }else{
            var elem = $('<option value="">Select State</option><option value=' + item.code + '>' + item.name + '</option>');
        }

        states.append(elem);
    }
    //End Billing
</script>
<script>
    function States() {
        if ($("#country").val() == 'US') {

            $('#administrative_area_level_22').show();
            $('#statesUK').hide();
            $('#states_other').hide();
            $('#shipping_time').hide();
            $('#shipping_time_US').show();
            $('#statesUK').removeAttr('required');
            $('#states_other').removeAttr('required');
            $('#administrative_area_level_22').prop('required', true);

        } else if ($("#country").val() == 'GB') {

            $('#statesUK').show();
            $('#administrative_area_level_22').hide();
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#states_other').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $('#states_other').removeAttr('required');
            $("#statesUK").prop('required', true);

        } else if ($("#country").val() == 'CA') {

            $('#statesUK').hide();
            $("#statesUK").removeAttr('required');
            $('#administrative_area_level_22').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#states_other').show();
            $('#states_other').html('');
            $("#states_other").prop('required', true);
            $("#states_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "AB": "Alberta",
                "BC": "British Columbia",
                "MB": "Manitoba",
                "NB": "New Brunswick",
                "NL": "Newfoundland and Labrador",
                "NT": "Northwest Territories",
                "NS": "Nova Scotia",
                "NU": "Nunavut",
                "ON": "Ontario",
                "PE": "Prince Edward Island",
                "QC": "Quebec",
                "SK": "Saskatchewan",
                "YT": "Yukon"
            };
            var $select;
            for (var key in myArray) {
                if (key == $('#C_state_other').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#states_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#country").val() == 'AU') {

            $('#states_other').show();
            $('#statesUK').hide();
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#administrative_area_level_22').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $("#statesUK").removeAttr('required');
            $('#states_other').html('');
            $("#states_other").prop('required', true);
            $("#states_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "ACT": "Australian Capital Territory",
                "NSW": "New South Wales",
                "NT": "Northern Territory",
                "QLD": "Queensland",
                "SA": "South Australia",
                "TAS": "Tasmania",
                "VIC": "Victoria",
                "WA": "Western Australia"
            };

            for (var key in myArray) {
                if (key == $('#C_state_other').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#states_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#country").val() == 'DE') {

            $('#states_other').show();
            $('#statesUK').hide();
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#administrative_area_level_22').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $("#statesUK").removeAttr('required');
            $('#states_other').html('');
            $("#states_other").prop('required', true);
            $("#states_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "BW": "Baden-Württemberg",
                "BY": "Bayern",
                "BE": "Berlin",
                "BB": "Brandenburg",
                "HB": "Bremen",
                "HH": "Hamburg",
                "HE": "Hessen",
                "MV": "Mecklenburg-Vorpommern",
                "NI": "Niedersachsen",
                "NW": "Nordrhein-Westfalen",
                "RP": "Rheinland-Pfalz",
                "SL": "Saarland",
                "SN": "Sachsen",
                "ST": "Sachsen-Anhalt",
                "SH": "Schleswig-Holstein",
                "TH": "Thüringen"
            };

            for (var key in myArray) {
                if (key == $('#C_state_other').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#states_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#country").val() == 'IE') {

            $('#states_other').show();
            $('#statesUK').hide();
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#administrative_area_level_22').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $("#statesUK").removeAttr('required');
            $('#states_other').html('');
            $("#states_other").prop('required', true);
            $("#states_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "CW": "Carlow",
                "CN": "Cavan",
                "CE": "Clare",
                "CK": "Cork",
                "DL": "Donegal",
                "DN": "Dublin",
                "GY": "Galway",
                "KY": "Kerry",
                "KE": "Kildare",
                "KK": "Kilkenny",
                "LS": "Laois",
                "LM": "Leitrim",
                "LK": "Limerick",
                "LD": "Longford",
                "LH": "Louth",
                "MO": "Mayo",
                "MH": "Meath",
                "MN": "Monaghan",
                "OY": "Offaly",
                "RN": "Roscommon",
                "SO": "Sligo",
                "TA": "Tipperary",
                "WD": "Waterford",
                "WH": "Westmeath",
                "WX": "Wexford",
                "WW": "Wicklow"
            };

            for (var key in myArray) {
                if (key == $('#C_state_other').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#states_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#country").val() == 'NZ') {

            $('#states_other').show();
            $('#statesUK').hide();
            $('#shipping_time').show();
            $('#shipping_time_US').hide();
            $('#administrative_area_level_22').hide();
            $('#administrative_area_level_22').removeAttr('required');
            $("#statesUK").removeAttr('required');
            $('#states_other').html('');
            $("#states_other").prop('required', true);
            $("#states_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "AUK": "Auckland",
                "BOP": "Bay of Plenty",
                "CAN": "Canterbury",
                "CIT": "Chatham Islands Territory",
                "GIS": "Gisborne District",
                "HKB": "Hawkes's Bay",
                "MWT": "Manawatu-Wanganui",
                "MBH": "Marlborough District",
                "NSN": "Nelson City",
                "N	": "North Island",
                "NTL": "Northland",
                "OTA": "Otago",
                "S	": "South Island",
                "STL": "Southland",
                "TKI": "Taranaki",
                "TAS": "Tasman District",
                "WKO": "Waikato",
                "WGN": "Wellington",
                "WTC": "West Coast"
            };

            for (var key in myArray) {
                if (key == $('#C_state_other').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#states_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        }
    }

    function StatesBilling() {
        if ($("#billing_country").val() == 'US') {
            $('#administrative_area_level_22_ship').show();
            $('#administrative_area_level_22_ship').prop('required', true);
            $('#billing_state_stateUK').hide();
            $('#billing_state_stateUK').removeAttr('required');
            $('#billing_state_state_other').hide();
            $('#billing_state_state_other').removeAttr('required');
            // console.log('usa');
        } else if ($("#billing_country").val() == 'GB') {
            $('#billing_state_stateUK').show();
            $('#billing_state_stateUK').prop('required', true);
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').hide();
            $('#billing_state_state_other').removeAttr('required');

        } else if ($("#billing_country").val() == 'CA') {

            $('#billing_state_stateUK').hide();
            $("#billing_state_stateUK").removeAttr('required');
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').show();
            $('#billing_state_state_other').html('');
            $("#billing_state_state_other").prop('required', true);
            $("#billing_state_state_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "AB": "Alberta",
                "BC": "British Columbia",
                "MB": "Manitoba",
                "NB": "New Brunswick",
                "NL": "Newfoundland and Labrador",
                "NT": "Northwest Territories",
                "NS": "Nova Scotia",
                "NU": "Nunavut",
                "ON": "Ontario",
                "PE": "Prince Edward Island",
                "QC": "Quebec",
                "SK": "Saskatchewan",
                "YT": "Yukon"
            };
            for (var key in myArray) {
                if (key == $('#C_state_other_blling').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#billing_state_state_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#billing_country").val() == 'AU') {
            $('#billing_state_stateUK').hide();
            $("#billing_state_stateUK").removeAttr('required');
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').show();
            $('#billing_state_state_other').html('');
            $("#billing_state_state_other").prop('required', true);
            $("#billing_state_state_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "ACT": "Australian Capital Territory",
                "NSW": "New South Wales",
                "NT": "Northern Territory",
                "QLD": "Queensland",
                "SA": "South Australia",
                "TAS": "Tasmania",
                "VIC": "Victoria",
                "WA": "Western Australia"
            };
            for (var key in myArray) {
                if (key == $('#C_state_other_blling').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#billing_state_state_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#billing_country").val() == 'DE') {
            $('#billing_state_stateUK').hide();
            $("#billing_state_stateUK").removeAttr('required');
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').show();
            $('#billing_state_state_other').html('');
            $("#billing_state_state_other").prop('required', true);
            $("#billing_state_state_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "BW": "Baden-Württemberg",
                "BY": "Bayern",
                "BE": "Berlin",
                "BB": "Brandenburg",
                "HB": "Bremen",
                "HH": "Hamburg",
                "HE": "Hessen",
                "MV": "Mecklenburg-Vorpommern",
                "NI": "Niedersachsen",
                "NW": "Nordrhein-Westfalen",
                "RP": "Rheinland-Pfalz",
                "SL": "Saarland",
                "SN": "Sachsen",
                "ST": "Sachsen-Anhalt",
                "SH": "Schleswig-Holstein",
                "TH": "Thüringen"
            };
            for (var key in myArray) {
                if (key == $('#C_state_other_blling').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#billing_state_state_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#billing_country").val() == 'IE') {
            $('#billing_state_stateUK').hide();
            $("#billing_state_stateUK").removeAttr('required');
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').show();
            $('#billing_state_state_other').html('');
            $("#billing_state_state_other").prop('required', true);
            $("#billing_state_state_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "CW": "Carlow",
                "CN": "Cavan",
                "CE": "Clare",
                "CK": "Cork",
                "DL": "Donegal",
                "DN": "Dublin",
                "GY": "Galway",
                "KY": "Kerry",
                "KE": "Kildare",
                "KK": "Kilkenny",
                "LS": "Laois",
                "LM": "Leitrim",
                "LK": "Limerick",
                "LD": "Longford",
                "LH": "Louth",
                "MO": "Mayo",
                "MH": "Meath",
                "MN": "Monaghan",
                "OY": "Offaly",
                "RN": "Roscommon",
                "SO": "Sligo",
                "TA": "Tipperary",
                "WD": "Waterford",
                "WH": "Westmeath",
                "WX": "Wexford",
                "WW": "Wicklow"
            };
            for (var key in myArray) {
                if (key == $('#C_state_other_blling').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#billing_state_state_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        } else if ($("#billing_country").val() == 'NZ') {
            $('#billing_state_stateUK').hide();
            $("#billing_state_stateUK").removeAttr('required');
            $('#administrative_area_level_22_ship').hide();
            $('#administrative_area_level_22_ship').removeAttr('required');
            $('#billing_state_state_other').show();
            $('#billing_state_state_other').html('');
            $("#billing_state_state_other").prop('required', true);
            $("#billing_state_state_other").html("<option value=''>Select State/Province</option>");
            var myArray = {
                "AUK": "Auckland",
                "BOP": "Bay of Plenty",
                "CAN": "Canterbury",
                "CIT": "Chatham Islands Territory",
                "GIS": "Gisborne District",
                "HKB": "Hawkes's Bay",
                "MWT": "Manawatu-Wanganui",
                "MBH": "Marlborough District",
                "NSN": "Nelson City",
                "N	": "North Island",
                "NTL": "Northland",
                "OTA": "Otago",
                "S	": "South Island",
                "STL": "Southland",
                "TKI": "Taranaki",
                "TAS": "Tasman District",
                "WKO": "Waikato",
                "WGN": "Wellington",
                "WTC": "West Coast"
            };
            for (var key in myArray) {
                if (key == $('#C_state_other_blling').val()) {
                    $select = "selected";
                } else {
                    $select = '';
                }
                $("#billing_state_state_other").append("<option value='" + key + "' " + $select + ">" + myArray[key] + "</option>");
            }
        }
    }

    $(function () {
        var i = 0;
        $('#checkout_form').submit(function () {
            if (!$(this).checkValidity || $(this).checkValidity()) {
                if (i > 0) {
                    return false;
                }
                i++;
            } else {
            }
        });
        // States();

//        if($("#country").val() != 'US'){
//            $('#administrative_area_level_22').hide();
//            $('#stateCA').show();
//            $('#administrative_area_level_22').removeAttr('required');
//            $("#stateCA").prop('required', true);
//        }else{
//            $('#administrative_area_level_22').show();
//            $('#administrative_area_level_22').prop('required', true);
//            $('#stateCA').hide();
//            $('#stateCA').removeAttr('required');
//        }
    });
    //    $( "#country" ).change(function() {
    //        if($("#country").val() == 'US'){
    //            $('#administrative_area_level_22').show();
    //            $('#stateCA').hide();
    //            $('#stateCA').removeAttr('required');
    //            $('#administrative_area_level_22').prop('required', true);
    //        }else{
    //            $('#administrative_area_level_22').hide();
    //            $('#stateCA').show();
    //            $('#administrative_area_level_22').removeAttr('required');
    //            $("#stateCA").prop('required', true);
    //        }
    //    });

/*    $("#country").change(function () {
        States();
    });*/

    //    $( "#billing_country").change(function() {
    //        if($("#billing_country").val() == 'US'){
    //            $('#administrative_area_level_22_ship').show();
    //            $('#administrative_area_level_22_ship').prop('required', true);
    //            $('#billing_state_stateCA').hide();
    //            $('#billing_state_stateCA').removeAttr('required');
    //            console.log('usa');
    //        }else{
    //            $('#administrative_area_level_22_ship').hide();
    //            $('#billing_state_stateCA').show();
    //            $('#billing_state_stateCA').prop('required', true);
    //            $('#administrative_area_level_22_ship').removeAttr('required');
    //            console.log('not');
    //        }
    //    });
/*    $("#billing_country").change(function () {
        StatesBilling();
    });*/

    function valueChanged($val) {
        if ($('.input_check').is(":checked")) {
            if ($val == 'YES') {
                $(".billing_add").hide();
                $('#billingSameAsShipping').val('YES');
                $('#autocompleteship').removeAttr('required');
                $('#locality_ship').removeAttr('required');
                $('#billing_country').removeAttr('required');
                $('#postal_code_ship').removeAttr('required');
                if ($("#billing_country").val() == 'US') {
                    $("#administrative_area_level_22_ship").removeAttr('required');
//                    $("#billing_state_stateCA").removeAttr('required');
//                    console.log('ttt');
                    $("#billing_state_stateUK").removeAttr('required');
                    $("#billing_state_state_other").removeAttr('required');
                } else {
//                    $("#billing_state_stateCA").removeAttr('required');
//                    $("#administrative_area_level_22_ship").removeAttr('required');
//                    console.log('t');

                    $("#administrative_area_level_22_ship").removeAttr('required');
                    $("#billing_state_stateUK").removeAttr('required');
                    $("#billing_state_state_other").removeAttr('required');
                }
            } else {
                $(".billing_add").show();
                $('#billingSameAsShipping').val('NO');
                $('#autocompleteship').attr("required", "required");
                $('#locality_ship').attr("required", "required");
                $('#billing_country').attr("required", "required");
                $('#postal_code_ship').attr("required", "required");
//                if ($("#billing_country").val() == 'US') {
//                    $("#administrative_area_level_22_ship").prop('required', true);
//                    $("#billing_state_stateCA").removeAttr('required');
//                    console.log('ttttttttt');
//                }else
//                {
//                    $("#billing_state_stateCA").prop('required', true);
//                    $("#administrative_area_level_22_ship").removeAttr('required');
//                    console.log('tttfff');
//                }
                StatesBilling();
            }
        }
    }

    if ($('#custom_product').val() == 142 || $('#custom_product').val() == 143) {
        $('#shipping_price_pro').val('0.00');
    } else {
        $('#shipping_price_pro').val('4.99');
        $('#shipping').html('4.99');
    }
</script>
<script>
    function valueChangedPayment($val)
    {
        if ($('input[type="radio"]').is(':checked')) {
            if ($val == 'credit_card') {
                $(".extended-li").show();
                $('#cardNumber').attr('required','required');
                $('#expirationMonth').attr('required','required');
                $('#expirationYear').attr('required','required');
                $('#securityCode').attr('required','required');
                $("#paySource_paypal").val('');
                $("#paypalBillerId_paypal").val('');
                $("#test_ty").val('0');
                $("#salesUrl_paypal").val('');
            } else {
                $(".extended-li").hide();
                $('#cardNumber').removeAttr('required');
                $('#expirationMonth').removeAttr('required');
                $('#expirationYear').removeAttr('required');
                $('#securityCode').removeAttr('required');
                $("#paySource_paypal").val('PAYPAL');
                $("#paypalBillerId_paypal").val('3');
                $("#test_ty").val('1');
                $("#salesUrl_paypal").val('https://buymaglife.com/up1.php');
            }
        }
    }

    function coupon() {
        var input_coupon = $('#fields_coupon').val();
        var campaign_id = $('#campaign_id').val();
        var custom_product = $('#custom_product').val();
        var couponCodeapi;
        var coupon_dis;
        var $product1price;
        var $product3price;
        var $product6price;
        if (input_coupon == 'NEW20' || input_coupon == 'TAKE10' ||
            input_coupon == 'TAKE20' || input_coupon == 'SMS20' ||
            input_coupon == 'SEARCH10') {
            $.ajax({
                method: "GET",
                async: false,
                url: "./kon/coupon_validate.php",
                dataType: "json",
            })
                .done(function (msg) {
                    var coupons = msg['message']['data'][campaign_id]['coupons'];
                    $.each(coupons, function (key, value) {
                        if (value.couponCode == input_coupon) {
                            switch (value.couponCode) {
                                case "NEW20":
                                    couponCodeapi = value.couponCode;
                                    coupon_dis = value.discountPerc;
                                    $product1price = (29.99 - (29.99 * coupon_dis));
                                    $product3price = (49.99 - (49.99 * coupon_dis));
                                    $product6price = (78.99 - (78.99 * coupon_dis));
                                    break;
                                case "TAKE10":
                                    couponCodeapi = value.couponCode;
                                    coupon_dis = value.discountPerc;
                                    $product1price = (29.99 - (29.99 * coupon_dis));
                                    $product3price = (49.99 - (49.99 * coupon_dis));
                                    $product6price = (78.99 - (78.99 * coupon_dis));
                                    break;
                                case "TAKE20":
                                    couponCodeapi = value.couponCode;
                                    coupon_dis = value.discountPerc;
                                    $product1price = (29.99 - (29.99 * coupon_dis));
                                    $product3price = (49.99 - (49.99 * coupon_dis));
                                    $product6price = (78.99 - (78.99 * coupon_dis));
                                    break;
                                case "SMS20":
                                    couponCodeapi = value.couponCode;
                                    coupon_dis = value.discountPerc;
                                    $product1price = (29.99 - (29.99 * coupon_dis));
                                    $product3price = (49.99 - (49.99 * coupon_dis));
                                    $product6price = (78.99 - (78.99 * coupon_dis));
                                    break;
                                case "SEARCH10":
                                    couponCodeapi = value.couponCode;
                                    coupon_dis = value.discountPerc;
                                    $product1price = (29.99 - (29.99 * coupon_dis));
                                    $product3price = (49.99 - (49.99 * coupon_dis));
                                    $product6price = (78.99 - (78.99 * coupon_dis));
                                    break;
                                default:
                                    $product1price = 29.99;
                                    $product3price = 49.99;
                                    $product6price = 78.99;
                            }
                        }
                    });
                    var shipping = parseFloat($('#shipping_price_pro').val());
                    if ($('#administrative_area_level_22').val() == "CT") {
                    let taxRate = 0.0635;
                    var taxes = parseFloat($('#custom_product_price').val()) * taxRate;
                    }
                    else {
                        taxes = 0.00;
                    }
                    if (msg.result == "SUCCESS") {
                        $('#applied').css("display", "block");
                        $('#applied_text').css("display", "block");
                        $('#coupon_msg').css("display", "block");
                        $('#apply_error').css("display", "none");
                        $('#applied_text_error').css("display", "none");
                        $('#coupon_discount_code').val(couponCodeapi);

                        if (custom_product == 141) {
                            var discount = parseFloat(coupon_dis);
                            var total_price = parseFloat($product1price);
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                            $("#discount_tr").css("display", "table-row");
                            if (couponCodeapi == "TAKE10" || couponCodeapi == "SEARCH10") {
                                $("#discount_td").html("- $" + Math.round(parseFloat($('#custom_product_price').val() - $product1price)).toFixed(2));
                            } else {
                                $("#discount_td").html("- $" + parseFloat($('#custom_product_price').val() - $product1price).toFixed(2));
                            }
                            $('#coupon_discount_price').val($product1price);
                            $('#coupon_discount_price_val').val(discount);
                        } else if (custom_product == 142) {
                            var discount = parseFloat(coupon_dis);
                            var total_price = parseFloat($product3price);
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                            $("#discount_tr").css("display", "table-row");
                            $("#discount_td").html("- $" + parseFloat($('#custom_product_price').val() - $product3price).toFixed(2));
                            $('#coupon_discount_price').val($product3price);
                            $('#coupon_discount_price_val').val(discount);
                        } else if (custom_product == 143) {
                            var discount = parseFloat(coupon_dis);
                            var total_price = parseFloat($product6price);
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                            $("#discount_tr").css("display", "table-row");
                            $("#discount_td").html("- $" + parseFloat($('#custom_product_price').val() - $product6price).toFixed(2));
                            $('#coupon_discount_price').val($product6price);
                            $('#coupon_discount_price_val').val(discount);
                        }
                    } else {
                        $('#applied').css("display", "none");
                        $('#applied_text').css("display", "none");
                        $('#applied_text_error').css("display", "none");
                        $('#apply_error').css("display", "block");
                        $('#coupon_msg').css("display", "block");
                        $("#discount_tr").css("display", "none");
                        var shipping = parseFloat($('#shipping_price_pro').val());
                        if (custom_product == 141) {
                            var total_price = parseFloat($('#custom_product_price').val());
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                        } else if (custom_product == 142) {
                            var total_price = parseFloat($('#custom_product_price').val());
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                        } else if (custom_product == 143) {
                            var total_price = parseFloat($('#custom_product_price').val());
                            var total = total_price + taxes + shipping;
                            $("#total_span").html('$' + total.toFixed(2));
                        }

                    }
                });
        } else {
            $('#applied').css("display", "none");
            $('#applied_text').css("display", "none");
            $('#applied_text_error').css("display", "none");
            $('#apply_error').css("display", "block");
            $('#coupon_msg').css("display", "block");
            $("#discount_tr").css("display", "none");
            var shipping = parseFloat($('#shipping_price_pro').val());
            if (custom_product == 141) {
                var total_price = parseFloat($('#custom_product_price').val());
                var total = total_price + taxes + shipping;
                $("#total_span").html('$' + total.toFixed(2));
            } else if (custom_product == 142) {
                var total_price = parseFloat($('#custom_product_price').val());
                var total = total_price + taxes + shipping;
                $("#total_span").html('$' + total.toFixed(2));
            } else if (custom_product == 143) {
                var total_price = parseFloat($('#custom_product_price').val());
                var total = total_price + taxes + shipping;
                $("#total_span").html('$' + total.toFixed(2));
            }
        }
    }
</script>
<!--<script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0V-n02jCzKqoQQzPq3ERm9ksAwVkUefI&libraries=places&callback=initAutocomplete">
</script>-->
<script>
    var placeSearch, autocomplete, autocompleteship;
    var componentForm = {
        // street_number: 'short_name',
        // route: 'long_name',
        locality: 'long_name',
        // administrative_area_level_1: 'long_name',
        // country: 'short_name',
        postal_code: 'short_name'
    };

    var componentFormShip = {
        locality_ship: 'long_name',
        // administrative_area_level_1_ship: 'long_name',
        // country_ship : 'short_name',
        postal_code_ship: 'short_name'
    };

    function initAutocomplete() {

        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        autocomplete.addListener('place_changed', fillInAddress);

        autocompleteship = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocompleteship')),
            {types: ['geocode']});

        autocompleteship.addListener('place_changed', fillInAddressShip);
    }

    function fillInAddress() {
        var place = autocomplete.getPlace();
        console.log(place);
        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if($('#locality').val() == ""){
                $('#locality').val(place.address_components[3][componentForm[addressType]]);
                $('#locality').attr('value', place.address_components[3][componentForm[addressType]]);
            }
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
        $('#autocomplete').val(place.name);
        $('#autocomplete').attr('value', place.name);
    }

/*    function geolocate() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocomplete.setBounds(circle.getBounds());
            });
        }
    }*/

    //Shipping auto complete

    function fillInAddressShip() {
        var place = autocompleteship.getPlace();
        console.log(place);
        for (var component in componentFormShip) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if($('#locality_ship').val() == ""){
                $('#locality_ship').val(place.address_components[3][componentFormShip[addressType+'_ship']]);
                $('#locality_ship').attr('value', place.address_components[3][componentFormShip[addressType+'_ship']]);
            }
            if (componentFormShip[addressType+'_ship']) {
                var val = place.address_components[i][componentFormShip[addressType+'_ship']];
                document.getElementById(addressType+'_ship').value = val;
            }
        }
        $('#autocompleteship').val(place.name);
        $('#autocompleteship').attr('value', place.name);
    }
/*    function geolocateship() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                autocompleteship.setBounds(circle.getBounds());
            });
        }
    }*/
</script>
<script>
/*
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
*/

    function partial() {
        $.ajax({
            method: "POST",
            async: false,
            url: "./kon/lead.php",
            data: $('#checkout_form').serializeArray()
        })
            .done(function (msg) {

            });
        ////make ajax request
    }

    $('.taxes').html("$0.00");
    var shipping = parseFloat($('#shipping_price_pro').val());
    $('#administrative_area_level_22').change(function () {
            if ($('#administrative_area_level_22').val() == "CT") {
                let taxRate = 0.0635;
                var taxes = parseFloat($('#custom_product_price').val()) * taxRate;
                var shipping = parseFloat($('#shipping_price_pro').val());
                var product_price = parseFloat($('#custom_product_price').val());
                var total_price = product_price + taxes + shipping;
                $('.taxes').html("$" + taxes.toFixed(2));
                $("#total_span").html('$' + total_price.toFixed(2));
                $("#salesTax").val(taxes);
            } else {
                $('.taxes').html("$0.00");
                $("#salesTax").val(0.00);
            }
        if ($('#fields_coupon').val() != "") {
            coupon();
        }
    });
</script>
<?php if (!empty($_REQUEST['product_id'])) ?>
<script> $(function () {
        addremoveclass('<?php echo $_REQUEST['product_id']; ?>');
    })</script>

<?php if (!empty($_SESSION['billingSameAsShipping']) && $_SESSION['billingSameAsShipping'] == 'NO'): ?>

    <script type="text/javascript">
        $(function () {
            $('#box1').click();
        })
    </script>
<?php endif; ?>
<script>
    var url_para = localStorage.getItem("hash_url");
    $('#hash_url').val(url_para);
</script>
<script>
    $(document).ready(function () {
        $('.accordion-header').toggleClass('inactive-header');
        var contentwidth = $('.accordion-header').width();
        $('.accordion-content').css({
            'width': contentwidth
        });
        $('.accordion-header').first().toggleClass('active-header').toggleClass('inactive-header');
        $('.accordion-content').first().slideDown().toggleClass('open-content');
        $('.accordion-header').click(function () {
            if ($(this).is('.inactive-header')) {
                $('.active-header').toggleClass('active-header').toggleClass('inactive-header').next().slideToggle().toggleClass('open-content');
                $(this).toggleClass('active-header').toggleClass('inactive-header');
                $(this).next().slideToggle().toggleClass('open-content');
            } else {
                $(this).toggleClass('active-header').toggleClass('inactive-header');
                $(this).next().slideToggle().toggleClass('open-content');
            }
        });
        return false;
    });
    $(document).ready(function () {
        $('a[href^="#"]').bind('click', function (e) {
            e.preventDefault();
            var target = this.hash;
            $target = $(target);
            $('html, body').stop().animate({
                'scrollTop': $target.offset() - 10
            }, 900, 'swing', function () {
            });
        });

        if ($('#error_table_msg').length != 0) {
            var fields_coupon = $('#fields_coupon').val();
            var coupon_discount_price = $('#coupon_discount_price').val();
            var coupon_discount_price_val = $('#coupon_discount_price_val').val();
            var billingSameAsShipping2 = $('#billingSameAsShipping2').val();
            var shipping = parseFloat($('#shipping_price_pro').val());
            if ($('#administrative_area_level_22').val() == "CT") {
                    let taxRate = 0.0635;
                    var taxes = parseFloat($('#custom_product_price').val()) * taxRate;
                    var product_price = parseFloat($('#custom_product_price').val());
                    var total_price = product_price + taxes + shipping;
                    $('.taxes').html("$" + taxes.toFixed(2));
                    $("#total_span").html('$' + total_price.toFixed(2));
                    $("#salesTax").val(taxes);
                } else {
                    $('.taxes').html("$0.00");
                    $("#salesTax").val(0.00);
                }
            if (fields_coupon != '') {
                coupon();
            }
            if (billingSameAsShipping2 == 'NO') {
                // valueChanged('NO');
                $('.input_check').click();
            }

        }
    });
    window.onload = function () {
        var newd = new LazyLoad({
            elements_selector: "img"
        });
        var newd1 = new LazyLoad({
            elements_selector: ".lazy"
        });
    }


</script>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.8.0/lazyload.min.js"></script>
</body>

</html>