<?php

namespace Drupal\endroid_qr_code\Controller;

use Drupal\endroid_qr_code\Response\QRImageResponse;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller which generates the image from defined settings.
 */
class QRImageGeneratorController extends ControllerBase {

  /**
   * Request stack
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * QRImageGeneratorController constructor.
   *
   * @param RequestStack $request
   */
  public function __construct(RequestStack $request) {
    $this->request = $request;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

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
   * Will return the response for external url.
   *
   * @return QRImageResponse
   */
  public function withUrl() {
    $externalUrl = $this->request->getCurrentRequest()->query->get('path');
    return new QRImageResponse($externalUrl, $this->getLogoWidth(), $this->getLogoSize(), $this->getLogoMargin());
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
