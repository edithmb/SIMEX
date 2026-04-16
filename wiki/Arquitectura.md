# Arquitectura del Sistema

## Visión general

SIMEX está compuesto por cuatro aplicaciones independientes que comparten una única base de datos **SQL Server** alojada en un servidor externo.

```
                     ┌─────────────────────────────┐
                     │   SQL Server (simex06)       │
                     │   51.83.192.177:1433         │
                     └──────────┬──────────────────┘
                                │
          ┌─────────────────────┼──────────────────────┐
          │                     │                      │
  ┌───────▼──────┐    ┌─────────▼────────┐   ┌────────▼───────┐
  │  Laravel API │    │   .NET 8 API     │   │  App Android   │
  │  (PHP 8.2)   │    │  + SignalR       │   │  (Kotlin)      │
  │  puerto 8000 │    │  puerto 5000     │   │  SIMEXMOVIL    │
  └───────┬──────┘    └─────────┬────────┘   └────────────────┘
          │                     │
  ┌───────▼──────┐              │
  │ Vue 3 + Vite │◄─────────────┘
  │  (frontend)  │   (también consume .NET API)
  │  puerto 3000 │
  └───────┬──────┘
          │
  ┌───────▼──────┐
  │  Nginx Proxy │  ← punto de entrada único (puerto 80)
  └──────────────┘
```

---

## SIMEXWeb — Aplicación Web

### Frontend
- **Framework**: Vue 3 con Composition API (`<script setup>`)
- **Build tool**: Vite 7
- **Router**: Vue Router 5 con rutas protegidas por autenticación y rol
- **Estado global**: Pinia (stores: `auth`, `role`, `counter`)
- **HTTP client**: Axios
- **Gráficas**: Chart.js 4
- **Imagen Docker**: `ddelacortep/simex-daw:frontend-latest`

### Backend (Laravel)
- **Framework**: Laravel 12 con PHP 8.2
- **Autenticación**: JWT mediante `tymon/jwt-auth`
- **Driver de base de datos**: `pdo_dblib` (FreeTDS) para conectar con SQL Server
- **API**: REST pura en `/api/*`
- **Imagen Docker**: `ddelacortep/simex-daw:backend-latest`
- **Librerías clave**:
  - `inertiajs/inertia-laravel` — SSR opcional
  - `laravel/sanctum` — tokens de sesión
  - `laravel/fortify` — autenticación headless

### Nginx (proxy inverso)
Actúa como punto de entrada único en el puerto 80 y enruta:
- `/api/*`, `/storage/*`, `/sanctum/*` → Backend Laravel (puerto 80 interno)
- Todo lo demás → Frontend Vue (puerto 80 interno)

---

## API_MOVIL — API para app móvil

- **Framework**: ASP.NET Core 8
- **Autenticación**: JWT Bearer (misma clave secreta que Laravel)
- **Tiempo real**: SignalR (WebSockets) con soporte de mensajes de hasta 10 MB
- **ORM**: Entity Framework Core (`Simex06Context`)
- **Base de datos**: misma instancia SQL Server que la web
- **Puerto**: 5000 (en desarrollo), 8080 (en Docker)
- **JWT Secret**: `YiJ9sYnlcsfK7NVBQU13NddUVIuNnnihtODjZYmiqeaSg9wXd5Gq9WPKBwPLQp9M`

---

## SIMEXMOVIL — App Android

- **Lenguaje**: Kotlin
- **HTTP**: Retrofit2 + Gson
- **APIs que consume**:
  - Laravel API: `http://simex6-backend-a0lkj2-5b243c-51-83-192-177.traefik.me/api/`
  - .NET API: `http://10.0.1.7:5000/api/`
- **Funcionalidades principales**:
  - Login / perfil de usuario
  - Home para clientes (`HomeClientActivity`) y agentes (`HomeAgentActivity`)
  - Seguimiento de operaciones (`TrackingActivity`, `TrackingAgentActivity`)
  - Crear nuevas solicitudes (`CreateRequestActivity`)
  - Historial de solicitudes (`HistoryRequestActivity`)
  - Chat en tiempo real (`ChatActivity`)
  - Notificaciones (`NotificationsActivity`)
  - Módulo de juego/gamificación (`GameActivity`)
  - Ajustes (`SettingsActivity`)

---

## Base de Datos

- **Motor**: Microsoft SQL Server
- **Host**: `51.83.192.177:1433`
- **Base de datos**: `simex06`
- **TDS Protocol**: versión 7.4 (configurado en FreeTDS)
- **Tablas principales**: `airports`, `carriers`, `cities`, `countries`, `ports`, `shipping_lines`, `container_types`, `clients`, `logistics_operations`, `commercial_offers`, `client_requests`, `documents`, `users`, `roles`

---

## Autenticación (flujo JWT compartido)

Tanto la web como la app móvil usan JWT con la misma clave secreta:

1. El usuario hace POST a `/api/login` con email y contraseña
2. Laravel valida las credenciales contra la BD
3. Laravel devuelve un JWT firmado con HS256
4. El cliente guarda el token y lo envía en cada request como `Authorization: Bearer <token>`
5. Laravel (y el .NET API) validan la firma del token sin consultar la BD

```
POST /api/login
Body: { "email": "...", "password": "..." }

Response: { "token": "eyJ...", "user": { "id": 1, "role_id": 2, ... } }
```
