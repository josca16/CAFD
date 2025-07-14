<?php

namespace Drupal\cafd_ui\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "cafd_ui_navbar_block",
 *   admin_label = @Translation("Barra de Navegación Personalizada"),
 * )
 */
class NavbarBlock extends BlockBase {

  public function build() {
    $links = [
      ['url' => '/', 'title' => 'Inicio'],
      ['url' => '/servicios', 'title' => 'Servicios'],
      ['url' => '/federaciones', 'title' => 'Federaciones'],
      ['url' => '/formacion', 'title' => 'Formación'],
      ['url' => '/equipo', 'title' => 'Equipo'],
      ['url' => 'https://cafdclubes.com/', 'title' => 'Atención a Clubes', 'external' => TRUE],
      ['url' => '/transparencia', 'title' => 'Transparencia'],
      ['url' => '/politica-privacidad', 'title' => 'Protección de Datos'],
    ];

    return [
      '#theme' => 'navbar',
      '#links' => $links,
      '#attached' => [
        'library' => ['cafd_ui/navbar'],
      ],
    ];
  }
}
