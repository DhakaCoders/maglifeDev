<?php
@session_start();
error_reporting(0);
include './kon/KonConfig.php';
$_SESSION['paypalAccept'] = $_GET['paypalAccept'];
$token = $_GET['token'];
$PayerID = $_GET['PayerID'];
$orderId = $_SESSION['order_id_lead'];
$product1_id = $_SESSION['custom_product_2'];
$coupon_code =  $_SESSION['coupon_discount_code_2'];

if ($_SESSION['test_ty'] == 0) {

} else {
    if (isset($_SESSION['paypalAccept']) && $_SESSION['paypalAccept'] == 1 && $_SESSION['test_ty'] == '1') {
        $ret = confirmPaypal($api_key_user, $api_key_pass, $campaign_id, $token, $PayerID, $_SESSION['session_id_new'], $orderId, $product1_id, $coupon_code);
        /*echo '<pre>';
        var_dump($ret);
        exit;*/
        if ($ret->result == 'ERROR') {
            $url = "step2.php?product_id=" . $product1_id . "&errorMessage=Sorry, order not processed. Try again";
            header("Location:{$url}");

        } else {
            $_SESSION['purchased_items'] = array();
            $_SESSION['purchased_items'][] = array('product_id' => $_SESSION['custom_product'], 'time' => time(), 'return' => $ret);
            $_SESSION['test_ty'] = 0;
        }
    } else {
        $url = "step2.php?product_id=" . $_SESSION['custom_product'];
        header("Location:{$url}");
    }

}
$response = '';
$click_id = $_GET['click_id'];
if ($_REQUEST['errorMessage']) {
    echo "<table id='error_message' style='width:100%;text-align: center;background-color: #ff0000;'><tr><td style='background-color:#ff0000;color:#ffffff;font-size: 18px;font-family: arial,helvetica,sans-serif;font-weight:bold;height: 10%;padding-top: 10px;text-align: center;'>" . urldecode($_REQUEST['errorMessage']) . "</td></tr></table>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Get Knee Sleeves</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.webp" type="image/png">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="assets/slick.slider/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="assets/slick.slider/slick.css">
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
        fbq('track', 'Purchase');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=725297681751604&ev=Purchase&noscript=1"
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
        @media (max-width: 767px){
            .upsell-two-grid-tp-price span:after {
                content: none;
            }
        }
        .up-footer-btm-inr a:last-of-type {
            display: inline-block !important;
        }
        .up-footer-btm-inr{
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

<section class="too-much-content-sec-wrp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="too-much-dsc-wrp">
                    <h3 class="too-much-dsc-title">Special Deal For New Customers Only!</h3>
                    <p>Stock up on GenuCare knee sleeves for you and your loved ones for just $14.99 per knee sleeve</p>
                </div>
            </div>
        </div>
    </div>
    <div class="too-much-btm-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="too-much-btm">
                        <div class="time-countdown-wrp">
                            <h4 class="up-too-much-btm-title">Special Offer Ends In:</h4>
                            <div class="countdown">00:00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-two-part-sec-wrp">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-two-grid-wrp clearfix">
                    <div class="mb-two-grid-lft">
                        <div class="up-slider-wrp clearfix">
                            <div class="up-slider-cntlr">
                                <div class="fl-prv-nxt">
                  <span class="fl-prev">
                  </span>
                                    <span class="fl-next">
                  </span>
                                </div>
                                <div class="slider slider-single">
                                    <div class="up-single-slider-item"
                                         style="background: url(assets/images/upsell-slider-img-1.jpg);">
                                    </div>
                                    <div class="up-single-slider-item"
                                         style="background: url(assets/images/upsell-slider-img-1.jpg);">
                                    </div>
                                    <div class="up-single-slider-item"
                                         style="background: url(assets/images/upsell-slider-img-3.png);">
                                    </div>

                                </div>
                            </div>
                            <div class="up-slider-pagination">
                                <div class="slider slider-nav">
                                    <div class="up-pg-slide-item">
                                        <div class="up-pg-slide-item-img"
                                             style="background: url(assets/images/upsell-slider-pg-img-1.jpg);"></div>
                                    </div>
                                    <div class="up-pg-slide-item">
                                        <div class="up-pg-slide-item-img"
                                             style="background: url(assets/images/upsell-slider-pg-img-2.jpg);"></div>
                                    </div>
                                    <div class="up-pg-slide-item">
                                        <div class="up-pg-slide-item-img"
                                             style="background: url(assets/images/upsell-slider-pg-img-3.png);"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-two-grid-rgt-dsc">
                        <h5>3 More Knee Sleeves</h5>
                        <p>This is the absolute lowest price you’ll ever see for <br> GenuCare… and it protects you from future price <br> increases as well as out of stocks!</p>
                        <div class="updell-price">
                            <strong>Savings:<u> $114 OFF RETAIL</u></strong> 

                            <span>Keep reading for all of the details.</span>
                        </div>

                        <p><strong>Hey there…</strong></p>
                        <p>My name is Pat Baker and I’m the founder of GenuCare.</p>
                        <p>I am so happy you made the right decision to purchase the GenuCare knee sleeves because I believe it truly is the BEST option out there for your knees.</p>
                        <p>You’ll be amazed at how well it works!</p>

                        <p><strong>However…</strong></p>
                        <p>A lot of people have been telling me that they’re worried they won’t be able to get more knee sleeves in the future…</p>
                        <p>This is because the fabric used to make the special OptiFlex material is very hard to source…</p>
                        <p>And we are a small company that hand makes each knee sleeve.</p>
                        <p>Those two things coupled with the insane demand for this new invention makes it very hard to keep them in stock…</p>
                        <p><strong>And I completely understand why people want more:</strong></p>
                        <p>People want to have extra knee sleeves to bring on vacation, to keep at work, or just in case they <br> lose them!</p>
                        <p>And people constantly see friends and family members who develop their own knee problems… <br> and they wish they could give them a knee sleeve.</p>
                        <p>Imagine using this knee sleeve and it giving you natural pain relief… but then you lose it and can’t <br> get any more because we’re out of stock! </p>
                        <p>Or imagine seeing a family member suffering from pain, and having no way to buy more… <br> because we’re out of stock! </p>
                        <p>It’s so easy to see why people might be worried that we’ll run out of stock…</p>
                        <p>And we do run out of stock quickly, and often - that’s the bad news.</p>
                        <p><strong>But here is the good news! </strong></p>
                        <p>Because of the amount of GenuCare knee sleeves that you just ordered…</p>
                        <p>On this private page, you can stock up on the GenuCare knee sleeves by adding 3 more knee <br> sleeves to your order for just $44.97…</p>
                        <p>That’s $14.99 per knee sleeve, or less than $0.05 per day over a year of use! </p>
                        <p>It’s also a total savings of $114 off the retail price.</p>
                        <p>This offer is only available to new customers, and I’m so glad you got a chance to be a part of <br> this…</p>
                        <p>So I recommend taking advantage of this exclusive one-time offer by clicking the button below.</p>
                        <p>This is the only time you’ll see this offer, and it’s also covered by our money-back guarantee… <br> so you have nothing to lose.</p>
                        <div class="upsell-two-grid-btm-dsc">
                            <div class="upsell-two-grid-tp-price">
                                <span>Price: </span>
                                <strong> $44.97</strong>
                                <span class="hide-xs">+ Sales tax (for CT residents)</span>
                                <span class="show-xs">+ Sales tax (for CT residents)</span>
                                <!--<span class="hide-xs">+<em> N/A</em> taxes </span>
                                <span class="show-xs">+ Tax & Shipping</span>-->
                            </div>
                            <!--<div class="upsell-two-grid-btm-price">
                                <div class="upsell-two-price-xs show-xs">
                                    <span>taxes: </span>
                                    <span><em>N/A</em> </span>
                                </div>
                                <span>Total: </span>
                                <span><em>N/A</em> </span>
                            </div>-->
                            <div class="md-size-select">
                              <select class="form-select" aria-label="Default select example">
                                <option selected>Select Size</option>
                                <option value="Small">Small</option>
                                <option value="Medium">Medium</option>
                                <option value="Large">Large</option>
                                <option value="X-Large">X-Large</option>
                                <option value="XX-Large">XX-Large</option>
                              </select>
                            </div>
                            <a href="step2.php?product_id=144" class="addcart">ADD TO ORDER</a>
                            <a href="/ds1.php" class="addcart">NO THANKS, I'LL PASS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<footer class="up-footer-wrp">
    <div class="up-footer-btm-wrp">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="up-footer-btm-inr">
                        <!--<a href="privacy.php">I consent to receive recurring automated marketing by text message through an
                            automatic telephone dialing system. Consent is not a condition to purchase. STOP to cancel,
                            HELP for help. Message and Data rates may apply. View Privacy Policy & ToS.</a>-->
                        <a href="privacy.php" class="footer-links" target="_blank">Privacy Policy</a> |
                        <a href="terms.php">Terms of service </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="assets/slick.slider/slick.js"></script>
<script src="assets/js/main.js"></script>
<script>
    history.pushState(null, null, document.URL);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
    });

    $(document).ready(function () {
        var i = 0;
        $('a.addcart').click(function () {
            if (i == 0) {
                i++;
            } else {
                return false;
            }
        });
    });

    if ($('#error_message').length) {
        setTimeout(function () {
            var domain = window.location.hostname;
            window.location = '/ds1.php';

        }, 5000);
    } else {

    }
</script>
</body>
</html>
