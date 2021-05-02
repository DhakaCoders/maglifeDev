<?php
@session_start();
error_reporting(0);
include('./kon/KonConfig.php');
include('./kon/confirm_order.php');

$response = '';
$click_id = $_GET['click_id'];
if ($_REQUEST['errorMessage']) {
    echo "<table id='error_message' style='width:100%;text-align: center;background-color: #ff0000;'><tr><td style='background-color:#ff0000;color:#ffffff;font-size: 18px;font-family: arial,helvetica,sans-serif;font-weight:bold;height: 10%;padding-top: 10px;text-align: center;'>" . urldecode($_REQUEST['errorMessage']) . "</td></tr></table>";
}

$totalAmlount = 0.00;
$transactionProducts = array();
if (count($_SESSION['purchased_items']) > 0) {
    foreach ($_SESSION['purchased_items'] as $v) {
        $v['time'];
        $v[''];
        $sku = $productArray[$v['product_id']]['sku'];
        $name = $productArray[$v['product_id']]['name'];
        $pr_price = $productArray[$v['product_id']]['price'];
        $quantity = $productArray[$v['product_id']]['qty'];
        $total = $pr_price + 0.00;
        $totalAmlount += $total;
        $returnArray = json_decode($v['return'], true);
        $products_details = [
            'sku' => $sku,
            'name' => $name,
            'price' => $pr_price,
            'quantity' => $quantity,
        ];
        array_push($transactionProducts, $products_details);
    }
}

$totalAmlount_sub2 = 0.00;
if (count($_SESSION['purchased_items']['return']) > 1) {
    $discount = 0.00;
    $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
    $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
    foreach ($_SESSION['purchased_items']['return'] as $v) {
        $image = $productArray[$v['productId']]['img'];
        $name = $v['name'];
        $name2 = explode("(", $name);
        $product_name = $name2[0];
        $pr_price = $v['refundRemaining'];
        $sub_price = $v['price'];
        $sub_total = $sub_price + 0.00;
        $totalAmlount_sub2 += $sub_total;
    }

} elseif (count($_SESSION['purchased_items']['return']) > 1) {
    $discount = 0.00;
    $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
    $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
    foreach ($_SESSION['purchased_items']['return'] as $v) {
        $image = $productArray[$v['productId']]['img'];
        $name = $v['name'];
        $name2 = explode("(", $name);
        $product_name = $name2[0];
        $pr_price = $v['refundRemaining'];
        $sub_price = $v['price'];
        $sub_total = $sub_price + 0.00;
        $totalAmlount_sub2 += $sub_total;
    }
}

$returnArray = json_decode($_SESSION['purchased_items'][0]['return'], true);
$order_id = $returnArray['message']['orderId'];
$tracAffiliation = 'Folliboost';
$traTotal = $totalAmlount;
$_SESSION['affId'] = '';
$_SESSION['c1'] = '';
$_SESSION['c2'] = '';
$_SESSION['c3'] = '';
$_SESSION['discountPrice'] = '';
if (!empty($_SESSION['upsell_price'])){
    $price = $_SESSION['upsell_prices'];
}else{
    $price = 0;
}

if (count($_SESSION['purchased_items']['return']) == 1 ){
    $base_shipping = (float)$_SESSION['purchased_items']['return'][0]->shipping;
}else{
    $base_shipping = (float)$_SESSION['purchased_items']['return'][0]["shipping"];
}

$base_totalAmlount = (float)$_SESSION['purchased_items']['total_amount'];

/*echo '<pre>';
var_dump($_SESSION);
exit;*/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thank You for Your Order!</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.webp" type="image/png">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Roboto&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">

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
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=725297681751604&ev=PageView&noscript=1"
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


    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .mb-sidebar-cntlr {
            max-width: 460px;
        }
        .mb-sidebar-product-dsc strong, .mb-sidebar-product-dsc span{
            width: 50%;
            margin-top: 20px;
        }
        .mb-sidebar-product-dsc strong {
            float: right;
        }
        .footer-v1-wrp a {
            display: inline-block !important;
        }
        .footer-v1-wrp {
            text-align: center;
        }
    </style>
</head>
<body>

<section class="thank-you-order-sec-wrp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="thank-you-order-wrp">
                    <p>Thank you for your order!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="thank-two-part-sec-wrp">
    <div class="mb-two-part-cntlr clearfix">
        <div class="mb-two-part-rgt mHc">
            <div class="mb-show-order-sm show-sm clearfix">
        <span><i class="fa fa-shopping-cart" aria-hidden="true"></i>
