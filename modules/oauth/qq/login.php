<?php

// 基本配置
$app_id = '101973685';
$redirect_uri = urlencode('https://onll.cn/oauth/qq/callback.php');

// 启动会话并生成状态码
session_start();
$state = bin2hex(random_bytes(16));
$_SESSION['qq_oauth_state'] = $state;

// 生成登录链接
$login_url = "https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=$app_id&redirect_uri=$redirect_uri&state=$state";

// 重定向到登录链接
header("Location: $login_url");
exit;
