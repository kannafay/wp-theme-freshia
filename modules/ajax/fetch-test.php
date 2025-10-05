<?php

add_action('wp_ajax_get_action', 'handle_get_request');
add_action('wp_ajax_post_action', 'handle_post_request');
add_action('wp_ajax_nopriv_get_action', 'handle_get_request');
add_action('wp_ajax_nopriv_post_action', 'handle_post_request');

function handle_get_request() {
    if (!wp_verify_nonce($_GET['_wpnonce'], 'freshia_nonce')) {
        wp_send_json_error(['message' => 'GET request failed']);
    }

    wp_send_json_success(['message' => 'GET request received']);
}

function handle_post_request() {
    if (!wp_verify_nonce($_POST['_wpnonce'], 'freshia_nonce')) {
        wp_send_json_error(['message' => 'POST request failed']);
    }

    wp_send_json_success(['message' => 'POST request received']);
}