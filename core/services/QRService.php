<?php

namespace app\core\services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Yii;

class QRService
{
    public const QR_CODE_SIZE = 300;
    public const QR_CODE_MARGIN = 10;
    public const QR_CODE_DIR = 'qrcodes';
    public const QR_CODE_IMAGE_EXT = '.png';

    /**
     * Генерация QR-кода в виде изображения
     * @param string $short_url - короткая ссылка
     * @param string $token - уникальный токен
     * @return string - путь к изображению с QR-кодом
     * @throws \RuntimeException
     */
    public function generateQRCode(string $short_url, string $token): string
    {
        $qrCode = new QrCode($short_url);
        $qrCode->setSize(self::QR_CODE_SIZE);
        $qrCode->setMargin(self::QR_CODE_MARGIN);

        $filePath = Yii::getAlias('@webroot/'.self::QR_CODE_DIR.'/') . $token . self::QR_CODE_IMAGE_EXT;
        $writer = new PngWriter();

        try {
            $result = $writer->write($qrCode);
            $result->saveToFile($filePath);
        } catch (\Exception $e) {
            Yii::error('Ошибка генерации QR-кода: ' . $e->getMessage());
            throw new \RuntimeException('Ошибка при создании QR-кода.');
        }

        return Yii::$app->request->hostInfo.Yii::getAlias('@web/'.self::QR_CODE_DIR .'/'. $token . self::QR_CODE_IMAGE_EXT);
    }

}
