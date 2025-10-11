<?php
/**
 * Template Name: 支付界面
 */
?>

<?php
    
    $orders = new Orders();
    $order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : uniqid('order_');

    // if (!$order_id) {
    //     wp_die('订单号不能为空');
    // }
    // $order = $orders->getByOrderID($order_id);
    // if (!$order) {
    //     wp_die('订单不存在');
    // }
    // if ($order['status'] !== 'pending') {
    //     wp_die('订单状态异常');
    // }
    // $product_name = $order['name'];
    // $product_price = $order['amount'];


    $mchid = '1680193707';
    $appid = 'wx73dde0301ce105ac';
    $apiKey = 'aCDWhjfkEp3cPrdIkWQRFbtCdKRVb804';
    $wechatPay = new WechatPayService($mchid, $appid, $apiKey);
    $outTradeNo = $order_id;
    $payAmount = 0.01;
    $orderName = '测试商品';
    $notifyUrl = 'https://freshia.onll.cn/wp-json/freshia/v1/wxpay/notify';
    $payTime = time();
    $resArr = $wechatPay->createJsBizPackage($payAmount, $outTradeNo, $orderName, $notifyUrl, $payTime);

    $qrcode = $resArr->code_url ? generate_qr_code($resArr->code_url) : '';
?>

<?php get_header(); ?>

<?php
    echo '<pre>';
    var_dump($resArr);
    echo '</pre>';
?>

<img src="<?php echo $qrcode; ?>" alt="">

<?php get_footer(); ?>