Show order summary</span>
                <strong><?php echo '$' . number_format($base_shipping + $base_totalAmlount, 2, '.', '') ?></strong>
            </div>
            <div class="mb-sidebar-cntlr">
                <div class="mb-sidebar-product-thumbnail-wrp">
                    <ul class="clearfix reset-list">
                        <li>
                            <div class="mb-sidebar-product-thum">
                                <h5>Order #<?php echo $_SESSION['purchased_items']['order_id']; ?></h5>
                                <?php
                                $totalAmlount = 0.00;
                                if (count($_SESSION['purchased_items']['return']) > 1):
                                    $discount = 0.00;
                                    $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
                                    $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
                                    foreach ($_SESSION['purchased_items']['return'] as $v):
                                        $image = $productArray[$v['productId']]['img'];
                                        $name = $v['name'];
                                        $name2 = explode("(", $name);
                                        $product_name = $name2[0];
                                        $pr_price = $v['refundRemaining'];
                                        $sub_price = $v['price'];
                                        $sub_total = $sub_price + 0.00;
                                        $totalAmlount_sub += $sub_total;
                                        $tax += $v['initialSalesTax'];
                                        $shipping += $v['shipping'];
                                        ?>
                                        <div class="mb-sidebar-product clearfix" style="margin: inherit;">
                                            <div class="mb-sidebar-product-img">
                                                <img src="<?php echo $image ?>">
                                                <span>1</span>
                                            </div>
                                            <div class="mb-sidebar-product-dsc">
                                                <span><?php echo $name; ?></span>
                                                <strong><?php echo '$' . $sub_price ?></strong>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>
                                <?php endif;
                                if (count($_SESSION['purchased_items']['return']) == 1 ):
                                $discount = 0.00;
                                $product_arr = $_SESSION['purchased_items']['return'][0];
                                $pr_price2 = $product_arr->refundRemaining;
                                $sub_price2 = $product_arr->price;
                                $image = $productArray[$product_arr->productId]['img'];
                                $name = $product_arr->name;
                                $name2 = explode("(",$name);
                                $product_name = $name2[0];
                                $pr_price = $product_arr->refundRemaining;
                                $sub_price = $product_arr->price;
                                $sub_total = $sub_price + 0.00;
                                $totalAmlount_sub += $sub_total;
                                $tax += $product_arr->initialSalesTax;
                                $shipping = $product_arr->shipping;
                                ?>
                                <div class="mb-sidebar-product clearfix" style="margin: inherit;">
                                    <div class="mb-sidebar-product-img">
                                        <img src="<?php echo $image ?>">
                                        <span>1</span>
                                    </div>
                                    <div class="mb-sidebar-product-dsc">
                                        <span><?php echo $name; ?></span>
                                        <strong><?php echo '$' . $sub_price ?></strong>
                                    </div>
                                </div>

                                <?php endif;
                                ConfirmOrder($api_key_user, $api_key_pass, $_SESSION['purchased_items']['order_id']);
                                if ($_SESSION['coupon_discount_code_2'] == 'NEW20' || $_SESSION['coupon_discount_code_2'] == 'TAKE10' ||
                                    $_SESSION['coupon_discount_code_2'] == 'TAKE20' || $_SESSION['coupon_discount_code_2'] == 'SMS20' ||
                                    $_SESSION['coupon_discount_code_2'] == 'SEARCH10') {
                                    $discount = $_SESSION['purchased_items']['discountPrice'];
                                }
                                ?>
                            </div>
                        </li>

                        <!--<li>
                            <div class="mb-sidebar-product-thum">
                                <h5>Special Offer </h5>
                                <div class="mb-sidebar-product clearfix">
                                    <div class="mb-sidebar-product-img">
                                        <img src="<?php /*echo $image */?>">
                                        <span>1</span>
                                    </div>
                                    <div class="mb-sidebar-product-dsc">
                                        <span><?php /*echo $name; */?></span>
                                        <strong><?php /*echo '$' . $sub_price */?></strong>
                                    </div>
                                </div>
                            </div>
                        </li>-->

                        <li>
                            <div class="mb-sidebar-product-thum">
                                <div class="mb-sidebar-product-subtotal clearfix">
                                    <span>Subtotal</span>
                                    <span><?php echo '$' . number_format((float)$totalAmlount_sub, 2, '.', '') ?></span>
                                    <span>Shipping </span>
                                    <span><?php echo '$' . number_format((float)$shipping, 2, '.', '') ?></span>
                                    <span>Discount </span>
                                    <span><?php echo '$' . number_format((float)$discount, 2, '.', ''); ?></span>
                                    <span>Taxes </span>
                                    <span><?php echo '$' . number_format((float)$tax, 2, '.', ''); ?> </span>
                                    <?php $t = $_SESSION['purchased_items']['total_amount'] + $shipping; ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="mb-sidebar-product-thum">
                                <div class="mb-sidebar-product-total clearfix">
                                    <strong>Total</strong>
                                    <strong><span>USD</span> <?php echo '$' . number_format((float)$t, 2, '.', '') ?>
                                    </strong>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="mb-information-wrp clearfix">
                    <h5>Information</h5>
                    <div class="mb-information-item shipping-address">
                        <h6>Shipping address </h6>
                        <ul class="clearfix reset-list">
                            <li>
                                <span><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2']; ?></span>
                            </li>
                            <li><span><?php echo $_SESSION['fields_address1_2']; ?></span></li>
                            <li><span><?php echo $_SESSION['shippingCity_2']; ?></span> - <span><?php echo $_SESSION['shippingZip_2']; ?></span></li>
                            <li><span><?php echo $_SESSION['shippingState_2']; ?></span> - <span><?php echo $_SESSION['fields_country_2']; ?></span></li>
                        </ul>
                    </div>
                    <div class="mb-information-item billing-address">
                        <h6>Billing address </h6>
                        <ul class="clearfix reset-list">
                            <?php if ($_SESSION['billingSameAsShipping_2'] == "NO") { ?>
                                <li>
                                    <span><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2']; ?></span>
                                </li>
                                <li><span><?php echo $_SESSION['billing_street_address_2']; ?></span></li>
                                <li><span><?php echo $_SESSION['billing_city_2']; ?></span> - <span><?php echo $_SESSION['billing_postcode_2']; ?></span></li>
                                <li><span><?php echo $_SESSION['billing_state_2']; ?></span> - <span><?php echo $_SESSION['billing_country_2']; ?></span></li>
                            <?php } else { ?>
                                <li>
                                    <span><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2']; ?></span>
                                </li>
                                <li><span><?php echo $_SESSION['fields_address1_2']; ?></span></li>
                                <li><span><?php echo $_SESSION['shippingCity_2']; ?></span> - <span><?php echo $_SESSION['shippingZip_2']; ?></span></li>
                                <li><span><?php echo $_SESSION['shippingState_2']; ?></span> - <span><?php echo $_SESSION['fields_country_2']; ?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="mb-information-item shipping-method">
                        <h6>Shipping method </h6>
                        <ul class="clearfix reset-list">
                            <li><span>Standard Shipping </span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
        <div class="mb-two-part-lft clearfix mHc">
            <div class="mb-two-part-lft-cntlr">
                <div class="mb-two-part-lft-logo">
                    <img src="assets/images/logo-2.png">
                </div>
                <div class="mb-two-part-lft-dsc">
                    <p>Your order was successfully processed. You should be receiving a confirmation email shortly. If
                        you have any questions please email us at <a href="mailto:support@maglifebracelet.com">support@maglifebracelet.com.</a>
                    </p>
                    <h4 class="mb-two-part-lft-dsc-title">Please note that your order will show up under the name
                        "MAGLIFE" on your credit card or debit card statement.</h4>
                </div>
                <div class="mb-two-part-lft-img">
                    <img src="assets/images/mb-two-part-lft-img.png">
                </div>
                <div class="mb-two-part-lft-discount">
                    <a href="https://buymaglife.com/?affId=1&c1=typage">GET MY 20% DISCOUNT</a>
                </div>
                <div class="footer-v1-wrp hide-sm">
                    <!--<a href="privacy.php" target="_blank">I consent to receive recurring automated marketing by text message through an automatic
                        telephone dialing system. Consent is not a condition to purchase. STOP to cancel, HELP for help.
                        Message and Data rates may
                        apply. View Privacy Policy & ToS. </a>-->
                    <a href="privacy.php" class="footer-links" target="_blank">Privacy Policy</a> |
                    <a href="terms.php" target="_blank">Terms of service</a>
                </div>
            </div>
            <div class="footer-v1-wrp show-sm">
                <div class="footer-v1-sm">
                    <!--<a href="privacy.php" target="_blank">I consent to receive recurring automated marketing by text message through an automatic
                        telephone dialing system. Consent is not a condition to purchase. STOP to cancel, HELP for help.
                        Message and Data rates may
                        apply. View Privacy Policy & ToS. </a>-->
                    <a href="privacy.php" class="footer-links" target="_blank">Privacy Policy</a> |
                    <a href="terms.php" target="_blank">Terms of service</a>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="assets/js/jquery.matchHeight-min.js"></script>
<script type="text/javascript">
    $('.mb-show-order-sm span').on('click', function () {
        $('.mb-sidebar-cntlr').slideToggle(300);
        $(this).toggleClass('active-collapse');
    });
    if ($('.mHc').length) {
        $('.mHc').matchHeight();
    }
    ;
</script>
<script>
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>

</body>
</html>