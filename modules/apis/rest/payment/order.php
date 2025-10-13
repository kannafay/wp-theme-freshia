<?php

add_action('rest_api_init', function() {
    // 创建订单
    freshia_register_rest_route('POST', 'order/create', function(WP_REST_Request $request) {
        return $request->get_paramss();
    });

    // 获取订单状态
    freshia_register_rest_route('GET', 'order/status', function(WP_REST_Request $request) {
        $order_id = $request->get_param('order_id');
        $orders = new Orders();
        $order = $orders->getByOrderID($order_id);

        if (!$order) {
            return [
                'status' => 'not_found',
                'message' => '订单不存在',
            ];
        }

        // if ($order['user_id'] != get_current_user_id()) {
        //     return [
        //         'status' => 'forbidden',
        //         'message' => '无权访问此订单',
        //     ];
        // }
        
        return [
            'status' => $order['status'],
            'message' => '订单状态获取成功',
        ];
        
        
    }, [
        'order_id' => [
            'required' => true,
            'type' => 'string',
            'description' => '订单号',
        ],
    ], );
});