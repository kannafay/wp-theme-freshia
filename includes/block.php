<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * 注册区块分类
 */
add_filter('block_categories_all', function ($categories) {
    $freshia_category = [
        'slug' => 'freshia',
        'title' => 'Freshia主题区块',
        'icon' => null,
    ];

    // 放到最前面
    array_unshift($categories, $freshia_category);

    // 放到最后面
    // $categories[] = $freshia_category;

    return $categories;
}, 10, 2);

/**
 * 注册区块
 */
add_action('init', function () {
    if (!function_exists('register_block_type')) {
        return;
    }

    // 扫描 blocks 目录下的所有区块并注册
    $blocks_dir = get_template_directory() . '/blocks/build';
    foreach (glob((string) $blocks_dir . '/*', GLOB_ONLYDIR) as $block_folder) {
        if (file_exists((string) $block_folder . '/block.json')) {
            register_block_type($block_folder);
        }
    }
});
