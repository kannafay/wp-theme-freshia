<?php get_header(); ?>

<h1>首页</h1>

<section>这是首页</section>

<div>
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

<!-- <pre>
    <?php
        var_dump(get_option('freshia_options'));
    ?>
</pre> -->

<?php get_footer(); ?>
