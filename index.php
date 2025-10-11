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

<pre>
    <?php
        $orders = new Orders();
        $order = $orders->getByUserID(1);
        var_dump($order);
        
        // var_dump(get_option('freshia_options'));
        // var_dump($_SERVER['SERVER_ADDR']);
    ?>
</pre>

<?php get_footer(); ?>
