// Función para alternar el colapso de las respuestas
function toggleCollapse(button) {
    const respuesta = button.nextElementSibling; // Selecciona el elemento siguiente (la respuesta)
    const flechaAbajo = button.querySelector('.flecha-abajo');
    const flechaArriba = button.querySelector('.flecha-arriba');

    // Verifica el estado actual de la respuesta
    if (respuesta.style.maxHeight === '0px' || respuesta.style.maxHeight === '') {
        respuesta.style.maxHeight = respuesta.scrollHeight + 'px'; // Despliega la respuesta
        flechaAbajo.style.display = 'none'; // Oculta la flecha hacia abajo
        flechaArriba.style.display = 'inline-block'; // Muestra la flecha hacia arriba
    } else {
        respuesta.style.maxHeight = '0px'; // Oculta la respuesta
        flechaAbajo.style.display = 'inline-block'; // Muestra la flecha hacia abajo
        flechaArriba.style.display = 'none'; // Oculta la flecha hacia arriba
    }
}

// Función para expandir todas las respuestas (opcional)
function toggleExpand(button) {
    const preguntas = document.querySelectorAll(".pregunta .respuesta");

    preguntas.forEach((respuesta) => {
        respuesta.style.maxHeight = respuesta.scrollHeight + "px"; // Expande todas las respuestas
    });

    button.classList.add("expanded"); // Cambia el estilo del botón si es necesario
}