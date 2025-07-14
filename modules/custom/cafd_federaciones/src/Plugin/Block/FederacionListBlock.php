<?php

namespace Drupal\cafd_federaciones\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Query\PagerSelectExtender;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Proporciona un bloque con la lista de federaciones en diseño flex-row.
 *
 * @Block(
 *   id = "federacion_list_block",
 *   admin_label = @Translation("Lista de Federaciones (Flex)"),
 * )
 */
class FederacionListBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new FederacionListBlock.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $request_stack) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $request = $this->requestStack->getCurrentRequest();
    $search = $request->query->get('nombre');
    
    $conn = \Drupal::database();
    $query = $conn->select('federaciones', 'f')
      ->fields('f', ['nombre', 'direccion', 'telefono', 'email', 'weburl', 'logo_fid']);
    
    // Filtro por nombre si se busca algo
    if (!empty($search)) {
      $query->condition('f.nombre', '%' . $conn->escapeLike($search) . '%', 'LIKE');
    }
    
    // Paginación con elemento específico
    $pager_query = $query->extend(PagerSelectExtender::class);
    $pager_query->element(0); // Asegura que este paginador use el elemento 0
    $pager_query->limit(6); // Número de elementos por página
    $result = $pager_query->execute();

    $federaciones = [];
    foreach ($result as $record) {
      $logo_url = NULL;

      if ($record->logo_fid) {
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
      '#titulo' => $this->t('Listado de Federaciones Deportivas'),
      '#federaciones' => $federaciones,
      '#search' => $search,
      '#pager' => [
        '#type' => 'pager',
        '#element' => 0,
        '#quantity' => 5,
        '#parameters' => !empty($search) ? ['nombre' => $search] : [],
      ],
      '#attached' => [
        'library' => [
          'cafd_federaciones/federaciones_styles',
          'core/drupal.pager',
        ],
      ],
      '#cache' => [
        'max-age' => 0,
        'contexts' => ['url.query_args:page', 'url.query_args:nombre'],
        'tags' => ['federacion_list'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    // Desactivar caché para este bloque
    return 0;
  }
}