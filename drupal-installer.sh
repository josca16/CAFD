#!/bin/bash

# Archivo: drupal-installer.sh
# Descripción: Menú interactivo para gestionar el entorno Docker y la instalación Drupal del proyecto CAFD con menú en español y ejecución directa.

# ======= COLORES =========
greenColour="\e[0;32m\033[1m"
endColour="\033[0m\e[0m"
redColour="\e[0;31m\033[1m"
blueColour="\e[0;34m\033[1m"
yellowColour="\e[0;33m\033[1m"
purpleColour="\e[0;35m\033[1m"
turquoiseColour="\e[0;36m\033[1m"
grayColour="\e[0;37m"

# ======= CONFIGURACIÓN =========
WAIT_SECONDS=15
MAX_ATTEMPTS=5
MAX_WAIT_DOWN_SECONDS=60
CONTAINER_DRUPAL="cafd_drupal"
CONTAINER_DB="cafd_db"

# ======= MÓDULOS Y TEMA =========
THEME_NAME="custom_theme"
PROJECT_NAME="CAFD"

MOD_UI="cafd_ui"
MOD_BLOCKS="cafd_blocks"
MOD_COLABS="cafd_colabs"
MOD_CONTACTO="cafd_contacto"
MOD_ESTADISTICAS="cafd_estadisticas"
MOD_RRSS="cafd_redessociales"
MOD_SERVICIOS="cafd_servicios"
MOD_TRANSPARENCIA="cafd_transparencia"
MOD_FEDERACIONES="cafd_federaciones"
MOD_JUNTA="junta_directiva"
MOD_FORMACION="cafd_formacion"
MOD_POLITICA_DATOS="cafd_politica_datos"

# ======= PARÁMETROS =========
for arg in "$@"; do
  case "$arg" in
    -l) DIRECT_OPTION="l" ;;
    -t) DIRECT_OPTION="t" ;;
    -c) DIRECT_OPTION="c" ;;
    -b) DIRECT_OPTION="b" ;;
    -v) DIRECT_OPTION="v" ;;
    -g) DIRECT_OPTION="g" ;;
    -d) DIRECT_OPTION="d" ;;
    -x) DIRECT_OPTION="x" ;;
  esac
done

# ======= TEXTOS EN ESPAÑOL (Posibles futuras traducciones) =========
MENU_TITLE="===== MENÚ DE INSTALACIÓN ${PROJECT_NAME} ====="
MENU_OPTIONS=(
  "1) Levantar contenedores (-l)"
  "2) Levantar contenedores y activar tema (-t)"
  "3) Levantamiento completo (-c)"
  "4) Bajar contenedores (-b)"
  "5) Bajar contenedores con -v (-v)"
  "6) Limpiar Caché"
  "7) Chuleta Git (-g)"
  "8) Chuleta Docker (-d)"
  "0) Salir (-x)"
)
MSG_CHOOSE_OPTION="Elige una opción (0-8): "
MSG_INVALID_OPTION="Opción no válida."
MSG_EXITING="Saliendo..."
MSG_ENTER="Presiona Enter para continuar..."

# ======= CURSOR (Mostrar/Ocultar) =========
tput civis
cleanup() {
  tput cnorm
  exit
}
trap cleanup INT TERM EXIT

# ======= UTILIDADES =========
print_title() {
  echo -e "\n${purpleColour}$1${endColour}"
}

pause() {
  echo -e "\n"
  read -r -p "$MSG_ENTER"
}

print_list() {
  while read -r line; do
    echo "[] $line"
  done <<< "$1"
}

check_active_containers() {
  docker ps --format '{{.Names}}' | grep -q "$CONTAINER_DRUPAL" && \
  docker ps --format '{{.Names}}' | grep -q "$CONTAINER_DB"
}

wait_apache() {
  echo -e "\n${yellowColour}⌛ Esperando que Apache responda...${endColour}\n"
  for ((i = 0; i < WAIT_SECONDS; i += 3)); do
    if curl -s http://localhost:8080 | grep -q "<html"; then
      echo -e "${greenColour}✔️ Apache responde correctamente.${endColour}\n"
      tput civis
      return 0
    fi
    tput civis
    echo -ne "${grayColour}⌛ ${endColour}"
    sleep 3
  done
  return 1
}

