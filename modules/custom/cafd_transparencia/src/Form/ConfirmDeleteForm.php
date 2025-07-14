<?php
namespace Drupal\cafd_transparencia\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ConfirmDeleteForm extends ConfirmFormBase {

  protected $pdfId;

  public function getFormId() {
    return 'cafd_transparencia_confirm_delete';
  }

  public function getQuestion() {
    return $this->t('¿Estás seguro de querer eliminar este PDF?');
  }

  public function getDescription() {
    return $this->t('¿Está seguro de que quiere eliminar este PDF?<br>Esta acción no se puede deshacer.');

  }

  public function getCancelUrl() {
    return new Url('cafd_transparencia.listado_pdfs');
  }

  public function getConfirmText() {
    return $this->t('Eliminar');
  }

  public function buildForm(array $form, FormStateInterface $form_state, $pdf_id = NULL) {
    $this->pdfId = $pdf_id;
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Eliminar el PDF
    $connection = \Drupal::database();
    $connection->delete('cafd_pdfs')
      ->condition('id', $this->pdfId)
      ->execute();

    // Invalidar caché
    \Drupal::service('cache_tags.invalidator')->invalidateTags(['cafd_pdfs_list']);
    
    // Mensaje de confirmación
    \Drupal::messenger()->addStatus($this->t('El PDF ha sido eliminado correctamente.'));

    // Redirigir al listado
    $form_state->setRedirect('cafd_transparencia.listado_pdfs');
  }
}