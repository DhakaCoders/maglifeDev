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
if (count($_SESSION['purchased_items']['return']) > 1){
    $discount = 0.00;
    $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
    $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
    foreach ($_SESSION['purchased_items']['return'] as $v){
        $image = $productArray[$v['productId']]['img'];
        $name = $v['name'];
        $name2 = explode("(",$name);
        $product_name = $name2[0];
        $pr_price = $v['refundRemaining'];
        $sub_price = $v['price'];
        $sub_total = $sub_price + 0.00;
        $totalAmlount_sub2 += $sub_total;
    }

}elseif (count($_SESSION['purchased_items']['return']) > 1){
    $discount = 0.00;
    $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
    $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
    foreach ($_SESSION['purchased_items']['return'] as $v){
        $image = $productArray[$v['productId']]['img'];
        $name = $v['name'];
        $name2 = explode("(",$name);
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
//echo '<pre>';
//var_dump($totalAmlount_sub2);
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Thank You - Folliboost™ Hair Growth Serum</title>
    <meta name="robots" content="noindex,nofollow">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <link rel="icon" href="favicon.ico"/>
    <link rel="stylesheet" href="style.css"/>
    <link rel="shortcut icon" href="https://cdn.shopify.com/s/files/1/2978/5256/t/3/assets/favicon.png?9"
          type="image/png">


    <!-- Outbrain Pixel Code -->
    <script data-obct type="text/javascript">
        /** DO NOT MODIFY THIS CODE**/
        !function(_window, _document) {
            var OB_ADV_ID='0025151f5b57d51e1b85234affe1edabf9';
            if (_window.obApi) {var toArray = function(object) {return Object.prototype.toString.call(object) === '[object Array]' ? object : [object];};_window.obApi.marketerId = toArray(_window.obApi.marketerId).concat(toArray(OB_ADV_ID));return;}
            var api = _window.obApi = function() {api.dispatch ? api.dispatch.apply(api, arguments) : api.queue.push(arguments);};api.version = '1.1';api.loaded = true;api.marketerId = OB_ADV_ID;api.queue = [];var tag = _document.createElement('script');tag.async = true;tag.src = '//amplify.outbrain.com/cp/obtp.js';tag.type = 'text/javascript';var script = _document.getElementsByTagName('script')[0];script.parentNode.insertBefore(tag, script);}(window, document);
        obApi('track', 'PAGE_VIEW');
    </script>
    <script>
        obApi('track', 'Total Purchase', {
            orderValue: '<?php echo $_SESSION['purchased_items']['total_amount'] - $_SESSION['main_price']; ?>' ,
            currency:'USD' ,});
    </script>
    <!-- End Outbrain Pixel Code -->
    <script type="text/javascript"  src="https://www.revdtrack.com/scripts/sdk/everflow.js"></script>
    <script type="text/javascript">
        EF.conversion({
            offer_id: 2,
            event_id: 4,
            amount: <?php echo str_replace('"', "'", json_encode($_SESSION['purchased_items']['total_amount'] - $_SESSION['main_price']));?>,
            adv1: "<?php echo $_SESSION['purchased_items']['order_id']; ?>",
        });
    </script>

    <style>
        td.dollar {
            float: right;
            width: 0;
            padding: 33px 10px 30px 65px;
        }

        td.dollar5 {
            width: 0;
            padding: 11px 10px 30px 270px;
            float: right;
        }

        td.dollar_total {
            padding: 5px 35px 30px 245px;
            float: right;
            width: 0;
        }

        @media screen and (max-width: 900px) {
            td.dollar {
                float: right !important;
                padding: 30px 0 0 !important;
                width: auto;
            }

            td.dollar5 {
                padding: 12px 0 0 !important;
                float: right !important;
                width: auto;
            }

            td.dollar_total {
                padding: 2px 0 0 !important;
                float: right !important;
                width: auto !important;
            }
        }

        @media screen and (max-width: 420px) {
            span.bigger_font {
                float: right !important;
            }
        }

        .dollar5_dis {
            width: 30%;
            padding: 11px 10px 30px 192px;
        }
    </style>
</head>

<body>

<script>
    window.dataLayer = window.dataLayer || [];
    dataLayer.push({
        'transactionId': <?php echo str_replace('"', "'", json_encode($order_id));?>, // string (required)
        'transactionAffiliation': <?php echo str_replace('"', "'", json_encode($tracAffiliation));?>, // string (required)
        'transactionTotal': <?php echo str_replace('"', "'", json_encode($traTotal));?>, // string (required)
        'transactionProducts': <?php echo str_replace('"', "'", json_encode($transactionProducts));?>
    });
</script>
<div class="width thankyou_pg">
    <div class="container">
        <div class="row">
            <div class="col-7 text-center">
                <div class="thankyou_bg">
                    <p class="mb-20"><img data-src="img/logo.png" src="img/pixel.png" alt="" class="log lazy"></p>
                    <p class="mb-40"><img data-src="img/verified.png" src="img/pixel.png" alt="" class="verified lazy">
                    </p>
                    <p class="text-medium-21 strong">Thanks for your order!</p>
                    <p class="text-small-14">A confirmation email has been sent to <?php echo $_SESSION['fields_email_2']; ?>
                    </p>
                    <p class="text-small-14 strong">Customer ID: <?php echo $_SESSION['purchased_items']['order_id'];  ?></p>
                    <p class="mb-20"><a href="index.php" class="continue_shop">Continue Shopping</a></p>
                    <p class="mb-20"><a href="#" class="print" onClick="printReceipt()">Print Receipt</a></p>
                </div>
                <div class="receipt">
                    <div class="col-6">
                        <ul>
                            <li class="open_bold text-small-16 pt-20">SHIPPING ADDRESS</li>
                            <li><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2']; ?></li>
                            <li><?php echo $_SESSION['fields_city_2']; ?></li>
                            <li><?php echo $_SESSION['fields_address1_2']; ?></li>
                            <li><?php echo $_SESSION['fields_country_2']; ?></li>
                            <li><?php $_SESSION['fields_zip_2']; ?></li>
                        </ul>
                    </div>

                    <div class="col-6">
                        <ul>
                            <li class="open_bold text-small-16 pt-20">BILLING ADDRESS</li>
                            <?php if($_SESSION['billingSameAsShipping_2'] == "NO"){ ?>
                            <li><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2'];  ?></li>
                            <li><?php echo $_SESSION['billing_city_2']; ?></li>
                            <li><?php echo $_SESSION['billing_street_address_2']; ?></li>
                            <li><?php echo $_SESSION['billing_country_2']; ?></li>
                            <li><?php $_SESSION['billing_postcode_2']; ?></li>
                            <?php } else { ?>
                                <li><?php echo $_SESSION['fields_fname_2'] . " " . $_SESSION['fields_lname_2']; ?></li>
                                <li><?php echo $_SESSION['fields_city_2']; ?></li>
                                <li><?php echo $_SESSION['fields_address1_2']; ?></li>
                                <li><?php echo $_SESSION['fields_country_2']; ?></li>
                                <li><?php $_SESSION['fields_zip_2']; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <p class="text-left mb-40 for_desk">All rights reserved.</p>
            </div>

            <div class="col-5">
                <div class="product_bg">
                    <table>
                        <?php
                        $totalAmlount = 0.00;
                        if (count($_SESSION['purchased_items']['return']) > 1):
                            $discount = 0.00;
                            $pr_price2 = $_SESSION['purchased_items']['return'][0]['refundRemaining'];
                            $sub_price2 = $_SESSION['purchased_items']['return'][0]['price'];
                            foreach ($_SESSION['purchased_items']['return'] as $v):
                                $image = $productArray[$v['productId']]['img'];
                                $name = $v['name'];
                                $name2 = explode("(",$name);
                                $product_name = $name2[0];
                                $pr_price = $v['refundRemaining'];
                                $sub_price = $v['price'];
                                $sub_total = $sub_price + 0.00;
                                $totalAmlount_sub += $sub_total;
                                ?>
                                <tr class="border">
                                    <td>
                                        <div class="pro_img">
                                            <img data-src="<?php echo $image ?>" src="img/pixel.png" alt=""
                                                 class="weird_rock lazy">
                                            <div class="item_quantity">1</div>
                                        </div>
                                    </td>
                                    <td style="    width: 50%"><?php echo $name; ?></td>
                                    <td class="dollar"><?php echo '$' . $sub_price ?></td>
                                </tr>
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
                            ?>
                            <tr class="border">
                                <td>
                                    <div class="pro_img">
                                        <img data-src="<?php echo $image ?>" src="img/pixel.png" alt=""
                                             class="weird_rock lazy">
                                        <div class="item_quantity">1</div>
                                    </div>
                                </td>
                                <td style="    width: 50%"><?php echo $name; ?></td>
                                <td class="dollar"><?php echo '$' . $sub_price ?></td>
                            </tr>
                        <?php endif;

                        ConfirmOrder($api_key_user, $api_key_pass, $_SESSION['purchased_items']['order_id']);
                        if ($_SESSION['coupon_discount_code_2'] == 'FOLLI10') {
                            $discount = $totalAmlount_sub - $_SESSION['purchased_items']['total_amount'];
                        } elseif ($_SESSION['coupon_discount_code_2'] == 'FOLLI20') {
                            $discount = $totalAmlount_sub - $_SESSION['purchased_items']['total_amount'];
                        }
                        ?>

                        <tr>
                            <td>Subtotal</td>
                            <td class="dollar5"><?php echo '$'.number_format((float)$totalAmlount_sub, 2, '.', '') ?></td>
                        </tr>
                        <tr class="border" style="    border-bottom: 0;">
                            <td>Shipping</td>
                            <td class="dollar5">$0.00</td>
                        </tr>
                        <tr class="border">
                            <td>Discount</td>
                            <td class="dollar5"><span style="color:green;"><?php echo '$'.number_format((float)($sub_price2 - $pr_price2), 2, '.', '') ?></span></td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td class="dollar_total"><span class="bigger_font"><?php echo '$'.number_format((float)$_SESSION['purchased_items']['total_amount'], 2, '.', '') ?></span>
                            </td>
                        </tr>
                    </table>

                </div>

                <p class="text-left mb-40 for_mob text-center">All rights reserved.</p>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript" src="jquery.min.js"></script>
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
    });
    window.onload = function () {
        var newd = new LazyLoad({
            elements_selector: "img"
        });
        var newd1 = new LazyLoad({
            elements_selector: ".lazy"
        });
    }

    function printReceipt() {
        window.print();
    }
</script>

<script>
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });
</script>

<script defer src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-lazyload/10.8.0/lazyload.min.js"></script>
</body>

</html>