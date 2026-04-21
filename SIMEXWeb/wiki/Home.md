# SIMEX — Wiki del proyecto

SIMEX es una plataforma web para la gestión de operaciones de comercio
internacional (clientes, solicitudes de cotización, presupuestos,
documentación aduanera y seguimiento de envíos).

## Índice

- [Arquitectura](Arquitectura) — visión general del sistema y las dos APIs.
- [Backend](Backend) — API principal en Laravel 12 (PHP 8.2).
- [Frontend](Frontend) — SPA en Vue 3 + Vite.
- [Despliegue](Despliegue) — Dockerfiles, Dockploy y variables de entorno.
- [CI/CD](CI-CD) — pipeline de tests y publicación de imágenes en Docker Hub.

## Stack en un vistazo

| Capa       | Tecnología                                    |
|------------|-----------------------------------------------|
| Frontend   | Vue 3, Pinia, Vue Router, Vite, Axios         |
| API #1     | Laravel 12, PHP 8.2, tymon/jwt-auth, Fortify  |
| API #2     | ASP.NET Core (microservicio auxiliar)         |
| Base de datos | SQL Server (vía FreeTDS `dblib`)           |
| Contenedores | Docker, Docker Hub, Dockploy                |
| CI         | GitHub Actions                                |

## Convenios

- **Idioma del código y comentarios**: español.
- **Branch principal**: `main`. Workflows también escuchan `develop`/`master`/`workos`.
- **Imágenes en Docker Hub**: `ddelacortep/simex-daw` con tags
  `backend-latest` y `frontend-latest`.
