# Plantillas de .claude/commands/

Cada comando es un archivo Markdown independiente. El nombre del archivo
(sin extensión) es el nombre del comando (ej. `spec-new.md` → `/spec-new`).

---

## .claude/commands/spec-new.md

```markdown
# /spec-new — Crear spec de una nueva feature

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
   \`\`\`markdown
   # Change: {{nombre}}

   ## Problema / Usuario
   {{problema}}

   ## Descripción funcional
   {{que_debe_hacer}}

   ## Criterios de aceptación
   {{criterios}}
   \`\`\`

   **`impact.md`**
   \`\`\`markdown
   # Impact: {{nombre}}

   ## Módulos/entidades existentes que toca
   {{modulos_afectados}}

   ## Endpoints nuevos o modificados
   {{cambios_api}}

   ## Cambios de datos
   {{cambios_datos}}

   ## Riesgos / dependencias con otras specs
   {{riesgos_y_dependencias}}
   \`\`\`

   **`task.md`**
   \`\`\`markdown
   # Tasks: {{nombre}}

   - [ ] {{tarea_1}}
   - [ ] {{tarea_2}}
   - [ ] {{tarea_n}}
   \`\`\`

   **`validation.md`**
   \`\`\`markdown
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
   \`\`\`

6. Pregunta si quiere que empieces a implementar ya, o si prefiere revisar la
   spec primero.
7. Antes de dar por cerrada la sesión (ya sea que solo se creó la spec, o que
   además se implementó), agrega una entrada en `/spec/progress-log.md`
   siguiendo el formato de `progress-log-template.md` del skill `sdd-init`.
   Usa `{{tipo}} = Feature` y menciona la carpeta `{{NNN}}-{{slug}}` creada. Si
   solo quedó la spec creada (sin implementar todavía), marca **Estado: ⚠️
   Parcial** y en "Siguiente paso sugerido" indica que falta implementarla.
```

---

## .claude/commands/spec-review.md

```markdown
# /spec-review — Revisar una spec contra el código actual

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
```

---

## .claude/commands/fix.md

```markdown
# /fix — Flujo guiado para bugs

Cuando el usuario invoque este comando:

1. Pregunta: "¿Qué comportamiento incorrecto estás viendo? ¿Cómo reproducirlo?"
2. Pide (si no las da ya): pasos de reproducción, comportamiento esperado vs.
   actual, logs/stacktrace si existen.
3. Calcula el correlativo: revisa las carpetas existentes en `/spec/fixes/`
   (formato `NNN-slug/`, correlativo independiente del de `/spec/features/`),
   toma el número más alto y súmale 1 (3 dígitos). Si no hay ninguna, empieza
   en `001`.
4. Investiga la causa raíz en el código antes de proponer el fix.
5. Genera la carpeta `/spec/fixes/{{NNN}}-{{slug-bug}}/` con estos 4 archivos:

   **`change.md`**
   \`\`\`markdown
   # Change: {{titulo_del_fix}}

   ## Reproducción
   {{pasos}}

   ## Comportamiento esperado
   {{esperado}}

   ## Comportamiento actual
   {{actual}}
   \`\`\`

   **`impact.md`**
   \`\`\`markdown
   # Impact: {{titulo_del_fix}}

   ## Causa raíz
   {{causa_raiz}}

   ## Módulos/archivos afectados
   {{modulos_afectados}}

   ## Riesgo de la corrección
   {{riesgo_de_regresion}}
   \`\`\`

   **`task.md`**
   \`\`\`markdown
   # Tasks: {{titulo_del_fix}}

   - [ ] {{tarea_1_ej_corregir_validacion}}
   - [ ] {{tarea_2_ej_agregar_test_de_regresion}}
   \`\`\`

   **`validation.md`**
   \`\`\`markdown
   # Validation: {{titulo_del_fix}}

   ## Test de regresión
   {{descripcion_del_test_que_falla_sin_el_fix_y_pasa_con_el}}

   ## Estado
   - [ ] Corregido
   - [ ] Testeado
   - [ ] Verificado en el comportamiento reportado
   \`\`\`

6. Implementa el fix + el test de regresión descrito en `validation.md`.
7. Marca el estado como "Corregido"/"Testeado" en `validation.md` una vez
   verificado.
8. Agrega una entrada en `/spec/progress-log.md` con `{{tipo}} = Fix`,
   mencionando la carpeta `{{NNN}}-{{slug}}` creada. Es la entrada más
   importante de documentar bien: incluye la causa raíz real y la solución
   aplicada tal cual quedaron en `impact.md`/`task.md`, para que quede
   consultable después sin tener que releer todo el código.
```

---

## .claude/commands/test.md

```markdown
# /test — Generar o correr tests según la estrategia del proyecto

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
```

---

## .claude/commands/cicd.md

```markdown
# /cicd — Generar o actualizar el pipeline de CI/CD

Cuando el usuario invoque este comando:

1. Revisa `/spec/02-architecture.md` para confirmar el destino de CI/CD elegido
   (Bitbucket Pipelines / GitHub Actions / Azure DevOps; AKS / App Service /
   Docker Compose / Vercel).
2. Si no hay nada definido aún, pregúntalo (mismas opciones que en la
   elicitación inicial — ver `stack-matrix.md` del skill `sdd-init`).
3. Genera o actualiza el archivo de pipeline correspondiente:
   - `bitbucket-pipelines.yml`
   - `.github/workflows/ci.yml` (+ `cd.yml` si aplica)
   - `azure-pipelines.yml`
4. El pipeline debe incluir como mínimo: restore/install de dependencias,
   build, correr tests (fallar el pipeline si fallan), y el paso de deploy al
   destino elegido.
5. Actualiza `/spec/02-architecture.md` y `AGENTS.md` si el pipeline cambia
   algo relevante (ej. se agrega CD que antes no existía).
6. Agrega una entrada en `/spec/progress-log.md` con `{{tipo}} = CI/CD`,
   indicando qué pipeline se generó/cambió y qué pasos ejecuta.
```
