<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <script>
        const option = {
            pjax: true,
        };
    </script>
</head>
<body <?php body_class(); ?>>

<header class="sticky top-0 bg-white z-50 shadow-md">
    <nav>
        <li><a href="/">Home</a></li>
        <li><a href="/auth">Login</a></li>
    </nav>
</header>

<div>
    <main id="pjax-container">
