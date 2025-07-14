<?php

namespace Drupal\junta_directiva\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Junta Directiva - Inicio' block.
 *
 * @Block(
 *   id = "junta_directiva_inicio_block",
 *   admin_label = @Translation("Junta Directiva - Inicio"),
 * )
 */
class JuntaDirectivaInicioBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  public function build() {
    $conn = Database::getConnection();
    $result = $conn->select('junta_directiva', 'j')
      ->fields('j', ['id', 'nombre', 'cargo', 'federacion', 'imagen_jun', 'imagen_fid'])
      ->condition('visibilidad', ['inicio', 'ambos'], 'IN')
      ->range(0, 5)
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
      '#theme' => 'junta_directiva_inicio',
      '#miembros' => $miembros,
      '#imagen_equipo' => '/themes/custom/custom_theme/images/equipo.png',
      '#attached' => [
        'library' => [
          'junta_directiva/bloque_inicio',
          'junta_directiva/swiper_inicio',
        ],
      ],
      '#cache' => [
        'tags' => ['junta_directiva_list'],
      ],
    ];
  }

}
