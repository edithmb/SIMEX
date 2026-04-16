# Tutorial: Despliegue en Local

Este tutorial explica cómo levantar **SIMEXWeb** (la aplicación web) en tu máquina local usando Docker Compose. No necesitas instalar PHP, Node ni ninguna dependencia adicional — todo corre dentro de contenedores.

---

## Requisitos previos

| Herramienta | Versión mínima | Cómo verificar |
|---|---|---|
| Docker Desktop | 24+ | `docker --version` |
| Docker Compose | v2 (incluido en Docker Desktop) | `docker compose version` |
| Conexión a internet | — | Para descargar las imágenes de Docker Hub |
| Acceso al servidor de BD | — | La BD vive en `51.83.192.177:1433` |

---

## Paso 1 — Clonar el repositorio

```bash
git clone <url-del-repo>
cd SIMEX/SIMEXWeb
```

---

## Paso 2 — Crear el fichero `.env` del backend

El fichero `.env` está ignorado en git (`.gitignore`) por seguridad, así que hay que crearlo manualmente.

```bash
cd backend
cp .env.example .env   # si existe, si no créalo desde cero
```

Contenido mínimo del `.env`:

```env
APP_NAME=SIMEX
APP_ENV=production
APP_KEY=                  # ← se genera en el paso 3
APP_DEBUG=false
APP_URL=http://localhost

LOG_CHANNEL=stack

# Conexión a SQL Server via FreeTDS (pdo_dblib)
DB_CONNECTION=sqlsrv
DB_HOST=51.83.192.177
DB_PORT=1433
DB_DATABASE=simex06
DB_USERNAME=sa
DB_PASSWORD=<contraseña_del_servidor>

# JWT
JWT_SECRET=<jwt_secret_compartido>

# Cache y sesiones
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

> **Nota sobre `DB_PASSWORD` y `JWT_SECRET`**: pídelos al administrador del proyecto. El `JWT_SECRET` debe coincidir con el que usa la API .NET (`YiJ9sYnlcsfK7NVBQU13NddUVIuNnnihtODjZYmiqeaSg9wXd5Gq9WPKBwPLQp9M`).

---

## Paso 3 — Generar el APP_KEY de Laravel

Laravel necesita una clave de cifrado para funcionar. Genera una así:

```bash
docker run --rm php:8.2-fpm php -r "echo base64_encode(random_bytes(32));"
```

Copia el resultado y ponlo en el `.env`:

```env
APP_KEY=base64:<resultado_del_comando_anterior>
```

Alternativamente, si tienes PHP instalado localmente:

```bash
php artisan key:generate --show
```

---

## Paso 4 — Volver a la raíz de SIMEXWeb y levantar Docker Compose

```bash
cd ..   # vuelve a SIMEXWeb/
docker compose up -d
```

Docker Compose descargará las imágenes de Docker Hub automáticamente:
- `ddelacortep/simex-daw:frontend-latest` — Vue 3 compilado, servido con Nginx
- `ddelacortep/simex-daw:backend-latest` — Laravel con PHP-FPM + Nginx

Los tres contenedores que arranca son:

| Contenedor | Imagen | Puerto interno |
|---|---|---|
| `simex-nginx` | `nginx:stable-alpine` | `80:80` (punto de entrada) |
| `simex-frontend` | `ddelacortep/simex-daw:frontend-latest` | 80 (interno) |
| `simex-backend` | `ddelacortep/simex-daw:backend-latest` | 80 (interno) |

---

## Paso 5 — Verificar que todo está corriendo

```bash
docker compose ps
```

Deberías ver los tres contenedores con estado `running`:

```
NAME             STATUS
simex-nginx      Up
simex-frontend   Up
simex-backend    Up
```

Comprueba los logs del backend por si hay algún error:

```bash
docker logs simex-backend
```

Si todo va bien, verás algo como:

```
[OK] Config cached
[OK] Routes cached
[OK] Views cached
[OK] PHP-FPM started
[OK] Nginx started
```

---

## Paso 6 — Abrir la aplicación

Abre el navegador en:

```
http://localhost
```

Deberías ver la pantalla de login de SIMEX. Usa las credenciales de un usuario existente en la base de datos.

---

## Cómo funciona el enrutamiento interno (Nginx proxy)

El contenedor `simex-nginx` es el único expuesto al exterior (puerto 80). Actúa de proxy inverso con estas reglas definidas en `nginx/default.conf`:

```
Petición a /            → proxy → simex-frontend:80
Petición a /api/*       → proxy → simex-backend:80
Petición a /storage/*   → proxy → simex-backend:80
Petición a /sanctum/*   → proxy → simex-backend:80
```

Esto significa que desde el navegador todo llega por el puerto 80, sin exponer directamente el frontend ni el backend.

---

## ¿Qué hace el backend al arrancar?

El fichero `backend/docker-entrypoint.sh` se ejecuta automáticamente cuando el contenedor de backend inicia:

```bash
#!/bin/bash
set -e

php artisan config:cache   # Cachea la configuración (lee el .env)
php artisan route:cache    # Cachea las rutas de la API
php artisan view:cache     # Cachea las vistas Blade

php-fpm -D                 # Arranca PHP-FPM en background
exec nginx -g "daemon off;" # Arranca Nginx en foreground
```

> **Importante**: Si cambias el `.env`, debes reiniciar el contenedor para que Laravel recargue la caché de configuración:
> ```bash
> docker compose restart backend
> ```

---

## Solución de problemas frecuentes

### Error 500 al arrancar el backend

**Causa más común**: `APP_KEY` vacío en el `.env`.

```bash
# Revisa el .env
grep APP_KEY backend/.env

# Si está vacío, genera una clave
docker run --rm php:8.2-fpm php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

### No conecta a la base de datos

Verifica que el servidor de BD es accesible desde tu máquina:

```bash
# Prueba de conectividad al SQL Server
telnet 51.83.192.177 1433
```

Si no conecta, el problema es de red (VPN, firewall, etc.).

### Los contenedores se reinician en bucle

Revisa los logs detallados:

```bash
docker logs simex-backend --tail 50
docker logs simex-frontend --tail 50
```

### Forzar descarga de la última imagen

Las imágenes se marcan con `pull_policy: always` en el `docker-compose.yml`, pero puedes forzar la descarga manualmente:

```bash
docker compose pull
docker compose up -d
```

---

## Detener la aplicación

```bash
docker compose down
```

Para borrar también los volúmenes y empezar limpio:

```bash
docker compose down -v
```

---

## Estructura de ficheros relevantes para el despliegue

```
SIMEXWeb/
├── docker-compose.yml          ← Orquestación de los 3 contenedores
├── nginx/
│   └── default.conf            ← Reglas del proxy inverso
└── backend/
    ├── .env                    ← Variables de entorno (NO en git)
    ├── DOCKERFILE              ← Imagen del backend Laravel
    ├── docker-entrypoint.sh    ← Script de arranque
    ├── freetds.conf            ← Configuración FreeTDS para SQL Server
    └── nginx.conf              ← Nginx interno del backend
```
