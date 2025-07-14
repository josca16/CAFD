<?php

namespace Drupal\cafd_federaciones\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Query\PagerSelectExtender;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FederacionController extends ControllerBase {

  /**
   * Lista todas las federaciones con paginación y filtro.
   */
  public function list(Request $request) {
    // Verificar si el usuario tiene permisos de administración
    $current_user = \Drupal::currentUser();
    
    // Si no es administrador, renderizar solo el bloque
    if (!$current_user->hasPermission('administer federaciones')) {
      return [
        '#type' => 'block',
        '#plugin_id' => 'federacion_list_block',
      ];
    }

    // Obtener el parámetro de búsqueda
    $search = $request->query->get('nombre');
    
    $conn = Database::getConnection();
    $query = $conn->select('federaciones', 'f')
      ->fields('f', ['id', 'nombre', 'direccion', 'telefono', 'email', 'weburl', 'logo_fid']);
    
    // Aplicar filtro si existe término de búsqueda
    if (!empty($search)) {
      $query->condition('f.nombre', '%' . $conn->escapeLike($search) . '%', 'LIKE');
    }
    
    // Aplicar paginación
    $pager_query = $query->extend(PagerSelectExtender::class);
    $pager_query->limit(8); // 5 elementos por página
    $result = $pager_query->execute();
  
    $rows = [];
    foreach ($result as $record) {
      // Crear enlaces para editar y eliminar
      $edit_url = Url::fromRoute('cafd_federaciones.edit', ['id' => $record->id]);
      $delete_url = Url::fromRoute('cafd_federaciones.delete', ['id' => $record->id]);
      
      $rows[] = [
        'data' => [
          $record->id,
          $record->nombre,
          $record->direccion,
          $record->telefono,
          $record->email,
          $record->weburl,
          [
            'data' => [
              '#type' => 'link',
              '#title' => 'Editar',
              '#url' => $edit_url,
            ],
          ],
          [
            'data' => [
              '#type' => 'link',
              '#title' => 'Eliminar',
              '#url' => $delete_url,
            ],
          ],
        ],
      ];
    }
  
    $header = ['ID', 'Nombre', 'Dirección', 'Teléfono', 'Email', 'Web URL', 'Editar', 'Eliminar'];
  
    // Usar la plantilla para el formulario de búsqueda
    $search_form = [
      '#theme' => 'federacion_admin_search',
      '#search' => $search,
      '#route_name' => 'cafd_federaciones.list',
    ];
  
    // Añadir botón para crear nueva federación
    $build = [
      'search_form' => $search_form,
      'add_link' => [
        '#type' => 'link',
        '#title' => 'Añadir federación',
        '#url' => Url::fromRoute('cafd_federaciones.form'),
        '#attributes' => ['class' => ['button', 'button--primary']],
        '#prefix' => '<div style="margin-bottom: 20px;">',
        '#suffix' => '</div>',
      ],
      'table_container' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['federaciones-admin-table-container']],
        'table' => [
          '#type' => 'table',
          '#header' => $header,
          '#rows' => $rows,
          '#empty' => 'No hay federaciones registradas.',
          '#attributes' => ['class' => ['federaciones-admin-table']],
        ],
      ],
      'pager' => [
        '#type' => 'pager',
        '#quantity' => 5,
        '#parameters' => !empty($search) ? ['nombre' => $search] : [],
      ],
      '#attached' => [
        'library' => [
          'core/drupal.pager',
          'cafd_federaciones/federaciones_admin', // Modificado
        ],
      ],
      '#cache' => [
        'max-age' => 0,
        'contexts' => ['url.query_args'],
      ],
    ];
  
    return $build;
  }
  /**
   * Editar una federación existente.
   */
  public function edit($id) {
    $conn = Database::getConnection();
    $query = $conn->select('federaciones', 'f')
      ->fields('f')
      ->condition('id', $id)
      ->execute();
    
    $federacion = $query->fetchAssoc();
    
    if (!$federacion) {
      $this->messenger()->addError('Federación no encontrada.');
      return new RedirectResponse(Url::fromRoute('cafd_federaciones.list')->toString());
    }
    
    $form = \Drupal::formBuilder()->getForm('Drupal\cafd_federaciones\Form\FederacionForm', $federacion); // Modificado
    
    return [
      '#type' => 'markup',
      '#markup' => '<h2>Editar Federación</h2>',
      'form' => $form,
    ];
  }

  /**
   * Eliminar una federación.
   */
  public function delete($id) {
    $conn = Database::getConnection();
    
    // Verificar si la federación existe
    $query = $conn->select('federaciones', 'f')
      ->fields('f', ['nombre'])
      ->condition('id', $id)
      ->execute();
    
    $federacion = $query->fetchAssoc();
    
    if (!$federacion) {
      $this->messenger()->addError('Federación no encontrada.');
      return new RedirectResponse(Url::fromRoute('cafd_federaciones.list')->toString());
    }
    
    // Mostrar formulario de confirmación
    $form = \Drupal::formBuilder()->getForm('Drupal\cafd_federaciones\Form\FederacionDeleteForm', $id, $federacion['nombre']); // Modificado
    
    return [
      '#type' => 'markup',
      '#markup' => '<h2>Eliminar Federación</h2>',
      'form' => $form,
    ];
  }

  /**
   * Lista paginada de federaciones para el bloque público.
   */
  public function listPaginated(Request $request) {
    $search = $request->query->get('nombre');
    $conn = Database::getConnection();
    $query = $conn->select('federaciones', 'f')
      ->fields('f', ['nombre', 'direccion', 'telefono', 'email', 'weburl', 'logo_fid']);
    // Filtro por nombre si se busca algo
    if (!empty($search)) {
      $query->condition('f.nombre', '%' . $conn->escapeLike($search) . '%', 'LIKE');
    }
    // Paginación con extender
    /** @var \Drupal\Core\Database\Query\PagerSelectExtender $pager_query */
    $pager_query = $query->extend(PagerSelectExtender::class);
    $pager_query->limit(6);
    $results = $pager_query->execute();
    $federaciones = [];
    foreach ($results as $record) {
      $logo_url = NULL;
      if (!empty($record->logo_fid)) {
        $file = File::load($record->logo_fid);
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
      '#theme' => 'federacion_list',
      '#federaciones' => $federaciones,
      '#search' => $search,
      '#pager' => [
        '#type' => 'pager',
        '#quantity' => 2,
        '#parameters' => !empty($search) ? ['nombre' => $search] : [],
      ],
      '#attached' => [
        'library' => [
          'core/drupal.pager',
          'cafd_federaciones/federaciones_styles', // Modificado
        ],
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }
}