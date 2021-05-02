<?php
/*$fields = array(
  'loginId' => $api_key_user,
 'password' => $api_key_pass,
  'firstName' => 'Boss',
 'lastName' => 'test',
 'emailAddress' => 'Bosstest@fakeemail.com',
  'phoneNumber' => '3213213213',
'shipAddress1' => '100 Alley Cat Lane',
 'shipPostalCode' => '30000',
   'shipState' => 'GA',
 'shipCountry' => 'US',
 'billShipSame' => true,
'confirmTOS' => true,
  'product1_id' => '8',
 'campaignId' => '2',
 "paySource" => 'PAYPAL',
  "sessionId" => '27dfb597c3e840efb625a3ef7ddad3f8',
   'ipAddress' => $_SERVER['REMOTE_ADDR'],
 "salesUrl" => 'https => //order.folliboosthair.com/test.php',
   "paypalBillerId" => '4',);
//echo "<pre>";
//var_dump($fields);
//exit;
$Curl_Session = curl_init();
curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/order/import/')
;curl_setopt($Curl_Session, CURLOPT_POST, 1);curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));
curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
$content = curl_exec($Curl_Session);
$ret = json_decode($content, true);
echo "<pre>";var_dump($ret);
exit;*/
?>