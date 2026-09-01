# Plantilla para skills especializados (.claude/skills/<nombre>/SKILL.md)

Genera un skill por cada tecnología relevante (backend, frontend, base de
datos, testing, despliegue). Sigue esta plantilla y el estilo "pushy" de
descripción recomendado por `skill-creator`.

```markdown
---
name: {{nombre-skill}}
description: Provee convenciones, snippets y buenas prácticas específicas de
  {{tecnologia}} para EL PROYECTO {{project_name}}. Úsalo siempre que se
  escriba, revise o depure código relacionado con {{tecnologia}} en este
  repositorio — por ejemplo al crear un nuevo {{unidad_de_trabajo}}, modificar
  {{capa_relevante}}, o resolver errores relacionados a {{tecnologia}}.
---

# {{nombre-skill}}

## Contexto de este proyecto
{{resumen_breve_de_como_se_usa_esta_tecnologia_aqui}}

## Convenciones específicas de este repo
{{convenciones_reales_detectadas_o_definidas}}

## Estructura de carpetas relevante
{{estructura_de_carpetas_de_esta_tecnologia_en_el_repo}}

## Patrones a seguir
{{patrones_ej_repository_service_dto}}

## Errores comunes a evitar en este proyecto
{{antipatrones_detectados_en_retrofit_si_aplica}}

## Snippet de referencia
\`\`\`{{lenguaje}}
{{snippet_representativo_del_propio_proyecto}}
\`\`\`
```

## Skills típicos a generar (ajusta según stack real del proyecto)

| Skill                         | Cuándo generarlo                                    |
|--------------------------------|------------------------------------------------------|
| `{{stack}}-backend-specialist` | Siempre, con el nombre del stack backend real (ej. `dotnet-ef-specialist`, `nestjs-specialist`, `laravel-specialist`) |
| `{{frontend}}-specialist`      | Si hay frontend (ej. `react-frontend-specialist`)     |
| `{{db}}-specialist`            | Siempre que haya base de datos (ej. `sqlserver-specialist`, `postgres-specialist`) |
| `testing-specialist`           | Siempre que haya (o se defina) estrategia de testing  |
| `{{cd_target}}-deploy-specialist` | Si hay destino de despliegue definido (ej. `aks-deploy-specialist`) |

No generes skills para tecnologías que no están presentes ni planeadas en el
proyecto — evita ruido.
