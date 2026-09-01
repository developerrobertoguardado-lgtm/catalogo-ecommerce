# Plantillas de /spec/

Reemplaza cada `{{placeholder}}` con la información recopilada en el Paso 1A/1B.
En modo retrofit, cuando un dato fue inferido en lugar de confirmado
explícitamente por el usuario, márcalo con `(inferido)` junto al valor.

---

## spec/00-vision.md

```markdown
# Visión del proyecto

## Nombre
{{project_name}}

## Objetivo
{{objetivo_negocio}}

## Alcance
{{alcance_incluye}}

### Fuera de alcance
{{alcance_excluye}}

## Usuarios / actores principales
{{actores}}

## Contexto
{{modo_greenfield_o_retrofit}} — {{fecha_adopcion_sdd}}
```

---

## spec/01-requirements.md

```markdown
# Requisitos

## Requisitos funcionales
{{lista_requisitos_funcionales}}

## Requisitos no funcionales
- Rendimiento: {{req_rendimiento}}
- Seguridad: {{req_seguridad}}
- Disponibilidad: {{req_disponibilidad}}
- Escalabilidad: {{req_escalabilidad}}

## Restricciones técnicas
{{restricciones}}
```

En modo retrofit, reconstruye esta lista revisando los endpoints/casos de uso
que ya existen en el código, no la dejes vacía.

---

## spec/02-architecture.md

```markdown
# Arquitectura

## Patrón arquitectónico
Estado actual: {{arquitectura_actual}}
{{#if retrofit}}Recomendación: {{arquitectura_recomendada_si_difiere}}{{/if}}

## Stack tecnológico
- Backend: {{stack_backend}}
- Frontend: {{stack_frontend}}
- Base de datos: {{base_datos}} + {{orm}}

## Decisiones arquitectónicas (ADR)

### ADR-001: {{titulo_decision}}
- **Contexto:** {{contexto}}
- **Decisión:** {{decision}}
- **Consecuencias:** {{consecuencias}}

(Agrega un ADR por cada decisión relevante detectada o tomada)

## Diagrama de capas
{{diagrama_texto_o_ascii}}
```

---

## spec/03-data-model.md

```markdown
# Modelo de datos

## Entidades

### {{entidad_1}}
| Campo | Tipo | Notas |
|-------|------|-------|
{{tabla_campos_entidad_1}}

(Repetir por cada entidad detectada/definida)

## Relaciones
{{relaciones_entre_entidades}}

## Migraciones
{{estado_migraciones_si_retrofit}}
```

En modo retrofit, genera esta tabla leyendo las clases de entidad y/o
migraciones reales — no inventes campos que no existen.

---

## spec/04-api-contracts.md

```markdown
# Contratos de API

## {{metodo}} {{ruta}}
- **Descripción:** {{descripcion}}
- **Request:** {{request_dto}}
- **Response:** {{response_dto}}
- **Códigos de error:** {{errores}}

(Repetir por cada endpoint)
```

En modo retrofit, genera esta lista a partir de los controllers/rutas
existentes.

---

## spec/05-testing-strategy.md

```markdown
# Estrategia de testing

## Niveles cubiertos
- Unitario: {{si_no}} — {{framework_unitario}}
- Integración: {{si_no}} — {{framework_integracion}}
- E2E: {{si_no}} — {{framework_e2e}}

## Cobertura actual
{{cobertura_detectada_o_meta}}

## Convenciones
- Ubicación de tests: {{ubicacion_tests}}
- Nomenclatura: {{convencion_nombres}}

## Gaps detectados (solo en retrofit)
{{gaps_de_cobertura}}
```

---

## spec/progress-log.md

Ver `references/progress-log-template.md` del skill `sdd-init` para el formato
completo, la política de rotación/archivado, y la regla de lectura/escritura
obligatoria. Al generar el proyecto por primera vez, crea este archivo con la
entrada inicial de "Setup: Adopción de SDD" definida ahí.

---

## docs/README.md

```markdown
# {{project_name}}

{{descripcion_corta}}

## Requisitos previos
{{requisitos_previos}}

## Cómo levantar el proyecto localmente
\`\`\`bash
{{comandos_setup}}
\`\`\`

## Cómo correr los tests
\`\`\`bash
{{comandos_test}}
\`\`\`

## Cómo desplegar
{{instrucciones_deploy}}

## Estructura de SDD
Este proyecto usa Spec-Driven Development. Ver:
- `/spec/` — especificaciones del proyecto
- `AGENTS.md` — reglas para agentes/IA trabajando en este repo
- `.claude/skills/` — skills especializados de este proyecto
- `.claude/commands/` — comandos SDD (`/spec-new`, `/spec-review`, `/fix`, `/test`, `/cicd`)
```
