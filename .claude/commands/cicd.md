# /cicd — Generar o actualizar el pipeline de CI/CD

Cuando el usuario invoque este comando:

1. Revisa `/spec/02-architecture.md` para confirmar el destino de CI/CD
   elegido (GitHub Actions; sin CD por ahora).
2. Si el usuario pide agregar CD, pregunta el destino (ver
   `stack-matrix.md` del skill `sdd-init`: AKS, Azure App Service, Docker
   Compose, Vercel, etc.) — no asumas uno.
3. Genera o actualiza `.github/workflows/ci.yml` con, como mínimo:
   - Checkout del código
   - Setup de PHP (versión compatible con Laravel 12) y de Node.js
   - Servicio de PostgreSQL en el workflow (`services:`) para que los tests
     corran contra una base real, igual que en local
   - `composer install`, `npm install && npm run build`
   - `php artisan migrate` contra la base de test
   - `php artisan test` (o `./vendor/bin/pest`) — el pipeline debe fallar si
     los tests fallan
   - Trigger: en cada `push` y `pull_request` hacia la rama principal
4. Si se agrega CD más adelante, generar `.github/workflows/cd.yml` según el
   destino elegido y actualizar `/spec/02-architecture.md` y `AGENTS.md`
   para reflejar el cambio.
5. Agrega una entrada en `/spec/progress-log.md` con `{{tipo}} = CI/CD`,
   indicando qué pipeline se generó/cambió y qué pasos ejecuta.
