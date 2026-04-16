# SIMEX — Sistema de Gestión Logística y Comercio Exterior

SIMEX es una plataforma integral para gestionar operaciones de logística y comercio exterior (freight forwarding). Permite a agencias de aduanas, transportistas y empresas de logística manejar todo el ciclo de negocio desde que un cliente solicita un presupuesto hasta que la mercancía llega a destino.

## ¿Para qué sirve?

- 📦 **Seguimiento de operaciones logísticas** en tiempo real con Incoterms
- 💼 **Gestión de solicitudes y presupuestos** de clientes
- 👥 **Administración de clientes** y datos maestros (puertos, aeropuertos, navieras, etc.)
- 📱 **App móvil** (Android) para agentes y clientes en campo
- 📊 **Dashboard** con KPIs: envíos activos, ofertas pendientes, operaciones completadas
- 📄 **Gestión documental** integrada
- 💬 **Chat en tiempo real** entre usuarios y clientes
- 🎮 **Gamificación** para mejorar engagement

## A quién va dirigida

- **Agentes/Operarios**: Acceso a dashboard, seguimiento, solicitudes, presupuestos y documentos
- **Administradores**: Gestión completa incluyendo clientes, datos maestros y configuración
- **Clientes**: Aplicación web y móvil para solicitar servicios y hacer seguimiento

---

## 🏗️ Arquitectura

```
SIMEX
├── SIMEXWeb/          → Aplicación web (Vue 3 + Laravel)
│   ├── frontend/      → Vue 3 + Vite + Pinia
│   └── backend/       → Laravel 12 + PHP 8.2
├── API_MOVIL/         → API REST para app móvil (.NET 8 + SignalR)
├── SIMEXMOVIL/        → App Android (Kotlin)
├── Juego_Unity/       → Módulo de gamificación
└── script.sql         → Schema de base de datos SQL Server
```

**Base de datos**: SQL Server (51.83.192.177:1433) — base `simex06`

---

## 🚀 Despliegue rápido en Local (Docker)

### Requisitos

- Docker Desktop 24+
- Conexión a internet (para descargar imágenes)
- Acceso a la base de datos SQL Server (51.83.192.177:1433)

### Pasos

#### 1. Clonar el repositorio

```bash
git clone <url-del-repo>
cd SIMEX/SIMEXWeb
```

#### 2. Crear archivo `.env` del backend

```bash
cd backend
cat > .env << 'EOF'
APP_NAME=SIMEX
APP_ENV=production
APP_KEY=base64:CAMBIAR_ESTO_POR_UNA_CLAVE_VALIDA
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=sqlsrv
DB_HOST=51.83.192.177
DB_PORT=1433
DB_DATABASE=simex06
DB_USERNAME=sa
DB_PASSWORD=PEDIR_AL_ADMIN

JWT_SECRET=YiJ9sYnlcsfK7NVBQU13NddUVIuNnnihtODjZYmiqeaSg9wXd5Gq9WPKBwPLQp9M

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF
cd ..
```

**Generar APP_KEY válido:**

```bash
docker run --rm php:8.2-fpm php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Reemplaza `CAMBIAR_ESTO_POR_UNA_CLAVE_VALIDA` con el resultado del comando anterior.

#### 3. Levantar con Docker Compose

```bash
docker compose up -d
```

Esto descargará e iniciará 3 contenedores:
- **simex-nginx** → Proxy inverso (puerto 80)
- **simex-frontend** → Vue 3 compilado
- **simex-backend** → Laravel + PHP-FPM

#### 4. Verificar estado

```bash
docker compose ps
```

Todos deben estar en estado `Up`.

#### 5. Abrir en el navegador

```
http://localhost
```

Usa las credenciales de un usuario existente en la BD para login.

---

## 🐳 Despliegue en Servidor (Producción)

### Opción 1: Docker en servidor Linux

```bash
# En el servidor
ssh user@servidor
git clone <url-del-repo>
cd SIMEX/SIMEXWeb

