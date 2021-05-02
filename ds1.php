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
          <h3 class="too-much-dsc-title">4 MagLife Bracelets Too Much?</h3>
          <p>You can still secure a HUGE discount on more MagLife Bracelets but with 2 additional bracelets instead of 4!</p>
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
                  <div class="up-single-slider-item" style="background: url(assets/images/upsell-slider-img-1.jpg);">
                  </div>
                  <div class="up-single-slider-item" style="background: url(assets/images/upsell-slider-img-1.jpg);">
                  </div>
                  
                </div>
              </div>
              <div class="up-slider-pagination">
                 <div class="slider slider-nav">
                   <div class="up-pg-slide-item">
                    <div class="up-pg-slide-item-img" style="background: url(assets/images/upsell-slider-pg-img-1.jpg);"></div>
                   </div>
                   <div class="up-pg-slide-item">
                    <div class="up-pg-slide-item-img" style="background: url(assets/images/upsell-slider-pg-img-2.jpg);"></div>
                   </div>
                   
                 </div> 
              </div>
            </div> 
          </div>
          <div class="mb-two-grid-rgt-dsc">
            <h5> MagLife Bracelet - 2 Units (Special <br> Offer) </h5>
            <span><u>NEW CUSTOMERS ONLY</u> PRICE PER <br> BRACELET: <ins>$89.99</ins> <strong>$14.99</strong></span>

            <strong>Savings: $59.99 OFF RETAIL</strong>
            
            <p><strong>Hey there…</strong></p>
            <p>I understand that 4 additional MagLife Bracelets may <br> not be what you had in mind. </p>
            <p>But I also know that staying pain-free and helping loved <br> ones and friends is truly important. </p>
            <p>I’ve seen first-hand how much of a blessing MagLife can <br> be for those in pain, it can truly change lives… </p>
            
            <p>For you, when you need extra bracelets or backups… </p>
            <p>And also for your family, friends, and loved ones… </p>
            <p>Remember - the raw materials it takes to create MagLife<br> are hard to source, and it takes a very long time to<br> manufacture </p>
            <p>It’s easy to see why people might be worried that we’ll <br> run out of stock… </p>
            <p>And we do run out of stock quickly, and often….</p>
            <p><strong>Which is why I want to give you one more chance to <br>secure an additional 2 MagLife bracelets </strong></p>
            <p>MagLife is not available in retail stores and the last thing <br> I want is for you to be having pain relief, or seeing a <br> loved one in pain… </p>
            <p>And for some reason not have an extra bracelet to <br> relieve yourself from that misery</p>
            <p>That’s why this is a private page only...</p>
            <p>You can stock up on MagLife Bracelets by adding 2 <br> more bracelets to your order for just $29.99…</p>
            <p>That’s just $14.99 per bracelet, or less than $0.05 per <br> day over a year of use! It’s also a total savings of almost <br> $60 off retail price… </p>
            <p>This offer is only available to new customers, and I’m so <br> glad you got a chance to be a part of this… </p>
            <p>So I recommend taking advantage of this exclusive one-<br>time offer by clicking the button below. </p>
            <p>This is the only time you’ll see this offer, and it’s also <br> covered by our money-back guarantee. So you have <br> nothing to lose.</p>
            <div class="upsell-two-grid-btm-dsc">
              <div class="upsell-two-grid-tp-price">
                <span>Price: </span>
                <strong> $29.99</strong>
                <span class="hide-xs">+ Sales tax (for CT residents)</span>
                <span class="show-xs">+ Sales tax (for CT residents)</span>
              </div>
              <!--<div class="upsell-two-grid-btm-price">
                <div class="upsell-two-price-xs show-xs">
                  <span>taxes: </span>
                  <span><em>N/A</em> </span>
                </div>
                <span>Total: </span>
                <span><em>N/A</em> </span>
              </div>-->
              <a href="step2.php?product_id=145" class="addcart">ADD TO ORDER</a>
              <a href="/up2.php" class="addcart">NO THANKS, I'LL PASS</a>
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
              <a href="privacy.php" target="_blank">Privacy Policy</a> |
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
            window.location = '/up2.php';

        }, 5000);
    } else {

    }
</script>
</body>
</html>