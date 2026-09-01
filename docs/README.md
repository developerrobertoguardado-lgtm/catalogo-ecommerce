# Ecommerce Catálogo + Pedidos por WhatsApp

Catálogo de productos con panel administrativo, donde cada producto tiene un
botón "Comprar" que arma un mensaje prellenado y abre WhatsApp con el
vendedor. Sin carrito ni pasarela de pago — ver `/spec/00-vision.md` para el
detalle completo.

## Requisitos previos
- Docker Desktop
- PHP 8.3+ y Composer (solo si se quiere correr fuera de Sail; con Sail no
  son estrictamente necesarios en el host)
- Node.js 20+ y npm (para compilar assets de Bootstrap/Alpine)

## Cómo levantar el proyecto localmente

En **macOS, Linux o Windows con WSL2**, el script `sail` funciona directo:
```bash
composer install
cp .env.example .env
php artisan key:generate

# Levantar Laravel Sail (PHP + PostgreSQL en Docker)
./vendor/bin/sail up -d

# Migraciones y datos de ejemplo
./vendor/bin/sail artisan migrate --seed

# Assets del frontend (Bootstrap + Alpine)
npm install
npm run build   # o "npm run dev" para desarrollo con recarga en caliente
```

En **Windows sin WSL2** (Git Bash / PowerShell), el script `./vendor/bin/sail`
no corre (solo soporta macOS/Linux/WSL2) — usa `docker compose` directamente,
que lee el mismo `compose.yaml` generado por Sail:
```bash
composer install
cp .env.example .env
php artisan key:generate

# Construir y levantar los contenedores (equivalente a "sail up -d")
docker compose up -d --build

# Migraciones y datos de ejemplo, ejecutados dentro del contenedor
docker compose exec laravel.test php artisan migrate --seed

# Assets del frontend
npm install
npm run build
```
Si el puerto 5432 ya está en uso en el host por otro proyecto, define
`FORWARD_DB_PORT` en `.env` (ej. `FORWARD_DB_PORT=5434`) antes de levantar los
contenedores — no afecta la conexión interna de Laravel a `pgsql:5432`.

La app queda disponible en `http://localhost` (o el puerto configurado en
`.env` / Sail).

## Cómo correr los tests
```bash
./vendor/bin/sail artisan test        # macOS/Linux/WSL2
docker compose exec laravel.test php artisan test   # Windows sin WSL2
```
Los tests corren contra la base de datos `testing` en el mismo PostgreSQL de
Sail (no SQLite), según lo definido en `/spec/05-testing-strategy.md`.

## Usuario admin de ejemplo (seeder)
- Email: `admin@example.com`
- Password: `password`

## Cómo desplegar
Aún no hay CD configurado (ver `/spec/02-architecture.md`). Cuando se defina
un destino de despliegue, usar el comando `/cicd` para generar el pipeline
correspondiente.

## Estructura de SDD
Este proyecto usa Spec-Driven Development. Ver:
- `/spec/` — especificaciones del proyecto
- `AGENTS.md` — reglas para agentes/IA trabajando en este repo
- `.claude/skills/` — skills especializados de este proyecto
- `.claude/commands/` — comandos SDD (`/spec-new`, `/spec-review`, `/fix`, `/test`, `/cicd`)
