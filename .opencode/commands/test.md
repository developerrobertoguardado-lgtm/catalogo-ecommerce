---
description: Generar o correr tests según la estrategia del proyecto
agent: build
---

Cuando el usuario invoque este comando:

1. Lee `/spec/05-testing-strategy.md` para saber framework y convenciones.
2. Pregunta: "¿Quieres correr los tests existentes, o generar tests nuevos
   para algo específico (una feature, un archivo, un endpoint)?"
3. Si es generar tests nuevos:
   - Pide o infiere qué se va a testear.
   - Genera tests siguiendo la convención de ubicación/nombres del proyecto.
   - Cubre casos felices, casos borde y casos de error.
4. Si es correr tests existentes:
   - Ejecuta el comando correspondiente al stack (ej. `dotnet test`,
     `npm test`, `php artisan test`).
   - Reporta resultados y, si algo falla, ofrece investigar la causa.
5. Si se generaron tests nuevos, o si se corrigió algún fallo detectado al
   correr los existentes, agrega una entrada en `/spec/progress-log.md` con
   `{{tipo}} = Test`, indicando qué se cubrió y qué fallos se corrigieron (si
   los hubo). Si solo se corrieron tests existentes sin cambios ni fallos, no
   hace falta agregar entrada.