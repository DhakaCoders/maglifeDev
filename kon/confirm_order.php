<?php
function ConfirmOrder($api_key_user, $api_key_pass, $order_id)
{
    $fields = array(
        'loginId' => $api_key_user,
        'password' => $api_key_pass,
        'orderId' => $order_id,
    );
    $Curl_Session = curl_init();
    curl_setopt(
        $Curl_Session,
        CURLOPT_URL,
        'https://api.konnektive.com/order/confirm/'
    );
    curl_setopt($Curl_Session,
        CURLOPT_POST,
        1
    );
    curl_setopt(
        $Curl_Session,
        CURLOPT_SSL_VERIFYPEER,
        false
    );
    curl_setopt(
        $Curl_Session,
        CURLOPT_POSTFIELDS,
        http_build_query($fields));
    curl_setopt($Curl_Session,
        CURLOPT_RETURNTRANSFER,
        1);
    $content = curl_exec($Curl_Session);
    /* echo "<pre>";     var_dump($content);     exit;   */
    $ret = json_decode($content,
        true
    );
    return $ret;
}

?>