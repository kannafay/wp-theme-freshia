<?php

/**
 * 主题支持
 */
add_action('after_setup_theme', 'freshia_setup');
function freshia_setup() {
    // 文章缩略图
    add_theme_support('post-thumbnails');

    // 自动添加标题标签
    add_theme_support('title-tag');

    // 禁用前端管理员工具栏
    add_filter('show_admin_bar', '__return_false');
}

/**
 * 简化引入函数
 */
function the_asset($path) {
    echo get_template_directory_uri() . '/' . $path;
}
function get_the_asset($path) {
    return get_template_directory_uri() . '/' . $path;
}