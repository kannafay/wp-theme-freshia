<?php

/**
 * 防止直接访问
 */
if (!defined('ABSPATH')) {
    exit;
}

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
 * 递归加载模组文件
 */
function freshia_collect_php_files(string $directory): array {
    $files = [];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            static function (SplFileInfo $current): bool {
                if ($current->isDir()) {
                    return !str_starts_with($current->getFilename(), '.');
                }

                return $current->isFile()
                    && strtolower($current->getExtension()) === 'php'
                    && !str_starts_with($current->getFilename(), '.');
            }
        ),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        /** @var SplFileInfo $file */
        $files[] = wp_normalize_path($file->getPathname());
    }

    sort($files, SORT_NATURAL | SORT_FLAG_CASE);

    return $files;
}

function freshia_require_directory(string $relative_path): void {
    static $loaded = [];

    $base_path = wp_normalize_path(get_theme_file_path($relative_path));
    $theme_root = wp_normalize_path(get_theme_file_path());

    if (!$base_path || !is_dir($base_path) || isset($loaded[$base_path])) {
        return;
    }

    if (!str_starts_with($base_path, $theme_root)) {
        return;
    }

    $loaded[$base_path] = true;

    foreach (freshia_collect_php_files($base_path) as $file) {
        if (str_starts_with($file, $theme_root)) {
            require_once $file;
        }
    }
}

foreach (['includes', 'modules'] as $directory) {
    freshia_require_directory($directory);
}
