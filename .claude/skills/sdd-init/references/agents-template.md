# Plantilla de AGENTS.md

```markdown
# AGENTS.md — {{project_name}}

Este archivo da contexto a cualquier agente de IA (Claude, Cursor, etc.) que
trabaje en este repositorio. Léelo por completo antes de escribir código.

## Resumen del proyecto
{{resumen_de_00_vision}}

## Regla principal de SDD
**Toda feature nueva debe tener una spec en `/spec/features/<nombre>.md` antes
de escribir código.** Usa el comando `/spec-new` para generarla con preguntas
guiadas. No implementes funcionalidad que no tenga spec aprobada.

## Regla de la bitácora histórica (obligatoria)
Este proyecto mantiene `/spec/progress-log.md`, un registro histórico de todo
lo que se ha implementado, qué errores hubo y cómo se resolvieron. Es la forma
en que este proyecto sobrevive a la compactación de contexto de cualquier IA.

- **Antes de empezar** cualquier tarea: lee las últimas entradas de
  `/spec/progress-log.md` para saber en qué quedó el proyecto.
- **Al terminar** cualquier tarea (feature, fix, refactor, cambio de CI/CD):
  agrega una entrada nueva siguiendo el formato definido en el propio archivo.
  Nunca edites ni borres entradas existentes — es append-only.
- Si el archivo crece demasiado, sigue la política de rotación descrita al
  final de `/spec/progress-log.md` (archivar resúmenes mensuales en
  `/spec/history/`).

## Convenciones de código
{{convenciones_segun_stack}}

Ejemplos según stack (usa solo la sección aplicable a este proyecto):

- **.NET:** PascalCase para clases/métodos, camelCase para variables locales,
  `I` como prefijo de interfaces, un archivo por clase, DTOs sufijados
  `Request`/`Response`.
- **Node/NestJS:** kebab-case en nombres de archivo, PascalCase en clases,
  módulos organizados por dominio (`/users`, `/orders`), DTOs con `class-validator`.
- **Laravel:** PascalCase en modelos/controladores, snake_case en columnas de
  BD, Form Requests para validación, Resources para transformar respuestas.

## Arquitectura
{{resumen_de_02_architecture}}

## Cómo correr tests
\`\`\`bash
{{comandos_test}}
\`\`\`

## Cómo desplegar
{{resumen_deploy}}

## Skills especializados disponibles en este proyecto
{{lista_skills_generados_paso4}}

## Comandos SDD disponibles
- `/spec-new` — crea la spec de una nueva feature con preguntas guiadas
- `/spec-review` — revisa una spec existente contra el código actual
- `/fix` — flujo guiado para corregir un bug (repro → mini-spec → parche → test)
- `/test` — genera/corre tests según `/spec/05-testing-strategy.md`
- `/cicd` — genera o actualiza el pipeline de CI/CD

{{#if retrofit}}
## Contexto heredado (proyecto adoptó SDD sobre código existente)
- Fecha de adopción de SDD: {{fecha}}
- Estado del proyecto al adoptar SDD: {{resumen_estado_al_adoptar}}
- Deudas técnicas detectadas: {{deudas_tecnicas_detectadas}}
{{/if}}
```
