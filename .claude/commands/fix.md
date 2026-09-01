# /fix — Flujo guiado para bugs

Cuando el usuario invoque este comando:

1. Pregunta: "¿Qué comportamiento incorrecto estás viendo? ¿Cómo reproducirlo?"
2. Pide (si no las da ya): pasos de reproducción, comportamiento esperado vs.
   actual, logs/stacktrace si existen (`storage/logs/laravel.log`).
3. Calcula el correlativo: revisa las carpetas existentes en `/spec/fixes/`
   (formato `NNN-slug/`, correlativo independiente del de `/spec/features/`),
   toma el número más alto y súmale 1 (3 dígitos). Si no hay ninguna, empieza
   en `001`.
4. Investiga la causa raíz en el código antes de proponer el fix.
5. Genera la carpeta `/spec/fixes/{{NNN}}-{{slug-bug}}/` con estos 4 archivos:

   **`change.md`**
   ```markdown
   # Change: {{titulo_del_fix}}

   ## Reproducción
   {{pasos}}

   ## Comportamiento esperado
   {{esperado}}

   ## Comportamiento actual
   {{actual}}
   ```

   **`impact.md`**
   ```markdown
   # Impact: {{titulo_del_fix}}

   ## Causa raíz
   {{causa_raiz}}

   ## Módulos/archivos afectados
   {{modulos_afectados}}

   ## Riesgo de la corrección
   {{riesgo_de_regresion}}
   ```

   **`task.md`**
   ```markdown
   # Tasks: {{titulo_del_fix}}

   - [ ] {{tarea_1_ej_corregir_validacion}}
   - [ ] {{tarea_2_ej_agregar_test_de_regresion}}
   ```

   **`validation.md`**
   ```markdown
   # Validation: {{titulo_del_fix}}

   ## Test de regresión
   {{descripcion_del_test_que_falla_sin_el_fix_y_pasa_con_el}}

   ## Estado
   - [ ] Corregido
   - [ ] Testeado
   - [ ] Verificado en el comportamiento reportado
   ```

6. Implementa el fix + un test de regresión con Pest (ver
   `testing-specialist`) que falle sin el fix y pase con él, descrito en
   `validation.md`.
7. Marca el estado como "Corregido"/"Testeado" en `validation.md` una vez
   verificado.
8. Agrega una entrada en `/spec/progress-log.md` con `{{tipo}} = Fix`,
   mencionando la carpeta `{{NNN}}-{{slug}}` creada. Es la entrada más
   importante de documentar bien: incluye la causa raíz real y la solución
   aplicada tal cual quedaron en `impact.md`/`task.md`, para que quede
   consultable después sin tener que releer todo el código.
