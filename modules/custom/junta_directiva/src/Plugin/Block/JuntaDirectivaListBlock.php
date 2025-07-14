<?php

namespace Drupal\junta_directiva\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Junta Directiva' resumen block.
 *
 * @Block(
 *   id = "junta_directiva_list_block",
 *   admin_label = @Translation("Junta Directiva - Resumen"),
 * )
 */
class JuntaDirectivaListBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $routeMatch;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $route_match;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }

  public function build() {
    $conn = Database::getConnection();
    $result = $conn->select('junta_directiva', 'j')
      ->fields('j', ['id', 'nombre', 'cargo', 'federacion', 'imagen_jun', 'imagen_fid'])
      ->condition('visibilidad', ['junta', 'ambos'], 'IN')
      ->execute();

    $miembros = [];

    foreach ($result as $record) {
      $imagen_url = NULL;

      if (!empty($record->imagen_fid)) {
        $file = \Drupal\file\Entity\File::load($record->imagen_fid);
        if ($file) {
          $imagen_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
        }
      } elseif (!empty($record->imagen_jun)) {
        $imagen_url = $record->imagen_jun;
      }

      $miembros[] = [
        'id' => $record->id,
        'nombre' => $record->nombre,
        'cargo' => $record->cargo,
        'federacion' => $record->federacion,
        'imagen' => $imagen_url,
      ];
    }

    return [
      '#theme' => 'junta_directiva_block',
      '#miembros' => $miembros,
      '#attached' => [
        'library' => [
          'junta_directiva/bloque_directiva',
        ],
      ],
      '#cache' => [
        'tags' => ['junta_directiva_list'],
      ],
    ];
  }

}
