#!/bin/bash

# Ejecutar: ./install-cafd-drupal

# ======= COLORES =========
greenColour="\e[0;32m\033[1m"
endColour="\033[0m\e[0m"
redColour="\e[0;31m\033[1m"
blueColour="\e[0;34m\033[1m"
yellowColour="\e[0;33m\033[1m"
purpleColour="\e[0;35m\033[1m"
turquoiseColour="\e[0;36m\033[1m"
grayColour="\e[0;37m\033[1m"

max_attempts=6
wait_seconds=11
retry_count=0
apache_ok=false

# Ocultar cursor
tput civis
trap "tput cnorm; exit" INT TERM ERR

echo -e "\n${yellowColour}[🔎] ${endColour}${turquoiseColour}Verificando si los contenedores ya están levantados...${endColour}"

if docker ps --format '{{.Names}}' | grep -q "cafd_drupal"; then
  echo -e "\n${greenColour}[✔] Contenedores ya activos, se omite.${endColour}"
  apache_ok=true
else
  # ======= FUNCIÓN: Esperar a Apache =========
  esperar_apache() {
    docker compose up -d --build
    echo -e "\n${yellowColour}[⏳] ${endColour}${turquoiseColour}Esperando que Docker responda...${endColour}"
    for ((i = 0; i < $wait_seconds; i += 3)); do
      if curl -s http://localhost:8080 | grep -q "<html"; then
        printf "${grayColour}[⏳] ${endColour}"
        echo -e "${greenColour}[⏳]${endColour}"
        echo -e "\n${yellowColour}[🐬] Docker responde correctamente.${endColour}"
        apache_ok=true
        return
      fi
      printf "${grayColour}[⏳] ${endColour}"
      sleep 3
    done
    printf "${greenColour}[⏳] ${endColour}"
  }

  # ======= BUCLE DE INTENTOS =========
  while [ $retry_count -lt $max_attempts ]; do
    echo -e "\n${blueColour}[🔝] ${endColour}${turquoiseColour}Intento $((retry_count + 1)) de levantar contenedores Docker...${endColour}\n"
    docker compose up -d --build
    esperar_apache

    if [ "$apache_ok" = true ]; then
      break
    else
      echo -e "\n${redColour}[❌] ${endColour}Docker no respondió tras $wait_seconds segundos."
      echo -e "\n${grayColour}Reintentando...${endColour}"
      docker compose down
      ((retry_count++))
    fi
  done

  if [ "$apache_ok" = false ]; then
    echo -e "\n${redColour}[❌] Fallo: Apache no pudo levantarse después de $max_attempts intentos.${endColour}"
    echo -e "${grayColour}Algo extraño ha pasado, revisa que ambos contenedores se hayan levantado.${endColour}"
    tput cnorm
    exit 1
  fi
fi

# ======= CONTINUAR INSTALACIÓN =========
echo -e "\n${greenColour}[✔️] ${endColour}${blueColour}Apache ya está activo. Iniciando configuración de Drupal con Tema personalizado de Beatle...${endColour}\n"

docker exec -it cafd_drupal bash -c "
  echo -e '${yellowColour}[📖] ${endColour}${turquoiseColour}Activando tema...${endColour}\n' &&
  drush theme:enable custom_theme &&
  drush config:set system.theme default custom_theme -y &&
  drush cr &&
  echo -e '\n${yellowColour}[📖] ${endColour}${turquoiseColour}Activando módulos personalizados...${endColour}\n' &&
  drush en cafd_ui -y &&
  drush en cafd_blocks -y &&
  drush en cafd_colabs -y &&
  drush en cafd_contacto -y &&
  drush cr &&
  drush en cafd_estadisticas -y &&
  drush cr &&
  drush en cafd_redessociales -y &&
  drush en cafd_servicios -y &&
  drush en cafd_transparencia -y &&
  drush cr &&
  drush en federaciones -y &&
  drush en junta_directiva -y &&
  drush en cafd_politica_datos -y &&
  drush cr &&
  echo -e '\n\n${greenColour}[✔️] Instalación finalizada correctamente.${endColour}'
"

# Restaurar cursor
tput cnorm
