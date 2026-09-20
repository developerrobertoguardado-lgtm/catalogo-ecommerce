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

## 2026-09-01 — Setup: README detallado del proyecto
**Estado:** ✅ Completado
**Qué se hizo:** Se pidió el README detallado del proyecto vía `/spec-new`,
pero al ser una tarea de documentación pura (sin cambios de datos, rutas ni
tests) no encajaba en el formato de spec de feature (`change`/`impact`/
`task`/`validation`), así que se hizo directo sin crear una carpeta en
`/spec/features/`. Se reemplazó el `README.md` raíz (que seguía siendo el
boilerplate genérico de Laravel, nunca actualizado) por un README completo:
funcionalidades, stack, instalación (con la variante Windows sin WSL2),
variables de entorno, estructura del proyecto, tests, credenciales del
admin de ejemplo, y enlaces a `spec/`/`AGENTS.md`. Se eliminó
`docs/README.md` (quedaba duplicado y sin ninguna referencia real hacia él
en el resto del proyecto) para no mantener dos documentos que pudieran
desincronizarse.
**Errores encontrados:** Ninguno.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Ninguno específico.

## 2026-09-01 — CI/CD: Fix del pipeline de GitHub Actions (falla real detectada)
**Estado:** ✅ Completado
**Qué se hizo:** Al revisar `.github/workflows/ci.yml` con `/cicd`, se
descubrió que el repositorio ya tiene remoto en GitHub
(`developerrobertoguardado-lgtm/catalogo-ecommerce`) con 2 commits pusheados
fuera de esta sesión, y que la última corrida real de CI **falló**. Se
consultó la API pública de GitHub Actions (runs → jobs → logs) para obtener
el error exacto en vez de adivinar.
**Errores encontrados:** El paso "Install PHP dependencies" fallaba porque
`composer.lock` (generado localmente con PHP 8.5) resolvió Symfony 8.1.x y
`nesbot/carbon` 3.13.2, que requieren PHP ≥8.4.1, mientras el workflow fijaba
`php-version: '8.3'` — un mismatch real entre lo que el lockfile exige y lo
que CI instalaba.
**Cómo se corrigió:** Se subió `php-version` a `'8.5'` en
`.github/workflows/ci.yml` (coincide con lo que ya usa Sail/`sail-8.5/app`
localmente). Se actualizó también `composer.json` (`"php": "^8.2"` →
`"^8.4"`) para que la restricción declarada refleje la realidad, y se corrió
`composer update --lock` (solo refresca el content-hash, no re-resuelve
paquetes — verificado con `git diff` que ningún paquete cambió de versión).
Se corrieron los 21 tests localmente tras el cambio: siguen en verde.
**Siguiente paso sugerido:** Falta confirmar que el push de este fix hace
que la corrida real de GitHub Actions pase (quedó pendiente de que el
usuario autorice el commit/push, ya que no se pidió explícitamente en este
turno).

## 2026-09-03 — Setup: Restauración de herramientas SDD (comandos y skills)
**Estado:** ✅ Completado
**Qué se hizo:** Se detectó que las carpetas `.claude/commands/` y
`.claude/skills/` existían pero estaban vacías (los archivos se habían
perdido). Se regeneraron los 5 comandos SDD (`/spec-new`, `/spec-review`,
`/fix`, `/test`, `/cicd`) y los 3 skills especializados (`laravel-specialist`,
`postgres-specialist`, `testing-specialist`) siguiendo las plantillas del
skill `sdd-init`.
**Errores encontrados:** Ninguno.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Los comandos y skills ya están operativos. Seguir
usando `/spec-new` y `/fix` normalmente.

## 2026-09-03 — Setup: Migración de comandos a .opencode/commands/ (openCode)
**Estado:** ✅ Completado
**Qué se hizo:** El usuario reportó que `/spec-new` no aparecía en los
comandos de openCode. Se investigó la documentación oficial de openCode
(`/docs/commands/`) y se descubrió que los comandos se leen de
`.opencode/commands/*.md` (no de `.claude/commands/`), con frontmatter
obligatoria (`description` + `agent`). Se movieron/regeneraron los 5 comandos
a `.opencode/commands/` con la frontmatter requerida y se eliminó la carpeta
`.claude/commands/` vacía. Los skills siguen en `.claude/skills/` (ubicación
correcta).
**Errores encontrados:** Los comandos estaban en `.claude/commands/` pero
openCode solo detecta comandos en `.opencode/commands/` — además les faltaba
la frontmatter `description`/`agent`.
**Cómo se corrigió:** Se recrearon los 5 archivos en `.opencode/commands/`
(los últimos 3 con cambios, ya que Demo de openCode espera la versión
markdown con frontmatter en rutas `.opencode/commands/`).
**Siguiente paso sugerido:** Reiniciar la sesión de openCode para que cargue
los comandos nuevos, luego probar `/spec-new` para crear la próxima feature.

