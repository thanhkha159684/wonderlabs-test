<?php

namespace Drupal\wonderlabs_product\Plugin\Block;

use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Drupal\node\NodeInterface;


/**
 * Provides an qr code block on product page.
 *
 * @Block(
 *   id = "wonderlabs_product_qr_code",
 *   admin_label = @Translation("Product QR code"),
 *   category = @Translation("Wonderlabs Product")
 * )
 */
class ProductQrCodeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new ProductQrCodeBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface && $node->bundle()=='product' && !empty($node->field_link->value)) {
      $renderer = new ImageRenderer(
          new RendererStyle(400),
          new ImagickImageBackEnd()
      );
      $writer = new Writer($renderer);
      $contents = $writer->writeString($node->field_link->value);

      $base64  = base64_encode($contents);

      $qr_code = [
        '#theme' => 'image',
        '#uri' => 'data:image/png;base64,'. $base64,
      ];
      return $qr_code;

    }
  }

}
