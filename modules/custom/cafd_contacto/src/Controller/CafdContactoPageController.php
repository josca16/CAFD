<?php

namespace Drupal\cafd_contacto\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for the CAFD contact page.
 */
class CafdContactoPageController extends ControllerBase {

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a new CafdContactoPageController object.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('form_builder')
    );
  }

  /**
   * Displays the contact page.
   *
   * @return array
   *   A render array as expected by Drupal.
   */
  public function contactPage() {
    $contact_info = [
      'visit_us' => [
        'title' => 'Haznos una visita',
        'address' => 'Calle Benidorm, nº 5 escalera 1, 2º A - 41001 Sevilla',
        'icon' => 'icon-map-marker',
      ],
      'email_us' => [
        'title' => 'Mándanos un e-mail',
        'email' => 'info@cafd.es',
        'icon' => 'icon-envelope',
      ],
      'call_us' => [
        'title' => 'Llámanos',
        'phone' => '954 460 110',
        'icon' => 'icon-phone',
      ],
    ];

    $map_embed_code = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3170.118321129534!2d-6.00606808469687!3d37.38700097983109!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1b5d9aa7bf%3A0x5d90b0ebb70b48b7!2sC.%20Benidorm%2C%205%2C%2041001%20Sevilla%2C%20Spain!5e0!3m2!1sen!2sus!4v1678886400000!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>';

    $contact_form = $this->formBuilder->getForm('\Drupal\cafd_contacto\Form\CafdContactoForm');

    return [
      '#theme' => 'cafd_contacto_page',
      '#contact_info' => $contact_info,
      '#contact_form' => $contact_form,
      '#map_embed_code' => $map_embed_code,
      '#attached' => [
        'library' => [
          'cafd_contacto/global-styling',
        ],
      ],
    ];
  }

}