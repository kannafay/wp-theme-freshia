<?php get_header(); ?>

<section class="">
    <?php
    get_part('card-list', [
        'images' => [
            'https://random.api.mikus.ink?1',
            'https://random.api.mikus.ink?2',
            'https://random.api.mikus.ink?3',
            'https://random.api.mikus.ink?4',
            'https://random.api.mikus.ink?5',
            'https://random.api.mikus.ink?6',
            'https://random.api.mikus.ink?7',
            'https://random.api.mikus.ink?8',
        ],
    ]);
    ?>
</section>

<?php get_footer(); ?>