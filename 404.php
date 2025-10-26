<?php
$message = get_query_var('wp_404_message');
?>

<?php get_header(); ?>

<section>
    <h1 class="text-2xl font-bold text-center"><?php echo $message ?: '404 | Not Found'; ?></h1>
</section>

<?php get_footer(); ?>