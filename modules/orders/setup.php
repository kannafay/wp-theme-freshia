<?php

// 阻止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 自动创建订单表（freshia_orders）
 */
add_action('after_switch_theme', 'freshia_create_orders_table');
function freshia_create_orders_table() {
    global $wpdb;

    // 动态获取表名
    $table_name = $wpdb->prefix . 'freshia_orders';

    // 获取字符集和排序规则
    $charset_collate = $wpdb->get_charset_collate();

    // 检查表是否已经存在
    $table_exists = $wpdb->get_var($wpdb->prepare(
        "SHOW TABLES LIKE %s",
        $table_name
    ));

    // 如果表已经存在，直接返回（避免重复建表）
    if ($table_exists === $table_name) {
        return;
    }

    // 注意 dbDelta 对 SQL 语法敏感，列定义和索引最后不能多余逗号
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_id VARCHAR(50) NOT NULL COMMENT '订单号',
        order_name VARCHAR(100) NOT NULL COMMENT '订单名称',
        user_id BIGINT(20) UNSIGNED NOT NULL COMMENT '支付者ID',
        post_id BIGINT(20) UNSIGNED NOT NULL COMMENT '关联的文章ID',
        fee_type VARCHAR(10) NOT NULL DEFAULT 'CNY' COMMENT '货币类型，默认CNY',
        total_fee DECIMAL(10,2) NOT NULL COMMENT '订单金额，单位元',
        payment_method VARCHAR(20) NOT NULL DEFAULT 'wechat' COMMENT '支付方式：wechat（微信支付）、alipay（支付宝支付）',
        status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '订单状态：pending（待支付）、paid（已支付）、cancelled（已取消）',
        transaction_id VARCHAR(100) DEFAULT NULL COMMENT '第三方支付交易号',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
        deleted_at DATETIME DEFAULT NULL COMMENT '软删除时间',
        PRIMARY KEY  (id),
        UNIQUE KEY order_id (order_id)
    ) $charset_collate;";

    // 引入 dbDelta 函数
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    // 执行建表
    dbDelta($sql);
}