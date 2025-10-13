<?php

// 阻止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 简化引入函数
 */
// 直接输出主题目录下 assets 目录的资源URL
function the_asset($path) {
    echo get_template_directory_uri() . '/assets/' . $path;
}
// 返回主题目录下 assets 目录的资源URL
function get_the_asset($path) {
    return get_template_directory_uri() . '/assets/' . $path;
}


/**
 * 递归加载指定目录下的所有 PHP 文件
 */
// 递归收集目录下的 PHP 文件
if (!function_exists('freshia_collect_php_files')) {
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
                        && strtolower($current->getFilename()) !== 'index.php'
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
}
// 递归加载目录下的 PHP 文件
if (!function_exists('freshia_require_directory')) {
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
}