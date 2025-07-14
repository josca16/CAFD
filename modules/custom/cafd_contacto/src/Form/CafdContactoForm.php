<?php

namespace Drupal\cafd_contacto\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Provides the CAFD contact form.
 */
class CafdContactoForm extends FormBase
{

  /**
   * The mail manager.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * Constructs a new CafdContactoForm.
   *
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(MailManagerInterface $mail_manager, LanguageManagerInterface $language_manager, ConfigFactoryInterface $config_factory, LoggerChannelFactoryInterface $logger_factory)
  {
    $this->mailManager = $mail_manager;
    $this->languageManager = $language_manager;
    $this->configFactory = $config_factory;
    $this->logger = $logger_factory->get('cafd_contacto'); // Logger channel name
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('language_manager'),
      $container->get('config.factory'),
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'cafd_contacto_form'; // Form ID
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Nombre')],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Email')],
    ];

    $form['telefono'] = [
      '#type' => 'tel',
      '#title' => $this->t('Teléfono'),
      '#attributes' => ['placeholder' => $this->t('Teléfono')],
    ];

    $form['asunto'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Asunto'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Asunto')],
    ];

    $form['mensaje'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Mensaje'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Mensaje')],
      '#description' => $this->t('Máximo 400 caracteres.'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // Validate 'nombre' - required, only letters and spaces allowed
    $nombre = $form_state->getValue('nombre');
    if (empty($nombre)) {
      $form_state->setErrorByName('nombre', $this->t('El campo Nombre es obligatorio.'));
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
      $form_state->setErrorByName('nombre', $this->t('El Nombre solo puede contener letras y espacios.'));
    }

    // Validate 'email' - required, valid email format
    $email = $form_state->getValue('email');
    if (empty($email)) {
      $form_state->setErrorByName('email', $this->t('El campo Email es obligatorio.'));
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('El Email no es válido.'));
    }

    // Validate 'telefono' - optional, but if provided must be valid phone number format
    $telefono = $form_state->getValue('telefono');
    if (!empty($telefono)) {
      // Simple phone validation: digits, spaces, +, -, parentheses allowed
      if (!preg_match('/^[0-9+\-\s\(\)]+$/', $telefono)) {
        $form_state->setErrorByName('telefono', $this->t('El Teléfono contiene caracteres no válidos.'));
      }

      $digits_only = preg_replace('/\D/', '', $telefono);
      if (strlen($digits_only) < 6) {
        $form_state->setErrorByName('telefono', $this->t('El Teléfono debe contener al menos 6 dígitos.'));
      }
    }

    // Validate 'asunto' - required, no special characters except basic punctuation
    $asunto = $form_state->getValue('asunto');
    if (empty($asunto)) {
      $form_state->setErrorByName('asunto', $this->t('El campo Asunto es obligatorio.'));
    } elseif (!preg_match('/^[\w\s\.\,\-\_\¿\?¡\!áéíóúÁÉÍÓÚñÑ]+$/u', $asunto)) {
      $form_state->setErrorByName('asunto', $this->t('El Asunto contiene caracteres no permitidos.'));
    }

    // Validate 'mensaje' - required, minimum length 10 characters, maximum 400 characters
    $mensaje = $form_state->getValue('mensaje');
    if (empty($mensaje)) {
      $form_state->setErrorByName('mensaje', $this->t('El campo Mensaje es obligatorio.'));
    } elseif (mb_strlen($mensaje) < 10) {
      $form_state->setErrorByName('mensaje', $this->t('El Mensaje debe tener al menos 10 caracteres.'));
    } elseif (mb_strlen($mensaje) > 400) {
      $form_state->setErrorByName('mensaje', $this->t('El Mensaje no puede exceder los 400 caracteres.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $values = $form_state->getValues();
    $nombre = $values['nombre'];
    $email_from = $values['email'];
    $telefono = $values['telefono'];
    $asunto = $values['asunto'];
    $mensaje_usuario = $values['mensaje'];

    $mail_manager = $this->mailManager;
    $module = 'cafd_contacto'; // Module name for hook_mail
    $key = 'cafd_contact_message'; // Mail key

    $to = 'info@cafd.es'; // Specific email for CAFD
    // Or use site admin: $to = $this->configFactory->get('system.site')->get('mail');

    $params['from'] = $email_from;
    $params['subject'] = $this->t('Nuevo mensaje de contacto CAFD: @asunto', ['@asunto' => $asunto]);
    $params['message'] = "Has recibido un nuevo mensaje desde el formulario de contacto de CAFD:\n\n" .
      "Nombre: $nombre\n" .
      "Email: $email_from\n" .
      "Teléfono: $telefono\n" .
      "Asunto: $asunto\n\n" .
      "Mensaje:\n$mensaje_usuario";

    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    $send = TRUE;

    $result = $mail_manager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] !== TRUE) {
      $this->messenger()->addError($this->t('Hubo un problema al enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.'));
      $this->logger->error('Error al enviar email de contacto CAFD: @error', ['@error' => print_r($result, TRUE)]);
    } else {
      $this->messenger()->addStatus($this->t('Gracias por tu mensaje. Nos pondremos en contacto contigo pronto.'));
      $this->logger->info('Email de contacto CAFD enviado correctamente a @to desde @from', ['@to' => $to, '@from' => $email_from]);
    }
  }
}
