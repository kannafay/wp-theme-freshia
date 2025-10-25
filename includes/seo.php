<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * meta 标签生成
 */
add_action('wp_head', function () {
    $title = wp_get_document_title();
    $description = get_bloginfo('description') ?: '欢迎访问我的网站！'; // 默认描述
    $keywords = ''; // 可根据需要添加关键词
    $image = get_the_asset('images/og-image.png'); // 默认图片
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    switch (true) {
        // 首页或前页
        case is_home() || is_front_page():
            $url = home_url();
            break;

        // 文章或页面
        case is_singular():
            global $post;
            if (has_excerpt($post->ID)) {
                $description = get_the_excerpt();
            } elseif (!empty($post->post_content)) {
                $description = wp_trim_words(strip_tags($post->post_content), 55);
            }
            $image = get_the_post_thumbnail_url($post->ID) ?: $image;
            break;

        // 分类或标签页
        case is_category() || is_tag():
            $term = get_queried_object();
            $description = $term->description ?: $description;
            break;
    }

    // 输出 meta
    echo '<meta name="description" content="' . esc_attr($description) . '">';
    if ($keywords) {
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '">';
    }

    // Open Graph (社交分享用)
    echo '<meta property="og:title" content="' . esc_attr($title) . '">';
    echo '<meta property="og:description" content="' . esc_attr($description) . '">';
    echo '<meta property="og:image" content="' . esc_url($image) . '">';
    echo '<meta property="og:url" content="' . esc_url($url) . '">';
}, 0);