<?php

namespace Drupal\cafd_federaciones\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class FederacionSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'federacion_search_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $default_value = '') {
    $form['#method'] = 'get';
    $form['#action'] = Url::fromRoute('cafd_federaciones.list')->toString();
    
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Buscar por nombre'),
      '#default_value' => $default_value,
      '#size' => 30,
      '#maxlength' => 64,
    ];
    
    $form['actions'] = [
      '#type' => 'actions',
    ];
    
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Buscar'),
    ];
    
    if (!empty($default_value)) {
      $form['actions']['reset'] = [
        '#type' => 'link',
        '#title' => $this->t('Limpiar búsqueda'),
        '#url' => Url::fromRoute('cafd_federaciones.list'),
        '#attributes' => ['class' => ['button']],
      ];
    }
    
    // Eliminar el token CSRF ya que es un formulario GET
    $form['#token'] = FALSE;
    
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // No es necesario hacer nada aquí ya que el formulario se envía por GET
  }
}