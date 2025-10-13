<?php

add_action('rest_api_init', function() {
    $is_admin = current_user_can('manage_options');
    $is_logged = is_user_logged_in();
    $can_upload = current_user_can('upload_files');

    // 测试 REST API 请求
    freshia_register_rest_route('POST', 'rest_test', function(WP_REST_Request $request) {
        // return $request->get_params();

        $images = isset($_FILES['images']) ? $_FILES['images'] : null;
        if ($images) {
            return $images;
        }
        
        return $request->get_params();
    }, [], $can_upload);

    // 测试带参数的 REST API 请求
    freshia_register_rest_route('POST', 'rest_args', function(WP_REST_Request $request) {
        return $request->get_params();
    }, [
        'id' => [
            'required' => true,
            'type' => 'integer',
            'description' => 'ID 参数，必须为正整数',
            'validate_callback' => function($param, $request, $key) {
                return intval($param) > 0;
            },
        ],
        'page' => [
            'required' => false,
            'type' => 'integer',
            'default' => 1,
            'description' => '页码，默认为 1',
            'validate_callback' => function($param, $request, $key) {
                return intval($param) > 0;
            },
        ]
    ]);
});