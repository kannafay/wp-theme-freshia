<?php

/**
 * SEO 优化
 */
add_action('wp_head', 'freshia_seo_meta', 0);
function freshia_seo_meta() {
    $title = wp_get_document_title();
    $description = get_bloginfo('description');
    $keywords = ''; // 可根据需要添加关键词
    $image = get_template_directory_uri() . '/default-image.jpg';
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    switch(true) {
        case is_home() || is_front_page():
            // 首页描述保持默认
            $url = home_url();
            break;
        case is_singular():
            global $post;
            if (has_excerpt($post->ID)) {
                $description = get_the_excerpt();
            } elseif (!empty($post->post_content)) {
                $description = wp_trim_words(strip_tags($post->post_content), 55);
            }
            $image = get_the_post_thumbnail_url($post->ID) ?: $image;
            break;
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
}
