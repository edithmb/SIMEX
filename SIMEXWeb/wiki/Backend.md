# Backend — Laravel 12

API principal del sistema, escrita en PHP 8.2 sobre Laravel 12.

## Estructura del proyecto

```
backend/
├── app/
│   ├── Actions/Fortify/       # CreateNewUser, ResetUserPassword
│   ├── Concerns/              # Traits (PasswordValidationRules…)
│   ├── Http/
│   │   ├── Controllers/       # 18 controllers + Settings/
│   │   ├── Middleware/        # EnsureUserHasRole, HandleInertiaRequests…
│   │   └── Requests/          # FormRequests (DatosMaestros/, Settings/)
│   ├── Models/                # 21 modelos Eloquent
│   └── Providers/             # AppServiceProvider, FortifyServiceProvider
├── config/                    # Configuración (array PHP, no documentado)
├── database/                  # Migrations, seeders, factories
├── routes/                    # api.php, web.php, settings.php, console.php
├── tests/                     # Pest 3 + PHPUnit
├── DOCKERFILE                 # Imagen de producción
├── docker-entrypoint.sh       # Cache de config/routes/views + nginx
├── freetds.conf               # Config FreeTDS para SQL Server
└── nginx.conf + default.conf  # Nginx embebido dentro del contenedor
```

## Dominios principales

| Modelo              | Descripción                                                   |
|---------------------|---------------------------------------------------------------|
| `User`              | Usuario; `JWTSubject` con claims `role`/`role_id`.            |
| `Role`              | Roles del sistema (admin, cliente, operador…).                |
| `Client`            | Empresa cliente; soft-deletes + auditoría (`created_by`…).   |
| `ClientRequest`     | Solicitud de cotización creada por un cliente.               |
| `CommercialOffer`   | Presupuesto; estados draft → accepted/rejected.              |
| `LogisticsOperation`| Operación logística; timeline a partir de Incoterms.         |
| `Incoterm`/`IncotermType`/`TrackingStep` | Catálogo usado para generar el timeline. |
| `Document`/`LogisticsOperationDocument` | Documentación adjunta.          |
| `LoginSession`      | Sesión JWT con `sid` para logout granular.                    |
| Datos maestros      | `Country`, `City`, `Port`, `Airport`, `Carrier`, `ShippingLine`, `ContainerType`. |

## Autenticación

- JWT emitido por `AuthController::login` con `tymon/jwt-auth`.
- Cada emisión crea una `LoginSession` e incrusta su id como claim `sid`
  para invalidar sesiones individualmente en `logout`.
- Las rutas privadas viven bajo `Route::middleware('auth:api')`.
- El middleware `role:<rol1>,<rol2>` (`EnsureUserHasRole`) protege
  escrituras de datos maestros a administradores.

## Endpoints clave

Ver `backend/routes/api.php` para la lista completa. Resumen:

- `POST /login`, `GET /me`, `POST /logout`
- `GET /clients`, `GET /roles`
- `GET /locations`, `GET /incoterms`
- `apiResource /client-requests-client` (vista cliente)
- `apiResource /client-requests-admin`  (vista admin)
- `GET|POST /commercial-offers`, `/commercial-offers/mine`,
  `PUT /commercial-offers/{id}/approve`, `PUT /commercial-offers/{id}/reject`
- `GET /logistics-operations`
- Datos maestros: `GET` público (autenticado); `POST/PUT/DELETE` sólo admin.

## Base de datos — SQL Server vía FreeTDS

- Driver `dblib` registrado manualmente en
  `AppServiceProvider::register` usando PDO + la extensión `pdo_dblib`.
- El archivo `freetds.conf` viaja dentro de la imagen Docker.
- Algunos modelos (`ClientRequest`, `CommercialOffer`) sobrescriben
  `asDateTime` para normalizar el formato `:AM`/`:PM` que devuelve SQL
  Server y que Carbon no parsea.

## Documentación del código

Todo el código de aplicación en `app/` está documentado con **PHPDoc en
español** (clase + todos los métodos, incluidos `private`/`protected`).

## Desarrollo local

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:secret --force
php artisan migrate --seed
php artisan serve
```

## Tests

- Framework: **Pest 3** (encima de PHPUnit).
- Arranque: `./vendor/bin/pest` o `php artisan test`.
- En CI se usa SQLite en memoria (`DB_CONNECTION=sqlite`,
  `DB_DATABASE=:memory:`).

## Linters y estilo

- PHP: **Pint** (`composer lint`).
- Front (dentro de este proyecto hay también JS): `npm run format`
  (Prettier) + `npm run lint` (ESLint).
