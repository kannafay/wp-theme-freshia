<?php
/**
 * Template Name: 支付页面
 */
?>

<?php
    $orders = new Orders();
    $order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';
    $order_name = isset($_GET['pname']) ? sanitize_text_field($_GET['pname']) : '支付测试';
    $order_price = isset($_GET['price']) ? floatval($_GET['price']) : 0.01;
    $order_status = 'pending';

    if (!$order_id) {
        wp_die('订单号不能为空');
    }

    $orders_data = $orders->getByOrderID($order_id);
    if ($order_id && $orders_data) {
        $order_name = $orders_data['order_name'];
        $order_price = $orders_data['total_fee'];
        $order_status = $orders_data['status'];
    } else {
        $order_id = uniqid('FRESHIA_');
        $orders->create([
            'order_id' => $order_id,
            'order_name' => $order_name,
            'user_id' => get_current_user_id(),
            'post_id' => 1,
            'fee_type' => 'CNY',
            'total_fee' => $order_price,
            'payment_method' => 'wechat',
            'status' => 'pending',
        ]);
    }

    $mchid = '1680193707';
    $appid = 'wx73dde0301ce105ac';
    $apiKey = 'aCDWhjfkEp3cPrdIkWQRFbtCdKRVb804';
    $wechatPay = new WechatPayService($mchid, $appid, $apiKey);
    $outTradeNo = $order_id;
    $payAmount = $order_price;
    $orderName = $order_name;
    $notifyUrl = 'http://freshia.onll.cn/wp-json/freshia/v1/wxpay/notify';
    $payTime = time();
    $resArr = $wechatPay->createJsBizPackage($payAmount, $outTradeNo, $orderName, $notifyUrl, $payTime);
    $qrcode = isset($resArr->code_url) ? generate_qr_code($resArr->code_url) : '';
?>

<?php get_header(); ?>
<?php if ($order_status === 'pending' && $qrcode): ?>
<div id="qrcode">
    <h2>当前订单为微信支付，请使用微信扫码支付</h2>
    <img src="<?php echo $qrcode; ?>" alt="">
</div>
<?php elseif ($order_status === 'paid') : ?>
    <h2>当前订单已支付</h2>
<?php else: ?>
    <h2>当前订单已取消</h2>
<?php endif; ?>

<h2>订单号：<?=$order_id?></h2>
<h2>订单名称：<?=$order_name?></h2>
<h2>订单金额：<?=$order_price?>元</h2>
<h2>订单状态：<span id="order-status" data-order-id="<?=$order_id?>"><?=$order_status?></span></h2>
<br>
<hr>
<br>
<h2>订单列表：</h2>
<ul id="order-list">
    <?php
        foreach ($orders->all() as $order) {
            echo '<li data-order-id="' . $order['order_id'] . '">';
            echo '订单号：' . $order['order_id'] . ' | ';
            echo '名称：' . $order['order_name'] . ' | ';
            echo '金额：' . $order['total_fee'] . '元 | ';
            echo '状态：<span>' . $order['status'] . '</span>';
            echo '</li>';
        }
    ?>
</ul>

<?php get_footer(); ?>