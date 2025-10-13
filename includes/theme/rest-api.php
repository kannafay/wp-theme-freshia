<?php

// 阻止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册自定义的 REST API 路由
 * @param string $method 请求方法 GET, POST, PUT, PATCH, DELETE
 * @param string $route 路由路径
 * @param callable $callback 回调函数，接收 WP_REST_Request 对象作为参数
 * @param bool $permission 是否需要权限验证，默认不需要
 */

if (!function_exists('freshia_register_rest_route')) {
    function freshia_register_rest_route($methods, $route, $callback, $params = [], $permission = true) {
        $namespace = 'freshia/v1';

        $allowed_methods = explode(', ', WP_REST_Server::ALLMETHODS);

        $http_method = in_array(strtoupper($methods), $allowed_methods) ? strtoupper($methods) : 'GET';

        $args = [
            'methods' => $http_method,
            'params' => is_array($params) ? $params : [],
            'permission' => $permission,
        ];

        register_rest_route($namespace, $route, [
            'methods' => $args['methods'],
            'callback' => function(WP_REST_Request $request) use ($callback) {
                try {
                    $result = call_user_func($callback, $request);
                    return wp_send_json_success($result);
                } catch (Exception $e) {
                    return wp_send_json_error([
                        'message' => $e->getMessage(),
                    ]);
                }
            },
            'args' => $args['params'],
            'permission_callback' => function() use ($args) {
                if ($args['permission']) {
                    return true;
                }
                
                return false;
            },
        ]);
    }
}
