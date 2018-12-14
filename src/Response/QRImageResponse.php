<?php

namespace Drupal\endroid_qr_code\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

/**
 * Response which is returned as the QR code.
 *
 * @package Drupal\endroid_qr_code\Response
 */
class QRImageResponse extends Response {

    /**
     * @var data
     */
    private $data;
    /**
     * @var logoWidth
     */
    private $logoWidth;

    /**
     * @var logoSize
     */
    private $logoSize;

    /**
     * @var logoMargin
     */
    private $logoMargin;

    /**
     * Recourse with generated image.
     *
     * @var resource
     */
    protected $image;

    /**
     * {@inheritdoc}
     */
    public function __construct($content, $logoWidth, $logoSize, $logoMargin, $status = 200, $headers = []) {
        parent::__construct(NULL, $status, $headers);
        $this->data = $content;
        $this->logoWidth = (NULL !== $logoWidth) ? (int)$logoWidth : 150;
        $this->logoSize = (NULL !== $logoSize) ? (int)$logoSize : 600;
        $this->logoMargin = (NULL !== $logoMargin) ? (int)$logoMargin : 10;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(Request $request) {
        return parent::prepare($request);
    }

    /**
     * {@inheritdoc}
     */
    public function sendHeaders() {
        $this->headers->set('content-type', 'image/jpeg');
        return parent::sendHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function sendContent() {
      $this->generateQRCode($this->data);
    }

    /**
     * Function generate QR code for the string or URL.
     * 
     * @param string $string
     * @return boolean
     */
    private function generateQRCode(string $string = '') {
        $logoPath = drupal_get_path('module', 'endroid_qr_code') . '/images/symfony.png';
        $qrCode = new QrCode($string);
        $qrCode->setLogoPath($logoPath);
        $qrCode->setLogoWidth($this->logoWidth);
        $qrCode->setSize($this->logoSize);
        $qrCode->setMargin($this->logoMargin);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);
        $response = new QrCodeResponse($qrCode); // Create a response object
        if ($response->isOk()) {
            $im = imagecreatefromstring($response->getContent());  // Generate Image.
            ob_start(); // Begin capturing the byte stream.
            imagejpeg($im);
            imagedestroy($im); // Clean up the image resource.  
        }
    }

}
