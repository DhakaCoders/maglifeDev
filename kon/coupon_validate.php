<?php
include('./KonConfig.php');
function GetCopuneInfo($api_key_user,$api_key_pass,$campaign_id) {
    $fields = array(
        'loginId' => $api_key_user,
        'password' => $api_key_pass,
        'campaignId' => $campaign_id,
    );
    $Curl_Session = curl_init();
    curl_setopt($Curl_Session, CURLOPT_URL, 'https://api.konnektive.com/campaign/query/');
    curl_setopt($Curl_Session, CURLOPT_POST, 1);
    curl_setopt($Curl_Session, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($Curl_Session, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($Curl_Session, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($Curl_Session);
    $ret = json_decode($content, true);
    $coupons = $ret['message']['data'][$campaign_id]['coupons'];
    return json_encode($ret);
}

echo GetCopuneInfo($api_key_user,$api_key_pass,$campaign_id);