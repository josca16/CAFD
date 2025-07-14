<?php

namespace Drupal\cafd_blocks\Controller;

use Drupal\Core\Controller\ControllerBase;

class FrontPageController extends ControllerBase {
  public function content() {
    return [
      '#title' => 'Inicio',
      '#markup' => '',
    ];
  }
}
