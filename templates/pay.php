<?php
/**
 * Template Name: 支付页面
 */
?>

<?php
    $order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';
    $order_name = isset($_GET['pname']) ? sanitize_text_field($_GET['pname']) : '支付测试';
    $order_price = isset($_GET['price']) ? floatval($_GET['price']) : 0.01;
    $order_status = 'pending';

    if (!$order_id && !isset($_GET['new'])) {
        wp_die('无效的订单号，输入参数new可创建新订单');
    }

    $orders = new Orders();
    $orders_data = $orders->getByOrderID($order_id);
    if ($order_id && $orders_data) {
        $order_name = $orders_data['order_name'];
        $order_price = $orders_data['total_fee'];
        $order_status = $orders_data['status'];
    } else {
        $order_id = uniqid('Freshia_');
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
    $order = $orders->getByOrderID($order_id);
?>

<?php get_header(); ?>
<?php if ($order_status === 'pending' && $qrcode): ?>
<div id="qrcode">
    <h2>当前订单为微信支付，请使用微信扫码支付。支付后请等待几秒钟。</h2>
    <img src="<?php echo $qrcode; ?>" alt="">
</div>
<?php endif; ?>

<h2>订单号：<?=$order_id?></h2>
<h2>订单名称：<?=$order_name?></h2>
<h2>订单金额：<?=$order_price?>元</h2>
<h2>订单状态：<span id="order-status" class="<?php echo $order_status == 'pending' ? 'text-orange-500' : ($order_status == 'paid' ? 'text-green-500' : 'text-red-500'); ?>" data-order-id="<?=$order_id?>"><?=$order_status?></span></h2>
<h2>订单创建日期：<?=$order['created_at']?></h2>
<br>
<hr>
<br>
<a href="/pay?new">创建新订单</a>
<br>
<br>
<hr>
<br>
<h2>订单列表：</h2>
<ul id="order-list">
    <?php
        foreach ($orders->all() as $order) {
            $class = $order['order_id'] === $order_id ? 'text-blue-500' : '';
            echo '<li data-order-id="' . $order['order_id'] . '" class="'. $class .'">';
            echo '订单号：<a href="/pay?order_id=' . $order['order_id'] . '">' . $order['order_id'] . '</a> | ';
            echo '名称：' . $order['order_name'] . ' | ';
            echo '金额：' . $order['total_fee'] . '元 | ';
            echo '状态：<span>' . $order['status'] . '</span> | ';
            echo '创建时间：' . $order['created_at'];
            echo '</li>';
        }
    ?>
</ul>

<?php get_footer(); ?>