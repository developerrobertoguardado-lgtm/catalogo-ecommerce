# Bitácora histórica: /spec/progress-log.md

Este archivo es la memoria persistente del proyecto a través del tiempo. Su
propósito es que cualquier IA (o persona) que retome el proyecto —incluso
después de que se compacte o se pierda el contexto de la conversación— pueda
leerlo y saber exactamente en qué quedó, qué errores hubo y cómo se resolvieron.

**Regla de oro: es append-only.** Nunca se borra ni se reescribe una entrada
existente. Solo se agrega al final. Si algo cambió de opinión respecto a una
entrada vieja, se agrega una entrada nueva que lo aclare — no se edita la vieja.

## Formato de cada entrada

```markdown
## {{YYYY-MM-DD HH:MM}} — {{tipo}}: {{titulo_corto}}
**Estado:** {{✅ Completado | ⚠️ Parcial | ❌ Bloqueado}}
**Qué se hizo:** {{resumen_de_2_a_4_lineas}}
**Errores encontrados:** {{descripcion_del_error_o_"Ninguno"}}
**Cómo se corrigió:** {{solucion_aplicada_o_"N/A"}}
**Siguiente paso sugerido:** {{que_seguiria_logicamente}}
```

`{{tipo}}` es uno de: `Setup`, `Feature`, `Fix`, `Refactor`, `Test`, `CI/CD`,
`Retrofit`.

## Cuándo se agrega una entrada

- Al generar el proyecto por primera vez (Paso 2 de `sdd-init`), con
  `{{tipo}} = Setup` (o `Retrofit` si el proyecto ya existía).
- Al final de `/spec-new` cuando una feature queda implementada (o al menos
  cuando se crea su spec, con estado `⚠️ Parcial` hasta que se implemente).
- Al final de `/fix`, documentando causa raíz y solución (esto es explícitamente
  lo que el usuario pidió poder consultar después).
- Al final de `/test` si se detectan y corrigen fallos.
- Al final de `/cicd` cuando se agrega o cambia el pipeline.
- Cualquier vez que una tarea quede bloqueada o parcialmente hecha — para que
  la siguiente sesión sepa retomarla sin re-investigar desde cero.

## Regla de lectura obligatoria

Antes de empezar cualquier tarea sobre este proyecto, lee al menos las últimas
5-10 entradas de este archivo (o todas las del último mes) para tener contexto
de continuidad. Esto es especialmente crítico después de una compactación de
contexto, un cambio de sesión, o un cambio de agente/modelo.

## Rotación y archivado (para que no crezca sin control)

Cuando `progress-log.md` supere aproximadamente 50 entradas o varios meses de
trabajo:

1. Crea (o actualiza) `/spec/history/{{YYYY-MM}}.md` con un resumen condensado
   de las entradas de ese mes — no copiadas literalmente, sino resumidas: qué
   se construyó, qué bugs importantes hubo y cómo se resolvieron, decisiones
   clave. Unas 5-15 líneas por mes suele bastar.
2. Elimina de `progress-log.md` las entradas ya archivadas, dejando solo las
   más recientes (último mes o dos).
3. Agrega al inicio de `progress-log.md` una línea apuntando al historial
   archivado: `> Entradas anteriores a {{fecha}} archivadas en /spec/history/`.

Esto mantiene el archivo activo liviano y rápido de leer, sin perder el
histórico completo (que queda condensado, no borrado).

## Entrada inicial (al generar el proyecto)

```markdown
## {{fecha_actual}} — Setup: Adopción de SDD
**Estado:** ✅ Completado
**Qué se hizo:** Se inicializó el proyecto con Spec-Driven Development usando
el skill `sdd-init`. Modo: {{greenfield|retrofit}}. Stack: {{resumen_stack}}.
**Errores encontrados:** Ninguno
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** {{primer_feature_o_tarea_sugerida}}
```
