<?php

/**
 * 标题设置
 */
add_filter('document_title_parts', 'freshia_title');
function freshia_title($title) {
    // 认证页面
    if (is_page_template('templates/auth.php')) {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'register':
                $title['title'] = '注册';
                break;
            case 'reset':
                $title['title'] = '重置密码';
                break;
            default:
                $title['title'] = '登录';
                break;
        }
    }
    
    return $title;
}