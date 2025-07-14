<?php

namespace Drupal\cafd_federaciones\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\file\Entity\File;

/**
 * Proporciona un bloque para mostrar el formulario de federaciones.
 *
 * @Block(
 *   id = "federacion_form_block",
 *   admin_label = @Translation("Formulario de Federación"),
 * )
 */
class FederacionFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\cafd_federaciones\Form\FederacionForm');
  
    $connection = \Drupal::database();
    $query = $connection->select('federaciones', 'f')
      ->fields('f');
    $result = $query->execute();
  
    $federaciones = [];
  
    foreach ($result as $record) {
      $logo_url = NULL;
  
      if (!empty($record->logo_fid)) {
        $file = \Drupal\file\Entity\File::load($record->logo_fid);
        if ($file) {
          $logo_url = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
        }
      }
  
      $federaciones[] = [
        'nombre' => $record->nombre,
        'direccion' => $record->direccion,
        'telefono' => $record->telefono,
        'email' => $record->email,
        'weburl' => $record->weburl,
        'logo_url' => $logo_url,
      ];
    }
  
    return [
      '#theme' => 'federacion_form_block',
      '#federaciones' => $federaciones,
      '#form' => $form,
      '#attached' => [
        'library' => [
          'cafd_federaciones/formulario',
        ],
      ],
    ];
  }
}