<?php

add_action('rest_api_init', function() {
    // 微信支付回调
    freshia_register_rest_route('POST', 'wxpay/notify', function(WP_REST_Request $request) {
        $mchid = '1680193707';
        $appid = 'wx73dde0301ce105ac';
        $apiKey = 'aCDWhjfkEp3cPrdIkWQRFbtCdKRVb804';
        $wechatPay = new WechatPayService($mchid, $appid, $apiKey);
        $result = $wechatPay->notify();
        
        // error_log(print_r($result, true));

        if ($result) {
            $res_order_id = (string)$result->out_trade_no;
            $res_total_fee = (int)$result->total_fee;

            $orders = new Orders();
            $order = $orders->getByOrderID($res_order_id);

            if (
                $order
                && (int)($order['total_fee']*100) === (int)$result->total_fee
                && $order['status'] !== 'paid'
            ) {
                $orders->update($order['order_id'], [
                    'transaction_id' => (string)$result->transaction_id,
                    'status' => 'paid',
                ]);
            }
        }
    });
});