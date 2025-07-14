<?php

namespace Drupal\junta_directiva\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

class JuntaDirectivaForm extends FormBase
{

  protected $id;

  public function getFormId()
  {
    return 'junta_directiva_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
  {
    $this->id = $id;

    $valores = [
      'nombre' => '',
      'cargo' => '',
      'federacion' => '',
      'imagen_jun' => '',
      'imagen_fid' => NULL,
      'visibilidad' => 'ambos',
    ];

    if ($id !== NULL) {
      $conn = \Drupal::database();
      $result = $conn->select('junta_directiva', 'j')
        ->fields('j')
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();

      if ($result) {
        $valores = $result;
      }
    }

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#default_value' => $valores['nombre'],
      '#required' => TRUE,
    ];

    $form['cargo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Cargo'),
      '#default_value' => $valores['cargo'],
      '#required' => TRUE,
    ];

    $form['federacion'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Federación'),
      '#default_value' => $valores['federacion'],
      '#required' => TRUE,
    ];

    $form['visibilidad'] = [
      '#type' => 'select',
      '#title' => $this->t('Visibilidad'),
      '#options' => [
        'inicio' => $this->t('Solo en Inicio'),
        'junta' => $this->t('Solo en Junta Directiva'),
        'ambos' => $this->t('Ambos lugares'),
      ],
      '#default_value' => $valores['visibilidad'],
      '#required' => TRUE,
    ];


    if (!empty($valores['imagen_jun'])) {
      $form['imagen_actual'] = [
        '#type' => 'item',
        '#title' => $this->t('Imagen actual'),
        '#markup' => '<div class="imagen-edicion"><img src="' . $valores['imagen_jun'] . '"></div>',
      ];
    }

    $form['imagen_jun'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Imagen del miembro'),
      '#upload_location' => 'public://imagenes/',
      '#default_value' => !empty($valores['imagen_fid']) ? [$valores['imagen_fid']] : NULL,
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg gif'],
      ],
      '#description' => $this->t('Formatos permitidos: jpg, png, gif.'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t($this->id ? 'Actualizar' : 'Guardar'),
    ];

    $form['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancelar'),
      '#url' => Url::fromRoute('junta_directiva.list'),
      '#attributes' => [
        'class' => ['button', 'button-cancel'],
        'style' => 'margin-left: 1rem;',
      ],
    ];

    $form['#attached']['library'][] = 'junta_directiva/formulario_junta';

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $conn = \Drupal::database();
    $values = [
      'nombre' => $form_state->getValue('nombre'),
      'cargo' => $form_state->getValue('cargo'),
      'federacion' => $form_state->getValue('federacion'),
      'visibilidad' => $form_state->getValue('visibilidad'),
    ];


    $valores_anteriores = [];
    if ($this->id) {
      $valores_anteriores = $conn->select('junta_directiva', 'j')
        ->fields('j', ['imagen_fid'])
        ->condition('id', $this->id)
        ->execute()
        ->fetchAssoc();
    }


    $nuevo_fid = $form_state->getValue('imagen_jun');


    if (!empty($nuevo_fid)) {
      $fid = $nuevo_fid[0];
      $file = File::load($fid);


      if ($file) {
        $file->setPermanent();
        $file->save();
        $values['imagen_fid'] = $fid;
        $values['imagen_jun'] = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
      }

      if (!empty($valores_anteriores['imagen_fid']) && $valores_anteriores['imagen_fid'] != $fid) {
        $anterior = File::load($valores_anteriores['imagen_fid']);
        if ($anterior) {
          $anterior->delete();
        }
      }

    } elseif ($this->id && empty($nuevo_fid) && !empty($valores_anteriores['imagen_fid'])) {
      $values['imagen_jun'] = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
    }

    if (!empty($valores_anteriores['imagen_fid']) && $valores_anteriores['imagen_fid'] != $fid) {
      $anterior = File::load($valores_anteriores['imagen_fid']);
      if ($anterior) {
        $anterior->delete();
      }

    } elseif ($this->id && empty($nuevo_fid) && !empty($valores_anteriores['imagen_fid'])) {
      $anterior = File::load($valores_anteriores['imagen_fid']);
      if ($anterior) {
        $anterior->delete();
      }
      $values['imagen_fid'] = NULL;
      $values['imagen_jun'] = NULL;
      $this->messenger()->addStatus($this->t('La imagen del miembro fue eliminada.'));
    }


    if ($this->id) {
      $conn->update('junta_directiva')
        ->fields($values)
        ->condition('id', $this->id)
        ->execute();
      $this->messenger()->addMessage($this->t('Miembro actualizado.'));
    } else {
      $conn->insert('junta_directiva')
        ->fields($values)
        ->execute();
      $this->messenger()->addMessage($this->t('Miembro añadido.'));
    }

    // Invalida la caché del bloque
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['junta_directiva_list']);

    $form_state->setRedirect('junta_directiva.list');
  }
}
