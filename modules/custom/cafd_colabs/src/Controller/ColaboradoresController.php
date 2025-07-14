<?php

namespace Drupal\cafd_colabs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

class ColaboradoresController extends ControllerBase {

  public function listar() {
    $conn = Database::getConnection();
    $result = $conn->select('colaboradores', 'c')
      ->fields('c', ['id', 'nombre', 'logo_fid'])
      ->execute();

    $colaboradores = [];
    foreach ($result as $record) {
      $logo_url = NULL;
      if ($record->logo_fid) {
        $file = File::load($record->logo_fid);
        if ($file) {
          $logo_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
        }
      }
      $colaboradores[] = [
        'id' => $record->id,            // para usar en links de editar/eliminar
        'nombre' => $record->nombre,
        'logo_url' => $logo_url,
      ];
    }

    return [
      '#theme' => 'colaboradores_listado-custom',
      '#colaboradores' => $colaboradores,
      '#title' => $this->t('Listado de colaboradores'),
    ];
  }

}
// End of file
