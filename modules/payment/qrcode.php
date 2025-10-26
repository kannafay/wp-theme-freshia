<?php

// 阻止直接访问
defined('ABSPATH') || exit;

/**
 * 二维码生成
 */
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if (!function_exists('generate_qrcode')) {
    /**
     * 生成二维码并返回Data URI
     *
     * @param string $data 要编码的数据
     * @param int $size 二维码大小（像素）
     * @return string Data URI格式的二维码图片
     */
    function generate_qrcode(string $data, int $size = 300): string {
        $writer = new PngWriter();
        $qrCode = new QrCode(
            data: $data,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Low,
            size: $size,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );
        return $writer->write($qrCode)->getDataUri();
    }
}
