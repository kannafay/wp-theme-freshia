<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * 获取 Vite 开发服务器状态
 */
function is_vite_dev_running(): bool {
    $connection = @fsockopen('localhost', 777, $errno, $errstr, 0.05);
    if (is_resource($connection)) {
        fclose($connection);
        return true;
    }
    return false;
}

/**
 * 引入样式和脚本
 */
add_action('wp_enqueue_scripts', function () {
    // 主题版本号
    $theme_version = wp_get_theme()->get('Version');

    if (!is_vite_dev_running()) {
        // 生产模式
        wp_enqueue_style(
            'main-style',
            get_template_directory_uri() . '/assets/css/style.min.css',
            [],
            filemtime(get_template_directory() . '/assets/css/style.min.css'),
        );
        wp_enqueue_script(
            'main-script',
            get_template_directory_uri() . '/assets/js/main.min.js',
            [],
            filemtime(get_template_directory() . '/assets/js/main.min.js'),
            false,
        );
    } else {
        // Vite Dev 模式
        wp_enqueue_script('vite-client', 'http://localhost:777/@vite/client', [], null, false);
        wp_enqueue_script('main-script', 'http://localhost:777/src/main.js', ['vite-client'], null, false);
    }

    // 本地化脚本，传递 PHP 数据到 JavaScript
    wp_localize_script('main-script', 'wp', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'rest_url' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
        'theme_version' => $theme_version,
        'theme_options' => [],
    ]);

    // 将指定脚本类型改为 module
    add_filter('script_loader_tag', function ($tag, $handle, $src) {
        $scripts = [
            'main-script',
            'vite-client'
        ];
        if (in_array($handle, $scripts)) {
            return str_replace('type="text/javascript"', 'type="module"', $tag);
        }
        return $tag;
    }, 10, 3);
});

/**
 * 主题支持
 */
add_action('after_setup_theme', function () {
    // 文章缩略图
    add_theme_support('post-thumbnails');

    // 自动添加标题标签
    add_theme_support('title-tag');

    // 禁用前端管理员工具栏
    add_filter('show_admin_bar', '__return_false');
});

/**
 * 设置网站标题
 */
add_filter('document_title_parts', function ($title) {
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
});

if (!function_exists('wp_send_404')) {
    /**
     * 输出 404 页面并终止执行
     *
     * @param string|null $message 自定义提示内容（会传入到 404 模板）
     */
    function wp_send_404($message = null) {
        global $wp_query;

        // 标记为 404
        $wp_query->set_404();

        // 设置响应头
        status_header(404);
        nocache_headers();

        // 将自定义信息传入模板
        if ($message !== null) {
            // 供 404.php 模板中调用
            set_query_var('wp_404_message', $message);
        }

        // 调用主题的 404 模板
        include get_404_template();

        // 停止执行
        exit;
    }
}