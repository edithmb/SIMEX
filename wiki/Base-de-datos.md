# Base de Datos

## Datos de conexión

| Parámetro | Valor |
|---|---|
| **Motor** | Microsoft SQL Server |
| **Host** | `51.83.192.177` |
| **Puerto** | `1433` |
| **Base de datos** | `simex06` |
| **TDS Protocol** | 7.4 |
| **Driver PHP** | `pdo_dblib` (FreeTDS) |
| **Driver .NET** | Entity Framework Core (SQL Server) |

---

## Configuración FreeTDS (backend Laravel)

El fichero `backend/freetds.conf` configura el driver FreeTDS para conectar con el SQL Server:

```ini
[global]
    tds version = 7.4
    client charset = UTF-8

[SIMEX]
    host = 51.83.192.177
    port = 1433
    tds version = 7.4
```

---

## Tablas principales

### `airports` — Aeropuertos
```sql
id        INT IDENTITY PK
code      CHAR(5)        -- código IATA (ej: MAD)
name      VARCHAR(120)
city_id   INT FK → cities
```

### `carriers` — Transportistas
```sql
id        INT IDENTITY PK
name      VARCHAR(50)
city_id   INT FK → cities
```

### `cities` — Ciudades
```sql
id          INT IDENTITY PK
name        VARCHAR(...)
country_id  INT FK → countries
```

### `countries` — Países
```sql
id    INT IDENTITY PK
name  VARCHAR(...)
code  CHAR(2/3)      -- código ISO
```

### `ports` — Puertos marítimos
```sql
id        INT IDENTITY PK
code      CHAR(5)
name      VARCHAR(...)
city_id   INT FK → cities
```

### `shipping_lines` — Navieras
```sql
id        INT IDENTITY PK
name      VARCHAR(50)
city_id   INT FK → cities
```

### `container_types` — Tipos de contenedor
```sql
id    INT IDENTITY PK
name  VARCHAR(...)   -- ej: 20GP, 40HC, 40RF
```

### `clients` — Clientes
```sql
id            INT IDENTITY PK
company_name  VARCHAR(...)
vat_number    VARCHAR(...)   -- NIF/CIF
address       VARCHAR(...)
country       VARCHAR(...)
postal_code   VARCHAR(...)
contact_name  VARCHAR(...)
email         VARCHAR(...)
```

### `users` — Usuarios del sistema
```sql
id        INT IDENTITY PK
name      VARCHAR(...)
email     VARCHAR(...) UNIQUE
password  VARCHAR(...)    -- hash bcrypt
role_id   INT FK → roles
```

### `roles` — Roles
```sql
id           INT IDENTITY PK
name         VARCHAR(...)   -- 'admin', 'agent', etc.
description  VARCHAR(...)
```

### `incoterms` — Incoterms
```sql
id    INT IDENTITY PK
code  CHAR(3)        -- EXW, FOB, CIF, DDP...
name  VARCHAR(...)
```

### `locations` — Ubicaciones/localizaciones
```sql
id       INT IDENTITY PK
name     VARCHAR(...)
city_id  INT FK → cities
```

### `logistics_operations` — Operaciones logísticas
```sql
id          INT IDENTITY PK
status      VARCHAR(...)   -- 'descarga', 'completed', 'completado', etc.
incoterm_id INT FK → incoterms
client_id   INT FK → clients
-- (más campos de la operación logística)
```

### `commercial_offers` — Ofertas comerciales (presupuestos)
```sql
id         INT IDENTITY PK
status     VARCHAR(...)   -- 'pending', 'approved', 'rejected'
client_id  INT FK → clients
user_id    INT FK → users  -- agente que la creó
-- (más campos económicos y de detalle)
```

### `client_requests` — Solicitudes de clientes
```sql
id         INT IDENTITY PK
client_id  INT FK → clients
-- (detalles de la solicitud)
```

### `documents` — Documentos
```sql
id          INT IDENTITY PK
-- documentos vinculados a personas o trámites
```

---

## Script de inicialización

El fichero `script.sql` en la raíz del proyecto contiene el DDL completo para crear todas las tablas en una instancia limpia de SQL Server:

```bash
# Con sqlcmd instalado localmente
sqlcmd -S 51.83.192.177,1433 -U sa -P <password> -i script.sql

# O desde SSMS: Abrir script.sql y ejecutar contra simex06
```

> **Nota**: El script tiene codificación UTF-16 (Unicode). Ábrelo con SSMS o SQL Server Management Studio para evitar problemas de encoding.
