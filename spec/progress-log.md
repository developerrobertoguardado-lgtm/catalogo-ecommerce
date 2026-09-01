# Bitácora histórica del proyecto

Este archivo es la memoria persistente del proyecto a través del tiempo. Su
propósito es que cualquier IA (o persona) que retome el proyecto —incluso
después de que se compacte o se pierda el contexto de la conversación— pueda
leerlo y saber exactamente en qué quedó, qué errores hubo y cómo se
resolvieron.

**Regla de oro: es append-only.** Nunca se borra ni se reescribe una entrada
existente. Solo se agrega al final. Si algo cambió de opinión respecto a una
entrada vieja, se agrega una entrada nueva que lo aclare — no se edita la
vieja.

**Antes de empezar cualquier tarea sobre este proyecto, lee al menos las
últimas 5-10 entradas de este archivo** para tener contexto de continuidad.

---

## 2026-08-31 09:04 — Setup: Adopción de SDD
**Estado:** ✅ Completado
**Qué se hizo:** Se inicializó el proyecto con Spec-Driven Development usando
el skill `sdd-init`. Modo: greenfield (carpeta vacía). Stack definido:
Laravel 12 + Blade + Alpine.js, PostgreSQL vía Docker/Sail, Pest, GitHub
Actions, MVC simple + Repository/Service selectivo. Se generaron `/spec/`
(6 archivos), `AGENTS.md`, los skills `laravel-specialist`,
`postgres-specialist`, `testing-specialist`, y los comandos
`/spec-new`, `/spec-review`, `/fix`, `/test`, `/cicd`.
**Errores encontrados:** Ninguno
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Implementar el proyecto base con `/spec-new`.

## 2026-08-31 23:04 — Feature: Proyecto base (scaffold inicial completo)
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó el scaffold completo — Laravel 12 pinneado
(no la última versión mayor), Sail + PostgreSQL, modelo de datos completo,
catálogo público con filtros, flujo de compra por WhatsApp, panel admin con
login custom, y CI en GitHub Actions. Ver carpeta
`/spec/features/001-proyecto-base/`.
**Errores encontrados:** El instalador `laravel/laravel` por defecto trae la
última versión mayor (13), no Laravel 12 como pedía la spec; el script
`./vendor/bin/sail` no corre en Windows sin WSL2 (solo detecta
macOS/Linux/WSL2); conflicto de puerto 5432 con otro contenedor Postgres ya
corriendo en la máquina.
**Cómo se corrigió:** Se reinstaló fijando `laravel/laravel:^12.0`; se
documentó y usó `docker compose` directo como alternativa a `sail` en
Windows sin WSL2; se configuró `FORWARD_DB_PORT=5434` en `.env` para evitar
el conflicto de puerto.
**Siguiente paso sugerido:** Mejorar la UI (catálogo y admin), luego seguir
con features incrementales vía `/spec-new`.

## 2026-08-31 23:41 — Setup: Rebuild de contenedores tras limpieza de Docker
**Estado:** ✅ Completado
**Qué se hizo:** El usuario limpió Docker local (contenedores e imagen del
proyecto se perdieron). Se reconstruyó la imagen y se volvieron a levantar
los contenedores; los datos sobrevivieron porque el volumen
`ecommerce_sail-pgsql` no se había borrado (migraciones y seed no hicieron
falta de nuevo). Se entregaron credenciales de conexión a PostgreSQL para
pgAdmin (host `localhost`, puerto `5434`, db `laravel`, user `sail`) y las
credenciales del admin del panel (`admin@example.com` / `password`).
**Errores encontrados:** Ninguno — el volumen de datos persistió.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Ninguno específico, quedó a la espera del
siguiente pedido del usuario.

## 2026-09-01 00:05 — Feature: Mejora de UI — catálogo estilo ecommerce + admin estilo dashboard
**Estado:** ✅ Completado
**Qué se hizo:** Se rediseñó el catálogo público (estilo inspirado en una
demo de PrestaShop) y el panel admin (estilo inspirado en Vuexy), ambos
reconstruidos en Tailwind (sin copiar Bootstrap/Vuexy) para no romper el
stack definido en `001-proyecto-base`. Se aclaró con el usuario que la
skill "ui-ux-pro-max-skill" que pidió no existe — se descartó esa vía y se
aplicaron las mejoras directamente. Ver carpeta
`/spec/features/002-mejora-ui-catalogo-admin/`.
**Errores encontrados:** Ninguno de fondo — solo ajustes menores de sintaxis
Blade durante la implementación (bindings de Alpine en componentes).
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Ninguno específico.

## 2026-09-01 01:04 — Feature: Migración a Bootstrap + CRUD del admin en modales
**Estado:** ✅ Completado
**Qué se hizo:** El usuario pidió CRUD de productos/categorías en modales y
usar Bootstrap para los inputs. Se le advirtió del riesgo real de mezclar
Bootstrap con Tailwind (conflictos de reset/clases); confirmó reemplazar
Tailwind por Bootstrap **en todo el proyecto**. Se removió Tailwind por
completo, se instaló Bootstrap 5, se reescribieron todas las vistas, y se
convirtió el CRUD de productos/categorías a modales con reapertura
automática ante error de validación (campo oculto `_modal` +
`bootstrap.Modal(...).show()`). Se documentó como ADR-004 en
`/spec/02-architecture.md`. Ver carpeta
`/spec/features/003-bootstrap-crud-modales/`.
**Errores encontrados:** Al reinstalar Laravel 12 se detectó que el
skeleton no traía `AGENTS.md`/`CLAUDE.md` de Laravel Boost (descartados sin
uso, contenido genérico). Sin errores de fondo en la migración de Bootstrap.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Ninguno específico.

## 2026-09-01 01:28 — Feature: Dropzone de fotos con reordenamiento por arrastre
**Estado:** ✅ Completado
**Qué se hizo:** Se reemplazó el input de fotos plano por un dropzone
(drag & drop) con miniaturas reordenables por arrastre, mezclando fotos ya
guardadas y nuevas en un solo orden. La foto en posición 0 se marca
`is_primary` automáticamente (ya no hay control manual separado). Se
mantiene compatibilidad con el flujo anterior (sin `orden_fotos`) como
fallback. Ver carpeta
`/spec/features/004-dropzone-fotos-reordenables/`.
**Errores encontrados:** Ninguno de fondo.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Ninguno específico.

## 2026-09-01 — Setup: Actualización del skill sdd-init (MODO UPDATE)
**Estado:** ✅ Completado
**Qué se hizo:** El skill `sdd-init` se actualizó a una versión más nueva
(agrega `progress-log.md`, formato de specs en carpeta `NNN-slug/` con 4
archivos, modo UPDATE). Se detectó que el proyecto ya tenía SDD instalado y
se corrió el flujo de actualización completo: se regeneraron los 5 comandos
en `.claude/commands/`, se revisaron los 3 skills especializados (ya
conformes al template actual, sin cambios necesarios), se creó este archivo
(`progress-log.md`, poblado retroactivamente con la historia real del
proyecto), y se migraron las 4 specs de features existentes del formato de
archivo único a carpetas `NNN-slug/` con `change.md`/`impact.md`/`task.md`/`validation.md`.
**Errores encontrados:** Ninguno.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Seguir usando `/spec-new` y `/fix` normalmente
— ya generan specs en el nuevo formato de carpeta y agregan entradas acá
automáticamente.
