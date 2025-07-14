<?php

namespace Drupal\cafd_colabs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'Colaborador Form' block.
 *
 * @Block(
 *   id = "colaborador_form_block",
 *   admin_label = @Translation("Formulario de Colaborador")
 * )
 */
class ColaboradoresFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * El servicio de constructor de formularios.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $formBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $formBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_user = \Drupal::currentUser();

    if ($current_user->isAnonymous()) {
      return [
        
      ];
    }

    return $this->formBuilder->getForm('Drupal\cafd_colabs\Form\ColaboradorForm');
  }

}
