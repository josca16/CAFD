<?php

namespace Drupal\cafd_formacion\Controller;

use Drupal\Core\Controller\ControllerBase;

class CafdFormacionController extends ControllerBase {
  public function page() {
    $theme_path = \Drupal::service('extension.list.theme')->getPath('custom_theme');
    return [
      '#theme' => 'page__formacion',
      '#directory' => '/' . $theme_path,
    ];
  }
}
