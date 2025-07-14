<?php

namespace Drupal\cafd_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Nuestros Valores' Block.
 *
 * @Block(
 *   id = "bloque_valores",
 *   admin_label = @Translation("Nuestros Valores"),
 *   category = @Translation("CAFD")
 * )
 */
class BloqueValores extends BlockBase {
  public function build() {
    return [
  '#markup' => '
    <section class="bloque-valores">
      <h2>Nuestros Valores</h2>
      <ul class="value-icons">
        <li>
          <img src="/sites/default/files/images/Logos_Valores/1.png" alt="Profesionalidad">
          <span>PROFESIONALIDAD</span>
        </li>
        <li>
          <img src="/sites/default/files/images/Logos_Valores/2.png" alt="Formación">
          <span>FORMACIÓN</span>
        </li>
        <li>
          <img src="/sites/default/files/images/Logos_Valores/4.png" alt="Transparencia">
          <span>TRANSPARENCIA</span>
        </li>
        <li>
          <img src="/sites/default/files/images/Logos_Valores/3.png" alt="Seguridad">
          <span>SEGURIDAD</span>
        </li>
        <li>
          <img src="/sites/default/files/images/Logos_Valores/5.png" alt="Compromiso">
          <span>COMPROMISO</span>
        </li>
      </ul>
    </section>
  ',

      '#cache' => ['max-age' => 0],
    ];
  }
}
