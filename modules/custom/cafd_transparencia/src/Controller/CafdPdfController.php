<?php

namespace Drupal\cafd_transparencia\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Controlador para mostrar la lista de PDFs.
 */
class CafdPdfController extends ControllerBase {

  public function listado() {
    $block_manager = \Drupal::service('plugin.manager.block');
    $plugin_block = $block_manager->createInstance('cafd_pdf_block', []);
  
    $render_array['pdf_list_block'] = $plugin_block->build();
  
    // Añadir el formulario para admins
    $current_user = \Drupal::currentUser();
    if ($current_user->hasPermission('administer site configuration') || in_array('administrator', $current_user->getRoles())) {
      $form = \Drupal::formBuilder()->getForm('Drupal\cafd_transparencia\Form\CafdPdfForm');
      $render_array['admin_pdf_form'] = $form;
    }
  
    return $render_array;
  }

  public function deletePdf($pdf_id) {
    // Obtener el formulario de confirmación
    $form = \Drupal::formBuilder()->getForm(
      'Drupal\cafd_transparencia\Form\ConfirmDeleteForm',
      $pdf_id
    );
  
    // Para peticiones AJAX, devolver el formulario modal
    if (\Drupal::request()->isXmlHttpRequest()) {
      $response = new AjaxResponse();
      $response->addCommand(new ReplaceCommand('#pdf-list-wrapper', $form));
      return $response;
    }
  
    // Para peticiones normales, renderizar la página completa
    return $form;
  }
  
  /**
   * Obtiene la tabla de PDFs actualizada con todos los estilos necesarios.
   */
  private function getUpdatedTable() {
    $form = \Drupal::formBuilder()->getForm('Drupal\cafd_transparencia\Form\CafdPdfForm');
    
    // Construir el contenedor con todos los assets necesarios
    $build = [
      '#type' => 'container',
      '#attributes' => ['id' => 'pdf-list-wrapper'],
      'table' => $form['pdf_list'],
      '#attached' => [
        'library' => [
          'cafd_transparencia/cafd_pdf_styles', // Tus estilos personalizados
          'core/drupal.ajax' // Asegura comportamientos AJAX de Drupal
        ],
      ],
    ];
    
    // Mantener las librerías adicionales del formulario
    if (isset($form['#attached']['library'])) {
      $build['#attached']['library'] = array_merge(
        $build['#attached']['library'],
        $form['#attached']['library']
      );
    }
    
    return $build;
  }
}