---
description: Generar o actualizar el pipeline de CI/CD
agent: build
---

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