# Crear .env (igual que en local, pero con datos reales de producción)
nano backend/.env

# Levantar
docker compose -f docker-compose.yml up -d

# Verificar
docker compose logs -f backend
```

### Opción 2: Servidor privado con Traefik (reverse proxy profesional)

Para desplegar con dominio real (ej: `app.empresa.com`), usa Traefik como reverse proxy:

```yaml
# docker-compose.yml con Traefik
version: '3.9'
services:
  traefik:
    image: traefik:latest
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    command:
      - "--providers.docker=true"
      - "--entrypoints.web.address=:80"

  nginx-proxy:
    image: nginx:stable-alpine
    labels:
      - "traefik.http.routers.simex.rule=Host(`app.empresa.com`)"
    depends_on:
      - frontend
      - backend

  # ... resto de servicios
```

### Opción 3: Deployment manual (sin Docker)

Si prefieres instalar todo directamente en el servidor:

**Backend (Laravel)**:
```bash
ssh user@servidor
cd /var/www/simex-backend
composer install --no-dev --optimize-autoloader
cp .env.production .env
php artisan config:cache
php artisan route:cache
# Usar PHP-FPM + Nginx
```

**Frontend (Vue)**:
```bash
cd /var/www/simex-frontend
npm install
npm run build
# Los archivos compilados van en `dist/`
# Servir con Nginx desde esa carpeta
```

---

## 🔐 Configuración importante

### Variables de entorno críticas

| Variable | Descripción | Ejemplo |
|---|---|---|
| `APP_KEY` | Clave de cifrado de Laravel | `base64:...` |
| `DB_HOST`, `DB_PASSWORD` | Conexión a SQL Server | `51.83.192.177`, `password` |
| `JWT_SECRET` | Clave para firmar tokens JWT | `YiJ9sYnlcsfK7NVBQU13...` |
| `APP_ENV` | Entorno (production/local) | `production` |
| `VITE_LARAVEL_API` | URL base del backend (frontend) | `http://api.empresa.com` |

### Conectar a SQL Server desde PHP (FreeTDS)

El archivo `backend/freetds.conf` configura la conexión:

```ini
[SIMEX]
    host = 51.83.192.177
    port = 1433
    tds version = 7.4
```

Si conecta a otro servidor, actualiza este archivo.

---

## 📚 Documentación completa

- **[Wiki - Arquitectura detallada](wiki/Arquitectura.md)**
- **[API Reference](wiki/API-Reference.md)**
- **[Base de Datos](wiki/Base-de-datos.md)**

---

## 🛠️ Solución de problemas

### Error 500 al arrancar el backend
```bash
# Verificar logs
docker logs simex-backend

# Problema común: APP_KEY vacío
docker compose restart backend
```

### No conecta a la base de datos
```bash
# Verificar conectividad al SQL Server
telnet 51.83.192.177 1433

# Verificar credenciales en backend/.env
grep DB_ backend/.env
```

### Reconstruir imágenes
```bash
docker compose pull
docker compose up -d --force-recreate
```

---

## 📱 App móvil (Android)

La app Android en `SIMEXMOVIL/` conecta a:
- **API Laravel**: `http://simex6-backend-a0lkj2-5b243c-51-83-192-177.traefik.me/api/`
- **API .NET**: `http://10.0.1.7:5000/api/`

Para cambiar los endpoints, edita `app/src/main/java/com/example/simex_movil/network/RetrofitClient.kt`:

```kotlin
val BASE_URL = "http://tu-servidor.com/api/"
val DOTNET_BASE_URL = "http://tu-api-dotnet.com/api/"
```

---

## 🤝 Contribuciones

Si encuentras bugs o tienes sugerencias, abre un issue o un pull request.

---

## 📝 Licencia

Proyecto privado de la empresa.

---

## 📞 Contacto

Para preguntas técnicas sobre despliegue, contacta al administrador del proyecto.
