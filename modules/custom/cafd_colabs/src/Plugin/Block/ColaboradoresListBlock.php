<?php

namespace Drupal\cafd_colabs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;

/**
 * Provides a 'Colaboradores List' Block.
 *
 * @Block(
 *   id = "colaboradores_block",
 *   admin_label = @Translation("Colaboradores List"),
 * )
 */
class ColaboradoresListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $connection = \Drupal::database();
    $query = $connection->select('colaboradores', 'c')
      ->fields('c', ['id', 'nombre', 'logo_fid']);
    $results = $query->execute()->fetchAll();

    $colaboradores = [];
    foreach ($results as $colaborador) {
      $file = File::load($colaborador->logo_fid);
      $url = $file ? \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri()) : NULL;

      $colaboradores[] = [
        'nombre' => $colaborador->nombre,
        'logo_url' => $url,
      ];
    }

    return [
      '#theme' => 'colaboradores_list', // Nombre del twig sin extensión
      '#colaboradores' => $colaboradores,
      '#attached' => [
        'library' => [
          'cafd_colabs/colaboradores_styles', // Tu CSS para estilos
        ],
      ],
      '#cache' => [
        'contexts' => ['user'],
        'tags' => ['colaboradores_list'],
        'max-age' => 0,
      ],
      
    ];
  }

}
