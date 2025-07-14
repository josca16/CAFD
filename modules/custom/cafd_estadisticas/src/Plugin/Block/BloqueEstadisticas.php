<?php

namespace Drupal\cafd_estadisticas\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;

/**
 * Provides a 'Estadísticas' Block.
 *
 * @Block(
 *   id = "bloque_estadisticas",
 *   admin_label = @Translation("Estadísticas"),
 *   category = @Translation("CAFD")
 * )
 */
class BloqueEstadisticas extends BlockBase {
  public function build() {
    $count = 0;

    try {
      $connection = Database::getConnection();
      $query = $connection->select('federaciones', 'f')
        ->fields('f', ['id']);
      $count = $query->countQuery()->execute()->fetchField();
    } catch (\Exception $e) {
      // Opcional: Log error o ignorar si la tabla no existe.
    }

    return [
      '#markup' => '
        <section class="bloque-estadisticas">
          <div><strong><span class="estadistica-numero">' . $count . '</span></strong><br><div class="estadistica-texto">Federaciones deportivas</div></div>
          <div><strong><span class="estadistica-numero">13,000</span></strong><br><div class="estadistica-texto">Clubes deportivos</div></div>
          <div><strong><span class="estadistica-numero">600,000</span></strong><br><div class="estadistica-texto">Deportistas</div></div>
        </section>
      ',
      '#attached' => [
        'library' => [
          'cafd_estadisticas/cafd_estadisticas_styles',
          'cafd_estadisticas/countup',
        ],
      ],
      '#cache' => [
        'max-age' => 3600,
        'tags' => ['federacion_list'],
      ],
    ];
  }
}
