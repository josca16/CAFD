<?php

namespace Drupal\cafd_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Ventajas CAFD' Block.
 *
 * @Block(
 *   id = "bloque_ventajas_cafd",
 *   admin_label = @Translation("Ventajas CAFD"),
 *   category = @Translation("CAFD")
 * )
 */
class BloqueVentajasCafd extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
'#markup' => '
  <div class="bloque-ventajas">
    <div class="ventajas-contenido">
      <div class="ventajas-texto">
        <h2>Ventajas CAFD</h2>
        <p>Disfruta de las ventajas Cafd solo por estar federado o ser miembro de la Confederación Andaluza de Federaciones Deportivas.</p>
          <a href="/contacto">
            <img src="/sites/default/files/images/ventajas.png" alt="Banner ventajas CAFD">
          </a>
      </div>
      <div class="ventajas-imagen">
        <img src="/sites/default/files/images/trofeo.png" alt="Trofeo CAFD">
      </div>
    </div>
  </div>
',

      '#cache' => ['max-age' => 0],
    ];
  }
}
