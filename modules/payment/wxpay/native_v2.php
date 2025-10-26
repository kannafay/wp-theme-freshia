<?php
/**
 * 微信支付服务类（NATIVE V2版本）
 * 
 * 公共方法：
 * - createOrder 创建支付订单
 * - queryOrder 查询订单
 * - closeOrder 关闭订单
 * - refundOrder 退款订单
 * 
 * 辅助方法：
 * - getSign 获取签名（MD5）
 * - generateOutTradeNo 生成商户订单号
 * - generateOutRefundNo 生成商户退款单号
 */
class WxPayNativeV2 {
    protected $mchid;
    protected $appid;
    protected $apiKey;

    /**
     * 构造函数
     * @param string $mchid 商户号
     * @param string $appid 应用ID
     * @param string $key API密钥
     */
    public function __construct(string $mchid, string $appid, string $key) {
        $this->mchid = $mchid;
        $this->appid = $appid;
        $this->apiKey = $key;
    }

    /**
     * 创建支付订单
     * @param string $orderName 订单名称
     * @param string $outTradeNo 唯一订单号
     * @param float $totalFee 金额（单位：元）
     * @param string $notifyUrl 通知地址（不带参数）
     * @return Exception|SimpleXMLElement
     */
    public function createOrder(string $orderName, string $outTradeNo, float $totalFee, string $notifyUrl): Exception|SimpleXMLElement {
        $unified = [
            'attach' => 'pay',
            'body' => $orderName,
            'notify_url' => $notifyUrl,
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1',
            'total_fee' => floatval($totalFee) * 100,
            'trade_type' => 'NATIVE',
        ];

        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

        return $this->requsetOrder($url, $unified);
    }

    /**
     * 查询订单
     * @param string $outTradeNo 商户订单号
     * @param string $transactionID 微信订单号
     * @return Exception|SimpleXMLElement
     */
    public function queryOrder(string|null $transactionID = null, string|null $outTradeNo = null): Exception|SimpleXMLElement {
        if ($transactionID === null && $outTradeNo === null) {
            return new Exception('必须提供微信订单号(transactionID)或商户订单号(outTradeNo)');
        }
        if ($transactionID !== null && $outTradeNo !== null) {
            return new Exception('不能同时提供商户订单号和微信订单号，请选择其中一个');
        }

        $query = [];

        if ($outTradeNo !== null) {
            $query['out_trade_no'] = $outTradeNo;
        } else {
            $query['transaction_id'] = $transactionID;
        }

        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';

        return $this->requsetOrder($url, $query);
    }

    /**
     * 关闭订单
     * @param string $outTradeNo 商户订单号
     * @return Exception|SimpleXMLElement
     */
    public function closeOrder(string $outTradeNo): Exception|SimpleXMLElement {
        $close = [
            'out_trade_no' => $outTradeNo,
        ];

        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';

        return $this->requsetOrder($url, $close);
    }

    /**
     * 退款订单
     * @param string $transactionID 微信订单号
     * @param float|null $refundFee 退款金额（单位：元），默认全部退款
     * @return Exception|SimpleXMLElement
     */
    public function refundOrder(string $transactionID, float|null $refundFee = null): Exception|SimpleXMLElement {
        $response = $this->queryOrder($transactionID);
        if ($response instanceof Exception) {
            return $response;
        }

        $refundFeeInt = floatval($refundFee) * 100;
        if ($refundFee !== null && $refundFeeInt > (int) $response->total_fee) {
            return new Exception('退款金额不能大于订单总金额');
        }

        $refund = [
            'transaction_id' => $transactionID,
            'out_refund_no' => self::generateOutRefundNo(),
            'total_fee' => (int) $response->total_fee,
            'refund_fee' => (int) $response->total_fee,
        ];

        if ($refundFee !== null) {
            $refund['refund_fee'] = floatval($refundFee) * 100;
        }

        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

        return $this->requsetOrder($url, $refund, [
            CURLOPT_SSLCERTTYPE => 'PEM',
            CURLOPT_SSLCERT => getcwd() . '/cert/apiclient_cert.pem',
            CURLOPT_SSLKEYTYPE => 'PEM',
            CURLOPT_SSLKEY => getcwd() . '/cert/apiclient_key.pem',
        ]);
    }

