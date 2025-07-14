(function ($, Drupal) {
    Drupal.behaviors.custom_theme = {
        attach: function (context, settings) {

            function setCookie(nombre, valor, segundos) {
                const fecha = new Date();
                fecha.setTime(fecha.getTime() + (segundos * 1000));
                const expires = "expires=" + fecha.toUTCString();
                document.cookie = nombre + "=" + valor + ";" + expires + ";path=/";
            }

            function getCookie(nombre) {
                const nombreEQ = nombre + "=";
                const ca = document.cookie.split(";");
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === " ") c = c.substring(1);
                    if (c.indexOf(nombreEQ) === 0) return c.substring(nombreEQ.length);
                }
                return null;
            }

            const cookiePopup = document.getElementById("cookie-popup");
            const cookieOverlay = document.getElementById("cookie-overlay");
            const acceptButton = document.getElementById("accept-cookies");
            const rejectButton = document.getElementById("reject-cookies");
            const preferencesButton = document.getElementById("preferences-cookies");
            const savePreferencesButton = document.getElementById("save-preferences");
            const cookiePreferences = document.getElementById("cookie-preferences");
            const mensajeConfirmacion = document.getElementById("mensaje-confirmacion");
            const enlacePolitica = document.getElementById("enlace-politica");

            const preferencias = document.getElementById("cookies-preferencias");
            const marketing = document.getElementById("cookies-marketing");
            const funcionales = document.getElementById("cookies-funcionales");

            function savePreferences() {
                const preferences = {
                    preferencias: preferencias.checked,
                    marketing: marketing.checked,
                    funcionales: funcionales.checked
                };
                localStorage.setItem("cookiesPreferences", JSON.stringify(preferences));
                mensajeConfirmacion.style.display = "block";
                setTimeout(() => {
                    mensajeConfirmacion.style.display = "none";
                }, 3000);
            }

            preferencesButton.addEventListener("click", function () {
                cookiePreferences.style.display = "block";
                preferencesButton.style.display = "none";
            });

            const cookiesAccepted = localStorage.getItem("cookiesAccepted");
            const cookiesViewed = getCookie("cookiesViewed"); // ahora desde cookie

            if ((!cookiesAccepted || cookiesAccepted === "false") && !cookiesViewed) {
                cookiePopup.style.display = "block";
                cookieOverlay.style.display = "block";
                document.body.classList.add("cookies-active");
            }

            acceptButton.addEventListener("click", function () {
                localStorage.setItem("cookiesAccepted", "true");
                localStorage.setItem("cookiesPreferences", JSON.stringify({
                    preferencias: true,
                    marketing: true
                }));
                setCookie("cookiesViewed", "true", 86400); // opcional: 1 día
                cookiePopup.style.display = "none";
                cookieOverlay.style.display = "none";
                document.body.classList.remove("cookies-active");
            });

            rejectButton.addEventListener("click", function () {
                localStorage.setItem("cookiesAccepted", "false");
                localStorage.setItem("cookiesPreferences", JSON.stringify({
                    preferencias: false,
                    marketing: false
                }));
                setCookie("cookiesViewed", "true", 86400);
                cookiePopup.style.display = "none";
                cookieOverlay.style.display = "none";
                document.body.classList.remove("cookies-active");
            });

            savePreferencesButton.addEventListener("click", savePreferences);

            // Nuevo: política con cookie de 1 segundos
            if (enlacePolitica) {
                enlacePolitica.addEventListener("click", function (e) {
                    e.preventDefault();
                    setCookie("cookiesViewed", "true", 1); // 10 segundos
                    cookiePopup.style.display = "none";
                    cookieOverlay.style.display = "none";
                    document.body.classList.remove("cookies-active");
                    window.location.href = this.href;
                });
            }
        }
    };
})(jQuery, Drupal);











