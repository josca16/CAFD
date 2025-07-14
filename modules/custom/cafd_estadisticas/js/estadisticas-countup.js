(function (Drupal, once) {
  Drupal.behaviors.cafdEstadisticasCountup = {
    attach: function (context, settings) {
      const elements = once('countup-observer', '.estadistica-numero', context);
      if (elements.length === 0) return;

      const observer = new IntersectionObserver(function (entries, observer) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const el = entry.target;
            const endVal = parseInt(el.textContent.replace(/\./g, '').replace(/,/g, ''));
            if (!isNaN(endVal)) {
              const counter = new countUp.CountUp(el, endVal, {
                duration: 4,
                useEasing: true,
                separator: '.',
              });
              if (!counter.error) {
                counter.start();
              } else {
                console.error(counter.error);
              }
            }
            observer.unobserve(el); // Evita que se vuelva a animar
          }
        });
      }, {
        threshold: 0.6, // Solo cuando el 60% del elemento esté visible
      });

      elements.forEach(el => {
        observer.observe(el);
      });
    }
  };
})(Drupal, once);
