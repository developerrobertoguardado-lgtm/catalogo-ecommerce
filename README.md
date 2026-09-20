# Ecommerce Catálogo + Pedidos por WhatsApp

Catálogo de productos online con panel administrativo, donde cada producto
tiene un botón **"Comprar"** que arma un mensaje prellenado y abre WhatsApp
con el vendedor. No hay carrito multi-producto ni pasarela de pago: el
cierre real de la venta ocurre por WhatsApp, fuera del sistema. Cada pedido
enviado queda registrado en base de datos para que el administrador lo vea
desde el panel.

## Índice

- [Funcionalidades](#funcionalidades)
- [Stack tecnológico](#stack-tecnológico)
- [Requisitos previos](#requisitos-previos)
- [Instalación y arranque local](#instalación-y-arranque-local)
- [Variables de entorno](#variables-de-entorno)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Tests](#tests)
- [Usuario administrador de ejemplo](#usuario-administrador-de-ejemplo)
- [Despliegue](#despliegue)
- [Documentación y Spec-Driven Development](#documentación-y-spec-driven-development)

## Funcionalidades

### Catálogo público
- Listado de productos en cards (foto principal, nombre, precio, categoría).
- Hasta 4 fotos por producto, con slider/carousel en el detalle.
- Categorías con navegación y filtro dedicado por categoría.
- Filtros combinables: nombre, categoría, rango de precio.
- Badges automáticos de stock ("Agotado" / "Últimas unidades") derivados del
  stock real, sin datos inventados.

### Compra directa por WhatsApp (sin carrito)
- Cada producto tiene su propio botón "Comprar" con selector de cantidad.
- Al confirmar, se arma un mensaje (producto, cantidad, precio, total) y se
  abre `wa.me` hacia el número de WhatsApp configurado en el admin.
- El pedido se guarda como `Order`/`OrderItem` para que el admin lo vea,
  aunque el cierre de la venta sea externo.
- Valida stock disponible antes de generar el pedido (rechaza con 409 si la
  cantidad pedida supera el stock).

### Panel administrativo (`/admin`, con login)
- CRUD de productos: nombre, categoría, precio, stock, descripción, y hasta
  4 fotos gestionadas con un dropzone (arrastrar y soltar, miniaturas
  reordenables por arrastre, foto principal = primera en el orden).
- CRUD de categorías.
- Crear/editar productos y categorías se hace en **modales**, no en páginas
  separadas.
- Listado y detalle de pedidos generados desde el catálogo (solo lectura).
- Configuración de tienda: nombre, número de WhatsApp destino, moneda.
- Cards de estadísticas reales (total de productos, sin stock, stock bajo,
  total de pedidos, total facturado).

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 12 (PHP 8.3+), Blade |
| Frontend | Bootstrap 5 (CSS + JS bundle) + Alpine.js (solo lógica puntual: stepper de cantidad, dropzone de fotos, fetch del flujo de compra) |
| Base de datos | PostgreSQL + Eloquent ORM |
| Entorno local | Docker vía Laravel Sail |
| Testing | Pest, corrido contra PostgreSQL real (no SQLite) |
| CI | GitHub Actions |
| Arquitectura | MVC simple + Repository/Service selectivo (solo donde aporta valor real) |

Decisiones de arquitectura documentadas como ADRs en
[`spec/02-architecture.md`](spec/02-architecture.md): sin carrito/pasarela
de pago, Sail para Docker local, Pest sobre PHPUnit puro, y Bootstrap 5 en
reemplazo de Tailwind (usado en la primera versión del proyecto).

## Requisitos previos

- Docker Desktop
- PHP 8.3+ y Composer (opcional si se corre todo dentro de Sail)
- Node.js 20+ y npm (para compilar los assets de Bootstrap/Alpine)

## Instalación y arranque local

### Script automático (Windows Git Bash / macOS / Linux)

```bash
bash setup.sh              # instalación completa
bash setup.sh --reinstall  # limpia node_modules y reinstala (fix @rollup)
```

### macOS, Linux o Windows con WSL2

```bash
composer install
cp .env.example .env
php artisan key:generate

# Levantar Laravel Sail (PHP + PostgreSQL en Docker)
./vendor/bin/sail up -d

# Migraciones y datos de ejemplo
./vendor/bin/sail artisan migrate --seed

# Assets del frontend
npm install
npm run build   # o "npm run dev" para desarrollo con recarga en caliente
```

### Windows sin WSL2 (Git Bash / PowerShell)

El script `./vendor/bin/sail` no corre en este entorno (solo soporta
macOS/Linux/WSL2) — usa `docker compose` directamente, que lee el mismo
`compose.yaml` generado por Sail:

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

Si el puerto `5432` ya está en uso en el host por otro proyecto, define
`FORWARD_DB_PORT` en `.env` (ej. `FORWARD_DB_PORT=5434`) antes de levantar
los contenedores — no afecta la conexión interna de Laravel a `pgsql:5432`.

La app queda disponible en `http://localhost`.

## Variables de entorno

Además de las estándar de Laravel (`APP_*`, `DB_*`, ya preconfiguradas en
`.env.example` para Sail/PostgreSQL), este proyecto agrega:

| Variable | Descripción |
|---|---|
| `WHATSAPP_NUMBER` | Número de WhatsApp por defecto usado como fallback si todavía no existe una fila en `store_settings` (se sobrescribe desde `/admin/configuracion`). |

## Estructura del proyecto

```
app/
├── Http/Controllers/Catalogo/   # Catálogo público + flujo de pedido
├── Http/Controllers/Admin/      # Panel administrativo (CRUD)
├── Http/Controllers/Auth/       # Login de admin (custom, sin Breeze)
├── Http/Requests/               # Form Requests de validación
├── Services/PedidoWhatsAppService.php  # Lógica de armado del pedido/mensaje
└── Models/                      # Category, Product, ProductImage, Order,
                                  # OrderItem, StoreSetting, User

resources/views/
├── catalogo/                    # Vistas públicas
├── admin/                       # Vistas del panel (con modales de CRUD)
└── components/                  # <x-producto-card>, <x-slider-fotos>,
                                  # <x-fotos-dropzone>, <x-icon>, layouts

resources/js/
├── app.js                       # Bootstrap JS bundle + Alpine
└── fotos-dropzone.js            # Lógica del dropzone de fotos reordenable

tests/
├── Feature/Admin/                # CRUD de productos y categorías
├── Feature/Catalogo/             # Filtros del catálogo
├── Feature/Pedidos/              # Flujo de compra por WhatsApp
└── Unit/                         # PedidoWhatsAppService

spec/                             # Especificaciones (Spec-Driven Development)
.claude/                          # Skills y comandos SDD
```

## Tests

```bash
./vendor/bin/sail artisan test                       # macOS/Linux/WSL2
docker compose exec laravel.test php artisan test    # Windows sin WSL2
```

Los tests corren contra la base de datos `testing` en el mismo PostgreSQL de
Sail (no SQLite), según lo definido en
[`spec/05-testing-strategy.md`](spec/05-testing-strategy.md). Cobertura
actual: CRUD de productos/categorías (incluyendo el límite de 4 fotos y el
reordenamiento vía dropzone), filtros del catálogo, generación del mensaje y
link de WhatsApp, y creación de `Order`/`OrderItem`.

## Usuarios de ejemplo

Creados por el `DatabaseSeeder` para que cualquiera pueda probar la app al instante:

| Rol | Email | Password | Notas |
|---|---|---|---|
| **Administrador** | `admin@example.com` | `password` | Acceso completo al panel `/admin` (CRUD productos, categorías, pedidos, configuración, paleta de colores). |
| **Demo** | `demo@example.com` | `demo12345` | Usuario de prueba con datos de ejemplo pre-cargados (4 categorías × 3 productos cada una con foto). |

> **Importante:** El password de `admin@example.com` se genera con el factory por defecto (`password`). El usuario `demo@example.com` tiene un password explícito (`demo12345`). Cambia ambos en producción.

## Despliegue

Sin CD configurado todavía (ver el ADR correspondiente en
[`spec/02-architecture.md`](spec/02-architecture.md)). Cuando se defina un
destino de despliegue, usar el comando `/cicd` para generar el pipeline.

### Despliegue en Dokploy

Dokploy es una alternativa open-source a Heroku/Vercel que corre sobre Docker.
Sigue estos pasos para desplegar esta aplicación en Dokploy.

> **Nota:** El repositorio usa **Laravel Sail** para desarrollo local (`compose.yaml`), que no incluye un `Dockerfile` de producción. Para Dokploy necesitarás crear un `Dockerfile` en la raíz (ver ejemplo abajo) o usar el **Buildpack "Dockerfile"** apuntando a uno que añadas.

#### 1. Crear `Dockerfile` de producción (en la raíz del repo)

```dockerfile
# ---- Builder stage ----
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- PHP/FPM + Nginx stage ----
FROM php:8.3-fpm-alpine

# Extensiones necesarias
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    gd \
    zip \
    bcmath \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código de la app
WORKDIR /var/www/html
COPY . .
COPY --from=frontend /app/public/build ./public/build

# Permisos y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && composer install --optimize-autoloader --no-dev --no-interaction \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link

# Nginx + Supervisor config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
```

> **Archivos auxiliares necesarios** (crea la carpeta `docker/` en la raíz):
> - `docker/nginx.conf` — configuración de Nginx para Laravel (ver más abajo)
> - `docker/supervisord.conf` — supervisa php-fpm + nginx

#### 2. `docker/nginx.conf` (configuración Nginx)

```nginx
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 3. `docker/supervisord.conf`

```ini
[supervisord]
nodaemon=true

[program:php-fpm]
command=php-fpm8.3 -F
user=root
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=nginx -g "daemon off;"
user=root
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
```

#### 4. Preparar el repositorio

```bash
git add Dockerfile docker/nginx.conf docker/supervisord.conf
git commit -m "Add production Dockerfile for Dokploy"
git push origin main
```

#### 5. En Dokploy: Crear el proyecto

1. Dashboard → **New Project** → **Git Repository**
2. Conecta tu repositorio, branch `main`
3. **Build Pack**: **Dockerfile** (usa el que acabas de crear)

#### 6. Variables de entorno en Dokploy

En **Environment Variables** del proyecto:

| Variable | Valor | Obligatoria |
|---|---|---|
| `APP_ENV` | `production` | Sí |
| `APP_DEBUG` | `false` | Sí |
| `APP_KEY` | `php artisan key:generate --show` | Sí |
| `APP_URL` | `https://tu-proyecto.dokploy.app` | Sí |
| `DB_CONNECTION` | `pgsql` | Sí |
| `DB_HOST` | `postgres` | Sí |
| `DB_PORT` | `5432` | Sí |
| `DB_DATABASE` | `ecommerce` | Sí |
| `DB_USERNAME` | (tu usuario PG) | Sí |
| `DB_PASSWORD` | (tu password PG) | Sí |
| `WHATSAPP_NUMBER` | `51999999999` | No |

> **Tip:** Crea la base de datos en Dokploy → **Databases** → **PostgreSQL** y usa esas credenciales.

#### 7. Desplegar

Haz clic en **Deploy**. El primer deploy:
1. Construye la imagen con tu `Dockerfile`
2. Ejecuta migraciones (`php artisan migrate --force`) al arrancar el contenedor
3. Expone la app en `https://tu-proyecto.dokploy.app`

#### 8. Primer acceso

- Usuario: `admin@example.com` / `password`
- Configura WhatsApp, moneda y paleta de colores en **Configuración → Aspecto visual**
- **Cambia la contraseña** del admin tras el primer login

#### 9. Dominio personalizado + SSL

Dokploy → **Domains** → **Add Domain** → apunta tu DNS → activa **SSL (Let's Encrypt)**.

## Documentación y Spec-Driven Development

Este proyecto usa **Spec-Driven Development (SDD)**: toda decisión y toda
feature quedan documentadas antes o junto con el código.

- [`spec/`](spec/) — especificaciones del proyecto (visión, requisitos,
  arquitectura con ADRs, modelo de datos, contratos de rutas, estrategia de
  testing) y `spec/features/NNN-slug/` con la spec de cada feature
  incremental.
- [`spec/progress-log.md`](spec/progress-log.md) — bitácora histórica
  append-only: qué se hizo, qué errores hubo y cómo se resolvieron, sesión a
  sesión.
- [`AGENTS.md`](AGENTS.md) — reglas para cualquier agente de IA (o persona)
  que trabaje en este repositorio.
- `.claude/skills/` — skills especializados (`laravel-specialist`,
  `postgres-specialist`, `testing-specialist`) con convenciones propias de
  este proyecto.
- `.claude/commands/` — comandos SDD: `/spec-new`, `/spec-review`, `/fix`,
  `/test`, `/cicd`.
