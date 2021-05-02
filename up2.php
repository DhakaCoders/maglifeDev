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
	<title>Get MagLife Bracelet</title>
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
          <p>Get 2 Pairs Of MagLife Insoles for Just $20 per pair!</p>
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
              <img src="assets/images/upsell-two-grid-lft-img-3.jpg">
            </div>
          </div>
          <div class="mb-two-grid-rgt-dsc">
            <h5> MagLife Insoles - 2 Pairs  </h5>
            <p>This is the absolute lowest price you’ll ever see for <br> MagLife Insoles…and gives you chance to grab <br> them <strong>FIRST</strong> before they are released to the public </p>
            <div class="updell-price">
              <strong style="color: rgb(57, 123, 33);">Savings: $60 OFF RETAIL</strong>
              <span>Keep reading for all of the details.</span>
            </div>
            
            <p><strong>Hey there…</strong></p>
            <p>It’s Henrik again…</p>
            <p>And I want to let you in on an exclusive offer that has <br> not yet been released to the public… </p>
            <p>You already know the MagLife bracelet can work <br> wonders on pain, especially in your hands, and <br> throughout the rest of your joints… </p>
            
            <p>And the MagLife bracelet is ALL you need to reach your <br> goals of a pain-free life… </p>
            <p><u style="text-decoration: underline;">HOWEVER:</u></p>
            <p>Some customers came to me, and said </p>
            <p><em>“The MagLife Bracelet works GREAT, I feel amazing… I <br> wish you had something similar for my feet and back pain” </em></p>
            <p>It turns out the MagLife bracelet is helping people <br> around the world with joint pain as well as bodily pain in <br> general… </p>
            <p>But some people wanted an <strong> extra boost.</strong></p>
            <p>So… we answered their request, and developed the <br> perfect complement to the MagLife bracelet, and that’s <br> this: </p>
            <p><strong>MagLife Magnetic Insoles</strong></p>
            <p>Now, not only can you have MagLife bracelets working <br> from your wrists and throughout your body… </p>
            <p>And you can also have an added pain relief “boost” that <br> works from the feet up, with every step that you walk!</p>
            <p>The insole fits <u style="text-decoration: underline;">all shoe sizes</u> and transforms to fit all shoe shapes!</p>
            <p>Now - you may have seen similar things on the market, <br> but I promise this is unlike any of those… </p>
            <p>It’s made using the same precision magnet types that <br> are embedded in the MagLife bracelet… </p>
            <p>And created in collaboration with a true natural pain <br>researcher…</p>
            <p>Now you can get a “soothing massage” with every step <br> you take… </p>
            <p>Hitting 175 acupressure points in your feet, and <br> providing an extra surge of relief to your back, hips, <br> neck and more!</p>
            <p>All you need to do is put the insole in your regular shoes <br> and when you stand up or walk, feel the pain dissolving <br> throughout your body</p>
            <p><strong>This invention has not yet been released to the <br> market, because stock is low… and we know once <br> it’s released, demand will go through the roof… </strong></p>
            <p>So we wanted to give our first time, loyal customers a <br> chance to jump on it first </p>
            <p>And if you add this offer on to your order today, you can <br> get 2 pairs of MagLife insoles for you... as extra backup <br> pairs... and for your family and friends… </p>
            <p><strong>For just $20 per pair for 2 pairs, that’s $60 off retail!</strong></p>
            <p>Once released to the public, the price will go up to $50 <br> per pair! (if it’s even in stock!)</p>
            <p>So take advantage of this exclusive offer now, by <br> clicking the button below. This too is covered by a <br> money-back guarantee so you have nothing to lose.</p>
            <div class="upsell-two-grid-btm-dsc">
              <div class="upsell-two-grid-tp-price">
                <span>Price: </span>
                <strong> $39.99</strong>
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
              <a href="step2.php?product_id=146" class="addcart">ADD TO ORDER</a>
              <a href="/ds2.php" class="addcart">NO THANKS, I'LL PASS</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<footer class="up-footer-wrp">
  <div class="up-footer-tp-wrp">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="up-footer-inr">
            <a href="javascript:void(0)">Order now!</a>
          </div>
        </div>
      </div>
    </div>
  </div>
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
            window.location = '/ds2.php';

        }, 5000);
    } else {

    }
</script>
</body>
</html>