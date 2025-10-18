<?php

add_action('rest_api_init', function () {

    freshia_register_rest_route('GET', 'oauth/qq', fn(WP_REST_Request $request) => $request->get_params());
});