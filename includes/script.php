<?php

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

    // 引入前置脚本
    wp_enqueue_script(
        'freshia-gsap',
        get_template_directory_uri() . '/assets/js/gsap.min.js',
        array(),
        null,
        true
    );
    wp_enqueue_script(
        'freshia-pjax',
        get_template_directory_uri() . '/assets/js/pjax.min.js',
        array(),
        null,
        true
    );


    // 引入主脚本
    wp_enqueue_script(
        'main-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array('freshia-gsap', 'freshia-pjax'),
        filemtime(get_template_directory() . '/assets/js/main.js'),
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