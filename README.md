# 🌀 CAFD WEB (Confederación Andaluza de Federaciones Deportivas)
El proyecto usa Docker Compose para levantar de forma sencilla el entorno de desarrollo de Drupal y su base de datos.
- **Versión Drupal:** Drupal 10
- **Base de datos:** Mariadb 10.6

---

# 🚀 Pasos para levantar el proyecto
1. Clonar el repositorio
   - `git clone git@github.com:CodeArts-Solutions/CAFD.git`
   - `cd CAFD`
2. Levantar los contenedores
   1.  Si ya tenías uno antiguo primero elimínalo:
       - `docker compose down -v`
   2. Levanta`docker compose up -d`
       - Si te falla la primera vez, tienes dos opciones:
         1. Borrar la carpeta completamente (CAFD) y levantar `docker compose -d`.
         2. `docker compose down -v` y levantar la primera vez con `docker compose up -d --build`
3. Acceso local
   - http://localhost:8080
---
### 🔒 Credenciales iniciales de administrador
- **Usuario**: admin
- **Contraseña**: admin

---
### 🛑 Detener el entorno
Para detener el proyecto usa:
- `docker compose down`

---

## 📦 Instalador Interactivo para Drupal CAFD

Este script `drupal-installer.sh` proporciona una herramienta interactiva en **Bash** para gestionar entornos de desarrollo Drupal basados en Docker. Es adaptable a **cualquier proyecto Drupal 9+** gracias a la definición de variables configurables al inicio del script.

Ofrece un menú en consola (en español) para tareas comunes como levantar contenedores, activar temas y módulos personalizados, limpiar caché, y consultar chuletas de comandos Git y Docker.

---

### 🎯 Objetivos

- Facilitar la puesta en marcha del entorno Drupal sin necesidad de conocimientos técnicos avanzados.
- Automatizar tareas repetitivas como levantar contenedores, activar configuraciones y limpiar caché.
- Servir como herramienta de apoyo y formación para nuevos desarrolladores del proyecto.

---

### 🧰 Características

- Menú interactivo en español.
- Gestión de contenedores Docker (`up`, `down`, `down -v`).
- Instalación de tema y módulos personalizados vía Drush.
- Verificación del estado de Apache.
- Chuletas integradas de Git y Docker.
- Detección de rama actual de Git.
- Soporte para ejecución directa por parámetro (`-l`, `-t`, etc.).
- Colores y estilo para mejor usabilidad en consola.

---

### ⚙️ Requisitos

- Docker & Docker Compose instalados.
- Git (para mostrar ramas y chuletas).
- Bash (probado en Linux y Windows).
- Proyecto `CAFD` clonado localmente.

---

### 🔧 Personalización
Antes de ejecutar, ajusta las siguientes variables del script según tu entorno y proyecto:

```bash
PROJECT_NAME="NombreDeTuProyecto"
THEME_NAME="nombre_del_tema"
CONTAINER_DRUPAL="nombre_contenedor_drupal"
CONTAINER_DB="nombre_contenedor_db"

MOD_UI="nombre_modulo_1"
MOD_OTRO="nombre_modulo_2"
# etc.
```

### 🛠️ Instalación

```bash
# LINUX
git clone git@github.com:CodeArts-Solutions/CAFD.git
cd CAFD
./drupal-installer.sh

# WINDOWS
git clone git@github.com:CodeArts-Solutions/CAFD.git
cd CAFD
bash ./drupal-installer.sh
```

### 🧪 Opciones del menú

| Opción | Descripción                                             |
|--------|---------------------------------------------------------|
| 1      | Levantar contenedores (`docker compose up`)             |
| 2      | Levantar contenedores y activar tema                    |
| 3      | Instalación completa (contenedores, tema, módulos, caché) |
| 4      | Bajar contenedores (`docker compose down`)              |
| 5      | Bajar contenedores y volúmenes (`docker compose down -v`) |
| 6      | Limpiar caché de Drupal con Drush                       |
| 7      | Mostrar chuleta Git de trabajo                          |
| 8      | Mostrar chuleta Docker básica|
| 0      | Salir del script|

🧠 Desarrollado como herramienta de mejora de procesos en entorno de prácticas. Su objetivo es facilitar el trabajo del equipo y evitar configuraciones manuales repetitivas.

### 🤝 Agradecimientos

Esta herramienta no habría sido posible sin la excelente estructura previa diseñada por el equipo de desarrollo. En especial, gracias a la decisión técnica de implementar archivos `.install` dentro de los módulos personalizados, que permiten:

1. Configurar automáticamente el contenido y estructura de cada página.
2. Eliminar todo el contenido por defecto de drupal.
3. Asignar bloques y vistas a sus regiones correspondientes.
4. Automatizar el despliegue visual y funcional sin intervención manual.

Gracias a esta base bien pensada, fue posible construir una herramienta flexible y funcional que facilita aún más el desarrollo y mantenimiento del entorno Drupal.



# 👥 Equipo de Trabajo Beatle

## BACKEND
| Nombre                | Rol              |
|-----------------------|------------------|
| Sergio Méndez Soler   | Líder            |
| Adam MY               |                  |
| Alberto Linero Reyes  |                  |
| Víctor Moreno Cabello |                  |
| Sara Martagón Beltrán |                  |
| Salva Martínez        |                  |
| Israel Pantoja        |                  |

