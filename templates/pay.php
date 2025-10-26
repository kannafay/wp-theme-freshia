<?php
/**
 * Template Name: 支付页面
 */
?>

<?php
$mchid = '1680193707';
$appid = 'wx73dde0301ce105ac';
$apiKey = 'aCDWhjfkEp3cPrdIkWQRFbtCdKRVb804';

$wxPay = new WxPayNativeV2($mchid, $appid, $apiKey);
$result = $wxPay->queryOrder(null, 'test11111');
?>

<?php get_header(); ?>



<section>
    <h2>支付界面</h2>
    <?php
    var_dump($result);
    ?>
</section>

<?php get_footer(); ?>