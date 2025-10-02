<?php

/**
 * 引入主题脚本和样式
 */
add_action('wp_enqueue_scripts', 'freshia_scripts');
function freshia_scripts() {
    // 引入tailwindcss
    wp_enqueue_style(
        'tailwindcss',
        get_template_directory_uri() . '/assets/css/output.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/output.css'),
    );

    // 引入主样式
    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . '/assets/css/main.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/main.css'),
    );

    // 引入 WordPress 内置 jQuery
    wp_enqueue_script('jquery');

    // 引入预先执行js文件
    wp_enqueue_script(
        'head-script',
        get_template_directory_uri() . '/assets/js/main-head.js',
        array('jquery'),
        filemtime(get_template_directory() . '/assets/js/main-head.js'),
        false // false 表示放在 <head>
    );

    // 引入gsap库
    wp_enqueue_script(
        'freshia-gsap',
        get_template_directory_uri() . '/assets/js/gsap.min.js',
        array(),
        null,
        true
    );

    // 引入pjax库
    wp_enqueue_script(
        'freshia-pjax',
        get_template_directory_uri() . '/assets/js/pjax.min.js',
        array(),
        null,
        true
    );

    // 本地化脚本，传递PHP数据到JS
    wp_localize_script('main-script', 'freshia', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'site_url' => get_site_url(),
        'nonce'    => wp_create_nonce('freshia_nonce'),
        'is_logged_in' => is_user_logged_in(),
        'user_id' => get_current_user_id(),
        'current_user_can_admin' => current_user_can('manage_options'),
    ));
}

// 引入主js文件
add_action('wp_footer', function() {
    $url = get_template_directory_uri() . '/assets/js/main.js';
    $ver = filemtime(get_template_directory() . '/assets/js/main.js');
    echo '<script type="module" src="' . esc_url($url) . '?ver=' . $ver . '"></script>';
}, 999);

/**
 * 移除前端的 jQuery Migrate，避免控制台提示
 */
add_action('wp_default_scripts', 'freshia_disable_jquery_migrate');
function freshia_disable_jquery_migrate(WP_Scripts $scripts): void {
    if (is_admin()) {
        return;
    }

    if (!isset($scripts->registered['jquery'])) {
        return;
    }

    $jquery = $scripts->registered['jquery'];

    if (empty($jquery->deps)) {
        return;
    }

    $jquery->deps = array_values(array_diff($jquery->deps, ['jquery-migrate']));
}