## FRONTEND
| Nombre                 | Rol              |
|------------------------|------------------|
| Antonio R Paredes      | Líder            |
| Miguel Lara            |                  |
| Manuel Pacheco Montero |                  |
| Gonzalo Pineda         |                  |
| José Carlos Membrive   |                  |
| José Fernando Nieto    |                  |


# 📖 Entendiendo el Funcionamiento del Proyecto

Esta sección está pensada para nuevos desarrolladores que se integren al proyecto y necesiten comprender las decisiones clave, automatizaciones y comportamientos menos evidentes de la instalación y configuración de Drupal en este entorno.

El proyecto está compuesto por un tema y 12 módulos personalizados. Cada uno de los módulos agrega un aspecto de la interfaz junto a una funcionalidad.

Cuenta con una página de Inicio, que está compuesta por bloques pertenecientes al resto de módulos, y una página por cada sección de la web, siendo estas: Inicio, Servicios, Federaciones, Formación, Equipo, Atención a Clubes, Transparencia y Protección de Datos.

#### Durante la instalación inicial, se ejecutan scripts .install incluidos en los módulos personalizados. Estos scripts, solo se ejecutan cuando se instala el módulo y se encargan de:

- 🧱 Eliminar todo el contenido por defecto de Drupal.

Se realiza en el script correspondiente al módulo Cafd_UI. Este bloque de código elimina las instancias de los bloques, no los bloques en si, permitiendo que posteriormente se puedan seguir gestionando con normalidad.

```
// Elimina las instancias de bloques que están colocadas por defecto.
$blocks = \Drupal::entityTypeManager()->getStorage('block')->loadMultiple();

foreach ($blocks as $block) {
  if ($block->getTheme() === $theme && ($block->getRegion() === 'header' || $block->getRegion() === 'content')) {
    $block->delete();
  }}

```

- 📄 Crear y configurar la pagina de inicio.

Drupal necesita tener configurada una pagina de inicio a la que denomina Front Page. Esta página se configura en `/admin/config/system/site-information`. En la configuración actual del proyecto se esta realizando mediante código a traves del script del archivo .install del módulo cafd_blocks.
```
  // Establecer /inicio como front page
  \Drupal::configFactory()->getEditable('system.site')
    ->set('page.front', '/inicio')
    ->save();
```

- 📦 Colocar automáticamente los bloques en las regiones correctas del tema sin intervención manual.

Cada uno de los módulos asigna su bloque o bloques correspondientes a la región de la página a la que pertenezca, teniendo en cuenta su ruta y su visibilidad. Esto se realiza durante la instalación a través del archivo .install del módulo.

Ejemplo de uso: el módulo junta_directiva tiene dos bloques. El primer bloque se posiciona en la página de inicio (/inicio), mientras que el segundo bloque se posiciona en la página de equipo (/equipo). Como se puede ver en el siguiente ejemplo, en cada bloque se configura su peso, determinando el orden de aparición, y su URL, determinando la página en la que aparecerá, entre otros aspectos.

```
// Posicionar el bloque en la región 'content'.
  $theme = \Drupal::config('system.theme')->get('default');

  $existing_block = \Drupal::entityTypeManager()
    ->getStorage('block')
    ->load('bloque_juntaInicio_auto');

  if (!$existing_block) {
    $block_1 = \Drupal\block\Entity\Block::create([
      'id' => 'bloque_juntaInicio_auto',
      'plugin' => 'junta_directiva_inicio_block',
      'region' => 'content',
      'theme' => $theme,
      'weight' => 2,
      'visibility' => [
        'request_path' => [
          'id' => 'request_path',
          'pages' => '/inicio',
          'negate' => FALSE,
          'context_mapping' => [],
        ],
      ],
    ]);
    $block_1->save();
  }

    $existing_block2 = \Drupal::entityTypeManager()
    ->getStorage('block')
    ->load('bloque_juntaList_auto');
    
  if (!$existing_block2) {
      $block_2 = \Drupal\block\Entity\Block::create([
    'id'         => 'bloque_juntaList_auto',
    'plugin'     => 'junta_directiva_list_block',
    'region'     => 'content',
    'theme'      => $theme,
    'weight'     => 0,
    'visibility' => [
    'request_path' => [
      'id' => 'request_path',
      'pages' => '/equipo',
      'negate' => FALSE,
      'context_mapping' => [],
    ],
  ],
  ]);
  $block_2->save();
  }
```

- 🧭 Incorporación de datos de ejemplo

Durante el período de desarrollo, se ha implementado en los archivos .install de los módulos la integración de datos de ejemplo a la base de datos. El código relativo a estas sentencias deberá ser eliminado antes de llevar a producción.

Ejemplo de Uso

```
/**
 * Inserta datos demo en la tabla federaciones.
 */
function _cafd_federaciones_insert_federaciones() {
  $federaciones = [
    [
      'nombre' => 'ACTIVIDADES SUBACUÁTICAS',
      'direccion' => 'C/ Francisco García Góngora, 17 - 04006 - Almería',
      'telefono' => '950278824',
      'email' => 'fa-as@live.com',
      'weburl' => 'https://faas.com.es/',
    ],
```

⚠️ Si editas los archivos .install o agregas nuevos módulos personalizados, asegúrate de replicar esta lógica si necesitas añadir nuevos bloques o configuraciones automáticas.