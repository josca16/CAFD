(function ($, Drupal, once) {
  Drupal.behaviors.cafdPoliticaDetalle = {
    attach: function (context, settings) {
      // Usamos 'once' para asegurar que el código se ejecuta una sola vez
      const $main = $(once('cafd-politica-detalle-behavior', '.cafd-politica-main', context));

      if ($main.length === 0) {
        return; // Salir si el contenedor no se encuentra en el contexto actual
      }

      const $politicaContent = $main.find('.cafd-politica-content');
      const $cards = $main.find('.cafd-politica-card');

      // Ocultar el contenido inicialmente
      $politicaContent.hide();

      $cards.on('click', function (e) {
        e.preventDefault();
        const $clickedCard = $(this);
        const seccionId = $clickedCard.data('seccion-id');

        // Si la tarjeta ya está activa, no hacer nada
        if ($clickedCard.hasClass('is-active')) {
          return;
        }

        // Quitar la clase activa de todas las tarjetas y añadirla a la que se hizo clic
        $cards.removeClass('is-active');
        $clickedCard.addClass('is-active');

        // Realizar la solicitud AJAX para cargar el contenido detallado
        $.ajax({
          url: '/cafd-politica/get-detail/' + seccionId,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response && response.success) {
              // Actualizar el contenido del área de detalle
              if ($politicaContent.is(':hidden')) {
                $politicaContent.html(''); // Limpiar contenido existente

                // Crear y añadir el nuevo título
                const $title = $('<div class="cafd-politica-title"></div>')
                  .text(response.titulo);

                // Crear y añadir la nueva descripción
                const $desc = $('<div class="cafd-politica-desc"></div>')
                  .html(response.descripcion);

                $politicaContent.append($title).append($desc);
                $politicaContent.fadeIn(300);
              } else {
                $politicaContent.fadeOut(200, function() {
                  $politicaContent.html('');
                  const $title = $('<div class="cafd-politica-title"></div>')
                    .text(response.titulo);
                  const $desc = $('<div class="cafd-politica-desc"></div>')
                    .html(response.descripcion);
                  $politicaContent.append($title).append($desc);
                  $politicaContent.fadeIn(300);
                });
              }

              // Desplazar suavemente hacia el área de detalle
              $('html, body').animate({
                scrollTop: $politicaContent.offset().top - 100
              }, 500);
            }
          },
          error: function() {
            // Alternativa si falla AJAX: usar datos de la propia tarjeta
            const titulo = $clickedCard.find('h3').text();
            const descripcionSimple = '<p>' + $clickedCard.find('p').text() + '</p>';

            if ($politicaContent.is(':hidden')) {
              $politicaContent.html('');
              $politicaContent.append(
                $('<div class="cafd-politica-title"></div>').text(titulo)
              ).append(
                $('<div class="cafd-politica-desc"></div>').html(descripcionSimple)
              );
              $politicaContent.fadeIn(300);
            } else {
              $politicaContent.fadeOut(200, function() {
                $politicaContent.html('');
                $politicaContent.append(
                  $('<div class="cafd-politica-title"></div>').text(titulo)
                ).append(
                  $('<div class="cafd-politica-desc"></div>').html(descripcionSimple)
                );
                $politicaContent.fadeIn(300);
              });
            }

            // Desplazar hacia el área de detalle
            $('html, body').animate({
              scrollTop: $politicaContent.offset().top - 100
            }, 500);
          }
        });
      });
    }
  };
})(jQuery, Drupal, once);
