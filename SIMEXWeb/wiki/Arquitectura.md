# Arquitectura

SIMEX se compone de tres servicios que se despliegan por separado y se
orquestan en el servidor mediante Dockploy.

```
┌────────────┐       ┌──────────────────┐       ┌───────────────────┐
│  Frontend  │──────▶│  API Laravel     │──────▶│   SQL Server      │
│  (Vue 3)   │       │  (principal)     │       │   (vía FreeTDS)   │
└────────────┘       └──────────────────┘       └───────────────────┘
       │                      ▲
       │                      │ (notificaciones, uploads, status)
       ▼                      │
┌────────────────────────────────┐
│  API .NET (microservicio)      │
└────────────────────────────────┘
```

## Servicios

### Frontend (Vue 3)
- SPA servida por Nginx.
- Habla con **ambas APIs**:
  - Laravel para datos de negocio (JWT-auth).
  - .NET para notificaciones push, subida/descarga de documentos y
    actualización de estado de operaciones logísticas.
- URLs configurables en build-time:
  - `VITE_LARAVEL_API` → base de la API Laravel.
  - `VITE_NET_API` → base de la API .NET.

### API #1 — Laravel 12 (principal)
- Expone `/api/*` con autenticación **JWT** (`tymon/jwt-auth`).
- Fortify gestiona vistas Inertia de perfil, 2FA y recuperación.
- Conecta a SQL Server mediante el driver `dblib` + FreeTDS (registrado
  a mano en `AppServiceProvider::register`).
- Ver detalle en [[Backend]].

### API #2 — .NET (microservicio)
- Microservicio auxiliar. **No se cubre en profundidad en esta wiki**;
  sólo los puntos en los que se integra:
  - `POST /api/notifications/trigger` — Laravel lo invoca (best-effort)
    al crear una oferta comercial para avisar al cliente.
  - `POST /DocumentsTramite/upload` y
    `GET /DocumentsPerson/download/{id}` — gestión de ficheros adjuntos a
    operaciones logísticas.
  - `POST /Clients`, `POST /Users` — alta de empresas/usuarios desde la
    pantalla de gestión de clientes.
  - `PUT /LogisticsOperations/{id}/status` — avance de estado de un envío.
- Se despliega como contenedor independiente y se referencia desde el
  frontend con `VITE_NET_API`.

## Flujos de dominio (alto nivel)

1. **Cotización**: cliente crea una `ClientRequest` → admin genera una
   `CommercialOffer` → cliente la aprueba o rechaza.
2. **Operación logística**: al aprobarse la oferta, Laravel crea
   automáticamente una `LogisticsOperation` dentro de una transacción.
3. **Seguimiento**: el admin avanza el estado de la operación a través
   de los pasos Incoterm definidos; la persistencia del estado pasa por
   la API .NET.
4. **Documentación**: cada `LogisticsOperation` acumula documentos
   subidos vía la API .NET y se listan en Laravel con sus tipos.
