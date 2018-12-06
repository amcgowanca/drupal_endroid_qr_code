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
        $this->logoWidth = (int)$logoWidth;
        $this->logoSize = (int)$logoSize;
        $this->logoMargin = (int)$logoMargin;
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
        $logoPath = drupal_get_path('module', 'endroid_qr_code') . '/images/Jugaad-logo.jpg';
        $qrCode = new QrCode($string);
        $qrCode->setLogoPath($logoPath);
        $qrCode->setLogoWidth((NULL !== $this->logoWidth) ? $this->logoWidth : 150);
        $qrCode->setSize((NULL !== $this->logoSize) ? $this->logoSize : 600);
        $qrCode->setMargin((NULL !== $this->logoMargin) ? $this->logoMargin : 10);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
        $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(true);
        $response = new QrCodeResponse($qrCode); // Create a response object
        if ($response->isOk()) {
            $im = imagecreatefromstring($response->getContent());  // Generate Image.
            ob_start(); // Begin capturing the byte stream.
            imagejpeg($im);
            imagedestroy($im); // Clean up the image resource.  
        }
    }

}
