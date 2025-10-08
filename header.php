<!DOCTYPE html>
<html <?php language_attributes(); ?> class="bg-[--color-bg] text-[--color-text]">
<head>
    <script>
        // 预先应用主题模式，防止深色模式首次渲染闪烁
        (function(){
            try {
                const mode = localStorage.getItem('theme-mode') || 'auto';
                let isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (mode === 'light') isDark = false; else if (mode === 'dark') isDark = true;
                const root = document.documentElement;
                isDark ? root.classList.add('dark') : root.classList.remove('dark');
                root.style.colorScheme = isDark ? 'dark' : 'light';
            } catch(e) {}
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="sticky top-0 z-50">
    <nav class="flex items-center gap-4 bg-[--color-box-bg] h-[--header-height] shadow-md">
        <ul class="flex items-center gap-4 mx-auto">
            <li><a href="/">首页</a></li>
            <li><a href="/auth">登录</a></li>
            <li><a href="/auth?action=register">注册</a></li>
            <li><a href="/auth?action=reset">找回密码</a></li>
            <li><a href="/1">文章</a></li>
        </ul>
    </nav>
</header>

<div>
    <main class="container mx-auto px-4 py-8">
