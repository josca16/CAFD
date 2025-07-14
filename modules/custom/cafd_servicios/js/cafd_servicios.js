(function ($, Drupal, once) {
  Drupal.behaviors.cafdServiciosDetalle = {
    attach: function (context, settings) {
      // Usamos 'once' para asegurar que el código se ejecuta una sola vez
      const $main = $(once('cafd-servicios-detalle-behavior', '.cafd-servicios-main', context));

      if ($main.length === 0) {
        return; // Salir si el contenedor no se encuentra en el contexto actual
      }

      const $serviciosContent = $main.find('.cafd-servicios-content');
      const $cards = $main.find('.cafd-servicio-card');

      // Ocultar el contenido inicialmente
      $serviciosContent.hide();

      $cards.on('click', function (e) {
        e.preventDefault(); 
        const $clickedCard = $(this);
        const servicioId = $clickedCard.data('servicio-id');
        
        // Si la tarjeta ya está activa, no hacer nada
        if ($clickedCard.hasClass('is-active')) {
          return;
        }
        
        // Quitar la clase activa de todas las tarjetas y añadirla a la que se hizo clic
        $cards.removeClass('is-active');
        $clickedCard.addClass('is-active');
        
        // Realizar la solicitud AJAX para cargar el contenido detallado
        $.ajax({
          url: '/cafd-servicios/get-detail/' + servicioId, 
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response && response.success) {
              // Si hay una URL de redirección (caso de seguros)
              if (response.redirect) {
                window.open(response.redirect, '_blank');
                return;
              }

              // Actualizar el contenido del área de detalle
              if ($serviciosContent.is(':hidden')) {
                $serviciosContent.html(''); // Limpiar contenido existente
                
                // Crear y añadir el nuevo título
                const $title = $('<div class="cafd-servicios-laboral"></div>')
                  .text(response.titulo);
                
                // Crear y añadir la nueva descripción
                const $desc = $('<div class="cafd-servicios-desc"></div>')
                  .html(response.descripcion);
                
                $serviciosContent.append($title).append($desc);
                $serviciosContent.fadeIn(300);
              } else {
                $serviciosContent.fadeOut(200, function() {
                  $serviciosContent.html('');
                  const $title = $('<div class="cafd-servicios-laboral"></div>')
                    .text(response.titulo);
                  const $desc = $('<div class="cafd-servicios-desc"></div>')
                    .html(response.descripcion);
                  $serviciosContent.append($title).append($desc);
                  $serviciosContent.fadeIn(300);
                });
              }
              
              // Desplazar suavemente hacia el área de detalle
              $('html, body').animate({
                scrollTop: $serviciosContent.offset().top - 100
              }, 500);
            } 
          },
          error: function() {
            // Alternativa si falla AJAX: usar datos de la propia tarjeta
            const titulo = $clickedCard.find('h3').text();
            const descripcionSimple = '<p>' + $clickedCard.find('p').text() + '</p>';
            
            if ($serviciosContent.is(':hidden')) {
              $serviciosContent.html('');
              $serviciosContent.append(
                $('<div class="cafd-servicios-laboral"></div>').text(titulo)
              ).append(
                $('<div class="cafd-servicios-desc"></div>').html(descripcionSimple)
              );
              $serviciosContent.fadeIn(300);
            } else {
              $serviciosContent.fadeOut(200, function() {
                $serviciosContent.html('');
                $serviciosContent.append(
                  $('<div class="cafd-servicios-laboral"></div>').text(titulo)
                ).append(
                  $('<div class="cafd-servicios-desc"></div>').html(descripcionSimple)
                );
                $serviciosContent.fadeIn(300);
              });
            }
            
            // Desplazar hacia el área de detalle
            $('html, body').animate({
              scrollTop: $serviciosContent.offset().top - 100
            }, 500);
          }
        });
      });
    }
  };
})(jQuery, Drupal, once);