document.addEventListener("DOMContentLoaded", function () {
  // Menú hamburguesa móvil
  const openMenuBtn = document.getElementById("openMenu");
  const closeMenuBtn = document.getElementById("closeMenu");
  const mobileMenu = document.getElementById("mobileMenu");
  const tabletMenu = document.getElementById("tabletMenu");

  function isMobile() {
    return window.innerWidth <= 768;
  }
  function isTablet() {
    return window.innerWidth >= 769 && window.innerWidth <= 1100;
  }

  // Menú móvil: solo se abre/cierra en móvil
  if (openMenuBtn && closeMenuBtn && mobileMenu) {
    openMenuBtn.addEventListener("click", function () {
      if (isMobile()) {
        mobileMenu.classList.add("open");
      }
    });

    closeMenuBtn.addEventListener("click", function () {
      mobileMenu.classList.remove("open");
    });

    // Cierra el menú móvil si cambia de tamaño fuera de móvil
    window.addEventListener("resize", function () {
      if (!isMobile()) {
        mobileMenu.classList.remove("open");
      }
    });
  }

  // Menú tablet: solo se abre/cierra en tablet
  if (openMenuBtn && tabletMenu) {
    openMenuBtn.addEventListener("click", function (e) {
      if (isTablet()) {
        e.stopPropagation();
        tabletMenu.classList.toggle("open");
      }
    });

    // Cierra el menú si se hace clic fuera
    document.addEventListener("click", function (e) {
      if (
        isTablet() &&
        tabletMenu.classList.contains("open") &&
        !tabletMenu.contains(e.target) &&
        !openMenuBtn.contains(e.target)
      ) {
        tabletMenu.classList.remove("open");
      }
    });

    // Cierra el menú tablet si cambia de tamaño fuera de tablet
    window.addEventListener("resize", function () {
      if (!isTablet()) {
        tabletMenu.classList.remove("open");
      }
    });
  }
});