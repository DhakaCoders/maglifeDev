<?php
@session_start();
error_reporting(0);
include './kon/KonConfig.php';
$response = '';
$click_id = $_GET['click_id'];
if ($_REQUEST['errorMessage']) {
    echo "<table id='error_message' style='width:100%;' align='center'><tr><td style='background-color:#ff0000;color:#ffffff;font-size: 18px;font-family: arial,helvetica,sans-serif; font-weight:bold;height:50px; padding-top: 10px; text-align: center;' align='center'>" . urldecode($_REQUEST['errorMessage']) . "</td></tr></table>";
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
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" type="text/css" href="assets/slick.slider/slick-theme.css">
  <link rel="stylesheet" type="text/css" href="assets/slick.slider/slick.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Roboto&display=swap" rel="stylesheet"> 
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
          <p>WAIT - Final Offer!</p>
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
          <h3 class="too-much-dsc-title">Final Bonus Offer - Get Lifetime Warranty </h3>
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
          <div class="mb-two-grid-lft upsell-grid-img">
            <div class="mb-two-grid-lft-img">
              <img src="assets/images/upsell-two-grid-lft-img-5.png">
            </div>
          </div>
          <div class="mb-two-grid-rgt-dsc">
            <h5>  Warranty </h5>
            <p>Hey, before I send you over the order confirmation page, I just wanted to share with you one final bonus offer that over 90% of our customers take.</p>
            <p>By accepting the offer below, you can get lifetime protection and replacement warranty for your knee sleeve for just $10…</p>
            <p>This means your product is covered for LIFE… and any issues that you encounter, we can fix by sending you a replacement at no-extra charge to you!</p>
            <p>Considering a GenuCare knee sleeve retails at $53, being able to get a new one for $10 is a steal! </p>
            <p>If you’re interested, just click the button below.</p>

            <div class="upsell-two-grid-btm-dsc">
              <div class="upsell-two-grid-tp-price">
                <span>Price: </span>
                <strong> $10.00</strong>
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
              <a href="step2.php?product_id=148" class="addcart">ADD TO ORDER</a>
              <a href="/thank-you.php" class="addcart">NO THANKS, I'LL PASS</a>
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
<!--            <a href="privacy.php">I consent to receive recurring automated marketing by text message through an automatic telephone dialing system. Consent is not a condition to purchase. STOP to cancel, HELP for help. Message and Data rates may apply. View Privacy Policy & ToS.</a>-->
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
            window.location = '/thank-you.php';

        }, 5000);
    } else {

    }
</script>
</body>
</html>