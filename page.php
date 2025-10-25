<?php get_header(); ?>

<div class="prose fade-up">
    <?php the_content(); ?>
</div>

<script>
    document.querySelectorAll('.prose > *').forEach(function (el) {
        el.classList.add('scroll-fade-up');
    });
</script>

<?php get_footer(); ?>