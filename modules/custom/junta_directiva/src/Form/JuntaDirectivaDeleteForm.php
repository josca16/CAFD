<?php

namespace Drupal\junta_directiva\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Cache\Cache;

class JuntaDirectivaDeleteForm extends ConfirmFormBase {

  protected $id;

  public function getFormId() {
    return 'junta_directiva_delete_form';
  }

  public function getQuestion() {
    return $this->t('¿Qué deseas eliminar?');
  }

  public function getCancelUrl() {
    return new Url('junta_directiva.list');
  }

  public function getConfirmText() {
    return $this->t('Eliminar miembro');
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;

    $miembro = \Drupal::database()->select('junta_directiva', 'j')
      ->fields('j')
      ->condition('id', $id)
      ->execute()
      ->fetchAssoc();

    if (!$miembro) {
      $this->messenger()->addError($this->t('Miembro no encontrado.'));
      $form_state->setRedirect('junta_directiva.list');
    }

    $form['info'] = [
      '#markup' => $this->t('<p><strong>Miembro:</strong> @nombre<br><strong>Cargo:</strong> @cargo</p>', [
        '@nombre' => $miembro['nombre'],
        '@cargo' => $miembro['cargo'],
      ]),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['delete_member'] = [
      '#type' => 'submit',
      '#value' => $this->t('Eliminar miembro completo'),
      '#button_type' => 'danger',
    ];

    $form['actions']['delete_image'] = [
      '#type' => 'submit',
      '#value' => $this->t('Eliminar solo la imagen'),
      '#submit' => ['::deleteImageOnly'],
    ];

    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancelar'),
      '#url' => $this->getCancelUrl(),
      '#attributes' => ['class' => ['button']],
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::database()->delete('junta_directiva')
      ->condition('id', $this->id)
      ->execute();

    // Invalida la cache del bloque
    Cache::invalidateTags(['junta_directiva_list']);

    $this->messenger()->addStatus($this->t('Miembro eliminado.'));
    $form_state->setRedirect('junta_directiva.list');
  }

  public function deleteImageOnly(array &$form, FormStateInterface $form_state) {
    $conn = \Drupal::database();

    $miembro = $conn->select('junta_directiva', 'j')
      ->fields('j', ['imagen_fid'])
      ->condition('id', $this->id)
      ->execute()
      ->fetchAssoc();

    if (!empty($miembro['imagen_fid'])) {
      $file = File::load($miembro['imagen_fid']);
      if ($file) {
        $file->delete();
      }
    }

    $conn->update('junta_directiva')
      ->fields([
        'imagen_jun' => NULL,
        'imagen_fid' => NULL,
      ])
      ->condition('id', $this->id)
      ->execute();

    // Invalida la cache del bloque
    Cache::invalidateTags(['junta_directiva_list']);

    $this->messenger()->addStatus($this->t('Imagen eliminada correctamente.'));
    $form_state->setRedirect('junta_directiva.list');
  }

}
