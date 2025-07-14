<?php

namespace Drupal\cafd_federaciones\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Database;
use Drupal\Core\Cache\Cache;
use Drupal\file\Entity\File;

/**
 * Formulario de confirmación para eliminar federaciones.
 */
class FederacionDeleteForm extends ConfirmFormBase {

  /**
   * ID de la federación a eliminar.
   *
   * @var int
   */
  protected $id;

  /**
   * Nombre de la federación a eliminar.
   *
   * @var string
   */
  protected $nombre;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cafd_federaciones_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL, $nombre = NULL) {
    $this->id = $id;
    $this->nombre = $nombre;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('¿Estás seguro de que deseas eliminar la federación %nombre?', ['%nombre' => $this->nombre]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('cafd_federaciones.list');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->t('Esta acción no se puede deshacer. Se eliminarán todos los datos asociados a esta federación, incluyendo su logo.');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Eliminar');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancelar');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $conn = Database::getConnection();
    
    try {
      // Primero obtenemos el fid del logo si existe
      $query = $conn->select('federaciones', 'f')
        ->fields('f', ['logo_fid'])
        ->condition('id', $this->id)
        ->execute();
      
      $federacion = $query->fetchAssoc();
      
      // Eliminar el archivo del logo si existe
      if ($federacion && !empty($federacion['logo_fid'])) {
        $file = File::load($federacion['logo_fid']);
        if ($file) {
          $file->delete();
        }
      }
      
      // Eliminar la federación
      $conn->delete('federaciones')
        ->condition('id', $this->id)
        ->execute();
      
      \Drupal::messenger()->addMessage($this->t('La federación %nombre ha sido eliminada correctamente.', ['%nombre' => $this->nombre]));
      
      // Invalidar caché
      Cache::invalidateTags(['federacion_list']);
    } 
    catch (\Exception $e) {
      \Drupal::messenger()->addError($this->t('Error al eliminar la federación: @error', ['@error' => $e->getMessage()]));
    }
    
    $form_state->setRedirect('cafd_federaciones.list');
  }
}