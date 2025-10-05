<?php

/**
 * 注册区块分类
 */
add_filter('block_categories_all', 'freshia_register_block_category', 10, 2);
function freshia_register_block_category($categories) {
    $freshia_category = [
        'slug'  => 'freshia',
        'title' => __('Freshia主题区块', 'freshia'),
        'icon'  => null,
    ];

    // 放到最前面
    array_unshift($categories, $freshia_category);

    // 放到最后面
    // $categories[] = $freshia_category;

    return $categories;
}

/**
 * 注册区块
 */
add_action('init', 'freshia_register_blocks');
function freshia_register_blocks() {
    if (!function_exists('register_block_type')) {
        return;
    }

    // 扫描 blocks 目录下的所有区块并注册
    $blocks_dir = get_template_directory() . '/blocks/build';
    foreach (glob($blocks_dir . '/*', GLOB_ONLYDIR) as $block_folder) {
        if (file_exists($block_folder . '/block.json')) {
            register_block_type($block_folder);
        }
    }
}