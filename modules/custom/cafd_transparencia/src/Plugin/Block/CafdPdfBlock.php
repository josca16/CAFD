<?php

namespace Drupal\cafd_transparencia\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;

/**
 * Provides a block to display PDFs grouped by category as cards.
 *
 * @Block(
 *   id = "cafd_pdf_block",
 *   admin_label = @Translation("PDFs agrupados por categoría (bloques)"),
 * )
 */
class CafdPdfBlock extends BlockBase {

  public function build() {
    $connection = \Drupal::database();
    $current_user = \Drupal::currentUser();
    $is_admin = in_array('administrator', $current_user->getRoles());

    $query = $connection->select('cafd_pdfs', 'c')
    ->fields('c', ['id', 'title', 'fid', 'categoria', 'peso', 'visible', 'agrupable', 'nombre_grupo']);

    if (!$is_admin) {
    // Solo filtra por visibles si NO es admin
    $query->condition('visible', 1);
}

$results = $query
  ->orderBy('categoria', 'ASC')
  ->orderBy('peso', 'ASC')
  ->execute()
  ->fetchAll();

$file_url_generator = \Drupal::service('file_url_generator');

$grouped = [];

    foreach ($results as $record) {
      $file = File::load($record->fid);
      $url = $file ? $file_url_generator->generateAbsoluteString($file->getFileUri()) : '';

      // Definir grupo de categoría
      $grupo = match ($record->categoria) {
        'Estructura Organizativa' => 'estructura',
        'Datos Económicos'       => 'economicos',
        'Normativa'              => 'normativa',
        'Actividades'            => 'actividades',
        default                  => 'otros',
      };

      // Asegurar que el array tiene el título correcto
      $grouped[$grupo]['title'] = $record->categoria;

      // Si el PDF es agrupable, lo añadimos a 'agrupados'
      if ($record->agrupable && !empty($record->nombre_grupo)) {
        $grouped[$grupo]['agrupados'][$record->nombre_grupo][] = [
          'title' => $record->title,
          'url' => $url,
          'id' => $record->id, 
        ];
      } else {
        // Si no tiene grupo, va a 'sueltos'
        $grouped[$grupo]['sueltos'][] = [
          'title' => $record->title,
          'url' => $url,
          'id' => $record->id, 
        ];
      }
    }

    if (empty($grouped)) {
      return [
        '#markup' => $this->t('No hay PDFs disponibles.'),
        '#cache' => [
          'tags' => ['cafd_pdfs_list'],
        ],
      ];
    }

    return [
      '#theme' => 'block--cafd-pdf-block',
      '#categorias' => $grouped,
      '#attached' => [
        'library' => [
          'cafd_transparencia/cafd_pdf_styles',
        ],
      ],
      '#cache' => [
        'tags' => ['cafd_pdfs_list'], // Invalidación de caché cuando se actualizan PDFs
        'contexts' => ['url'], // Diferente caché por URL
      ],
    ];
  }

  public function getCacheMaxAge() {
    return 0; 
  }

  public function getCacheTags() {
    return ['cafd_pdfs_list'];
  }

  public function getCacheContexts() {
    return ['url'];
  }
}