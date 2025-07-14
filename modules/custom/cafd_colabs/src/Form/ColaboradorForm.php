<?php

namespace Drupal\cafd_colabs\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

class ColaboradorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'colaborador_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    
        // Título del listado
    $form['colaboradores_title'] = [
      '#markup' => '<h2 class="colaboradores-form-title" style="text-align:center; margin-top:20px;">' . $this->t('Gestionar Colaboradores:') . '</h2>',
    ];
    
    // Contenedor para los campos del formulario
    $form['colabs_formulario'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['colabs-formulario']],
    ];

    // Campo de nombre
    $form['colabs_formulario']['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Colaborador'),
      '#required' => TRUE,
    ];

    // Campo de logo
    $form['colabs_formulario']['logo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Logo del colaborador'),
      '#upload_location' => 'public://colaboradores/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#default_value' => [],
      '#required' => TRUE,
    ];

    // Botón de enviar
    $form['colabs_formulario']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar'),
    ];



    // Listado de colaboradores
    $conn = Database::getConnection();
    $results = $conn->select('colaboradores', 'c')
      ->fields('c', ['id', 'nombre'])
      ->execute()
      ->fetchAll();

    $items_data = [];
    foreach ($results as $record) {
      $items_data[] = [
        'nombre' => $record->nombre,
        'edit_url' => Url::fromRoute('cafd_colabs.colaborador_edit', ['id' => $record->id]),
        'delete_url' => Url::fromRoute('cafd_colabs.colaborador_delete', ['id' => $record->id]),

      ];
    }

    $form['colaboradores_nombres'] = [
      '#theme' => 'colaboradores_listado_custom',
      '#colaboradores' => $items_data,
    ];

    // Contenedor principal para todo el formulario
    $form['colabs_formulario_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['colabs-formulario-wrapper']],
    ];

    // Mover el contenido existente dentro del contenedor principal
    $form['colabs_formulario_wrapper']['colaboradores_title'] = $form['colaboradores_title'];
    unset($form['colaboradores_title']);

    $form['colabs_formulario_wrapper']['colabs_formulario'] = $form['colabs_formulario'];
    unset($form['colabs_formulario']);

    $form['colabs_formulario_wrapper']['colaboradores_nombres'] = $form['colaboradores_nombres'];
    unset($form['colaboradores_nombres']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fid = NULL;
    $logo = $form_state->getValue('logo');

    if (!empty($logo)) {
      $fid = $logo[0];
      $file = File::load($fid);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    }

    $conn = Database::getConnection();
    $conn->insert('colaboradores')->fields([
      'nombre' => $form_state->getValue('nombre'),
      'logo_fid' => $fid,
    ])->execute();

    // Invalidar caché para actualizar el bloque
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['colaboradores_list']);

    $this->messenger()->addMessage($this->t('Colaborador guardado correctamente.'));
  }

}