    /**
     * 发起请求订单
     * @param string $url 请求url
     * @param array $config 配置参数
     * @param mixed $options 请求配置项
     * @return Exception|SimpleXMLElement
     */
    protected function requsetOrder(string $url, array $config, mixed $options = []): Exception|SimpleXMLElement {
        $config['mch_id'] = $this->mchid;
        $config['appid'] = $this->appid;
        $config['nonce_str'] = self::createNonceStr();
        $config['sign'] = self::getSign($config, $this->apiKey);

        // 发起请求
        $response = self::curlPost($url, self::arrayToXml($config), $options);
        if ($response === false) {
            return new Exception('请求失败');
        }

        // 解析XML
        $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($result === false) {
            return new Exception('XML解析错误');
        }

        // 检查返回状态
        if ($result->return_code != 'SUCCESS') {
            return new Exception((string) $result->return_msg);
        }
        if ($result->result_code != 'SUCCESS') {
            return new Exception((string) $result->err_code_des);
        }

        // 验证签名
        $sign = self::getSign($result, $this->apiKey);
        if ($sign !== (string) $result->sign) {
            return new Exception('无效签名');
        }

        return $result;
    }

    /**
     * 处理支付结果通知
     * @return bool|Exception|SimpleXMLElement
     */
    public function notify(): bool|Exception|SimpleXMLElement {
        $xmlData = file_get_contents('php://input');

        // 解析XML
        $result = simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($result === false) {
            return new Exception('XML解析错误');
        }

        // 检查返回状态
        if ($result->return_code != 'SUCCESS') {
            return new Exception((string) $result->return_msg);
        }
        if ($result->result_code != 'SUCCESS') {
            return new Exception((string) $result->err_code_des);
        }

        // 验证签名
        $sign = self::getSign($result, $this->apiKey);
        if ($sign !== (string) $result->sign) {
            return new Exception('无效签名');
        }

        echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';

        return $result;
    }

    /**
     * 获取签名（MD5）
     * @param array|SimpleXMLElement $params 数组或XML对象
     * @param string $key API密钥
     * @return string
     */
    public static function getSign(array|SimpleXMLElement $params, string $key): string {
        // 解析XML对象为数组，移除签名字段
        if ($params instanceof SimpleXMLElement) {
            $params = json_decode(json_encode($params), true);
            unset($params['sign']);
        }

        // 生成签名
        $paramsStr = self::formatQueryParaMap($params);
        $sign = sprintf('%s&key=%s', $paramsStr, $key);
        return strtoupper(md5($sign));
    }

    /**
     * 格式化参数，签名过程需要使用
     * @param array $paraMap 参数数组
     * @return string
     */
    protected static function formatQueryParaMap(array $paraMap): string {
        ksort($paraMap, SORT_STRING);
        $paraMapStr = http_build_query($paraMap, '', '&');
        return urldecode($paraMapStr);
    }

    /**
     * 生成商户订单号
     * @return string
     */
    public static function generateOutTradeNo(): string {
        $datetime = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
        return $datetime->format('YmdHisu');
    }

    /**
     * 生成商户退款单号
     * @return string
     */
    public static function generateOutRefundNo(): string {
        $datetime = new DateTime('now', new DateTimeZone('Asia/Shanghai'));
        return 'RF' . $datetime->format('YmdHisu');
    }

    /**
     * 创建随机字符串
     * @return string
     */
    protected static function createNonceStr(): string {
        return md5(uniqid(rand(), true));
    }

    /**
     * 将数组转换为XML
     * @param array $arr 参数数组
     * @return string
     */
    protected static function arrayToXml(array $arr): string {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            $xml .= "<{$key}><![CDATA[{$val}]]></{$key}>";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * post请求
     * @param string $url 请求url
     * @param mixed $data XML数据
     * @param mixed $options curl选项
     * @return bool|string
     */
    protected static function curlPost(string $url, mixed $data, mixed $options = []): bool|string {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_TIMEOUT => 30,
            // CURLOPT_SSL_VERIFYPEER => false, // 信任任何证书
            // CURLOPT_SSL_VERIFYHOST => false, // 检查证书中是否设置域名
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $response;
    }
}
