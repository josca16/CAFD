(function ($, Drupal) {
  Drupal.behaviors.transparenciaLogic = { // Cambié el nombre del comportamiento a uno más genérico si abarca ambas funciones
    attach: function (context, settings) {

      // --- Funciones auxiliares internas al comportamiento ---
      // Estas funciones NO son accesibles directamente desde el HTML con 'onclick'.
      // Solo son llamadas por los event listeners que se adjuntan aquí en JS.
      function toggleCollapse(button) {
        const content = button.nextElementSibling;
        const arrow = button.querySelector(".flecha");

        const computedStyle = window.getComputedStyle(content);
        const isVisible = computedStyle.display !== "none";

        content.style.display = isVisible ? "none" : "block";
        arrow.style.transform = isVisible ? "rotate(0deg)" : "rotate(180deg)";

        const iconoCerrado = button.querySelector('.icono-folder-cerrado');
        const iconoAbierto = button.querySelector('.icono-folder-abierto');
        if (iconoCerrado && iconoAbierto) {
          iconoCerrado.style.display = isVisible ? 'inline' : 'none';
          iconoAbierto.style.display = isVisible ? 'none' : 'inline';
        }
      }

      function toggleExpand(button) {
        const lista = button.closest('.contenido-desplegable');
        const ocultos = lista.querySelector('.ocultos');

        if (lista.classList.contains('expandida')) {
          lista.classList.remove('expandida');
          lista.classList.add('colapsada');
          ocultos.style.display = 'none';
          button.textContent = 'Ver más';
        } else {
          lista.classList.remove('colapsada');
          lista.classList.add('expandida');
          ocultos.style.display = 'block';
          button.textContent = 'Ver menos';
        }
      }
      // --- Fin de funciones auxiliares ---


      // --- Enlace de eventos con .once() ---
      // 1. Para los botones que colapsan/despliegan el contenido
      $(once('transparencia-collapse', '.desplegable-btn', context)).on('click', function () {
        toggleCollapse(this);
      });

      // 2. Para los botones que "Ver más"/"Ver menos"

      $(once('transparencia-expand', '.ver-mas-btn', context)).on('click', function () {
        toggleExpand(this);
      });
      // --- Fin de enlace de eventos ---

    }
  };
})(jQuery, Drupal);