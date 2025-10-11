<?php

/**
 * 自动创建必要的页面
 */
add_action('after_switch_theme', 'freshia_create_pages');
function freshia_create_pages() {
    $pages = [
        ['title' => '认证', 'slug' => 'auth', 'template' => 'templates/auth.php'],
        ['title' => '支付', 'slug' => 'pay', 'template' => 'templates/pay.php'],
    ];

    foreach ($pages as $page) {
        // 检查模板文件是否存在
        if (!file_exists(get_theme_file_path($page['template']))) {
            continue;
        }

        // 检查页面是否已存在
        if (get_page_by_path($page['slug'])) {
            continue;
        }

        // 创建页面
        $new_page_id = wp_insert_post([
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ]);

        if (!is_wp_error($new_page_id) && $new_page_id) {
            // 设置页面模板
            update_post_meta($new_page_id, '_wp_page_template', $page['template']);
        }
    }
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
        user_id BIGINT(20) UNSIGNED NOT NULL COMMENT '用户ID',
        name VARCHAR(100) NOT NULL COMMENT '商品名称',
        amount DECIMAL(10,2) NOT NULL COMMENT '订单金额',
        status VARCHAR(20) NOT NULL DEFAULT 'pending' COMMENT '订单状态：pending（待支付）、paid（已支付）、cancelled（已取消）',
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
