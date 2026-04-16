# API Reference

SIMEX expone dos APIs REST independientes:

1. **Laravel API** — gestión principal (web + móvil)
2. **.NET API (API_MOVIL)** — funcionalidades móviles con tiempo real (SignalR)

---

## Laravel API

**Base URL (producción)**: `http://simex6-backend-a0lkj2-5b243c-51-83-192-177.traefik.me/api`  
**Base URL (local)**: `http://localhost/api`

### Autenticación

```
POST /api/login
Content-Type: application/json

{
  "email": "usuario@ejemplo.com",
  "password": "contraseña"
}
```

Respuesta:
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "user": { "id": 1, "name": "...", "role_id": 2 }
}
```

Todas las rutas protegidas requieren el header:
```
Authorization: Bearer <token>
```

```
GET  /api/me          → Datos del usuario autenticado
POST /api/logout      → Invalida el token
```

---

### Rutas — Acceso para todos los usuarios autenticados

#### Operaciones logísticas
```
GET  /api/logistics-operations        → Lista de operaciones logísticas
```

#### Ofertas comerciales (presupuestos)
```
GET  /api/commercial-offers           → Todas las ofertas
GET  /api/commercial-offers/mine      → Solo las ofertas del usuario actual
POST /api/commercial-offers           → Crear nueva oferta
PUT  /api/commercial-offers/{id}/approve  → Aprobar oferta
PUT  /api/commercial-offers/{id}/reject   → Rechazar oferta
```

#### Solicitudes de clientes
```
GET    /api/client-requests-client      → Listado (vista cliente)
POST   /api/client-requests-client      → Crear solicitud (vista cliente)
PUT    /api/client-requests-client/{id} → Editar solicitud
DELETE /api/client-requests-client/{id} → Eliminar solicitud

GET    /api/client-requests-admin       → Listado (vista admin/agente)
POST   /api/client-requests-admin       → Gestionar solicitud
PUT    /api/client-requests-admin/{id}  → Editar
DELETE /api/client-requests-admin/{id}  → Eliminar
```

#### Datos de referencia (lectura libre)
```
GET /api/incoterms         → Lista de Incoterms
GET /api/clients           → Lista de clientes
GET /api/locations         → Ubicaciones
GET /api/countries         → Países
GET /api/cities            → Ciudades
GET /api/ports             → Puertos
GET /api/airports          → Aeropuertos
GET /api/shipping-lines    → Navieras
GET /api/carriers          → Transportistas
GET /api/container-types   → Tipos de contenedor
GET /api/roles             → Roles del sistema
```

---

### Rutas — Solo administradores (`role:admin`)

#### Gestión de datos maestros (POST/PUT/DELETE)
```
POST   /api/countries          PUT /api/countries/{id}       DELETE /api/countries/{id}
POST   /api/cities             PUT /api/cities/{id}          DELETE /api/cities/{id}
POST   /api/ports              PUT /api/ports/{id}           DELETE /api/ports/{id}
POST   /api/airports           PUT /api/airports/{id}        DELETE /api/airports/{id}
POST   /api/shipping-lines     PUT /api/shipping-lines/{id}  DELETE /api/shipping-lines/{id}
POST   /api/carriers           PUT /api/carriers/{id}        DELETE /api/carriers/{id}
POST   /api/container-types    PUT /api/container-types/{id} DELETE /api/container-types/{id}
```

---

## .NET API (API_MOVIL)

**Base URL**: `http://10.0.1.7:5000/api`  
**Puerto Docker**: `8080`

Usa la misma clave JWT que Laravel para validar tokens.

### Controladores disponibles

| Controlador | Ruta base | Descripción |
|---|---|---|
| `ClientsController` | `/api/clients` | Clientes |
| `CommercialOffersController` | `/api/commercial-offers` | Ofertas comerciales |
| `DocumentsPersonController` | `/api/documents/person` | Documentos de personas |
| `DocumentsTramiteController` | `/api/documents/tramite` | Documentos de trámites |
| `LogisticsOperationsController` | `/api/logistics-operations` | Operaciones logísticas |
| `UsersController` | `/api/users` | Usuarios |

### WebSockets (SignalR)

La API .NET incluye un Hub de SignalR para comunicación en tiempo real (chat, notificaciones):

- **Endpoint**: `http://10.0.1.7:5000/hub/...`
- **Tamaño máximo de mensaje**: 10 MB (configurado para transferencia de documentos)
- El cliente Android se conecta usando la librería SignalR para Kotlin