wait_lowered_containers() {
  echo -e "\n${yellowColour}⏳ Esperando a que los contenedores se detengan completamente...${endColour}"
  local elapsed=0
  while docker ps --format '{{.Names}}' | grep -q "$CONTAINER_DRUPAL\|$CONTAINER_DB"; do
    if [ $elapsed -ge $MAX_WAIT_DOWN_SECONDS ]; then
      echo -e "\n${redColour}❌ Tiempo de espera agotado. Los contenedores no se han detenido.${endColour}\n"
      return 1
    fi
    echo -ne "${grayColour}. ${endColour}"
    sleep 2
    ((elapsed+=2))
  done
  echo -e "\n${greenColour}✔️ Contenedores detenidos correctamente.${endColour}\n"
  return 0
}

up_containers() {
  local tries=0
  while [ $tries -lt $MAX_ATTEMPTS ]; do
    echo -e "\n${blueColour}🔁 Intento $((tries + 1))/${MAX_ATTEMPTS} de levantar contenedores...${endColour}"
    docker compose up -d --build
    sleep 3
    if check_active_containers && wait_apache; then
      return 0
    fi
    echo -e "\n${redColour}❌ Apache no responde o contenedores no levantados. Reintentando...${endColour}"
    docker compose down
    if ! wait_lowered_containers; then
      return 1
    fi
    ((tries++))
  done
  echo -e "\n${redColour}❌ Fallo: Apache no pudo levantarse después de ${MAX_ATTEMPTS} intentos.${endColour}"
  return 1
}

activate_theme(){
  docker exec -it "$CONTAINER_DRUPAL" bash -c "
    drush theme:enable $THEME_NAME &&
    drush config:set system.theme default $THEME_NAME -y &&
    drush cr
  "
}

activate_theme_and_modules() {
  docker exec -it "$CONTAINER_DRUPAL" bash -c "
    drush theme:enable $THEME_NAME &&
    drush config:set system.theme default $THEME_NAME -y &&
    drush cr &&
    drush en $MOD_UI $MOD_BLOCKS $MOD_COLABS $MOD_CONTACTO $MOD_ESTADISTICAS \\
             $MOD_RRSS $MOD_SERVICIOS $MOD_TRANSPARENCIA $MOD_FEDERACIONES \\
             $MOD_JUNTA $MOD_FORMACION $MOD_POLITICA_DATOS -y &&
    drush cr
  "
}

up_containers_and_theme(){
  if up_containers; then
    activate_theme
    echo -e "\n${greenColour}✔️ Instalación del tema finalizada.${endColour}"
  fi
}

full_install() {
  if up_containers; then
    activate_theme_and_modules
    echo -e "\n${greenColour}✔️ Instalación completa finalizada.${endColour}"
  fi
}

down_containers() {
  docker compose down
}

down_containers_v() {
  docker compose down -v
}

print_table_row() {
  printf "%-45s | %s\n" "$1" "$2"
}

git_commands() {
  print_title "🧠 CHULETA GIT - Proyecto CAFD"

  print_title "⬇️ CLONAR Y PREPARAR EL PROYECTO (PRIMER USO)"
  print_table_row "Clonar repositorio"                  "git clone git@github.com:CodeArts-Solutions/CAFD.git"
  print_table_row "Entrar a la carpeta del proyecto"    "cd CAFD"
  print_table_row "Cambiar a develop"                   "git checkout develop"
  print_table_row "Crear rama funcionalidad"            "git checkout -b feature/nombre_descriptivo"

  print_title "💻 FLUJO DE TRABAJO DIARIO"
  print_table_row "Ver archivos modificados"              "git status"
  print_table_row "Donde estoy"                           "git branch --show-current"
  print_table_row "Guardar cambios temporalmente"         "git stash"
  print_table_row "Actualizar rama"                       "git pull origin rama_a_actualizar"
  echo -e "${blueColour}🧪 COMMIT Y PUSH${endColour}"
  print_table_row "1. Agregar todos los cambios"              "git add ."
  print_table_row "   Restaurar archivo (estado anterior)"    "git restore <archivo>"
  print_table_row "2. Crear un commit"                        "git commit -m 'feat/fix: que se agrega/arregla'"
  print_table_row "3. Push (subir) tu rama"                   "git push origin tu_rama"

  print_title "🔄 1. ACTUALIZAR TU RAMA CON DEVELOP"
  print_table_row "Actualizar referencias"                   "git fetch --all"
  print_table_row "Ir a develop"                             "git checkout develop"
  print_table_row "Obtener nuevos cambios"                   "git pull origin develop"
  print_table_row "Volver a tu rama"                         "git checkout tu_rama"
  print_table_row "Fusionar develop en tu rama"              "git merge develop"

  print_title "🔀 2. MERGE A DEVELOP (FINAL)"
  print_table_row "Cambiar a develop"                        "git checkout develop"
  print_table_row "Actualizar develop (traer cambios)"       "git pull origin develop"
  print_table_row "Fusionar tu rama"                         "git merge tu_rama"
  print_table_row "Subir merge"                              "git push origin develop"
}

