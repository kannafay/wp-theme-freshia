<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * 加载 Composer 自动加载文件
 */
if (file_exists($autoload_file = wp_normalize_path(get_theme_file_path('vendor/autoload.php')))) {
    require_once $autoload_file;
}

/**
 * 引入指定目录下的所有 PHP 文件
 */
if (file_exists($include_file = wp_normalize_path(get_theme_file_path('includes/_init.php')))) {
    require_once $include_file;

    $folders = [
        'includes',
        'modules',
    ];

    foreach ($folders as $directory) {
        freshia_require_directory($directory);
    }
}
