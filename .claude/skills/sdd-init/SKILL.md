---
name: sdd-init
description: Initializes Spec-Driven Development (SDD) structure for a project — spec/, docs/, AGENTS.md, specialized sub-skills, and slash-commands (spec-new, spec-review, fix, test, cicd). MUST be used whenever the user asks to "iniciar SDD", "generar estructura SDD", "implementar Spec-Driven Development", "agregar SDD a mi proyecto", or similar, whether the project is empty (greenfield) or already has code (brownfield/retrofit). Always run this skill's detection step first before asking any question.
---

# SDD Init

Este skill convierte cualquier carpeta (vacía o con un proyecto ya avanzado) en un
proyecto estructurado bajo **Spec-Driven Development (SDD)**: especificaciones
formales, `AGENTS.md`, skills especializados por tecnología y comandos SDD
reutilizables (`/spec-new`, `/spec-review`, `/fix`, `/test`, `/cicd`).

No escribas código de la aplicación en este skill. Este skill solo genera
**estructura, documentación, specs y otros skills/comandos**. La implementación
del proyecto en sí ocurre después, ya con SDD como marco de trabajo.

## Flujo general (sigue el orden exacto)

1. **Paso 0 — Detección** (obligatorio, siempre primero, nunca preguntes antes de esto)
2. **Paso 1 — Elicitación, Confirmación o Actualización** (según el modo detectado en el Paso 0: GREENFIELD, RETROFIT o UPDATE)
3. **Paso 2 — Generar `/spec/`** (incluye `progress-log.md`, la bitácora histórica)
4. **Paso 3 — Generar `AGENTS.md`**
5. **Paso 4 — Generar skills especializados en `.claude/skills/`**
6. **Paso 5 — Generar comandos SDD en `.claude/commands/`**
7. **Paso 6 — Resumen final y próximos pasos**

---

## Paso 0 — Detección del estado del proyecto

Antes de preguntar cualquier cosa, inspecciona el directorio de trabajo actual:

- Busca marcadores de proyecto: `package.json`, `*.csproj`, `*.sln`, `composer.json`,
  `pom.xml`, `build.gradle`, `requirements.txt`, `go.mod`, `Cargo.toml`.
- Busca código fuente real: carpetas `src/`, `app/`, `lib/`, controladores, modelos.
- Busca infraestructura existente: `Dockerfile`, `docker-compose.yml`,
  `.github/workflows/`, `bitbucket-pipelines.yml`, `azure-pipelines.yml`,
  manifiestos de Kubernetes.
- Ignora ruido irrelevante: `.git` vacío recién inicializado, `README.md` default,
  `.gitignore` solo, carpetas vacías.

Clasifica el resultado en uno de tres modos:

- **MODO UPDATE** — ya existen `/spec/`, `AGENTS.md` y `.claude/commands/`
  generados por este mismo skill (SDD ya está instalado en el proyecto) → ve a
  **Paso 1C**. Revisa esto ANTES que los otros dos modos: si SDD ya existe, no
  importa qué tan avanzado esté el código, no es un caso de "retrofit desde
  cero" — es una actualización del skill sobre un proyecto que ya lo usa.
- **MODO GREENFIELD** — no hay marcadores de proyecto ni código real (y
  tampoco hay SDD instalado) → ve a **Paso 1A**.
- **MODO RETROFIT** — hay al menos un stack identificable con código real,
  pero SDD todavía no está instalado → ve a **Paso 1B**.

Nunca saltes este paso, incluso si el usuario ya mencionó su stack en el chat:
la detección real del filesystem tiene prioridad sobre lo que el usuario "cree"
que tiene, porque puede haber cambiado o el usuario puede describirlo mal.

---

## Paso 1C — Actualización (MODO UPDATE)

Este modo existe para cuando el skill `sdd-init` mismo se actualizó (nuevas
plantillas, nuevos comandos, cambios de estructura) y el usuario quiere que su
proyecto ya existente se ponga al día — **sin tocar sus specs ni su progreso**.

1. Pregunta explícitamente qué quiere actualizar (puede elegir varias):

   ```
   Detecté que este proyecto ya tiene SDD instalado. ¿Qué quieres actualizar
   a la última versión del skill?

   [ ] Comandos (.claude/commands/: spec-new, spec-review, fix, test, cicd)
   [ ] Skills especializados (.claude/skills/, excepto sdd-init mismo)
   [ ] Estructura de /spec/ (ej. agregar progress-log.md si no existe todavía,
       o migrar specs de features/fixes al formato de carpeta con correlativo)
   [ ] Todo lo anterior
   ```

2. **Nunca sobrescribas sin avisar** contenido que tenga información específica
   del proyecto ya generada (specs con contenido real, `progress-log.md` con
   entradas, `AGENTS.md` con el resumen del proyecto). Antes de tocar algo así,
   confirma explícitamente con el usuario.