docker_commands() {
  print_title "🐳 CHULETA DOCKER & DRUSH - Proyecto CAFD"

  print_title "🚀 DOCKER BÁSICO"
  print_table_row "Levantar contenedores"                 "docker compose up -d"
  print_table_row "Bajar contenedores"                    "docker compose down"
  print_table_row "Bajar contenedores con volúmenes"      "docker compose down -v"
  print_table_row "Acceder a Drupal (contenedor)"         "docker exec -it $CONTAINER_DRUPAL bash"
  print_table_row "Ver logs de un contenedor"             "docker logs $CONTAINER_DRUPAL"
  print_table_row "Reiniciar un contenedor"               "docker restart $CONTAINER_DRUPAL"

  print_title "🧰 COMANDOS DRUSH ÚTILES"
  print_table_row "Limpiar caché"                         "drush cr"
  print_table_row "Exportar configuración"                "drush cex -y"
  print_table_row "Importar configuración"                "drush cim -y"
  print_table_row "Login automático (enlace)"             "drush uli"
  print_table_row "Listar módulos instalados"             "drush pm:list --type=module"
  print_table_row "Activar un módulo"                     "drush en <nombre_modulo> -y"
  print_table_row "Desactivar un módulo"                  "drush pm:uninstall <nombre_modulo> -y"
  print_table_row "Ver estado del sistema"                "drush status"

  print_title "📦 BASE DE DATOS Y CONFIG"
  print_table_row "Exportar base de datos"                "drush sql-dump > dump.sql"
  print_table_row "Importar base de datos"                "drush sql-cli < dump.sql"
  print_table_row "Reinstalar base desde cero"            "drush site:install"
}

show_git_status_summary() {
  if ! git rev-parse --is-inside-work-tree &>/dev/null; then
    echo -e "${redColour}⚠ Este directorio no es un repositorio Git.${endColour}\n"
    return
  fi
  echo -e "${yellowColour}🌿 Rama actual${endColour} → ${greenColour}$(git branch --show-current)${endColour}\n"
}

drush_interactive() {
 print_title "🧹 Limpiando la caché de Drupal con Drush..."
  docker exec -it "$CONTAINER_DRUPAL" drush cr
  echo -e "\n${greenColour}✔️ Caché limpia.${endColour}"
}

show_menu() {
  clear
  show_git_status_summary
  echo -e "${purpleColour}${MENU_TITLE}${endColour}"
  for linea in "${MENU_OPTIONS[@]}"; do
    echo -e "$linea"
  done
  echo ""
}

# ======= EJECUCIÓN CON PARÁMETROS =========
if [[ -n "$DIRECT_OPTION" ]]; then
  case $DIRECT_OPTION in
    l) up_containers ;;
    t) up_containers_and_theme ;;
    c) full_install ;;
    b) down_containers ;;
    v) down_containers_v ;;
    g) git_commands ;;
    d) docker_commands ;;
    x) echo -e "${yellowColour}${MSG_EXITING}${endColour}"; cleanup ;;
    *) echo -e "${redColour}${MSG_INVALID_OPTION}${endColour}"; cleanup ;;
  esac
fi

# ======= MENÚ PRINCIPAL =========
while true; do
  show_menu
  read -r -p "$MSG_CHOOSE_OPTION" option
  case $option in
    1) up_containers ;;
    2) up_containers_and_theme ;;
    3) full_install ;;
    4) down_containers ;;
    5) down_containers_v ;;
    6) drush_interactive ;;
    7) git_commands ;;
    8) docker_commands ;;
    0) echo -e "\n${yellowColour}${MSG_EXITING}${endColour}"; break ;;
    *) echo -e "\n${redColour}${MSG_INVALID_OPTION}${endColour}";;
  esac
  pause
done

# Restaurar cursor al salir
cleanup
