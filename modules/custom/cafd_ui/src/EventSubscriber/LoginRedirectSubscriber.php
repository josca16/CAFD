<?php

namespace Drupal\cafd_ui\EventSubscriber;

use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Redirects users after login.
 */
class LoginRedirectSubscriber implements EventSubscriberInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['onKernelRequest', 30], // Prioridad alta
    ];
  }

  /**
   * Redirects the user after login.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The request event.
   */
  public function onKernelRequest(RequestEvent $event) {
    $request = $event->getRequest();
    $route_name = $request->attributes->get('_route');

    // Solo actuar en la ruta de perfil de usuario (a donde Drupal redirige por defecto).
    if ($route_name === 'entity.user.canonical' && $this->currentUser->isAuthenticated()) {
      // Verificar si el usuario tiene el rol 'administrator'.
      if ($this->currentUser->hasRole('administrator')) {
        $response = new RedirectResponse('/inicio');
        $event->setResponse($response);
      }
    }
  }
}