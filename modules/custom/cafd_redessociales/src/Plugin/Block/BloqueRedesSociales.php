<?php

namespace Drupal\cafd_redessociales\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a unified block for all social media feeds.
 *
 * @Block(
 *   id = "bloque_redes_sociales",
 *   admin_label = @Translation("Redes Sociales - Sección Completa")
 * )
 */
class BloqueRedesSociales extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'inline_template',
      '#template' => '
        <section class="bloque-redes-sociales">
          <h2>Nuestras Redes Sociales</h2>
          <div class="redes-grid">
            <div class="red-social instagram">
              <h3>Instagram</h3>
              <div class="feed-contenido instagram-container">
                <iframe 
                  src="https://www.instagram.com/cafd_oficial/embed"
                  width="340" 
                  height="500" 
                  frameborder="0" 
                  scrolling="no" 
                  allowtransparency="true"
                  allow="encrypted-media">
                </iframe>
              </div>
            </div>
            <div class="red-social facebook">
              <h3>Facebook</h3>
              <div class="feed-contenido">
                <div id="fb-root"></div>
                <div class="fb-page" 
                    data-href="https://www.facebook.com/Cafdsocial" 
                    data-tabs="timeline" 
                    data-width="340" 
                    data-height="500" 
                    data-small-header="false" 
                    data-adapt-container-width="true" 
                    data-hide-cover="false" 
                    data-show-facepile="true">
                  <blockquote cite="https://www.facebook.com/Cafdsocial" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/Cafdsocial">Confederación Andaluza de Federaciones Deportivas</a>
                  </blockquote>
                </div>
              </div>
            </div>
          </div>
        </section>
      ',
      '#attached' => [
        'library' => [
          'cafd_redessociales/facebook_sdk',
        ],
      ],
      '#cache' => ['max-age' => 0],
    ];
  }
}

