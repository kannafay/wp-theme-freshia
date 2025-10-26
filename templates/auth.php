<?php
/**
 * Template Name: 认证页面
 */
?>

<?php
// 验证 action 参数是否合法
$action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'login';
$allowed_actions = ['login', 'register', 'reset'];

if (!in_array($action, $allowed_actions, true)) {
    wp_send_404();
}
?>

<?php get_header(); ?>

<section class="container mx-auto px-6">
    <?php echo $action; ?>
</section>

<?php get_footer(); ?>