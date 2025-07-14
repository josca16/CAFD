<?php

namespace Drupal\junta_directiva\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;

class JuntaDirectivaController extends ControllerBase {

  protected $currentUser;

  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  public function listado_junta() {
    $es_admin = $this->currentUser->hasPermission('administer site configuration');

    // ❌ Si no es admin, no mostramos contenido, pero mantenemos ruta accesible
    if (!$es_admin) {
      return [
        '#markup' => '',
        '#cache' => ['contexts' => ['user.roles']],
      ];
    }

    // ✅ Si es admin, cargar miembros y mostrar contenido
    $conn = Database::getConnection();
    $result = $conn->select('junta_directiva', 'j')
      ->fields('j', ['id', 'nombre', 'cargo', 'federacion', 'visibilidad'])
      ->execute();

    $miembros = [];

    foreach ($result as $record) {
      $edit_url = Url::fromRoute('junta_directiva_edit', ['id' => $record->id]);
      $delete_url = Url::fromRoute('junta_directiva_delete', ['id' => $record->id]);

      $miembros[] = [
        'id' => $record->id,
        'nombre' => $record->nombre,
        'cargo' => $record->cargo,
        'federacion' => $record->federacion,
        'visibilidad' => $record->visibilidad, 
        'edit_link' => Link::fromTextAndUrl('Editar', $edit_url)->toRenderable(),
        'delete_link' => Link::fromTextAndUrl('Eliminar', $delete_url)->toRenderable(),
      ];
    }

    return [
      '#theme' => 'junta_directiva_list',
      '#miembros' => $miembros,
      '#nueva_url' => Url::fromRoute('junta_directiva.form')->toString(),
      '#mostrar_acciones' => TRUE,
      '#attached' => [
        'library' => [
          'junta_directiva/listado_junta',
        ],
      ],
      '#cache' => ['contexts' => ['user.roles']],
    ];
  }
}
