<?php

namespace Drupal\cafd_federaciones\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\File\FileSystemInterface;

class FederacionForm extends FormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'federacion_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $federacion = NULL)
  {
    // Guardar el ID de la federación si estamos editando
    if ($federacion && isset($federacion['id'])) {
      $form_state->set('federacion_id', $federacion['id']);
    }

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => 'Federación',
      '#required' => TRUE,
      '#default_value' => $federacion ? $federacion['nombre'] : '',
    ];

    $form['direccion'] = [
      '#type' => 'textfield',
      '#title' => 'Dirección',
      '#required' => TRUE,
      '#default_value' => $federacion ? $federacion['direccion'] : '',
    ];

    $form['telefono'] = [
      '#type' => 'textfield',
      '#title' => 'Teléfono',
      '#required' => TRUE,
      '#default_value' => $federacion ? $federacion['telefono'] : '',
    ];

    $form['email'] = [
      '#type' => 'textfield',
      '#title' => 'Email',
      '#required' => TRUE,
      '#default_value' => $federacion ? $federacion['email'] : '',
    ];

    $form['weburl'] = [
      '#type' => 'textfield',
      '#title' => 'Pagina Web',
      '#required' => TRUE,
      '#default_value' => $federacion ? $federacion['weburl'] : '',
    ];

    // Configurar el valor por defecto para el logo si estamos editando
    $default_logo = [];
    if ($federacion && !empty($federacion['logo_fid'])) {
      $default_logo = [$federacion['logo_fid']];
    }

    $form['logo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Logo de la federación'),
      '#description' => $this->t('Si no se sube ninguna imagen, se utilizará una imagen por defecto.'),
      '#upload_location' => 'public://logos/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
        'file_validate_size' => [25600000],
      ],
      '#default_value' => $default_logo,
      '#required' => FALSE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $federacion ? 'Actualizar' : 'Guardar',
    ];

    $form['actions']['cancel'] = [
      '#type' => 'link',
      '#title' => $this->t('Cancelar'),
      '#url' => Url::fromRoute('cafd_federaciones.list'),
      '#attributes' => ['class' => ['button']],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // Validar email
    if (!empty($form_state->getValue('email')) && !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('El email no tiene un formato válido.'));
    }

    // Validar URL
    if (!empty($form_state->getValue('weburl')) && !filter_var($form_state->getValue('weburl'), FILTER_VALIDATE_URL)) {
      $form_state->setErrorByName('weburl', $this->t('La URL no tiene un formato válido.'));
    }

    // Validar teléfono
    if (!empty($form_state->getValue('telefono')) && !preg_match('/^\+?[0-9\s\-()]+$/', $form_state->getValue('telefono'))) {
      $form_state->setErrorByName('telefono', $this->t('El teléfono no tiene un formato válido.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $fid = NULL;
    $logo = $form_state->getValue('logo');
    
    if (!empty($logo)) {
      // Si se ha subido un logo, usarlo
      $fid = $logo[0];
      $file = File::load($fid);
      if ($file) {
        $file->setPermanent();
        $file->save();
      }
    } else {
      // Si no se ha subido un logo, usar uno por defecto
      $fid = $this->getDefaultLogoFid();
    }

    $fields = [
      'nombre' => $form_state->getValue('nombre'),
      'direccion' => $form_state->getValue('direccion'),
      'telefono' => $form_state->getValue('telefono'),
      'email' => $form_state->getValue('email'),
      'weburl' => $form_state->getValue('weburl'),
      'logo_fid' => $fid,
    ];

    $conn = Database::getConnection();
    
    // Determinar si es una actualización o inserción
    $federacion_id = $form_state->get('federacion_id');
    
    try {
      if ($federacion_id) {
        // Actualizar federación existente
        $conn->update('federaciones')
          ->fields($fields)
          ->condition('id', $federacion_id)
          ->execute();
        
        \Drupal::messenger()->addMessage('Federación actualizada correctamente.');
      } 
      else {
        // Insertar nueva federación
        $conn->insert('federaciones')
          ->fields($fields)
          ->execute();
        
        \Drupal::messenger()->addMessage('Federación guardada correctamente.');
      }
      
      // Invalidar caché
      Cache::invalidateTags(['federacion_list']);
      
      // Redireccionar a la lista de federaciones
      $form_state->setRedirect('cafd_federaciones.list');
    } 
    catch (\Exception $e) {
      \Drupal::messenger()->addError('Error al guardar la federación: ' . $e->getMessage());
    }
  }
  
  /**
   * Obtiene el FID de la imagen por defecto, creándola si es necesario.
   *
   * @return int|null
   *   El ID del archivo de la imagen por defecto, o NULL si no se pudo crear.
   */
  protected function getDefaultLogoFid() {
    $conn = Database::getConnection();
    $query = $conn->select('file_managed', 'f')
      ->fields('f', ['fid'])
      ->condition('filename', 'default_federation_logo.png')
      ->condition('uri', 'public://logos/default_federation_logo.png')
      ->execute();
    $result = $query->fetchField();
  
    if ($result) {
      return $result;
    }
  
    // Ruta al archivo fuente en el módulo
    $module_path = \Drupal::service('extension.list.module')->getPath('cafd_federaciones');
    $default_image_path = $module_path . '/images/default_federation_logo.png';
  
    // Verificar que el archivo existe
    if (!file_exists($default_image_path)) {
      \Drupal::messenger()->addError('No se encontró la imagen por defecto en el módulo.');
      return NULL;
    }
  
    // Crear el directorio si no existe
    $directory = 'public://logos';
    \Drupal::service('file_system')->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
  
    // Copiar el archivo al sistema de archivos público
    $destination = $directory . '/default_federation_logo.png';
    if (!file_exists(\Drupal::service('file_system')->realpath($destination))) {
      // Copiar el archivo físicamente
      \Drupal::service('file_system')->copy($default_image_path, $destination, FileSystemInterface::EXISTS_REPLACE);
    }
  
    // Crear el objeto File de Drupal
    $file = File::create([
      'uri' => $destination,
      'status' => 1, // 1 = permanente
      'filename' => 'default_federation_logo.png',
    ]);
    $file->save();
  
    return $file->id();
  }
  /**
   * Crea una imagen simple por defecto si no existe la imagen en el módulo.
   */
  protected function createSimpleDefaultImage() {
    $directory = 'public://logos';
    \Drupal::service('file_system')->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
    
    // Crear una imagen simple
    $width = 200;
    $height = 200;
    $image = imagecreatetruecolor($width, $height);
    
    // Colores
    $bg_color = imagecolorallocate($image, 240, 240, 240); // Gris claro
    $text_color = imagecolorallocate($image, 50, 50, 50); // Gris oscuro
    
    // Rellenar el fondo
    imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);
    
    // Añadir texto
    $text = "Logo";
    $font_size = 5; // Tamaño de fuente para imagestring
    
    // Centrar el texto
    $text_width = strlen($text) * imagefontwidth($font_size);
    $text_height = imagefontheight($font_size);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    imagestring($image, $font_size, $x, $y, $text, $text_color);
    
    // Guardar la imagen
    $file_path = $directory . '/default_federation_logo.png';
    imagepng($image, \Drupal::service('file_system')->realpath($file_path));
    imagedestroy($image);
  }
}