3. Para "Comandos": regenera los 5 archivos de `.claude/commands/` usando
   `references/commands-templates.md` tal cual está en la versión actual del
   skill. Esto es seguro sobrescribir directo, ya que esos archivos son
   siempre genéricos (no contienen datos específicos del proyecto).

4. Para "Skills especializados": revisa cada skill en `.claude/skills/`
   (excepto `sdd-init`) y ofrece regenerarlos con
   `references/specialist-skill-template.md`, preservando las secciones que sí
   tienen contenido específico del proyecto (convenciones reales detectadas,
   snippets propios) y solo actualizando la estructura/formato si cambió.

5. Para "Estructura de /spec/":
   - Si no existe `/spec/progress-log.md` todavía, créalo con la entrada
     inicial de `references/progress-log-template.md`.
   - Si existen specs viejas en formato de un solo archivo
     (`/spec/features/<slug>.md` en vez de `/spec/features/NNN-slug/` con los
     4 archivos), pregunta si quiere migrarlas. Si dice que sí, por cada una:
     calcula el siguiente correlativo, crea la carpeta `NNN-slug/`, distribuye
     el contenido existente entre `change.md`, `impact.md`, `task.md` y
     `validation.md` según corresponda, y borra el `.md` viejo solo después de
     confirmar que la migración quedó bien.

6. Al terminar, agrega una entrada en `/spec/progress-log.md` con
   `{{tipo}} = Setup`, detallando qué partes del skill se actualizaron.

En **MODO UPDATE** los Pasos 2 a 5 (generación completa desde cero) NO
aplican — el Paso 1C ya hizo toda la generación/actualización necesaria. Salta
directo al **Paso 6 — Resumen final**.

---

## Paso 1A — Elicitación (MODO GREENFIELD)

Pregunta en cascada, una pregunta (o bloque corto) a la vez, proponiendo opciones
según la respuesta anterior. Usa el archivo `references/stack-matrix.md` como
catálogo de opciones por tipo de proyecto — no inventes stacks fuera de ese
catálogo salvo que el usuario pida explícitamente algo distinto.

Orden de preguntas:

1. **Tipo de proyecto**: API REST / Full-stack web / Microservicios / CLI /
   Mobile backend / Librería
2. **Stack backend** (opciones según tipo elegido — ver `stack-matrix.md`)
3. **Frontend** (si aplica): React / Next.js / Blazor / Ninguno
4. **Base de datos** + ORM
5. **Testing**: qué niveles quiere (unitario / integración / e2e) y con qué
   framework
6. **Integración continua (CI)**: Bitbucket Pipelines / GitHub Actions / Azure
   DevOps / Ninguno por ahora
7. **Despliegue continuo (CD)**: destino (AKS, Azure App Service, Docker
   Compose, Vercel, ninguno por ahora)
8. **Arquitectura**: Clean Architecture / Hexagonal / MVC simple / DDD

Al terminar, **resume todas las decisiones en una tabla** y pide confirmación
antes de generar nada.

---

## Paso 1B — Confirmación (MODO RETROFIT)

1. Reporta lo detectado en el Paso 0 en un resumen breve, por ejemplo:

   ```
   🔍 Analicé el proyecto actual y encontré:
   - Backend: .NET 8 / ASP.NET Core MVC
   - ORM/DB: Entity Framework Core → SQL Server
   - Testing: no se detectaron proyectos de test
   - CI/CD: no se detectó pipeline
   - Arquitectura aparente: MVC simple (sin capas Domain/Application separadas)
   ```

2. Pregunta explícitamente:

   > ¿Quieres que implemente SDD (Spec-Driven Development) sobre este proyecto
   > existente, documentando y estructurando lo que ya tienes?

   Si responde que no, detente ahí sin generar nada.

3. Si responde que sí, **solo pregunta lo que no pudiste inferir con certeza o
   que presenta ambigüedad**, por ejemplo:
   - Objetivo/alcance real del proyecto (esto casi nunca es inferible del código)
   - Confirmación de cosas detectadas con baja certeza ("Detecté X, ¿es correcto?")
   - Estrategia de testing si no existe ninguna
   - Estrategia de CI/CD si no existe ninguna
   - Cómo estandarizar si el código mezcla patrones inconsistentes

   No repitas preguntas cuyo dato ya detectaste con certeza en el Paso 0.

4. Genera la spec de forma **retroactiva**: cada archivo de `/spec/` documenta
   lo que YA existe en el código, no un diseño ideal. Si detectas desviaciones
   de buenas prácticas, anótalas como "Estado actual" vs. "Recomendación" en
   `02-architecture.md`, sin reescribir código todavía.

Usa `references/retrofit-inference.md` para la lista detallada de señales de
detección por stack (dónde buscar connection strings, cómo identificar el
patrón arquitectónico, etc.).

---

## Paso 2 — Generar `/spec/`

Usa las plantillas en `references/spec-templates.md`. Genera, en este orden:

```
/spec/00-vision.md
/spec/01-requirements.md
/spec/02-architecture.md
/spec/03-data-model.md
/spec/04-api-contracts.md
/spec/05-testing-strategy.md
/spec/progress-log.md
```

Reemplaza cada placeholder `{{...}}` de las plantillas con la información
recopilada (o inferida, en modo retrofit). No dejes placeholders sin resolver
en el resultado final.

`/spec/progress-log.md` es la **bitácora histórica** del proyecto: un registro
append-only (solo se agrega al final, nunca se reescribe ni se borra) pensado
para sobrevivir a la compactación de contexto de cualquier IA que trabaje en
el proyecto después. Al generarlo por primera vez, agrega una entrada inicial
indicando que se adoptó SDD (y si fue greenfield o retrofit). Ver
`references/progress-log-template.md` para el formato exacto y la política de
rotación/archivado.

También crea `/docs/README.md` con instrucciones de cómo levantar el proyecto,
según el stack (comandos reales: `dotnet run`, `npm run dev`, `docker compose up`, etc.).

## Paso 3 — Generar `AGENTS.md`

Usa la plantilla `references/agents-template.md`. Debe incluir:

- Resumen ejecutivo del proyecto (tomado de `00-vision.md`)
- Convenciones de código específicas del stack detectado/elegido
- Regla explícita: *"Toda feature nueva debe tener spec en `/spec/features/`
  antes de escribir código"*
- Cómo correr tests, cómo desplegar
- Referencia a los skills especializados generados en el Paso 4
- Si es modo retrofit: sección **"Contexto heredado"** resumiendo el estado del
  proyecto al momento de adoptar SDD
- Regla explícita de bitácora (obligatoria, no opcional):
  - **Al empezar** cualquier tarea (feature, fix, revisión): leer las últimas
    entradas de `/spec/progress-log.md` antes de hacer nada, para saber en qué
    quedó el proyecto — esto reemplaza la memoria que se pierde cuando el
    contexto de la IA se compacta.
  - **Al terminar** cualquier tarea: agregar una entrada nueva a
    `/spec/progress-log.md` siguiendo el formato de
    `references/progress-log-template.md`, ANTES de dar la tarea por cerrada.

## Paso 4 — Generar skills especializados (`.claude/skills/`)

Por cada tecnología relevante detectada o elegida, genera un skill dedicado
(carpeta + `SKILL.md`) siguiendo el patrón de `references/specialist-skill-template.md`.
Ejemplos de nombres: `dotnet-ef-specialist`, `react-frontend-specialist`,
`sqlserver-specialist`, `testing-specialist`, `aks-deploy-specialist`.

Cada skill especializado debe:
- Tener una `description` "empujadora" (pushy) que indique claramente cuándo
  debe activarse, siguiendo el estilo de `skill-creator`.
- Documentar convenciones y snippets propios de ESTE proyecto (no genéricos de
  internet) — por ejemplo, cómo están organizadas las carpetas reales, cómo se
  nombran los DTOs, qué patrón de repositorio usa este proyecto en particular.

No generes más de un skill por tecnología relevante; no dupliques (ej. no crear
`efcore-specialist` y `dotnet-ef-specialist` a la vez).

## Paso 5 — Generar comandos SDD (`.claude/commands/`)

Usa las plantillas en `references/commands-templates.md` para generar:

- `spec-new.md` → crea una nueva spec de feature con preguntas guiadas. Cada
  spec queda en `/spec/features/NNN-slug/` (correlativo de 3 dígitos) con 4
  archivos: `change.md`, `impact.md`, `task.md`, `validation.md`.
- `spec-review.md` → revisa una spec existente (carpeta `NNN-slug/`) contra el
  código actual
- `fix.md` → flujo guiado para bugs (repro → mini-spec del fix → parche →
  test). Cada fix queda en `/spec/fixes/NNN-slug/` (correlativo propio,
  independiente del de features) con los mismos 4 archivos
- `test.md` → genera/corre tests según `05-testing-strategy.md`
- `cicd.md` → genera/actualiza el pipeline elegido en el Paso 1

## Paso 6 — Resumen final

Termina siempre con:
1. Árbol de archivos generado (`tree`-style)
2. Lista de skills especializados creados y cuándo se activan
3. Lista de comandos SDD disponibles y qué hace cada uno
4. Sugerencia del siguiente paso lógico (normalmente: `/spec-new` para la
   primera feature, o revisar `/spec/01-requirements.md` si es retrofit)

---

## Referencias

- `references/stack-matrix.md` — catálogo de opciones por tipo de proyecto (Paso 1A)
- `references/retrofit-inference.md` — señales de detección por stack (Paso 1B)
- `references/spec-templates.md` — plantillas de los 6 archivos de `/spec/`
- `references/agents-template.md` — plantilla de `AGENTS.md`
- `references/specialist-skill-template.md` — plantilla para skills especializados
- `references/commands-templates.md` — plantillas de los 5 comandos SDD
- `references/progress-log-template.md` — formato de la bitácora histórica y política de rotación/archivado
