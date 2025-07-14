<?php

namespace Drupal\cafd_colabs\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

class ColaboradorDeleteForm extends ConfirmFormBase {

  protected $id;

  public function getFormId() {
    return 'colaborador_delete_form';
  }

  public function getQuestion() {
    return $this->t('¿Estás seguro de que deseas eliminar este colaborador?');
  }

  public function getCancelUrl() {
    return Url::fromRoute('cafd_colabs.list');
  }

  public function getConfirmText() {
    return $this->t('Eliminar');
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = $id;

    $form = parent::buildForm($form, $form_state);

    // Reemplaza el mensaje por defecto "This action cannot be undone."
    $form['description'] = [
      '#markup' => '<p>' . $this->t('Esta acción no se puede deshacer.') . '</p>',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = \Drupal::database();

    // Obtener el fid del logo para eliminarlo
    $logo_fid = $connection->select('colaboradores', 'c')
      ->fields('c', ['logo_fid'])
      ->condition('id', $this->id)
      ->execute()
      ->fetchField();

    if ($logo_fid) {
      $file = File::load($logo_fid);
      if ($file) {
        $file->delete();
      }
    }

    // Eliminar el colaborador de la base de datos
    $connection->delete('colaboradores')
      ->condition('id', $this->id)
      ->execute();

    // Invalida la caché y muestra mensaje
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['colaboradores_list']);
    $this->messenger()->addMessage($this->t('Colaborador eliminado.'));

    $form_state->setRedirectUrl(\Drupal\Core\Url::fromUri('internal:/inicio'));

  }

}
