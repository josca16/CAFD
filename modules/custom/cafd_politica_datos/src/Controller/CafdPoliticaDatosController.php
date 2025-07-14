<?php

namespace Drupal\cafd_politica_datos\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller para la página de servicios con vista detallada.
 */
class CafdPoliticaDatosController extends ControllerBase {


  /**
   * Datos de los servicios.
   *
   * @return array
   *   Array con los datos de todos los servicios.
   */
  private function getPoliticaDatos() {
    return [
      [
        'id' => 'proteccion-datos-personales',
        'titulo' => 'Compromiso con la Protección de Datos Personales',
        'descripcion' => 'Nuestro compromiso con la protección de datos personales: "Personas informadas y datos protegidos"',
        'contenido_detallado' => 'La Política de Protección de Datos de Confederación Andaluza de Federaciones Deportivas descansa en el principio de responsabilidad proactiva, según el cual el responsable del tratamiento es responsable del cumplimiento del marco normativo y jurisprudencial que gobierna dicha Política, y es capaz de demostrarlo ante las autoridades de control competentes.
        <br><br>
        En tal sentido, el responsable del tratamiento se regirá por los siguientes principios que deben servir a todo su personal como guía y marco de referencia en el tratamiento de datos personales:
          <ul>
            <li>Protección de datos desde el diseño: el responsable del tratamiento aplicará, tanto en el momento de determinar los medios de tratamiento como en el momento del propio tratamiento, medidas técnicas y organizativas apropiadas, como la seudonimización, concebidas para aplicar de forma efectiva los principios de protección de datos, como la minimización de datos, e integrar las garantías necesarias en el tratamiento.</li>
            <li>Protección de datos por defecto: el responsable del tratamiento aplicará las medidas técnicas y organizativas apropiadas con miras a garantizar que, por defecto, solo sean objeto de tratamiento los datos personales que sean necesarios para cada uno de los fines específicos del tratamiento.</li>
            <li>Protección de datos en el ciclo de vida de la información: las medidas que garanticen la protección de los datos personales serán aplicables durante el ciclo completo de la vida de la información.</li>
            <li>Licitud, lealtad y transparencia: los datos personales serán tratados de manera lícita, leal y transparente en relación con el interesado.</li>
            <li>Limitación de la finalidad: los datos personales serán recogidos con fines determinados, explícitos y legítimos, y no serán tratados ulteriormente de manera incompatible con dichos fines.</li>
            <li>Minimización de datos: los datos personales serán adecuados, pertinentes y limitados a lo necesario en relación con los fines para los que son tratados.</li>
            <li>Exactitud: los datos personales serán exactos y, si fuera necesario, actualizados; se adoptarán todas las medidas razonables para que se supriman o rectifiquen sin dilación los datos personales que sean inexactos con respecto a los fines para los que se tratan.</li>
            <li>Limitación del plazo de conservación: los datos personales serán mantenidos de forma que se permita la identificación de los interesados durante no más tiempo del necesario para los fines del tratamiento de los datos personales.</li>
            <li>Integridad y confidencialidad: los datos personales serán tratados de tal manera que se garantice una seguridad adecuada de los datos personales, incluida la protección contra el tratamiento no autorizado o ilícito y contra su pérdida, destrucción o daño accidental, mediante la aplicación de medidas técnicas u organizativas apropiadas.</li>
            <li>Información y formación: una de las claves para garantizar la protección de los datos personales es la formación e información que se facilite al personal involucrado en el tratamiento de los mismos. Durante el ciclo de vida de la información, todo el personal con acceso a los datos será convenientemente formado e informado acerca de sus obligaciones en relación con el cumplimiento de la normativa de protección de datos.</li>
          </ul>',
        'active' => true,
      ],
      [
        'id' => 'politica-cookies',
        'titulo' => 'Política de Cookies',
        'descripcion' => 'Bienvenida/o a la POLÍTICA DE COOKIES de la página web de la entidad Confederación Andaluza de Federaciones Deportivas, provista de NIF V91460543, donde te explicaremos en un lenguaje claro y sencillo todas las cuestiones necesarias para que puedas tener el control sobre ellas en base a tus decisiones personales.',
        'contenido_detallado' => '<strong> ¿QUÉ SON LAS COOKIES Y PARA QUÉ LAS USAMOS? </strong>
        <br><br>
        Una cookie o galleta informática es un pequeño archivo de información que se guarda en tu ordenador, “smartphone” o tableta cada vez que visitas nuestra página web.
        <br>
        En principio, una cookie es inofensiva: no contiene virus, troyanos, gusanos, etc. que puedan dañar tu terminal, pero sí tiene cierto impacto sobre tu derecho a la protección de tus datos personales, pues recoge determinada información concerniente a tu persona (hábitos de navegación, identidad, preferencias, etc.).
        <br><br>
        Es por ello que, en base a lo establecido en la normativa aplicable (LSSI y normativa vigente de protección de datos personales), la activación de determinados tipos de cookies necesitará de tu autorización previa.
        <br>
        Antes de ello, te daremos alguna información adicional que te ayudará a una mejor toma de decisiones al respecto:
        <br><br>
        1. Las cookies pueden ser de varios tipos en función de su finalidad:
        <br>
        - Las cookies técnicas son necesarias para que nuestra página web pueda funcionar, no necesitan de tu autorización y son las únicas que tenemos activadas por defecto.
        <br>
        - El resto de cookies sirven para mejorar nuestra página, para personalizarla en base a tus preferencias, o para poder mostrarte publicidad ajustada a tus búsquedas, gustos e intereses personales. Puedes aceptar todas estas cookies pulsando el botón ACEPTAR, rechazarlas pulsando el botón RECHAZAR o configurarlas clicando en el apartado CONFIGURACIÓN DE COOKIES.
        <br><br>
        2. Algunas cookies son nuestras (las denominaremos cookies propias) y otras pertenecen a empresas externas que prestan servicios para nuestra página web (las denominaremos cookies de terceros: un ejemplo podrían ser las cookies de proveedores externos como Google). En este sentido, es importante que sepas que algunos de dichos proveedores externos pueden estar ubicados fuera de España.
        <br><br>
        3. Finalmente, indicarte que, en función del plazo de tiempo que permanecen activas, las cookies pueden ser de dos tipos:
        <br>
        <li>Cookies de sesión: expiran automáticamente cuando terminas la sesión en tu ordenador, “smartphone” o tableta. Suelen emplearse para conservar la información necesaria mientras se te presta un servicio en una sola ocasión.</li>
        <li>Cookies persistentes: permanecen almacenadas en tu ordenador, “smartphone” o tableta durante un periodo determinado, que puede variar desde unos minutos hasta varios años.</li>
        <br>
        <strong> ¿QUÉ TIPO DE COOKIES PODEMOS UTILIZAR EN NUESTRA PÁGINA WEB? </strong>
        <br><br>
        <strong>- Cookies Técnicas:</strong>
        <br>
        Las cookies técnicas son estrictamente necesarias para que nuestra página web funcione y puedas navegar por la misma. Este tipo de cookies son las que, por ejemplo, nos permiten identificarte, darte acceso a determinadas partes restringidas de la página si fuese necesario, o recordar diferentes opciones o servicios ya seleccionados por ti, como tus preferencias de privacidad. Por ello, están activadas por defecto, no siendo necesaria tu autorización al respecto.
        <br><br>
        <strong>- Cookies de Análisis:</strong>
        <br>
        Las cookies de análisis nos permiten estudiar la navegación de los usuarios de nuestra página web en general (por ejemplo, qué secciones de la página son las más visitadas, qué servicios se usan más y si funcionan correctamente, etc.).
        <br>
        A partir de la información estadística sobre la navegación en nuestra página web, podemos mejorar tanto el propio funcionamiento de la página como los distintos servicios que ofrece. Por tanto, estas cookies no tienen una finalidad publicitaria, sino que únicamente sirven para que nuestra página web funcione mejor, adaptándose a nuestros usuarios en general. Activándolas contribuirás a dicha mejora continua.
        <br><br>
        <strong>- Cookies de Funcionalidad y Personalización:</strong>
        <br>
        Las cookies de funcionalidad nos permiten recordar tus preferencias, para personalizar a tu medida determinadas características y opciones generales de nuestra página web, cada vez que accedas a la misma (por ejemplo, el idioma en que se te presenta la información, las secciones marcadas como favoritas, tu tipo de navegador, etc.).
        <br>
        Por tanto, este tipo de cookies no tienen una finalidad publicitaria, sino que activándolas mejorarás la funcionalidad de la página web (por ejemplo, adaptándose a tu tipo de navegador) y la personalización de la misma en base a tus preferencias (por ejemplo, presentando la información en el idioma que hayas escogido en anteriores ocasiones), lo cual contribuirá a la facilidad, usabilidad y comodidad de nuestra página durante tu navegación.
        <br><br>
        <strong>- Cookies de Publicidad:</strong>
        <br>
        Las cookies de publicidad nos permiten la gestión de los espacios publicitarios incluidos en nuestra página web en base a criterios como el contenido mostrado o la frecuencia en la que se muestran los anuncios.
        <br>
        Así por ejemplo, si se te ha mostrado varias veces un mismo anuncio en nuestra página web, y no has mostrado un interés personal haciendo clic sobre él, este no volverá a aparecer. En resumen, activando este tipo de cookies, la publicidad mostrada en nuestra página web será más útil y diversa, y menos repetitiva.
        <br><br>
        <strong>- Cookies de Publicidad Comportamental:</strong>
        <br>
        Las cookies de publicidad comportamental nos permiten obtener información basada en la observación de tus hábitos y comportamientos de navegación en la web, a fin de poder mostrarte contenidos publicitarios que se ajusten mejor a tus gustos e intereses personales.
        <br>
        Para que lo entiendas de manera muy sencilla, te pondremos un ejemplo ficticio: si tus últimas búsquedas en la web estuviesen relacionadas con literatura de suspense, te mostraríamos publicidad sobre libros de suspense.
        <br>
        Por tanto, activando este tipo de cookies, la publicidad que te mostremos en nuestra página web no será genérica, sino que estará orientada a tus búsquedas, gustos e intereses, ajustándose por tanto exclusivamente a ti.
        <br><br>
        <strong>¿QUÉ PUEDES HACER CON LAS COOKIES?</strong>
        <br><br>
        Cuando accedes por primera vez a nuestra página web, se te muestra una ventana en la que te informamos que las cookies pueden ser de varios tipos:
        <br>
        <li>Las cookies técnicas son necesarias para que nuestra página web pueda funcionar, no necesitan de tu autorización y son las únicas que tenemos activadas por defecto.</li>
        <li>El resto de cookies sirven para mejorar nuestra página, para personalizarla en base a tus preferencias, o para poder mostrarte publicidad ajustada a tus búsquedas, gustos e intereses personales. Puedes aceptar todas estas cookies pulsando el botón ACEPTAR, rechazarlas pulsando el botón RECHAZAR o configurarlas clicando en el apartado CONFIGURACIÓN DE COOKIES.</li>
        <br>
        Informarte también que, una vez que hayas activado cualquier tipo de cookies, tienes la posibilidad de desactivarlas en el momento que desees, con el simple paso de desmarcar la casilla correspondiente en el apartado CONFIGURACIÓN DE COOKIES de nuestra página web. Siempre será tan sencillo para ti activar nuestras cookies propias como desactivarlas.
        <br>
        Recordarte asimismo que, a través de la configuración de tu navegador, puedes bloquear o alertar de la presencia de cookies, si bien dicho bloqueo puede afectar al correcto funcionamiento de las distintas funcionalidades de nuestra página web en el caso de las cookies técnicas necesarias.
        <br><br>
        Por último, indicarte que, si activas cookies de terceros (empresas externas que prestan servicios para nuestra página web) y posteriormente deseas desactivarlas, podrás hacerlo de dos formas: usando las herramientas de desactivación de cookies de tu navegador o a través de los propios sistemas habilitados por dichos proveedores externos.
        <br>
        Para que te sea mucho más fácil, a continuación te relacionamos una serie de enlaces a las pautas de desactivación de cookies de los navegadores de uso común:
        <ul>
          <li><a href="https://support.google.com/chrome/answer/95647?hl=es">Google Chrome</a></li>
          <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we">Mozilla Firefox</a></li>
          <li><a href="https://support.microsoft.com/es-es/help/278835/how-to-delete-cookie-files-in-internet-explorer">Internet Explorer</a></li>
          <li><a href="https://support.apple.com/es-es/HT201265">Safari</a></li>
          <li><a href="https://support.apple.com/es-es/105082">Safari para IOS (iPhone y iPad)</a></li>
          <li><a href="https://help.opera.com/en/latest/web-preferences/#cookies">Opera</a></li>
          <li><a href="https://support.google.com/accounts/answer/61416?co=GENIE.Platform%3DAndroid&hl=es&oco=1">Google Chrome para Android</a></li>
          <li><a href="https://support.google.com/accounts/answer/61416?co=GENIE.Platform%3DiOS&hl=es&oco=1">Google Chrome para Iphone y Ipad.</a></li>
        </ul>',
        'active' => false,
      ],
      [
        'id' => 'politica-privacidad',
        'titulo' => 'Política de Privacidad',
        'descripcion' => 'Información en cumplimiento de la normativa de protección de datos personales',
        'contenido_detallado' => 'En Europa y en España existen normas de protección de datos pensadas para proteger su información personal de obligado cumplimiento para nuestra entidad.
        <br>
        Por ello, es muy importante para nosotros que entienda perfectamente qué vamos a hacer con los datos personales que le pedimos.
        <br>
        Así, seremos transparentes y le daremos el control de sus datos, con un lenguaje sencillo y opciones claras que le permitirán decidir qué haremos con su información personal.
        <br>
        Por favor, si una vez leída la presente información le queda alguna duda, no dude en preguntarnos.
        <br>
        Muchas gracias por su colaboración.
        <br><br>
        <strong>¿Quiénes somos?</strong>
        <br><br>
        <ul>
          <li>Nuestra denominación: Confederación Andaluza de Federaciones Deportivas</li>
          <li>Nuestro CIF / NIF: V91460543</li>
          <li>Nuestra actividad principal: CONFEDERACIÓN</li>
          <li>Nuestra dirección: Calle Benidorm, nº 5 escalera 1, 2º A, 41001 - Sevilla (Sevilla)</li>
          <li>Nuestro teléfono de contacto: 954460110</li>
          <li>Nuestra dirección de correo electrónico de contacto: ADMINISTRACION@CAFD.ES</li>
          <li>Nuestra página web: https://cafd.es/</li>
        </ul>
        <br>
        Para su confianza y seguridad, le informamos que somos una entidad inscrita en el siguiente Registro Mercantil /Registro Público:
        <br>
        Nuestra actividad principal está sujeta a un régimen de autorización administrativa previa. Para su confianza y seguridad, le facilitamos los datos relativos a dicha autorización y los identificativos del órgano competente de nuestra supervisión:
        <br>
        <strong>Autorización administrativa:</strong>
        <br>
        <strong>Órgano encargado de nuestra supervisión:</strong>
        <br><br>
        El responsable de esta página web ejerce una profesión regulada, para lo cual le facilitamos la siguiente información:
        <br>
        <strong>Colegio profesional al que pertenece:</strong>
        <br>
        <strong>Título académico oficial o profesional:</strong>
        <br>
        <strong>Estado de la Unión Europea en que se expidió dicho título:</strong>
        <br>
        <strong>Normas profesionales aplicables al ejercicio de la profesión:</strong>
        <br><br>
        <strong>¿Para qué vamos a usar sus datos?</strong>
        <br><br>
        Con carácter general, sus datos personales serán usados para poder relacionarnos con usted y poder prestarle nuestros servicios.
        <br>
        Asimismo, también pueden ser usados para otras actividades, como enviarle publicidad o promocionar nuestras actividades.
        <br><br>
        <strong>¿¿Por qué necesitamos usar sus datos?</strong>
        <br><br>
        Sus datos personales son necesarios para poder relacionarnos con usted y poder prestarle nuestros servicios. En este sentido, pondremos a su disposición una serie de casillas que le permitirán decidir de manera clara y sencilla sobre el uso de su información personal.
        <br><br>
        <strong>¿Quién va a conocer la información que le pedimos?</strong>
        <br><br>
        Con carácter general, sólo el personal de nuestra entidad que esté debidamente autorizado podrá tener conocimiento de la información que le pedimos.
        <br>
        De igual modo, podrán tener conocimiento de su información personal aquellas entidades que necesiten tener acceso a la misma para que podamos prestarle nuestros servicios. Así por ejemplo, nuestro banco conocerá sus datos si el pago de nuestros servicios se realiza mediante tarjeta o transferencia bancaria.
        <br>
        Asimismo, tendrán conocimiento de su información aquellas entidades públicas o privadas a las cuales estemos obligados a facilitar sus datos personales con motivo del cumplimiento de alguna ley. Poniéndole un ejemplo, la Ley Tributaria obliga a facilitar a la Agencia Tributaria determinada información sobre operaciones económicas que superen una determinada cantidad.
        <br>
        En el caso de que, al margen de los supuestos comentados, necesitemos dar a conocer su información personal a otras entidades, le solicitaremos previamente su permiso a través de opciones claras que le permitirán decidir a este respecto.
        <br><br>
        <strong>¿Cómo vamos a proteger sus datos?</strong>
        <br><br>
        Protegeremos sus datos con medidas de seguridad eficaces en función de los riesgos que conlleve el uso de su información.
        <br>
        Para ello, nuestra entidad ha aprobado una Política de Protección de Datos y se realizan controles y auditorías anuales para verificar que sus datos personales están seguros en todo momento.
        <br><br>
        <strong>¿Enviaremos sus datos a otros países?</strong>
        <br><br>
        En el mundo hay países que son seguros para sus datos y otros que no lo son tanto. Así por ejemplo, la Unión Europea es un entorno seguro para sus datos. Nuestra política es no enviar su información personal a ningún país que no sea seguro desde el punto de vista de la protección de sus datos.
        <br>
        En el caso de que, con motivo de prestarle el servicio, sea imprescindible enviar sus datos a un país que no sea tan seguro como España, siempre le solicitaremos previamente su permiso y aplicaremos medidas de seguridad eficaces que reduzcan los riesgos del envío de su información personal a otro país.
        <br><br>
        <strong>¿Durante cuánto tiempo vamos a conservar sus datos?</strong>
        <br><br>
        Conservaremos sus datos durante nuestra relación y mientras nos obliguen las leyes. Una vez finalizados los plazos legales aplicables, procederemos a eliminarlos de forma segura y respetuosa con el medio ambiente.
        <br><br>
        <strong>¿Cuáles son sus derechos de protección de datos?</strong>
        <br><br>
        En cualquier momento puede dirigirse a nosotros para saber qué información tenemos sobre usted, rectificarla si fuese incorrecta y eliminarla una vez finalizada nuestra relación, en el caso de que ello sea legalmente posible.
        <br>
        También tiene derecho a solicitar el traspaso de su información a otra entidad. Este derecho se llama “portabilidad” y puede ser útil en determinadas situaciones.
        <br>
        Para solicitar alguno de estos derechos, deberá realizar una solicitud escrita a nuestra dirección, para poder identificarle.
        <br>
        En las oficinas de nuestra entidad disponemos de formularios específicos para solicitar dichos derechos y le ofrecemos nuestra ayuda para su cumplimentación.
        <br>
        Para saber más sobre sus derechos de protección de datos, puede consultar la página web de la Agencia Española de Protección de Datos (www.aepd.es).
        <br><br>
        <strong>¿Puede retirar su consentimiento si cambia de opinión en un momento posterior?</strong>
        <br><br>
        Usted puede retirar su consentimiento si cambia de opinión sobre el uso de sus datos en cualquier momento.
        <br>
        Así por ejemplo, si usted en su día estuvo interesado/a en recibir publicidad de nuestros productos o servicios, pero ya no desea recibir más publicidad, puede hacérnoslo constar a través del formulario de oposición al tratamiento disponible en las oficinas de nuestra entidad.
        <br><br>
        <strong>En caso de que entienda que sus derechos han sido desatendidos, ¿dónde puede formular una reclamación?</strong>
        <br><br>
        En caso de que entienda que sus derechos han sido desatendidos por nuestra entidad, puede formular una reclamación en la Agencia Española de Protección de Datos, a través de alguno de los medios siguientes:
        <ul>
          <li>Sede electrónica: www.aepd.es</li>
          <li>Dirección postal: Agencia Española de Protección de Datos, C/ Jorge Juan, 6, 28001-Madrid</li>
          <li>Vía telefónica: Telf. 901 100 099 / Telf. 91 266 35 17</li>
        </ul>
        Formular una reclamación en la Agencia Española de Protección de Datos no conlleva ningún coste y no es necesaria la asistencia de abogado ni procurador.
        <br><br>
        <strong>¿Elaboraremos perfiles sobre usted?</strong>
        <br><br>
        Nuestra política es no elaborar perfiles sobre los usuarios de nuestros servicios.
        <br>
        No obstante, pueden existir situaciones en las que, con fines de prestación del servicio, comerciales o de otro tipo, necesitemos elaborar perfiles de información sobre usted. Un ejemplo pudiera ser la utilización de su historial de compras o servicios para poder ofrecerle productos o servicios adaptados a sus gustos o necesidades.
        <br>
        En tal caso, aplicaremos medidas de seguridad eficaces que protejan su información en todo momento de personas no autorizadas que pretendan utilizarla en su propio beneficio.
        <br><br>
        <strong>¿Usaremos sus datos para otros fines?</strong>
        <br><br>
        Nuestra política es no usar sus datos para otras finalidades distintas a las que le hemos explicado. Si, no obstante, necesitásemos usar sus datos para actividades distintas, siempre le solicitaremos previamente su permiso a través de opciones claras que le permitirán decidir al respecto.',
        'active' => false,
      ],
    ];
  }

  /**
   * Muestra la página de servicios.
   *
   * @return array
   *   Un array renderizable de Drupal.
   */
  public function page() {
    return [
      '#theme' => 'cafd_politica_datos_page',
      '#politica_data' => $this->getPoliticaDatos(),
      '#attached' => [
        'library' => [
          'cafd_politica_datos/detalle_politica',
        ],
      ],
    ];
  }

  /**
   * Devuelve los datos de un servicio específico en formato JSON.
   *
   * @param string $servicio_id
   *   El ID del servicio solicitado.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Respuesta JSON con los datos del servicio.
   */
public function getPoliticaDetalle($seccion_id) {
    $politicaDatos = $this->getPoliticaDatos();
    
    foreach ($politicaDatos as $seccion) {
        if ($seccion['id'] === $seccion_id) {
            return new JsonResponse([
                'success' => TRUE,
                'id' => $seccion['id'],
                'titulo' => $seccion['titulo'],
                'descripcion' => $seccion['contenido_detallado'],
            ]);
        }
    }
    
    return new JsonResponse([
        'success' => FALSE,
        'message' => 'Sección de política no encontrada',
    ], 404);
}
}