<?php

namespace Drupal\cafd_transparencia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Core\Url;

class CafdPdfForm extends FormBase {

  public function getFormId() {
    return 'cafd_pdf_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Título'),
      '#required' => TRUE,
    ];

    $form['pdf_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Archivo PDF'),
      '#upload_location' => 'public://cafd_pdfs/',
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['pdf'],
      ],
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
    ];

    $form['peso'] = [
      '#type' => 'number',
      '#title' => $this->t('Peso (orden)'),
      '#default_value' => 0,
    ];

    $form['visible'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('¿Visible?'),
      '#default_value' => 1,
    ];

    $form['agrupable'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('¿Agrupable?'),
      '#default_value' => 0,
    ];

    $form['nombre_grupo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre del grupo'),
      '#states' => [
        'visible' => [
          ':input[name="agrupable"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Guardar PDF'),
    ];

    // Encabezados de tabla
    $header = [
      'id' => $this->t('ID'),
      'title' => $this->t('Título'),
      'categoria' => $this->t('Categoría'),
      'peso' => $this->t('Peso'),
      'visible' => $this->t('Visible'),
      'agrupable' => $this->t('Agrupable'),
      'nombre_grupo' => $this->t('Nombre del grupo'),
      'archivo' => $this->t('Archivo PDF'),
      'operations' => $this->t('Operaciones'),
    ];

    $connection = Database::getConnection();
    $results = $connection->select('cafd_pdfs', 'c')
      ->fields('c', ['id', 'title', 'fid', 'categoria', 'peso', 'visible', 'agrupable', 'nombre_grupo'])
      ->orderBy('peso', 'ASC')
      ->execute()
      ->fetchAll();

    $file_url_generator = \Drupal::service('file_url_generator');

    foreach ($results as $record) {
      $file = File::load($record->fid);
      $url = $file ? $file_url_generator->generateAbsoluteString($file->getFileUri()) : '';

      $form['pdf_list'][$record->id] = [
        'id' => ['#markup' => $record->id],
        'title' => ['#markup' => $record->title],
        'categoria' => ['#markup' => $record->categoria],
        'peso' => ['#markup' => $record->peso],
        'visible' => ['#markup' => $record->visible ? $this->t('Sí') : $this->t('No')],
        'agrupable' => ['#markup' => $record->agrupable ? $this->t('Sí') : $this->t('No')],
        'nombre_grupo' => ['#markup' => $record->nombre_grupo ?: '-'],
        'archivo' => ['#markup' => $url ? Link::fromTextAndUrl($this->t('Ver PDF'), Url::fromUri($url, ['attributes' => ['target' => '_blank']]))->toString() : ''],
        'operations' => [
          '#type' => 'dropbutton',
          '#links' => [
            'edit' => [
              'title' => $this->t('Editar'),
              'url' => Url::fromRoute('cafd_transparencia.edit_pdf', ['pdf_id' => $record->id]),
            ],
        'delete' => [
        'title' => $this->t('Eliminar'),
        'url' => Url::fromRoute('cafd_transparencia.delete_pdf', ['pdf_id' => $record->id]),
        'attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(['width' => 700]),
        ],
          ],


          ],
        ],
      ];
    }

    $form['pdf_list']['#type'] = 'table';
    $form['pdf_list']['#header'] = $header;
    $form['pdf_list']['#empty'] = $this->t('No hay PDFs registrados.');
    $form['pdf_list']['#prefix'] = '<div id="pdf-list-wrapper">';
    $form['pdf_list']['#suffix'] = '</div>';

    $form['pdf_list']['#cache']['tags'][] = 'cafd_pdfs_list';

    // Agregar biblioteca para AJAX y modales
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $connection->insert('cafd_pdfs')
      ->fields([
        'title' => $form_state->getValue('title'),
        'fid' => $form_state->getValue('pdf_file')[0],
        'categoria' => $form_state->getValue('categoria'),
        'peso' => $form_state->getValue('peso'),
        'visible' => $form_state->getValue('visible') ? 1 : 0,
        'agrupable' => $form_state->getValue('agrupable') ? 1 : 0,
        'nombre_grupo' => $form_state->getValue('nombre_grupo'),
      ])
      ->execute();

    $this->messenger()->addStatus($this->t('El PDF fue guardado correctamente.'));
    $form_state->setRedirect('<current>');
    \Drupal\Core\Cache\Cache::invalidateTags(['cafd_pdfs_list']);
  }

  public function deletePdfSubmit(array &$form, FormStateInterface $form_state) {
    // Obtener el ID del PDF desde el valor enviado en el formulario
    $pdf_id = $form_state->getValue('pdf_id');
  
    if ($pdf_id) {
      $connection = \Drupal::database();
      $connection->delete('cafd_pdfs')->condition('id', $pdf_id)->execute();
  
      $this->messenger()->addStatus($this->t('PDF eliminado.'));
      \Drupal\Core\Cache\Cache::invalidateTags(['cafd_pdfs_list']);
    }
    else {
      $this->messenger()->addError($this->t('No se pudo identificar el PDF para eliminar.'));
    }
  
    // Redirigir o devolver el fragmento actualizado con AJAX
    if ($form_state->isAjax()) {
      // Devuelve la tabla actualizada tras eliminar
      return $form['pdf_list'];
    }
    else {
      $form_state->setRedirect('<current>');
    }
  }
  

  public function ajaxDeleteCallback(array &$form, FormStateInterface $form_state) {
    return $form['pdf_list'];
  }
}