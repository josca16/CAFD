<?php

namespace Drupal\cafd_colabs\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Database\Database;

class ColaboradorEditForm extends FormBase {

  public function getFormId() {
    return 'colaborador_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    if (!$id) {
      \Drupal::messenger()->addError($this->t('ID del colaborador no proporcionado.'));
      return [];
    }

    $conn = Database::getConnection();
    $colaborador = $conn->select('colaboradores', 'c')
      ->fields('c', ['id', 'nombre', 'logo_fid'])
      ->condition('id', $id)
      ->execute()
      ->fetchObject();

    if (!$colaborador) {
      \Drupal::messenger()->addError($this->t('Colaborador no encontrado.'));
      return [];
    }

    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $colaborador->id,
    ];

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre del colaborador'),
      '#default_value' => $colaborador->nombre,
      '#required' => TRUE,
    ];

    $form['logo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Logo del colaborador (opcional para cambiar)'),
      '#upload_location' => 'public://colaboradores/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
      ],
      '#default_value' => $colaborador->logo_fid ? [$colaborador->logo_fid] : [],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Actualizar'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $form_state->getValue('id');
    $nombre = $form_state->getValue('nombre');
    $logo = $form_state->getValue('logo');
    $fid = $logo[0] ?? NULL;

    if ($fid) {
      $file = File::load($fid);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    }

    $fields = ['nombre' => $nombre];
    if ($fid) {
      $fields['logo_fid'] = $fid;
    }

    Database::getConnection()->update('colaboradores')
      ->fields($fields)
      ->condition('id', $id)
      ->execute();

    \Drupal::service('cache_tags.invalidator')->invalidateTags(['colaboradores_list']);
    \Drupal::messenger()->addMessage($this->t('Colaborador actualizado.'));

    // Redirige a la lista de colaboradores después de actualizar
    $form_state->setRedirectUrl(\Drupal\Core\Url::fromUri('internal:/inicio'));
  }
}
