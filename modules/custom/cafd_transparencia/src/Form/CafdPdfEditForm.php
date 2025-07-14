<?php

namespace Drupal\cafd_transparencia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;

class CafdPdfEditForm extends FormBase {

  protected $pdfId;

  public function getFormId() {
    return 'cafd_pdf_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $pdf_id = NULL) {
    $this->pdfId = $pdf_id;
    $connection = Database::getConnection();
    $pdf = $connection->select('cafd_pdfs', 'c')
      ->fields('c')
      ->condition('id', $pdf_id)
      ->execute()
      ->fetchObject();

    if (!$pdf) {
      $this->messenger()->addError($this->t('El PDF no existe.'));
      return $form;
    }

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#required' => TRUE,
      '#default_value' => $pdf->title,
    ];

    $file = File::load($pdf->fid);
    $form['pdf_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Archivo PDF'),
      '#upload_location' => 'public://cafd_pdfs/',
      '#required' => FALSE,
      '#upload_validators' => [
        'file_validate_extensions' => ['pdf'],
      ],
      '#default_value' => [$pdf->fid],
      '#description' => $this->t('Dejar en blanco para mantener el archivo actual.'),
    ];

    $form['categoria'] = [
      '#type' => 'select',
      '#title' => $this->t('Categoría'),
      '#required' => TRUE,
      '#options' => [
        'Estructura Organizativa' => $this->t('Estructura Organizativa'),
        'Datos Económicos' => $this->t('Datos Económicos'),
        'Normativa' => $this->t('Normativa'),
        'Actividades' => $this->t('Actividades'),
      ],
      '#default_value' => $pdf->categoria,
    ];

    $form['peso'] = [
      '#type' => 'number',
      '#title' => $this->t('Peso (orden)'),
      '#default_value' => $pdf->peso,
    ];

    $form['visible'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('¿Visible?'),
      '#default_value' => $pdf->visible,
    ];

    $form['agrupable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('¿Agrupable?'),
      '#default_value' => $pdf->agrupable,
    ];

    $form['nombre_grupo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre del grupo'),
      '#default_value' => $pdf->nombre_grupo,
      '#states' => [
        'visible' => [
          ':input[name="agrupable"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Actualizar PDF'),
    ];

    $form['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancelar'),
      '#url' => Url::fromRoute('cafd_transparencia.listado_pdfs'),
      '#attributes' => ['class' => ['button']],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $update_data = [
      'title' => $form_state->getValue('title'),
      'categoria' => $form_state->getValue('categoria'),
      'peso' => $form_state->getValue('peso'),
      'visible' => $form_state->getValue('visible') ? 1 : 0,
      'agrupable' => $form_state->getValue('agrupable') ? 1 : 0,
      'nombre_grupo' => $form_state->getValue('nombre_grupo'),
    ];

    // Actualizar archivo solo si se subió uno nuevo
    if (!empty($form_state->getValue('pdf_file'))) {
      $update_data['fid'] = $form_state->getValue('pdf_file')[0];
    }

    $connection->update('cafd_pdfs')
      ->fields($update_data)
      ->condition('id', $this->pdfId)
      ->execute();

    $this->messenger()->addStatus($this->t('El PDF fue actualizado correctamente.'));
    $form_state->setRedirect('cafd_transparencia.listado_pdfs');

    \Drupal\Core\Cache\Cache::invalidateTags(['cafd_pdfs_list']);
  }
}