## 2026-09-03 11:27 — Feature: Mejora UI/UX del catálogo público
**Estado:** ✅ Completado
**Qué se hizo:** Se rediseñó el catálogo público inspirado en la web de referencia
`app.orderypro.com/tienda/mayoristas`. Header reducido a solo logo + barra de
búsqueda de producto; se quitaron la barra superior oscura ("Pedidos directos por
WhatsApp · Sin registro, sin pasarela de pago") y el botón "WhatsApp" del header.
Cards de producto rediseñadas (foto cuadrada, nombre, categoría, precio, botón
"Agregar" y hover sutil). Se mantuvieron los filtros intactos (nombre, categoría,
rango de precio) en su misma posición. Se ajustó el hero del catálogo y se
agregaron estilos en `app.css` (solo Bootstrap 5). Ver carpeta
`/spec/features/005-mejora-ui-catalogo/`.
**Errores encontrados:** Ninguno de fondo — durante la verificación el primer
`Invoke-WebRequest` falló por reinicio transitorio del contenedor; al reintentar
la app respondió 200 sin errores en el log (solo había trazas viejas de una
corrida de tests).
**Cómo se corrigió:** N/A (se reintentó la petición HTTP).
**Siguiente paso sugerido:** Ninguno específico. La feature quedó implementada y
con los 21 tests pasando; el paso de "Desplegada" del `validation.md` queda
pendiente porque no hay CD configurado.

## 2026-09-03 11:33 — Setup: Nuevo usuario admin demo
**Estado:** ✅ Completado
**Qué se hizo:** El usuario no recordaba sus credenciales de acceso al panel. Se creó
un nuevo usuario admin con email `demo@example.com` y clave `demo12345` (8 chars,
cumple el mínimo de Laravel; se eligió esta opción tras aclarar que el login es por
email+password y no por nombre de usuario, y que la clave literal `demo` de 4 chars
no pasaría la validación por defecto). Se creó el usuario en la BD actual vía
`php artisan tinker` con `User::firstOrCreate` (ID 3) y se agregó la misma creación
al `DatabaseSeeder` con `firstOrCreate` para que quede documentada/reproducible sin
duplicar al re-correr el seeder. Se verificó el login completo: POST `/admin/login`
devuelve 302 y `/admin/productos` carga 200 autenticado.
**Errores encontrados:** Ninguno.
**Cómo se corrigió:** N/A
**Siguiente paso sugerido:** Recordar también que el usuario original
`admin@example.com` / `password` sigue existiendo y es válido. No borrarlo salvo que
el usuario lo pida explícitamente.

## 2026-09-03 — Fix: Imágenes públicas rotas después de guardar
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/001-imagenes-publicas-rotas/` y se corrigió el flujo de imágenes de productos. La causa raíz real fue doble: Laravel 12 registraba `/storage/{path}` para el disco privado `local` porque tenía `serve => true`, interceptando las URLs públicas y devolviendo 403; además, el directorio compartido `storage/app/public/product-images` no tenía permisos de escritura para el usuario `sail`, por lo que `store()` devolvía `false` y se persistía como `path=0`. Se desactivó el serving del disco privado, se habilitó explícitamente para el disco público, se configuró `throw => true` para no persistir fallos silenciosos y se ajustaron los permisos del directorio en el entorno Sail/Windows.
**Errores encontrados:** El primer test comparaba el contenido de una respuesta transmitida y falló aunque el HTTP era 200; se corrigió para validar el `Content-Type`. La validación MCP inicial confirmó registros históricos dañados (`path=0` y archivos inexistentes), que no pueden restaurarse automáticamente.
**Cómo se corrigió:** Se modificó `config/filesystems.php`, se agregó el test de regresión en `tests/Feature/Admin/ProductoCrudTest.php`, y se documentaron tareas, impacto y validación en `001-imagenes-publicas-rotas`. Se ejecutaron los 25 tests: todos pasaron (86 assertions). MCP confirmó una subida real guardada con ruta válida, imagen visible en la tabla y miniatura visible al reabrir el modal, ambas con `naturalWidth=1024` y respuesta HTTP 200.
**Siguiente paso sugerido:** Los productos antiguos con `path=0` o archivos eliminados requieren limpieza o nueva carga manual; las nuevas subidas quedan corregidas.

## 2026-09-03 — Feature: Sistema global de alertas SweetAlert2
**Estado:** ✅ Completado
**Qué se hizo:** Se creó la spec `/spec/features/006-alertas-sweetalert2-globales/` y se implementó un sistema global de alertas reutilizable para mejorar la UI/UX del administrador y centralizar alertas en toda la aplicación. La funcionalidad reutiliza los endpoints actuales, no agrega entidades ni campos, y contempla alertas de éxito, error, información, advertencia, confirmación de eliminaciones, validaciones y errores de red/servidor.
**Errores encontrados:** Ninguno durante la definición. La verificación contra `/spec/01-requirements.md` y `/spec/03-data-model.md` no detectó conflictos.
**Cómo se corrigió:** Se instaló SweetAlert2 y se creó `resources/js/alertas.js` con la API global `window.alertas` (`success`, `error`, `info`, `warning`, `confirm`). Se agregó el componente Blade `resources/views/components/alertas.blade.php` a los layouts público, admin y login; se migraron mensajes flash, errores y confirmaciones de eliminación; y se reemplazaron los `alert()` del flujo de pedidos. Se agregaron pruebas de regresión y se ejecutaron 25 tests con 86 assertions exitosas.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Imágenes de categorías y filtro visual en catálogo completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/014-imagenes-categorias-filtro-catalogo/`. Las categorías ahora admiten imagen opcional en el CRUD y el catálogo muestra avatares circulares, fallback genérico, selección sombreada con texto semibold y botón `Eliminar filtros`, conservando rutas, consultas y operaciones.
**Errores encontrados:** Ninguno funcional. La validación se realizó manteniendo Bootstrap 5, el modo claro/oscuro y el bloqueo de formularios existente.
**Cómo se corrigió:** Se agregó `image_path` a `categories`, validación de imágenes, carga y eliminación física, inputs multipart en los modales, imágenes circulares en la tabla y filtro visual con el mismo `categoria_id`. Se añadieron tests para persistencia/eliminación y catálogo. La suite terminó con 40 tests y 153 assertions exitosas. MCP verificó cinco inputs de imagen en categorías, selección `is-selected`, fallback, botón de filtros y cero overflow a 390px.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Imágenes de categorías y filtro visual en catálogo
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/014-imagenes-categorias-filtro-catalogo/` para mejorar la búsqueda visual de productos. Define imágenes opcionales por categoría, fallback genérico circular, selección sombreada con texto semibold, botón para eliminar filtros y estados vacíos requeridos, conservando rutas, consultas y operaciones.
**Errores encontrados:** Ninguno durante la definición. La feature amplía `Category` con `image_path`, reutiliza los endpoints actuales y no contradice los requisitos existentes.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar la imagen opcional, el filtro visual, validaciones, tests y verificación MCP.

## 2026-09-03 — Feature: Bloqueo de formularios durante el guardado completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/013-bloqueo-formularios-guardando/` para evitar doble clic y envíos repetidos en formularios administrativos. Los formularios de productos, categorías, configuración y login ahora muestran `Guardando...`, bloquean controles visibles y botones de cierre/cancelación mientras procesan.
**Errores encontrados:** MCP detectó que la primera versión deshabilitaba campos visibles sin conservar sus valores, provocando validaciones incompletas y 405 por campos hidden deshabilitados. Se corrigió mediante los fixes `/spec/fixes/006-metodo-formulario-bloqueado/` y `/spec/fixes/007-campos-bloqueados-no-enviados/`.
**Cómo se corrigió:** Se creó `resources/js/bloqueo-formularios.js`, que bloquea controles visibles, preserva `_token`/`_method`, crea espejos hidden para valores y mantiene file inputs utilizables pero bloqueados visualmente. Se agregó una regresión en `tests/Feature/Admin/ProductoCrudTest.php`, se recompiló el frontend y se ejecutaron 38 tests con 142 assertions exitosas. MCP confirmó `Guardando...`, campos bloqueados y guardado real de Configuración sin 405.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Fix: Drag & drop para el logo de configuración
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/005-dragdrop-logo-configuracion/` para corregir la gestión del logo en `/admin/configuracion`, que solo tenía un input convencional y no permitía interacción de carga, reemplazo y eliminación mediante drag & drop.
**Errores encontrados:** MCP confirmó que no existían dropzone, previsualización ni botón de eliminación. El backend ya borraba el archivo anterior al guardar, pero faltaba la interacción frontend para controlar reemplazo y eliminación.
**Cómo se corrigió:** Se agregó `resources/js/logo-dropzone.js` con arrastre, selección por clic, previsualización, reemplazo y botón `×` que limpia el archivo y envía `eliminar_logo=1`. Se integró en Configuración y se conservaron validación, almacenamiento público y borrado de `logo_path` y archivo físico. Se agregaron pruebas en `tests/Feature/Admin/ConfiguracionTest.php`. La suite pasó con 37 tests y 138 assertions. MCP verificó carga real, previsualización, botón `×`, limpieza del input, persistencia en el header administrativo y restauración del logo al finalizar la prueba.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Fix: Estilo y acordeón del filtro de catálogo
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/008-filtro-catalogo-acordeon-precio/` para corregir bordes gruesos, fondo blanco, encabezados coloreados, cierre acoplado de acordeones y falta de presentación visual del rango de precio.
**Errores encontrados:** MCP confirmó que Bootstrap aplicaba el estilo predeterminado del acordeón y que ambos bloques compartían `data-bs-parent="#filtrosAccordion"`, provocando el cierre automático. El filtro de precio solo tenía inputs numéricos.
**Cómo se corrigió:** Se eliminaron los `data-bs-parent` para hacer independientes los acordeones, se agregaron estilos de fondo opaco y bordes finos, encabezados neutros y una barra visual tipo progress conservando `precio_min` y `precio_max`. Se agregó regresión en `tests/Feature/Catalogo/FiltrosTest.php`. La suite pasó con 41 tests y 159 assertions. MCP verificó filtro en móvil, ambos acordeones, barra de precio, bordes de `0.8px`, encabezado transparente y cero overflow.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Feature: Paleta de colores de la empresa
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/015-paleta-colores-empresa/`. Define un tab nuevo `Aspecto visual` en Configuración con pickers de color (primario, secundario, botones, menú, fondo y texto) que persisten en `store_settings` y se aplican vía variables CSS al catálogo público y al panel admin, conservando el modo claro/oscuro y el endpoint `PUT /admin/configuracion`.
**Errores encontrados:** Ninguno durante la definición. La verificación contra `/spec/01-requirements.md` y `/spec/03-data-model.md` no detectó conflictos: se extiende `store_settings` con campos nullable y no se agregan rutas ni entidades nuevas. Se calculó el correlativo `015` tomando el máximo actual (`014`).
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar la paleta de colores, la migración de campos, los pickers/tab y sus tests de regresión.

## 2026-09-03 — Fix: Conservar valores al bloquear formularios
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/007-campos-bloqueados-no-enviados/` después de que MCP detectara que un formulario bloqueado enviaba POST/PUT incompleto y fallaba validación.
**Errores encontrados:** Los controles `disabled` no se envían en formularios HTML. El bloqueo de `resources/js/bloqueo-formularios.js` deshabilitaba campos visibles antes del submit y eliminaba sus valores de la petición, aunque `_token` y `_method` ya se preservaban.
**Cómo se corrigió:** Se implementó la creación de copias `input[type=hidden]` para los controles nombrados antes de deshabilitarlos; los inputs file permanecen habilitados pero bloqueados visualmente para conservar multipart. Se agregó una regresión en `tests/Feature/Admin/ProductoCrudTest.php`. La suite terminó con 38 tests y 142 assertions exitosas; MCP confirmó `Guardando...`, campos visibles bloqueados, hidden `_token`/`_method` preservados y guardado real de Configuración sin 405.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Fix: Preservar método de formularios bloqueados
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/006-metodo-formulario-bloqueado/` después de que MCP detectara un 405 al guardar Configuración durante el bloqueo de formularios.
**Errores encontrados:** El script deshabilitaba todos los inputs, incluidos `_token` y `_method`. Al enviarse el formulario, Laravel no recibía el method spoofing PUT y lo interpretaba como POST.
**Cómo se corrigió:** `resources/js/bloqueo-formularios.js` ahora bloquea únicamente inputs visibles (`input:not([type="hidden"])`), selects, textareas y botones, preservando los campos ocultos de CSRF y método. Se agregó una regresión en `tests/Feature/Admin/ProductoCrudTest.php`, se recompiló el frontend y MCP confirmó el comportamiento corregido.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Feature: Bloqueo de formularios durante el guardado
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/013-bloqueo-formularios-guardando/` para evitar doble clic, envíos repetidos y duplicación de operaciones en formularios administrativos. Define el estado `Guardando...`, bloqueo de campos y botones incluyendo cerrar/cancelar, reactivación ante errores y aplicación en productos, categorías, configuración, login y formularios futuros.
**Errores encontrados:** Ninguno durante la definición. La feature es frontend, no agrega datos ni endpoints y no contradice `/spec/01-requirements.md` ni `/spec/03-data-model.md`.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta iniciar la implementación del comportamiento compartido y agregar sus pruebas de regresión.

## 2026-09-03 — Feature: Logo de tienda en headers completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/012-logo-tienda-headers/`. Se eliminó la franja negra del catálogo y se agregó carga, reemplazo y eliminación del logo desde Configuración, mostrando el logo en los headers público y administrativo con fallback al nombre de la tienda.
**Errores encontrados:** No se detectaron errores funcionales. MCP encontró inicialmente overflow móvil causado por el bundle CSS anterior; después de recompilar el frontend el overflow quedó en cero.
**Cómo se corrigió:** Se agregó `logo_path` mediante migración y al modelo `StoreSetting`, validación de PNG/JPG/JPEG/WEBP hasta 2 MB, almacenamiento público y eliminación segura del archivo anterior. Se actualizó `PUT /admin/configuracion` con multipart, el formulario de configuración y ambos layouts. La suite terminó con 37 tests y 136 assertions exitosas. MCP confirmó logo cargado en ambos headers, franja negra ausente y cero overflow en móvil.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Logo de tienda en headers
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/012-logo-tienda-headers/` para eliminar la franja negra del header del catálogo y permitir cargar un logo desde Configuración. El logo se mostrará en los headers público y administrativo, con fallback al nombre de la tienda, validación de PNG/JPG/JPEG/WEBP hasta 2 MB y adaptación responsive.
**Errores encontrados:** Ninguno durante la definición. La feature amplía `StoreSetting` con `logo_path`, reutiliza `PUT /admin/configuracion` y no contradice los requisitos funcionales existentes.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar la migración, carga/reemplazo/eliminación del logo, headers y tests de regresión.

## 2026-09-03 — Fix: Miniatura del producto en modal de compra
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/004-miniatura-producto-modal-compra/` para corregir que el modal de pedido no mostrara la imagen del producto, dificultando al cliente identificar qué estaba comprando.
**Errores encontrados:** MCP reprodujo que el modal tenía cero elementos `img` y que el resumen solo mostraba nombre, cantidad y total. La vista de detalle cargaba `images`, pero no `primaryImage` de forma explícita ni la renderizaba en el resumen.
**Cómo se corrigió:** Se actualizó `CatalogoController::show()` para cargar `primaryImage` y se agregó la miniatura `purchase-summary-image` al resumen del modal usando `Storage::url()`. Se añadió una prueba de regresión en `tests/Feature/Catalogo/FiltrosTest.php`. La suite terminó con 34 tests y 125 assertions exitosas. MCP verificó la miniatura en modo oscuro a 390px, con `naturalWidth=947` y cero overflow horizontal.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Fix: Inputs del modal de compra a ancho completo
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/003-inputs-modal-compra-ancho-completo/` para corregir que nombre, teléfono, zona y ciudad ocuparan solo media fila dentro del modal de compra.
**Errores encontrados:** MCP confirmó que esos cuatro campos usaban `col-md-6`, mientras dirección y notas usaban `col-12`; en escritorio los controles quedaban con ancho reducido aunque en móvil se expandían correctamente.
**Cómo se corrigió:** Se cambiaron los seis campos del modal a `col-12`, conservando márgenes, iconos, validaciones, modo claro/oscuro y responsive. Se agregó una regresión estructural en `tests/Feature/Catalogo/FiltrosTest.php`. La suite terminó con 33 tests y 122 assertions exitosas. MCP verificó a 1280px y 390px que todos los controles ocupan el ancho interno disponible y que no existe overflow horizontal.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Feature: Mejora visual del modal de compra
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/011-mejora-modal-compra/` para rediseñar visualmente el modal de compra según la referencia proporcionada, conservando campos, validaciones, creación del pedido, redirección actual y compatibilidad claro/oscuro y móvil. Incluye apertura automática de WhatsApp cuatro segundos después de crear el pedido.
**Errores encontrados:** Ninguno durante la definición. La feature no agrega datos ni endpoints y depende del flujo ya implementado en `010-datos-entrega-pedido-whatsapp`.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta iniciar la implementación visual, temporizador y pruebas de regresión.

## 2026-09-03 — Feature: Mejora visual del modal de compra completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/011-mejora-modal-compra/`. El modal de compra ahora presenta una guía visual inspirada en la referencia: encabezado con título/subtítulo, resumen del producto, total destacado, iconos por campo, placeholders, separadores, botón principal y cierre/cancelación. Se conservaron campos, validaciones, creación del pedido, redirección y soporte claro/oscuro.
**Errores encontrados:** MCP detectó que la primera versión había dejado el modal fuera del alcance Alpine, causando cantidad y total vacíos; también fue necesario ajustar una aserción de test para una respuesta HTML con texto en minúscula.
**Cómo se corrigió:** Se reubicó el estado Alpine en el contenedor común, se agregaron iconos `phone`, `map-pin`, `building` y `check`, estilos `purchase-modal` para ambos temas y responsive, y se añadió un temporizador de 4000 ms en la página intermedia `/pedidos/{pedido}/whatsapp`, manteniendo el enlace manual. La suite de pedidos pasó con 6 tests y 21 assertions; MCP validó modal, errores inline, modo oscuro, modo claro, campos, placeholders, cero overflow a 390px, persistencia del pedido y mensaje WhatsApp.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Fix: Contraste de tablas en modo oscuro
**Estado:** ✅ Completado
**Qué se hizo:** Se creó `/spec/fixes/002-contraste-tablas-modo-oscuro/` para corregir el fondo blanco y el texto poco legible de las tablas administrativas en modo oscuro.
**Errores encontrados:** MCP reprodujo que las celdas del cuerpo conservaban `rgb(255,255,255)` aunque el tema estaba en oscuro. La causa fue que Bootstrap aplicaba sus variables efectivas de fondo a `.table` y `.table-hover`, y las reglas anteriores solo modificaban parcialmente esas variables.
**Cómo se corrigió:** Se actualizaron las reglas oscuras de `.sash-table` en `resources/css/app.css` para definir `--bs-table-bg`, `--bs-table-bg-type`, colores de texto y hover, y para forzar el fondo correcto en filas y celdas. Se agregó una prueba de regresión que verifica las reglas CSS. La suite terminó con 32 tests y 113 assertions exitosas. MCP verificó productos, categorías y pedidos en 390px: fondo `rgb(31, 41, 55)`, texto `rgb(242, 244, 247)`, encabezado `rgb(24, 34, 48)` y cero overflow horizontal.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-03 — Feature: Datos de entrega antes del pedido por WhatsApp
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/010-datos-entrega-pedido-whatsapp/` para agregar un modal de datos de cliente y entrega antes de crear el pedido. Incluye nombre, teléfono, zona Lima/provincias, dirección, ciudad y notas, validación inline, resumen, estado `Creando pedido...` y página intermedia hacia WhatsApp.
**Errores encontrados:** Se detectó un conflicto con el comportamiento actual: `PedidoWhatsAppService` rechaza cantidades superiores al stock y sus tests esperan `Stock insuficiente`. El usuario confirmó reemplazar esa restricción y permitir registrar pedidos con stock cero o insuficiente.
**Cómo se corrigió:** N/A; la spec documenta el cambio aprobado, los nuevos campos de `orders`, la modificación de `POST /pedidos` y la actualización necesaria del servicio y tests.
**Siguiente paso sugerido:** Falta implementar la feature, migrar los datos, actualizar validaciones y verificar el flujo completo con MCP.

## 2026-09-03 — Feature: Datos de entrega antes del pedido por WhatsApp completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/010-datos-entrega-pedido-whatsapp/`. El detalle de producto ahora abre un modal responsive con nombre, teléfono, zona Lima/provincias, dirección, ciudad y notas; muestra resumen de producto/cantidad/total, valida campos debajo de cada input, indica `Creando pedido...`, persiste la información y dirige a una página intermedia de WhatsApp.
**Errores encontrados:** Se detectó y resolvió que el modal estaba fuera del alcance Alpine y mostraba cantidad/total vacíos; además, los tests y el servicio aún aplicaban la restricción de stock aprobada para eliminar.
**Cómo se corrigió:** Se agregó la migración de datos de entrega, campos fillable en `Order`, validación en `CreatePedidoRequest`, persistencia en `PedidoWhatsAppService`, datos en el mensaje de WhatsApp y ruta/vista `/pedidos/{pedido}/whatsapp`. Se eliminó el bloqueo por stock y se actualizaron los tests. La suite terminó con 32 tests y 115 assertions exitosas. MCP validó apertura sin creación prematura, errores inline, creación completa, datos visibles y enlace de WhatsApp con los datos de entrega.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Rediseño visual de tablas CRUD con estilo Sash
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/009-tablas-crud-estilo-sash/` para mejorar visualmente las tablas CRUD administrativas de productos, categorías y pedidos usando como referencia `data-tables.html` de Sash Bootstrap 5. Se definió conservar completamente filtros, búsqueda, paginación, modales, botones, confirmaciones, textos, rutas, temas y operaciones.
**Errores encontrados:** Ninguno durante la definición. La verificación contra `/spec/01-requirements.md` y `/spec/03-data-model.md` no detectó conflictos; la feature no agrega datos ni endpoints.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta iniciar la implementación visual y agregar las pruebas de regresión.

## 2026-09-03 — Feature: Rediseño visual de tablas CRUD con estilo Sash completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/009-tablas-crud-estilo-sash/` aplicando el lenguaje visual de `data-tables.html` de Sash Bootstrap 5 a las tablas administrativas de productos, categorías, pedidos y detalle de pedido. Se conservaron filtros, búsqueda, paginación, modales, botones, confirmaciones, textos, rutas, endpoints, datos y modo claro/oscuro.
**Errores encontrados:** Ninguno funcional. La suite completa terminó con 31 tests y 111 assertions exitosas.
**Cómo se corrigió:** Se añadieron las clases visuales compartidas `sash-table-card`, `sash-table`, `sash-actions` y `sash-table-card-inner`, con estilos para encabezados, filas, estados, acciones, badges, bordes, espaciado y paginación en ambos temas. Se mantuvo el contenedor responsive de Bootstrap y se agregaron regresiones que comprueban la presencia de las clases sin modificar el contenido funcional. MCP validó productos, categorías y pedidos a 390px, sin overflow horizontal, con menú móvil y tema persistente.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Rediseño visual del administrador y tema claro/oscuro
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/008-rediseño-admin-tema-claro-oscuro/` para rediseñar visualmente todas las páginas administrativas inspirándose en Sash Bootstrap 5. Incluye menú lateral, header, tarjetas, tablas, botones, formularios, modales, badges, alertas, paginación, login, responsive móvil y alternancia claro/oscuro persistida en `localStorage`.
**Errores encontrados:** Ninguno durante la definición. La revisión contra `/spec/01-requirements.md` y `/spec/03-data-model.md` no detectó conflictos: no se modifican textos, rutas, endpoints, entidades ni campos.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta iniciar la implementación del rediseño y agregar sus pruebas de regresión.

## 2026-09-03 — Feature: Rediseño visual del administrador y tema claro/oscuro completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó `/spec/features/008-rediseño-admin-tema-claro-oscuro/`. Se aplicó un lenguaje visual inspirado en Sash Bootstrap 5 al layout administrativo, login, productos, categorías, pedidos, detalle de pedido y configuración, manteniendo textos, rutas y operaciones sin cambios. Se incorporó un botón de tema claro/oscuro con persistencia en `localStorage`.
**Errores encontrados:** La primera aplicación parcial del layout no coincidió con el contexto exacto del archivo y se dividió en parches menores; no quedaron errores funcionales. MCP detectó únicamente imágenes históricas inexistentes (`path=0` o archivos borrados), fuera del alcance visual de esta feature.
**Cómo se corrigió:** Se agregaron tokens CSS para ambos temas, estilos de sidebar/topbar/cards/tablas/formularios/modales/badges/paginación, menú responsive con offcanvas, botón reutilizable `theme-toggle`, inicialización temprana del tema para evitar parpadeo y el script `resources/js/tema.js`. Se agregó una prueba de regresión para el layout/tema, se compiló el frontend y los 31 tests pasaron con 105 assertions. MCP verificó tema claro, alternancia a oscuro, persistencia `ecommerce-theme=dark`, cero overflow horizontal en 390px y navegación responsive.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-03 — Feature: Búsqueda y paginación en tablas CRUD
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec `/spec/features/007-busqueda-paginacion-crud/` para mejorar la experiencia de búsqueda del administrador. Define búsqueda por nombre y descripción, coincidencias parciales sin distinguir mayúsculas/minúsculas, filtros mediante `GET`, 10 registros por página, conservación del término al cambiar de página y el estado vacío `Sin resultados disponibles`.
**Errores encontrados:** Ninguno durante la definición. La verificación contra `/spec/01-requirements.md` y `/spec/03-data-model.md` no detectó conflictos; no se agregan datos ni endpoints nuevos.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta iniciar la implementación de la feature y agregar sus tests de regresión.

## 2026-09-03 — Feature: Búsqueda y paginación en tablas CRUD completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó la spec `/spec/features/007-busqueda-paginacion-crud/`. Los índices administrativos de productos y categorías ahora aceptan `buscar` por `GET`, muestran 10 registros por página, conservan los parámetros al paginar y muestran `Sin resultados disponibles` cuando no hay coincidencias. Productos buscan en `name` y `description`; categorías buscan en `name`, que es el único campo textual disponible según el modelo.
**Errores encontrados:** Ninguno en la implementación. La suite se ejecutó con 31 tests y 105 assertions exitosas.
**Cómo se corrigió:** Se actualizaron `ProductController` y `CategoryController` con consultas insensibles a mayúsculas mediante `LOWER(...) LIKE`, `paginate(10)` y `withQueryString()`. Se agregaron formularios Bootstrap de búsqueda, estados vacíos y tests para coincidencias, mayúsculas, paginación y query string. MCP confirmó filtros reales en productos y categorías y no reportó errores de consola en la vista de categorías.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la validación local y MCP quedó completada.

## 2026-09-08 — Feature: CRUD completo de pedidos en admin con modal, estados y auditoría
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec /spec/features/016-crud-pedidos-admin-modal-estados/. Extiende el listado de pedidos (RF-14) con modal de edición completa: datos cliente/entrega, nota interna, selector de estado (PENDIENTE/EN_PROCESO/ENVIADO/ENTREGADO), items con miniatura, cantidad editable con recálculo en vivo, agregar/eliminar items, y auditoría de cambios de estado en tabla order_status_logs. Bloquea edición en estado ENTREGADO salvo reabrir cambiando estado.
**Errores encontrados:** Ninguno durante la definición. Verificación contra /spec/01-requirements.md y /spec/03-data-model.md sin conflictos: requiere migraciones para status, 
otes en orders y nueva tabla order_status_logs.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar migraciones, modelo OrderStatusLog, modal con Alpine.js, controller y tests de regresión.

## 2026-09-08 — Feature: CRUD completo de pedidos en admin con modal, estados y auditoría completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó /spec/features/016-crud-pedidos-admin-modal-estados/. El listado de pedidos ahora tiene columna Estado (badges PENDIENTE/EN_PROCESO/ENVIADO/ENTREGADO) y botón Editar que carga un modal vía AJAX (GET /admin/pedidos/{id}/edit) con todos los datos: cliente, entrega editable, nota interna, selector de estado, items con miniatura del producto, cantidad editable con recálculo en vivo de subtotal/total, y modal anidado para agregar items con búsqueda debounced (GET /admin/productos/search). Al guardar (PUT /admin/pedidos/{id}) se actualiza el pedido en transacción: sincroniza items (crear/actualizar/eliminar), recalcula el total y registra en order_status_logs cuando cambia el estado. Los pedidos ENTREGADO se abren en solo-lectura (inputs disabled, sin botón agregar), pero el selector de estado queda activo para reabrirlos.
**Errores encontrados:** (1) Variable $tienda indefinida: el estilo brand se calculaba antes de definirla en el layout; se resolvió llamando brandCss() sin argumento. (2) $product indefinido en el partial: se usaba en markup Blade dentro de un x-for de Alpine; se cambió a x-if/x-bind. (3) @push('scripts') no se renderizaba porque el layout admin no tiene @stack; se movió el script inline al index. (4) ootstrap no definido: el import ESM no expone window.bootstrap; se agregó window.bootstrap en app.js. (5) pedidoModal is not defined por carrera entre el MutationObserver de Alpine y la inyección AJAX: se movió la definición del componente al script del index (cargado antes). (6) El método init() de Alpine se invoca automáticamente sin argumentos además del x-init; se renombró a cargar(). (7) El listener hidden.bs.modal borraba el placeholder al abrir el modal anidado; se eliminó. (8) Vite dev sirvió app.js obsoleto por cache de mtime del volumen Windows→Docker; se reinició el dev server.
**Cómo se corrigió:** Ver arriba por caso. Suite completa: 56 tests y 226 assertions exitosas. MCP validó en escritorio (1440px) y móvil (390px): modal AJAX con miniaturas, búsqueda y agregado de items con recálculo (20→30 al agregar), guardado persistiendo estado/nota/total (30.00) con log PENDIENTE→EN_PROCESO, cero overflow horizontal.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la feature quedó implementada, testeada y validada.

## 2026-09-08 — Fix: Iconos, alineación, ancho y SweetAlert2 del modal de pedido en admin
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/fixes/010-modal-pedido-iconos-alineacion-alertas/ para corregir 4 problemas del modal de edición de pedidos.
**Errores encontrados:** (1) Iconos invisibles: el componente <x-icon> no tenía los nombres trash/image/save en su mapa de paths, renderizando <path d=""> sin trazos. (2) Combo de Estado descuadrado: el bloque Cliente usaba doble nivel de labels (fw-semibold + small internos) y Estado uno solo, quedando el select más arriba que los inputs. (3) Modal demasiado ancho: .modal-xl { max-width: 90% } en app.css. (4) Uso de confirm()/lert() nativos en removeItem y en el catch del fetch, cuando el proyecto ya tiene SweetAlert2 global (window.alertas de resources/js/alertas.js).
**Cómo se corrigió:** (1) Se agregaron los paths SVG de trash, image y save al icon component. (2) Se reestructuró la fila superior a tres col-md-4 hermanos (Nombre/Teléfono/Estado) con labels small idénticos, quedando alineados en la misma línea base. (3) Se cambió a max-width: 63% en escritorio con media query de 95% bajo 991.98px. (4) emoveItem() ahora es async y usa window.alertas.confirm('Eliminar item', ...); el catch del fetch usa window.alertas.error(...). Test de regresión agregado que verifica el path del icono con trazos (no d=""), la fila de 3 col-md-4 compartida y el 63% en el CSS. Suite completa: 57 tests y 233 assertions. MCP validó: modal a 63% en 1440px y 95% en 390px, icono de basura visible, confirm SweetAlert2 con eliminación funcional (items 2→1, total 30→10), cero overflow horizontal.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-08 — Feature: Script setup.sh para levantar el sistema manualmente
**Estado:** ✅ Completado
**Qué se hizo:** Se agregó setup.sh en la raíz con los 7 pasos de arranque manual: composer install, .env + key:generate, docker compose up -d --build, espera a PostgreSQL con pg_isready, migrate --seed, npm install dentro del contenedor (binarios de Linux) y npm run build. Acepta --reinstall para limpiar node_modules/package-lock ante el error nativo de @rollup. Se documentó la opción del script al inicio de la sección 'Instalación y arranque local' del README. Uso: ash setup.sh (Git Bash en Windows).
**Errores encontrados:** Ninguno; script informativo sin lógica de negocio.
**Cómo se corrigió:** N/A; no toca código ni datos.
**Siguiente paso sugerido:** Si se automatiza CI/CD, reutilizar los pasos del script en el pipeline.

## 2026-09-08 — Fix: Error de build groupadd (WWWUSER/WWWGROUP) + seeder idempotente
**Estado:** ✅ Completado
**Qué se hizo:** Se corrigió el fallo al levantar el sistema (docker compose up --build fallaba con groupadd --force -g  exit code 3).
**Errores encontrados:** (1) El .env no definía WWWUSER/WWWGROUP, por lo que el Dockerfile de Sail recibía un $WWGROup vacío y groupadd -g fallaba con código 3 (id de grupo inválido). (2) Con la BD ya poblada, DatabaseSeeder usaba User::factory()->create() para admin y abortaba con users_email_unique (SQLSTATE 23505); además faltaba el import de Hash en el seeder.
**Cómo se corrigió:** Se agregaron WWWUSER=1000 y WWWGROUP=1000 a .env y .env.example, y setup.sh ahora los añade automáticamente si faltan antes del docker compose up. El seeder usa ahora User::firstOrCreate(['email' => 'admin@example.com'], ['password' => Hash::make('password')]) (idempotente) con el import de Hash. Verificado: build exitoso, migrate --seed sin errores, http://localhost y /admin/login responden 200, y login admin@example.com/password validado por tinker.
**Siguiente paso sugerido:** Ejecutar ash setup.sh desde el inicio en entornos nuevos: ya no requiere intervención manual para estas variables.

## 2026-09-08 — Feature: Catálogo responsive con mayor visibilidad e iconos de carrito
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec /spec/features/017-catalogo-responsive-carrito/. Define la adaptación móvil del catálogo, filtros, cards y detalle de producto, mejor visibilidad de productos, control compacto para filtros, icono de carrito en botones Agregar y conservación completa del flujo actual de filtros y pedidos por WhatsApp.
**Errores encontrados:** Ninguno durante la definición. La verificación contra /spec/01-requirements.md y /spec/03-data-model.md no detectó conflictos: no se agregan datos ni endpoints.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar la adaptación responsive, iconografía, tests y validación MCP.

## 2026-09-17 — Feature: Catálogo responsive con mayor visibilidad e iconos de carrito completada
**Estado:** ✅ Completado
**Qué se hizo:** Se implementó /spec/features/017-catalogo-responsive-carrito/. El catálogo público ahora usa un panel de filtros offcanvas-md: offcanvas en móvil y panel fijo desde escritorio, conservando todos los campos, parámetros GET, acordeones y eliminación de filtros. Las cards mantienen el contenido funcional y mejoran su espaciado/legibilidad móvil. Los botones Agregar ahora usan icono de carrito (cart) en lugar de WhatsApp. El detalle de producto incorpora layout responsive, stepper de cantidad adaptable, card de compra y modal de pedido usable en pantallas pequeñas.
**Errores encontrados:** Ninguno funcional durante la implementación. MCP reportó errores de recursos de imágenes históricas inexistentes fuera del alcance; no hubo overflow horizontal en catálogo ni detalle.
**Cómo se corrigió:** Se agregó el path SVG cart al componente de iconos, se reorganizó el aside de filtros con offcanvas-md, se añadieron reglas responsive para cards/detalle/modal y se agregaron regresiones de catálogo. Build exitoso. Suite completa: 58 tests y 245 assertions. MCP validó 390px: filtros abiertos mediante botón, panel de 343px sin overflow, cards con carrito, detalle apilado y 0 overflow.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la feature quedó implementada, testeada y validada.

## 2026-09-17 — Feature: Rediseño marketplace responsive del catálogo
**Estado:** ⚠️ Parcial
**Qué se hizo:** Se creó la spec /spec/features/018-rediseño-marketplace-catalogo/. Define un rediseño visual integral del catálogo público inspirado en Marketplace Moderno Versión 4: desktop/mobile, light/dark, header, buscador, breadcrumb, filtros/drawer, categorías, cards con badges y carrito, detalle de producto, accesibilidad y consumo de paleta dinámica desde Admin.
**Errores encontrados:** Ninguno durante la definición. La revisión contra /spec/01-requirements.md y /spec/03-data-model.md no detectó conflictos: no se agregan datos ni endpoints. La feature depende visualmente de  17-catalogo-responsive-carrito y  15-paleta-colores-empresa.
**Cómo se corrigió:** N/A; la spec quedó creada para aprobación antes de implementar.
**Siguiente paso sugerido:** Falta implementar el rediseño marketplace y agregar sus tests de regresión.

## 2026-09-17 — Fix: Paridad visual del catalogo movil con la referencia de diseno
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/fixes/011-paridad-movil-referencia/ para cerrar la brecha entre la versión móvil implementada y la imagen de referencia "Versión Móvil" del módulo marketplace.
**Errores encontrados:** Causa raíz: la feature 018 no incluyó tres elementos estructurales de la referencia: (1) la fila "Categorías" con chips scrollables (avatar + nombre) bajo el hero no se implementó; (2) la media de la card usaba cover full-bleed en vez de imagen contenida centrada sobre la superficie de la card; (3) la paginación y los radios del drawer no fueron restilizados a lenguaje app. Todo era presentación, sin defecto en queries, rutas ni lógica.
**Cómo se corrigió:** Se agregó la sección móvil "Categorías" con chips (imagen o fallback de icono, chip activo en color de marca y "Ver todas" que abre el drawer); la media de la card ahora usa object-fit: contain con padding sobre var(--admin-surface); la paginación va dentro de .catalog-pagination con pill redondeado; los radios del drawer muestran círculo de selección con radial-gradient de marca, y en móvil el drawer oculta los avatares de categoría (que quedan en escritorio y en la fila de chips). Test de regresión que valida markup de chips/paginación y CSS (contain + pill). Suite completa: 60 tests y 263 assertions. MCP en 390px validó: 9 chips de categoría visibles con chip activo, media contain (16px/20px), paginación presente, drawer con radios circulares y avatares ocultos, y 0 overflow horizontal en light y dark.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; el fix quedó corregido, testeado y verificado.

## 2026-09-17 — Fix: Botones de filtros flotando y sobreponiéndose al contenido
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/fixes/012-botones-filtros-flotando-superpuestos/ para corregir que "Aplicar filtros" y "Eliminar filtros" flotaban y se superponían al contenido en móvil y web.
**Causa raíz:** El footer .sticky-filter-cta (definido en el fix 008) usaba position: sticky; bottom: 0; margin-top: auto. En móvil el contenedor de scroll es el offcanvas-body y el sticky mantiene la barra pegada al fondo encima del acordeón al hacer scroll (overlapWithAccordion: true); en escritorio la card de filtros es más alta que el footer y el sticky lo adhiere al viewport quedando flotando a media card (tnBottomVsCard: -352). Además g-transparent de Bootstrap (clase en el card-footer) dejaba ver los controles subyacentes a través de los botones.
**Cómo se corrigió:** Se quitó position: sticky/ottom: 0 de .sticky-filter-cta (queda position: static en flujo normal, al final de la card/sidebar, con order-radius: 0 0 1rem 1rem) y se eliminó la clase g-transparent del card-footer para que el fondo sólido ar(--admin-surface) no deje entrever contenido. Test de regresión que valida que .sticky-filter-cta no declara position: sticky ni ottom: 0 y que el blade ya no usa card-footer bg-transparent. Suite completa: 62 tests y 272 assertions. MCP: móvil (cta static, fondo rgb(255,255,255), overlap false) y escritorio (footer anclado al final de la card, flotando false).
**Siguiente paso sugerido:** Ninguno; el fix quedó corregido, testeado y verificado.

## 2026-09-17 — Fix: Paginación del catálogo centrada en la versión móvil
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/fixes/013-paginacion-centrada-movil/ para centrar los controles "« Previous / Next »" en móvil.
**Causa raíz:** Laravel 12 (bootstrap-5) genera la paginación con dos bloques; el visible en móvil (≤575px) es <div class="d-flex justify-content-between flex-fill d-sm-none"> con solo la ul.pagination como hijo. Al tener un único hijo, justify-content-between lo alinea a la izquierda. Además la utilidad Bootstrap .justify-content-between{justify-content:space-between!important} usa !important, por lo que una regla sin !important (.catalog-pagination .d-sm-none{justify-content:center}) no la vencía — el primer intento parecía correcto por el test de texto pero el navegador seguía mostrándolo en space-between hasta agregar !important.
**Cómo se corrigió:** Regla .catalog-pagination .d-sm-none { justify-content: center !important; } en esources/css/app.css. Test de regresión que valida que el bloque CSS .catalog-pagination .d-sm-none existe con justify-content: center. Suite: 63 tests y 274 assertions. MCP 390px: justify: center, ul centrada (188px = centro del contenedor); escritorio 1280px: bloque de números (‹ 1 2 3 ›) sigue alineado a la derecha sin afectarse.
**Siguiente paso sugerido:** Ninguno; el fix quedó corregido, testeado y verificado.

## 2026-09-17 — Feature: Botón de filtros como icono pequeño en la versión móvil
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/features/020-boton-filtro-icono-movil/ para reemplazar el botón full-width "Filtros" en móvil por un icono compacto (sliders) alineado a la derecha de la fila de Categorías.
**Qué se implementó:** Se eliminó el botón <button class="btn btn-outline-primary w-100 d-md-none catalog-filter-trigger"> (línea 48 del blade original) y se reemplazó con un <button class="catalog-filter-icon-btn"> dentro de la sección de Categorías (junto al heading "Categorías", reemplazando el enlace "Ver todas"). El nuevo botón tiene: icono sliders sin texto, fondo ar(--admin-surface-soft), border-radius .6rem, tamaño 34x34px, ria-label="Abrir filtros", y data-bs-target="#catalogFiltersPanel" (mismo offcanvas). En desktop (≥768px) el botón se oculta (d-md-none heredado del section) y el sidebar de filtros se mantiene intacto. Se eliminó la clase .catalog-categories-more del CSS (ya no se usa). Test FiltrosTest actualizado: catalog-filter-icon-btn en vez de catalog-filter-trigger. Suite: 63 tests y 274 assertions. MCP: móvil 390px — icono visible 34x34px, alineado a la derecha (right: 359), fondo rgb(248,249,252), border-radius 9.6px, aria-label presente, oldBtnExists false; escritorio 1280px — icono oculto, sidebar visible.
**Siguiente paso sugerido:** Feature completada; desplegar cuando exista un destino de CD.

## 2026-09-17 — Feature: Card de productos con layout estable, fallback de imagen y texto truncado
**Estado:** ✅ Completado
**Qué se hizo:** Se creó /spec/features/021-card-layout-estable/ para corregir 3 problemas de las cards del catálogo: botón "Agregar" desplazado, imagen rota rompía la card, texto desbordado.
**Qué se implementó:**
1. **Botón abajo**: Bootstrap .card ya tiene display: flex; flex-direction: column. Se agregó d-flex flex-column flex-grow-1 al card-body y mt-auto al precio para empujar el CTA al final. Resultado: mismo高度 por fila (422px móvil, 492px escritorio).
2. **Fallback de imagen**: se agregó <div class="product-card-fallback"> con icono image y spect-ratio: 1/1 que se muestra cuando la img falla (onerror en el <img>). El fallback ocupa el mismo espacio que la imagen y no desplaza elementos.
3. **Texto truncado**: .product-card-desc con line-clamp: 3, overflow: hidden, -webkit-box-orient: vertical. Se quitó el display: none en móvil que ocultaba la descripción; ahora se muestra truncada a 3 líneas.
**Archivos modificados:** esources/views/components/producto-card.blade.php (flex, fallback, onerror), esources/css/app.css (line-clamp-3, fallback, quitado display:none en móvil). Test actualizado: catalog-filter-icon-btn en vez de catalog-filter-trigger (del fix 020).
**Suite:** 63 tests y 274 assertions. MCP: móvil 390px — cards alineadas [422, 422], line-clamp 3, fallback con d-none (img OK); escritorio 1280px — cards [492, 492], line-clamp 3.
**Siguiente paso sugerido:** Feature completada; desplegar cuando exista un destino de CD.

## 2026-09-19 � Feature: Descripcion larga con texto enriquecido (Quill.js) en admin de productos
**Estado:** Completado
**Que se hizo:** Se creo /spec/features/022-descripcion-larga-producto-admin/ y se implemento un campo "Descripcion Larga" con editor de texto enriquecido (Quill.js) en el modal de crear/editar producto del admin. El campo long_description (text, nullable) se agrega a la tabla products y se renderiza como HTML en la pagina de detalle del catalogo publico.
**Que se implemento:**
1. **Migracion**: dd_long_description_to_products_table agrega long_description (text, nullable) despues de description.
2. **Modelo Product**: long_description agregado al $fillable.
3. **Form Requests**: StoreProductRequest y UpdateProductRequest ahora validan long_description como nullable string.
4. **Editor Quill.js**: Se instalo quill via npm, se importo CSS (quill/dist/quill.snow.css) en pp.css, se creo esources/js/quill-init.js que inicializa editores Quill con toolbar (headings, bold, italic, lists, blockquote, code-block, link, image, align, clean). Se detectan editores nuevos via MutationObserver al abrir modales y al hacer submit se sincroniza el contenido al textarea hidden.
5. **Vista admin**: El partial _campos.blade.php ahora incluye un campo "Descripcion larga" con editor Quill debajo de la descripcion corta.
6. **Vista publica**: show.blade.php renderiza long_description con {!! !!} (HTML) debajo de la descripcion corta, solo si no esta vacio.
7. **Tests**: 4 tests nuevos en ProductoCrudTest.php � persistencia al crear, persistencia al editar, acepta valor nulo/vacio, y presencia del editor en el index.
**Errores encontrados:** Ninguno funcional. La suite completa termino con 67 tests y 287 assertions exitosas.
**Como se corrigio:** N/A.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD; la feature quedo implementada, testeada y verificada.

## 2026-09-19 � Feature: Optimizacion de rendimiento y carga de vistas (spec 023)
**Estado:** Completado
**Que se hizo:** Se implemento /spec/features/023-optimizacion-rendimiento-catalogo-admin/. Se optimizo significativamente la performance del catalogo publico y del admin.
**Que se implemento:**
1. **Code splitting**: vite.config.js con manualChunks (vendor: Bootstrap+Alpine, quill: Quill). Nuevo entry point admin.js para scripts admin-only (dropzones, paleta, quill-init). El catalogo publico carga ~214KB JS (antes 466KB, -54%).
2. **Cache de StoreSetting**: Cache::remember() en current() con TTL de 1h. flushCache() manual en StoreSettingController despues de update. Elimina 12+ queries redundantes por pagina.
3. **Footer optimizado**: CatalogoController pasa . Layout app.blade.php usa la variable en vez de Category::get() hardcodeado.
4. **Indices de BD**: Migracion add_performance_indexes en products(price), products(created_at), product_images(product_id, is_primary), orders(status), orders(created_at).
5. **Fix N+1 OrderController**: Pre-carga productos con whereIn() antes del loop de items en update().
6. **Google Fonts**: preconnect + link en ambos layouts (app y admin). Eliminado @import url() de app.css.
7. **Quill init dinamico**: import() de Quill solo cuando se abre el modal (lazy loading).
**Errores encontrados:** Boot() de StoreSetting con eventos saved/deleted causaba warnings de Undefined array key en Eloquent. Se reemplazo por flushCache() manual en el controller. Test de Google Fonts actualizado (verifica layout en vez de CSS).
**Suite:** 67 tests y 287 assertions exitosas.
**Build result:** app.js 214KB, vendor.js 55KB, quill.js 203KB (admin only), admin.js 6KB.
**Siguiente paso sugerido:** Desplegar cuando exista un destino de CD.
