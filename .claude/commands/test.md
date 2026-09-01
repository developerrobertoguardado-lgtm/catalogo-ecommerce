# /test — Generar o correr tests según la estrategia del proyecto

Cuando el usuario invoque este comando:

1. Lee `/spec/05-testing-strategy.md` para confirmar framework (Pest) y
   convenciones de este proyecto.
2. Pregunta: "¿Quieres correr los tests existentes, o generar tests nuevos
   para algo específico (una feature, un archivo, una ruta)?"
3. Si es generar tests nuevos:
   - Pide o infiere qué se va a testear.
   - Genera tests Pest siguiendo la convención de ubicación/nombres del
     proyecto (ver `testing-specialist`).
   - Cubre casos felices, casos borde y casos de error.
4. Si es correr tests existentes:
   - Ejecuta `./vendor/bin/sail artisan test` (macOS/Linux/WSL2) o
     `docker compose exec laravel.test php artisan test` (Windows sin
     WSL2).
   - Reporta resultados y, si algo falla, ofrece investigar la causa.
5. Si se generaron tests nuevos, o si se corrigió algún fallo detectado al
   correr los existentes, agrega una entrada en `/spec/progress-log.md` con
   `{{tipo}} = Test`, indicando qué se cubrió y qué fallos se corrigieron (si
   los hubo). Si solo se corrieron tests existentes sin cambios ni fallos, no
   hace falta agregar entrada.
