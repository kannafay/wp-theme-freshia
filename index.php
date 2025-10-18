<?php get_header(); ?>

<!-- <h1>首页</h1>

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
</div> -->

<section class="mb-4">
    <?php
    // get_part('card', [
    //     'image' => 'https://random.api.mikus.ink?1'
    // ]);
    ?>
</section>

<section>
    <?php
    // get_part('card-list', [
    //     'images' => [
    //         'https://random.api.mikus.ink?2',
    //         'https://random.api.mikus.ink?3',
    //         'https://random.api.mikus.ink?4',
    //         'https://random.api.mikus.ink?5',
    //         'https://random.api.mikus.ink?6',
    //         'https://random.api.mikus.ink?7',
    //     ],
    // ]);
    
    var_dump(get_template_directory());

    ?>
</section>

<?php get_footer(); ?>