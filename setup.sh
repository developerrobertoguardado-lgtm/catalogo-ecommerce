#!/usr/bin/env bash
# setup.sh — Levanta todo el entorno del proyecto manualmente (Techelex).
# Uso:  ./setup.sh          → instalación completa
#       ./setup.sh --reinstall  → limpia node_modules y reinstala
set -e

REINSTALL=0
[ "$1" = "--reinstall" ] && REINSTALL=1

step() { echo ""; echo "==> $1"; }

step "1/7 Instalando dependencias PHP (composer install)"
composer install

step "2/7 Preparando .env"
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
    echo ".env creado y APP_KEY generada."
else
    echo ".env ya existe, se conserva."
fi

# WWWUSER/WWWGROUP son obligatorios para el build de Sail (groupadd -g $WWWGROUP)
if ! grep -q '^WWWGROUP=' .env; then
    printf '\nWWWUSER=1000\nWWWGROUP=1000\n' >> .env
    echo "WWWUSER/WWWGROUP agregados a .env."
fi

step "3/7 Levantando contenedores (docker compose up -d --build)"
docker compose up -d --build

step "4/7 Esperando a que PostgreSQL esté listo..."
for i in $(seq 1 30); do
    if docker compose exec -T pgsql pg_isready -q -U "${DB_USERNAME:-sail}" -d "${DB_DATABASE:-laravel}" 2>/dev/null; then
        echo "PostgreSQL listo."
        break
    fi
    sleep 2
done

step "5/7 Migraciones y datos de ejemplo (migrate --seed)"
docker compose exec -T laravel.test php artisan migrate --seed --force

step "6/7 Instalando node_modules DENTRO del contenedor (binarios de Linux)"
if [ "$REINSTALL" = "1" ]; then
    echo "Limpiando node_modules y package-lock (--reinstall)..."
    docker compose exec -T laravel.test rm -rf node_modules package-lock.json
fi
docker compose exec -T laravel.test npm install

step "7/7 Compilando assets del frontend (npm run build)"
docker compose exec -T laravel.test npm run build

cat <<'EOF'

✅ Sistema levantado.

   Catálogo:   http://localhost
   Panel:      http://localhost/admin/login
   Admin:      admin@example.com / password
   Demo:       demo@example.com / demo12345

Accesorios:
   docker compose exec laravel.test php artisan test    # suite de pruebas
   docker compose exec laravel.test npm run dev &       # hot reload (Vite, puerto 5173)
   docker compose down                                   # detener contenedores
EOF
