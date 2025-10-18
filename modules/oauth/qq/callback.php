<?php

// 验证码校验
session_start();
if (!isset($_GET['state']) || !isset($_SESSION['qq_oauth_state']) || $_GET['state'] !== $_SESSION['qq_oauth_state']) {
  exit("state 校验失败，可能存在 CSRF 攻击！");
}
unset($_SESSION['qq_oauth_state']);

// 获取登录授权码
if (!isset($_GET['code'])) {
  exit("授权码不存在，请先登录。");
}

$code = $_GET['code'];

// 获取 access_token
$app_id = '101973685';
$app_key = '1e63a48a23654f8ea6a76a408cc3e789';
$redirect_uri = 'https://onll.cn/oauth/qq/callback.php';
$token_url = "https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=$app_id&client_secret=$app_key&code=$code&redirect_uri=" . urlencode($redirect_uri);

// 发送请求获取 access_token
$token_response = file_get_contents($token_url);
// var_dump($token_response);

parse_str($token_response, $token_info);
if (!isset($token_info['access_token'])) {
  exit("access_token 获取失败：$token_response");
}
$access_token = $token_info['access_token'];

// 获取 openid
$openid_url = "https://graph.qq.com/oauth2.0/me?access_token={$access_token}";
$openid_response = file_get_contents($openid_url);
// var_dump($openid_response);

$matches = [];
if (preg_match('/"openid"\s*:\s*"(.+?)"/', $openid_response, $matches)) {
  $openid = $matches[1];
} else {
  exit("openid 获取失败：$openid_response");
}
// var_dump($openid);

$user_info_url = "https://graph.qq.com/user/get_user_info?access_token=$access_token&oauth_consumer_key=$app_id&openid=$openid";
$user_info = json_decode(file_get_contents($user_info_url), true);

echo '<pre>';
var_dump($user_info);
echo '</pre>';







