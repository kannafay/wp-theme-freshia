<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color">
    <script>
        const mode = localStorage.getItem('color-mode') || 'auto';
        let isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (mode === 'light') isDark = false; else if (mode === 'dark') isDark = true;
        const root = document.documentElement;
        isDark ? root.classList.add('dark') : root.classList.remove('dark');
        root.style.colorScheme = isDark ? 'dark' : 'light';
    </script>
    <?php wp_head(); ?>
</head>

<body>
    <header class="sticky top-0 z-50 bg-[var(--color-box-bg)] w-full h-[var(--header-height)]">
        <nav class="container mx-auto px-6 flex items-center gap-4 h-full">
            <a href="/">首页</a>
            <div class="group relative inline-block">
                <a href="/1" data-turbo-frame="content">文章</a>
                <div
                    class="bg-white absolute top-full left-1/2 -translate-x-1/2 opacity-0 invisible group-hover:opacity-100 group-hover:visible shadow-md px-4 py-2">
                    <a href="/sample-page?pname=kanna" class="text-nowrap">示例页面</a>
                </div>
            </div>
            <a href="/sample-page">示例页面</a>
            <a href="/auth">登录</a>
            <a href="/auth?action=register">注册</a>
            <a href="/auth?action=reset">找回密码</a>
            <a href="/auth?action=resets">404</a>
            <a href="/pay">支付</a>
        </nav>
    </header>

    <div class="w-full min-h-[calc(100vh_-_var(--header-height))] flex flex-col justify-between py-4">
        <main id="swup">