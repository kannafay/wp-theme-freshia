<?php

// 测试 AJAX POST 请求，接收 JSON 数据
add_action('wp_ajax_ajax_test', 'handle_ajax_test');
add_action('wp_ajax_nopriv_ajax_test', 'handle_ajax_test');
function handle_ajax_test() {
    if (!check_ajax_referer('ajax_nonce', '_wpnonce', false)) {
        wp_send_json_error([
            'message' => 'Invalid nonce',
            'code' => 403
        ]);
    }

    // $json = json_decode(file_get_contents('php://input'), true);
    // wp_send_json_success($json);

    $images = isset($_FILES['images']) ? $_FILES['images'] : null;
    if ($images) {
        wp_send_json_success($images);
    }

    wp_send_json_success($_REQUEST);
}
