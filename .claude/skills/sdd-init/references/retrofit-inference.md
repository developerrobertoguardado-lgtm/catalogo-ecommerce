# Señales de detección para MODO RETROFIT

Usa esta guía en el Paso 1B para inferir el máximo posible sin preguntar.

## Detección de stack backend

| Señal en el filesystem                          | Stack inferido                     |
|--------------------------------------------------|-------------------------------------|
| `*.csproj` + `Program.cs`                        | .NET / ASP.NET Core                 |
| `*.csproj` con `<TargetFramework>net48</...>`    | .NET Framework (legacy)             |
| `package.json` con `"next"` en dependencies       | Next.js                             |
| `package.json` con `"@nestjs/core"`               | NestJS                              |
| `composer.json` con `"laravel/framework"`         | Laravel                             |
| `pom.xml` o `build.gradle` con `spring-boot`      | Spring Boot                         |

## Detección de base de datos / ORM

- Buscar `appsettings.json` / `appsettings.*.json` → sección `ConnectionStrings`
  (indica motor por el driver: `Microsoft.Data.SqlClient` → SQL Server,
  `Npgsql` → PostgreSQL).
- Buscar `.env` / `.env.example` → variables `DB_CONNECTION`, `DATABASE_URL`.
- Buscar carpeta `Migrations/` (EF Core) o `database/migrations/` (Laravel) →
  confirma ORM y permite inferir el modelo de datos real leyendo las migraciones.
- Buscar `schema.prisma` (Prisma) o `docker-compose.yml` con imagen
  `postgres`/`mysql`/`mssql`/`mongo`.

## Detección de testing existente

- Buscar carpetas/proyectos `*.Tests`, `*.Test`, `__tests__`, `tests/`.
- Revisar el framework de test por sus dependencias (`xunit`, `nunit`, `jest`,
  `phpunit`, `pest`, `cypress`, `playwright`).
- Si no se encuentra nada de esto, se marca como "sin estrategia de testing
  definida" y se pregunta en el Paso 1B.

## Detección de CI/CD existente

- `.github/workflows/*.yml` → GitHub Actions
- `bitbucket-pipelines.yml` → Bitbucket Pipelines
- `azure-pipelines.yml` → Azure DevOps
- `Dockerfile` + manifiestos `*.yaml` con `kind: Deployment` → indicios de
  despliegue a Kubernetes
- Si nada de esto existe, se marca como "sin CI/CD definido" y se pregunta.

## Detección de arquitectura

- Carpetas `Domain/`, `Application/`, `Infrastructure/`, `API/` (o similar)
  separadas → Clean Architecture.
- Carpetas `Controllers/`, `Models/`, `Views/` sin separación de capas →
  MVC simple.
- Presencia de `Ports/`, `Adapters/` → Arquitectura Hexagonal.
- Si el código mezcla patrones (ej. algunos controllers llaman directo a
  Entity Framework, otros usan repositorios) → marcar como **inconsistente**
  y preguntar al usuario cómo quiere estandarizarlo, no asumir.

## Nivel de certeza al reportar

Al resumir hallazgos al usuario en el Paso 1B, distingue siempre:

- **Detectado con certeza** (ej. el `.csproj` referencia explícitamente
  `Microsoft.EntityFrameworkCore.SqlServer`) → repórtalo como hecho, sin
  preguntar de nuevo.
- **Inferido con baja certeza** (ej. solo hay una carpeta `Data/` sin más
  pistas) → repórtalo como suposición y pide confirmación explícita.
- **No detectable** (ej. el objetivo de negocio del proyecto) → pregúntalo
  siempre, nunca lo inventes.
