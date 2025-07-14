<?php

namespace Drupal\cafd_servicios\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller para la página de servicios con vista detallada.
 */
class CafdServiciosController extends ControllerBase {

  /**
   * Datos de los servicios.
   *
   * @return array
   *   Array con los datos de todos los servicios.
   */
  private function getServiciosData() {
    return [
      [
        'id' => 'asesoria-laboral',
        'titulo' => 'Asesoría Laboral',
        'descripcion' => 'Desde el área laboral, la CAFD le ofrece el servicio de asesoramiento y gestión administrativa para un óptimo desarrollo de su departamento de recursos humanos.',
        'contenido_detallado' => 'Desde el área laboral, la CAFD le ofrece el servicio de asesoramiento y gestión administrativa para un óptimo desarrollo de su departamento de recursos humanos.
          <ul>
            <li>Inscripción alta empresa en seguridad social</li>
            <li>Elaboración de contratos y liquidación de trabajadores</li>
            <li>Estudio y aplicación de convenio colectivo</li>
            <li>Confección de nóminas y seguros sociales</li>
            <li>Tramitación de partes de incapacidad (Enfermedad Común y Accidente Laboral)</li>
            <li>Resolución de conflictos laborales.</li>
            <li>Presentación de impuestos Hacienda.</li>
            <li>Presupuestos e informes de situación mensual.</li>
            <li>Prevención de Riesgos Laborales</li>
            <li>Inspecciones de Trabajo.</li>
            <li>Asesoramiento Jurídico-Laboral</li>
            <li>Tramitación de prestaciones (maternidad, paternidad, incapacidad, jubilación...)</li>
          </ul>',
        'active' => true,
      ],
      [
        'id' => 'asesoria-contable-fiscal',
        'titulo' => 'Asesoría Contable y Fiscal',
        'descripcion' => 'Este departamento te garantiza el control económico de tu entidad deportiva, máxima transparencia y fiabilidad de los datos contables y fiscales.',
        'contenido_detallado' => 'Le garantizamos un óptimo funcionamiento CONTABLE Y FISCAL de su entidad deportiva consiguiendo su control económico, máxima transparencia y fiabilidad de los datos contables y fiscales.
          <ul>
            <li>Contabilidad de Federaciones Deportivas (P.G.C de 2 de febrero de 1994). Adaptados en lo necesario al Plan General de Contabilidad aprobado por el Real Decreto 1514/2007, de 16 de noviembre.</li>
            <li>Contabilidad de Federaciones Deportivas acogidas a la Ley 49/2002.</li>
            <li>Cuentas Anuales (Balance de Situación, Cuenta de Resultados, Memoria Económica), para su presentación a la Asamblea y Agencia Tributaria.</li>
            <li>Asesoramiento personal al departamento contable.</li>
            <li>Obligaciones fiscales de Federaciones Deportivas acogidas a la Ley 49/2002.</li>
            <li>Obligaciones fiscales de Federaciones Deportivas parcialmente exentas en el impuesto sobre sociedades.</li>
            <li>Tratamiento fiscal y contable de Federaciones Deportivas que realizan actividad económica.</li>
            <li>Asesoramiento en las obligaciones contables y fiscales de Patrocinios, Convenios de Colaboración y Donaciones.</li>
            <li>Contabilidad y fiscalidad de clubes deportivos.</li>
            <li>Informe de Verificación Contable del Gasto, obligatorio cada dos años y recomendable de forma anual.</li>
          </ul>',
        'active' => false,
      ],
      [
        'id' => 'asesoria-juridico-deportiva',
        'titulo' => 'Asesoría Jurídico-Deportiva',
        'descripcion' => 'Con la Asesoría Jurídica CAFD podrás obtener una asistencia integral dentro del marco Jurídico Deportivo de tu entidad.',
        'contenido_detallado' => 'Con la CAFD podrás obtener una asistencia integral dentro del marco Jurídico Deportivo de su entidad, tanto desde el punto de vista como gestor deportivo hasta el punto de vista estrictamente deportivo.
          <ul>
            <li>Asesoramiento tipología actividad económica.</li>
            <li>Asesoramiento documental de la entidad: Elaboración y modificación.</li>
            <li>Registro de Asociación Deportiva: Tramites.</li>
            <li>Órganos: Convocatorias.</li>
            <li>Subvenciones: Tramitación y Justificaciones.</li>
            <li>Responsabilidad.</li>
            <li>Certificado Digital. Tramites de solicitud.</li>
            <li>Ley de Protección de Datos.</li>
            <li>Defensa Jurídica.</li>
            <li>Reclamaciones administrativas.</li>
            <li>Trámites ante el Registro Andaluz de Entidades Deportivas.</li>
            <li>Protección de Datos.</li>
            <li>Procesos electorales.</li>
            <li>Transparencia y buen gobierno.</li>
            <li>Relaciones con la Administración Pública.</li>
            <li>Recursos administrativos.</li>
            <li>Contratos.</li>
            <li>Disciplina deportiva.</li>
          </ul>',
        'active' => false,
      ],
      [
        'id' => 'marketing-comunicacion',
        'titulo' => 'Marketing y Comunicación',
        'descripcion' => 'La CAFD ofrece un asesoramiento completo en servicios de marketing y comunicación para cubrir las necesidades del deporte federado.',
        'contenido_detallado' => 'La CAFD ofrece un asesoramiento completo en servicios de marketing y comunicación para cubrir las necesidades del deporte federado en la obtención de un retorno de la inversión y máxima difusión entre sus púbicos objetivos.
          <ul>
            <li>Gabinete de prensa.</li>
            <li>Asesoramiento en patrocinios.</li>
            <li>Gestión de campañas de marketing y publicidad.</li>
            <li>Estrategia en Redes Sociales.</li>
            <li>Organización de Eventos.</li>
            <li>Servicios Audiovisuales:
              <ul>
                <li>Grabación y retransmisión de eventos en streaming.</li>
                <li>Realización de vídeos corporativos</li>
                <li>Grabación y gestión de canal propio en Youtube</li>
              </ul>
            </li>
          </ul>',
        'active' => false,
      ],
      [
        'id' => 'seguros',
        'titulo' => 'Seguros',
        'descripcion' => 'Unifedan, especialistas en seguros deportivos. Te asesoramos en la contratación de todo tipo de seguros.',
        'contenido_detallado' => '',
        'external_url' => 'https://www.unifedan.com',
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
      '#theme' => 'cafd_servicios_page',
      '#servicios_data' => $this->getServiciosData(),
      '#attached' => [
        'library' => [
          'cafd_servicios/detalle_servicios',
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
  public function getServiceDetail($servicio_id) {
    $servicios = $this->getServiciosData();
    
    // Buscar el servicio con el ID proporcionado
    foreach ($servicios as $servicio) {
      if ($servicio['id'] === $servicio_id) {
        // Si es el servicio de seguros, devolver la URL externa
        if ($servicio['id'] === 'seguros') {
          return new JsonResponse([
            'success' => TRUE,
            'redirect' => $servicio['external_url'],
          ]);
        }
        
        return new JsonResponse([
          'success' => TRUE,
          'id' => $servicio['id'],
          'titulo' => $servicio['titulo'],
          'descripcion' => $servicio['contenido_detallado'],
        ]);
      }
    }
    
    // Si no se encuentra el servicio, devolver error
    return new JsonResponse([
      'success' => FALSE,
      'message' => 'Servicio no encontrado',
    ], 404);
  }
}