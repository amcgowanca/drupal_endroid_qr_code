<?php

namespace Drupal\endroid_qr_code\Controller;

use Drupal\endroid_qr_code\Response\QRImageResponse;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller which generates the image from defined settings.
 */
class QRImageGeneratorController extends ControllerBase{

    /**
     * Main method that throw ImageResponse object to generate image.
     *
     * @return QRImageResponse
     *   Make a QR image in JPEG format.
     */
    public function image($content) {
      return new QRImageResponse($content, $this->getLogoWidth(), $this->getLogoSize(), $this->getLogoMargin());
    }

    /**
     * LogoSize.
     *
     * @return int
     */
    public function getLogoSize() {
      return $this->config('endroid_qr_code.settings')->get('set_size');
    }

    /**
     * LogoWidth.
     *
     * @return int
     */
    public function getLogoWidth() {
      return $this->config('endroid_qr_code.settings')->get('logo_width');
    }

    /**
     * LogoMargin.
     *
     * @return int
     */
    public function getLogoMargin() {
      return $this->config('endroid_qr_code.settings')->get('set_margin');
    }

}
