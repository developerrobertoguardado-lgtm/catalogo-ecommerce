---
description: Revisar una spec existente contra el código actual
agent: build
---

Cuando el usuario invoque este comando:

1. Pregunta qué spec revisar (o lista las carpetas disponibles en
   `/spec/features/` y `/spec/fixes/`, formato `NNN-slug/`).
2. Lee los 4 archivos de esa carpeta (`change.md`, `impact.md`, `task.md`,
   `validation.md`) y compáralos contra el código real:
   - ¿Todos los criterios de aceptación de `change.md`/`validation.md` están
     implementados?
   - ¿Los endpoints coinciden con lo documentado en `impact.md` y en
     `04-api-contracts.md`?
   - ¿El modelo de datos coincide con `03-data-model.md`?
   - ¿Todas las tareas de `task.md` están marcadas como completadas y
     realmente lo están en el código?
   - ¿Existen los tests planeados en `validation.md`?
3. Reporta un checklist con ✅/⚠️/❌ por cada criterio.
4. Si hay discrepancias, pregunta si:
   - actualizar la spec (los archivos de la carpeta) para reflejar el código real, o
   - actualizar el código para cumplir la spec.
5. Actualiza los archivos correspondientes según la decisión del usuario.
6. Agrega una entrada en `/spec/progress-log.md` (`{{tipo}} = Feature` o
   `Refactor`, según lo que cambió) mencionando la carpeta revisada y
   resumiendo qué discrepancias se encontraron y cómo se resolvieron.