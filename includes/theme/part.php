<?php

// 阻止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 安全高效地引入 theme 的组件模板
 *
 * @param string $part 组件名，对应 template-parts 目录下的文件
 * @param array $args 可选参数，会在组件内以变量形式可用
 */
if (!function_exists('get_part')) {
    function get_part(string $part, array $args = []) {
        // 防止路径穿越
        $part = preg_replace('/[^a-z0-9_\-\/]/i', '', $part);

        // template-parts 目录下的文件
        $file = "template-parts/{$part}.php";

        // locate_template 支持子主题和父主题优先级
        $template = locate_template($file, false, false);

        if ($template) {
            // 提取参数为变量
            if (!empty($args)) {
                extract($args, EXTR_SKIP); // 避免覆盖已有变量
            }

            include $template;
        } else {
            // 可选：开发环境提示文件不存在
            if (WP_DEBUG) {
                echo "<!-- Template part '{$part}' not found -->";
            }
        }
    }
}
