<?php

add_action('rest_api_init', function() {
    freshia_register_rest_route('POST', 'wxpay/notify', function(WP_REST_Request $request) {
        $mchid = '1680193707';
        $appid = 'wx73dde0301ce105ac';
        $apiKey = 'aCDWhjfkEp3cPrdIkWQRFbtCdKRVb804';
        $wechatPay = new WechatPayService($mchid, $appid, $apiKey);
        $result = $wechatPay->notify();
        error_log(print_r($result, true));
    });
});