# Matriz de opciones por tipo de proyecto

Usa esta tabla para proponer opciones concretas en el Paso 1A. No la muestres
completa al usuario: pregúntale el tipo de proyecto primero, y luego ofrece
solo las opciones de la fila correspondiente.

## 1. Tipo de proyecto → Stacks backend sugeridos

| Tipo de proyecto     | Opciones de stack backend                                  |
|-----------------------|-------------------------------------------------------------|
| API REST              | .NET (ASP.NET Core Web API) · Node.js (NestJS/Express) · Laravel · Spring Boot / Quarkus |
| Full-stack web        | .NET (ASP.NET Core MVC) + React · Next.js (fullstack) · Laravel + Blazor/React |
| Microservicios         | .NET + Docker/K8s · Node.js (NestJS) + Docker/K8s · Spring Boot + Kafka |
| CLI                    | .NET (System.CommandLine) · Node.js (Commander/oclif) · Python (Click/Typer) |
| Mobile backend         | .NET Web API · Node.js (NestJS) · Firebase Functions |
| Librería               | .NET (NuGet package) · Node.js (npm package) · Python (paquete pip) |

## 2. Frontend (si aplica)

- React (Vite o Create React App)
- Next.js (App Router)
- Blazor (Server o WASM)
- Angular
- Ninguno (solo backend / API consumida por otro cliente)

## 3. Base de datos + ORM

| Base de datos | ORM/driver sugerido según stack                            |
|----------------|---------------------------------------------------------------|
| SQL Server     | Entity Framework Core (.NET) · Sequelize/Prisma (Node)        |
| PostgreSQL     | Entity Framework Core · Prisma/TypeORM (Node) · Eloquent (Laravel) |
| MySQL          | Eloquent (Laravel) · Prisma/TypeORM (Node)                    |
| MongoDB        | MongoDB.Driver (.NET) · Mongoose (Node)                        |

## 4. Testing

| Nivel        | Framework sugerido según stack                              |
|--------------|----------------------------------------------------------------|
| Unitario     | xUnit/NUnit (.NET) · Jest (Node) · PHPUnit (Laravel) · JUnit (Spring) |
| Integración  | WebApplicationFactory (.NET) · Supertest (Node) · Pest (Laravel) |
| E2E          | Playwright · Cypress                                            |

## 5. CI (Integración continua)

- Bitbucket Pipelines
- GitHub Actions
- Azure DevOps Pipelines
- Ninguno por ahora (agregar después con `/cicd`)

## 6. CD (Despliegue continuo) — destino

- Azure Kubernetes Service (AKS)
- Azure App Service
- Docker Compose (VPS propio)
- Vercel / Netlify (frontends o Next.js fullstack)
- Ninguno por ahora

## 7. Arquitectura

- Clean Architecture (Domain / Application / Infrastructure / API)
- Arquitectura Hexagonal (Ports & Adapters)
- MVC simple (para proyectos pequeños o de aprendizaje)
- Domain-Driven Design (DDD) completo (para dominios complejos)

Regla general: si el usuario duda, recomienda **Clean Architecture** para
proyectos API/full-stack de tamaño medio-grande, y **MVC simple** para
proyectos pequeños o de aprendizaje — pero deja la decisión final al usuario.
