<?php get_header(); ?>

<h1>首页</h1>

<section>这是首页</section>

<div class="dark:bg-red-500">
    <form>
        <input type="file" name="images[]" id="" multiple />
    </form>
</div>

<div>
    <button class="request" id="ajax">WP AJAX 请求测试</button>
</div>

<div>
    <button class="request" id="rest">WP REST API 请求测试</button>
</div>

<?php
    var_dump(date('Y-m-d H:i:s', strtotime('-2 hours', current_time('timestamp'))));
?>

<?php get_footer(); ?>
