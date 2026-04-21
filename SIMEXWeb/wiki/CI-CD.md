# CI/CD — GitHub Actions

Tenemos tres workflows, todos en `.github/workflows/`:

| Workflow                        | Archivo                          | Dispara                        |
|---------------------------------|----------------------------------|--------------------------------|
| **Build and Push to Docker Hub**| `.github/workflows/deploy.yml`   | `push` a `main` en `SIMEXWeb/**` |
| **tests** (backend)             | `backend/.github/workflows/tests.yml` | `push`/`PR` a `main`, `develop`, `master`, `workos` |
| **linter**                      | `backend/.github/workflows/lint.yml` | `push`/`PR` a las mismas ramas |

## Pipeline principal: `deploy.yml`

Se dispara sólo si el push modifica algo dentro de `SIMEXWeb/**`.
Ejecuta tres jobs:

### 1. `backend-tests`
- `ubuntu-latest`, PHP 8.2, composer v2.
- Cachea `vendor/`.
- Prepara el entorno:
  ```bash
  cp .env.example .env
  php artisan key:generate
  php artisan jwt:secret --force
  ```
- Tests con `php artisan test` contra SQLite en memoria
  (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).

### 2. `frontend-tests`
- `ubuntu-latest`, Node 22, cache de `npm`.
- `npm ci` + `npx vitest run`.

### 3. `build-and-push` (necesita que los dos anteriores pasen)
- Login en Docker Hub con los secrets
  `DOCKERHUB_USERNAME` y `DOCKERHUB_TOKEN`.
- Construye y publica dos imágenes a `ddelacortep/simex-daw`:
  - Backend → tags `backend` y `backend-latest`.
  - Frontend → tags `frontend` y `frontend-latest`.

## Workflow `tests.yml` (legacy/matriz)

Heredado del starter kit de Laravel:
- Matriz de PHP `8.4`/`8.5` (mayor que la 8.2 que usamos en producción —
  sirve como smoke test de compatibilidad futura).
- Instala dependencias PHP + Node, genera key, hace `npm run build` y
  corre Pest (`./vendor/bin/pest`).

## Workflow `lint.yml`

- Ejecuta Pint (PHP) y Prettier/ESLint (frontend).
- El paso de auto-commit de los fixes está comentado.
  Si quieres que se aplique solo, descomenta el bloque
  `stefanzweifel/git-auto-commit-action@v7`.

## Secrets requeridos

En **Settings → Secrets and variables → Actions** del repo:

| Secret              | Uso                                   |
|---------------------|---------------------------------------|
| `DOCKERHUB_USERNAME`| Login en Docker Hub (`ddelacortep`).  |
| `DOCKERHUB_TOKEN`   | Token con permiso de push sobre `simex-daw`. |

## Flujo de release recomendado

1. Trabaja en una rama (`feat/<algo>` o `fix/<algo>`).
2. Abre PR hacia `main` → se dispara `tests.yml` y `lint.yml`.
3. Merge a `main` → se dispara `deploy.yml`:
   - Si los tests fallan, **no se publica imagen**.
   - Si todo va bien, las imágenes `:*-latest` quedan actualizadas en
     Docker Hub.
4. En Dockploy: pulsar Redeploy sobre los servicios afectados (ver
   [[Despliegue]]).

## Añadir otro servicio al pipeline

Si en el futuro quieres que el workflow también construya y publique
la imagen del microservicio .NET, duplica el paso `Build & Push Backend`
apuntando al contexto `./SIMEXWeb/dotnet` (o donde esté) y añade los
tags correspondientes a `ddelacortep/simex-daw:dotnet-*`.
