<?php

// 阻止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 1. 注册定时事件
add_action('wp', function () {
    if (!wp_next_scheduled('auto_cancel_orders_hook')) {
        wp_schedule_event(time(), 'fifteen_minutes', 'auto_cancel_orders_hook');
    }
});

// 2. 添加自定义间隔（15分钟）
add_filter('cron_schedules', function ($schedules) {
    $schedules['fifteen_minutes'] = [
        'interval' => 1,
        'display' => '每1秒',
    ];
    return $schedules;
});

// 3. 回调函数：取消超时订单
add_action('auto_cancel_orders_hook', function () {
    global $wpdb;
    $table = $wpdb->prefix . 'freshia_orders'; // 你的订单表名 -2 hours
    $two_hours_ago = date('Y-m-d H:i:s', strtotime('-30 seconds', current_time('timestamp')));

    $orders = $wpdb->get_results($wpdb->prepare(
        "SELECT id FROM $table WHERE status = %s AND created_at <= %s AND deleted_at IS NULL",
        'pending', // 未支付状态
        $two_hours_ago
    ));

    foreach ($orders as $order) {
        $wpdb->update($table, ['status' => 'cancelled'], ['id' => $order->id]);
        // 这里可以加发送邮件、库存回滚等操作
    }
});
