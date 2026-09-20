---
description: Crear spec de una nueva feature con preguntas guiadas
agent: build
---

Cuando el usuario invoque este comando:

1. Pregunta: "¿Qué feature o funcionalidad quieres construir?"
2. Con la respuesta, pregunta en cascada (una a la vez):
   - ¿Qué problema resuelve / para qué usuario?
   - ¿Qué debe pasar exactamente? (criterios de aceptación, casos borde)
   - ¿Toca datos nuevos? (nuevas entidades/campos)
   - ¿Toca endpoints nuevos? (método, ruta, request/response esperado)
   - ¿Necesita tests específicos además de los estándar del proyecto?
3. Verifica contra `/spec/01-requirements.md` y `/spec/03-data-model.md` que no
   haya conflicto con lo ya definido; si lo hay, señálalo al usuario antes de
   continuar.
4. Calcula el correlativo: revisa las carpetas existentes en
   `/spec/features/` (formato `NNN-slug/`), toma el número más alto y súmale 1
   (3 dígitos, con ceros a la izquierda: `001`, `002`...). Si no hay ninguna
   carpeta todavía, empieza en `001`.
5. Genera la carpeta `/spec/features/{{NNN}}-{{slug-feature}}/` con estos 4
   archivos:

   **`change.md`**
   ```markdown
   # Change: {{nombre}}

   ## Problema / Usuario
   {{problema}}

   ## Descripción funcional
   {{que_debe_hacer}}

   ## Criterios de aceptación
   {{criterios}}
   ```

   **`impact.md`**
   ```markdown
   # Impact: {{nombre}}

   ## Módulos/entidades existentes que toca
   {{modulos_afectados}}

   ## Endpoints nuevos o modificados
   {{cambios_api}}

   ## Cambios de datos
   {{cambios_datos}}

   ## Riesgos / dependencias con otras specs
   {{riesgos_y_dependencias}}
   ```

   **`task.md`**
   ```markdown
   # Tasks: {{nombre}}

   - [ ] {{tarea_1}}
   - [ ] {{tarea_2}}
   - [ ] {{tarea_n}}
   ```

   **`validation.md`**
   ```markdown
   # Validation: {{nombre}}

   ## Criterios de aceptación a validar
   {{criterios}}

   ## Plan de testing
   {{plan_testing}}

   ## Estado
   - [ ] Spec aprobada
   - [ ] Implementada
   - [ ] Testeada
   - [ ] Desplegada
   ```

6. Pregunta si quiere que empieces a implementar ya, o si prefiere revisar la
   spec primero.
7. Antes de dar por cerrada la sesión (ya sea que solo se creó la spec, o que
   además se implementó), agrega una entrada en `/spec/progress-log.md`
   siguiendo el formato de `progress-log-template.md` del skill `sdd-init`.
   Usa `{{tipo}} = Feature` y menciona la carpeta `{{NNN}}-{{slug}}` creada. Si
   solo quedó la spec creada (sin implementar todavía), marca **Estado: ⚠️
   Parcial** y en "Siguiente paso sugerido" indica que falta implementarla.