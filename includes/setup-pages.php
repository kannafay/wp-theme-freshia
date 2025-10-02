<?php
/**
 * 自动创建必要的页面
 */

add_action('after_switch_theme', 'freshia_create_pages');
function freshia_create_pages() {
    $pages = [
        ['title' => '认证', 'slug' => 'auth', 'template' => 'templates/auth.php'],
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